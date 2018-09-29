import sys, os, argparse, glob
import pandas as pd
from collections import defaultdict, Counter, OrderedDict
from Bio import SeqIO

class AMRentry:
    """A class that loads the standard AMR-DB FASTA entry."""
    def __init__(self, header):
        header_split = header.split("|")
        self.source = header_split[0]
        self.amrdb_id = header_split[1]
        self.source_id = header_split[2].split(":")[0]
        self.cds = header_split[2].split(":")[1]
        self.gene_symbol = header_split[3]
        self.allele = header_split[4]
        self.drug_class = header_split[5]
        self.drug_family = header_split[6]
        self.drugs = header_split[7]
        self.parent_allele = header_split[8]
        self.parent_allele_family = header_split[9]
        self.snp_info = header_split[10]

    def gene_name(self):
        if self.allele == "":
            name = self.gene_symbol
        else:
            name = self.gene_symbol + "-" + self.allele
        return name

    def new_header(self):
        return ">{}|{}|{}:{}|{}|{}|{}|{}|{}|{}|{}|{}|".format(self.source, self.amrdb_id, self.source_id, self.cds, self.gene_symbol, self.allele, self.drug_class, self.drug_family, self.drugs, self.parent_allele, self.parent_allele_family, self.snp_info)

    def __getitem__(self, item):
        return getattr(self, item)

def load_top_hits(top_hits_file):
    df = pd.read_csv(top_hits_file, sep = '\t', header = 0, index_col = 0)
    df.index.name = "Gene"
    df = df.fillna("").transpose().sort_index()
    return df

def get_script_path():
    return os.path.dirname(os.path.realpath(sys.argv[0]))

def get_db_files():
    out_files = []
    script_location = get_script_path()
    for root, dirs, files in os.walk(script_location + "/../../dbs/amr_dbs/"):
        for i in files:
            out_files.append(script_location + "/../../dbs/amr_dbs/" + i)
    return out_files

def make_annotation_dict(fasta_files):
    out_dict = defaultdict(dict)
    carbapenemase_list = []
    for file in fasta_files:
        for record in SeqIO.parse(file, 'fasta'):
            new_entry = AMRentry(record.description)
            out_dict[new_entry.gene_name()] = {"Drug Class": new_entry.drug_class, "Drug Family": new_entry.drug_family, "Drugs": new_entry.drugs}
            if 'carbapenemase' in new_entry.drug_family.lower():
                carbapenemase_list.append(new_entry.gene_name)

    return out_dict, carbapenemase_list

def color_palette():
    colors = ["#89C5DA", "#DA5724", "#74D944", "#CE50CA", "#3F4921",
    				"#C0717C", "#CBD588", "#5F7FC7", "#673770", "#D3D93E",
    				"#38333E", "#508578", "#D7C1B1", "#689030", "#AD6F3B",
    				"#CD9BCD", "#D14285", "#6DDE88", "#652926", "#7FDCC0",
    				"#C84248", "#8569D5", "#5E738F", "#D1A33D", "#8A7C64",
                     "#599861"]
    return colors

def get_counts(df, annotation_dict, subset_class, remove_multidrug_genes, group_alleles):
    if subset_class:
        check_drug_class(annotation_dict, subset_class)
    counts_dict = defaultdict(lambda: defaultdict(dict))
    df_dict = df.to_dict()
    for genome, info in df_dict.items():
        for hit, hit_info in info.items():
            if hit_info:
                drug_family = annotation_dict[hit]["Drug Family"]
                drug_class = annotation_dict[hit]["Drug Class"]
                drugs = annotation_dict[hit]["Drugs"]
                if subset_class:
                    if drug_class != subset_class:
                        continue #Fake exit so it isn't included
                if remove_multidrug_genes:
                    if drug_class == 'Multi-drug_resistance':
                        continue #Fake exit so it isn't included

                if group_alleles:
                    hit = hit.rsplit("-", 1)[0]
                    if hit in counts_dict[genome]["Hit"]:
                        current_hit_id = counts_dict[genome]["Hit"][hit]
                        new_hit_id = hit_info.split("|")[0].replace("*", "").split("/")[0]
                        if float(new_hit_id) > float(current_hit_id):
                            counts_dict[genome]["Hit"][hit] = hit_info.split("|")[0].replace("*", "")
                    else:
                        counts_dict[genome]["Hit"][hit] = hit_info.split("|")[0].replace("*", "")
                        if drug_family != "":
                            if drug_family in counts_dict[genome]["Drug Family"] :
                                counts_dict[genome]["Drug Family"][drug_family] += 1
                            else:
                                counts_dict[genome]["Drug Family"][drug_family] = 1
                        if drug_class != "":
                            if drug_class in counts_dict[genome]["Drug Class"]:
                                counts_dict[genome]["Drug Class"][drug_class] += 1
                            else:
                                counts_dict[genome]["Drug Class"][drug_class] = 1
                        if drugs != "":
                            if drugs in counts_dict[genome]["Drugs"]:
                                counts_dict[genome]["Drugs"][drugs] += 1
                            else:
                                counts_dict[genome]["Drugs"][drugs] = 1
                else:
                    counts_dict[genome]["Hit"][hit] = hit_info.split("|")[0].replace("*", "")

                    if drug_family != "":
                        if drug_family in counts_dict[genome]["Drug Family"] :
                            counts_dict[genome]["Drug Family"][drug_family] += 1
                        else:
                            counts_dict[genome]["Drug Family"][drug_family] = 1
                    if drug_class != "":
                        if drug_class in counts_dict[genome]["Drug Class"]:
                            counts_dict[genome]["Drug Class"][drug_class] += 1
                        else:
                            counts_dict[genome]["Drug Class"][drug_class] = 1
                    if drugs != "":
                        if drugs in counts_dict[genome]["Drugs"]:
                            counts_dict[genome]["Drugs"][drugs] += 1
                        else:
                            counts_dict[genome]["Drugs"][drugs] = 1

    return counts_dict

#----------------------------------------------------------------#
#Annotation
#For Bar plot level is Drug Class, for Binary level is Hit
##Choices is color_palette, for  level is [1,2,3,4,5,6]
def make_annotation(counts_dict, choices, level):
    options = set()
    for genome, resistance_level_info in counts_dict.items():
        for resistance_level, level_options in resistance_level_info.items():
            if resistance_level == level:
                for i in level_options.keys():
                    options.add(i)
    options = sorted(list(options))
    out_dict = {}
    for i in range(0, len(options)):
        option = options[i]
        if i < len(choices):
            out_dict[option] = choices[i]
        else:
            choice = i % len(choices)
            out_dict[option] = choices[choice]
    #FIX
    if level == 'Drug Class':
        return out_dict, options
    else:
        return out_dict, options


#------------------------------------------------------------------------#
#Bar Plot-- Plotting Drug Class
def write_bar_plot(counts_dict, color_dict, color_options, out_file):
    print counts_dict
    genome_to_class = {}
    for genome, info in counts_dict.items():
        genome_to_class[genome] = info['Drug Class']

    if len(color_dict.keys()) > 1:
        dataset = "DATASET_MULTIBAR"
    else:
        dataset = "DATASET_SINGLEBAR"
    #Legend Info
    legend_shapes = "LEGEND_SHAPES"
    legend_labels = ",".join(color_options)
    legend_colors = ",".join([color_dict[x] for x in color_options])
    for i in range(0, len(color_options)):
        legend_shapes = legend_shapes + ",1"

    field_labels = "FIELD_LABELS," + legend_labels
    field_colors = "FIELD_COLORS," + legend_colors
    #DATA

    with open(out_file, 'w') as f:
        f.write("DATASET_MULTIBAR\n")
        f.write("SEPARATOR COMMA\n")
        f.write("DATASET_LABEL,AMR Bar Chart\n")
        f.write("LEGEND_TITLE,AMR Classes\n")
        f.write(legend_shapes + "\n")
        f.write("LEGEND_COLORS," + legend_colors + "\n")
        f.write("LEGEND_LABELS," + legend_labels + '\n')
        f.write("COLOR,#ff0000\n")
        f.write("HEIGHT_FACTOR,2\n")
        f.write(field_labels+ "\n")
        f.write(field_colors+ "\n")
        f.write("ALIGN_FIELDS,1\n")
        f.write("DATA\n")

        for genome in genome_to_class:
            out_string = str(genome)
            for opt in color_options:
                if opt in counts_dict[genome]["Drug Class"]:
                    counts = counts_dict[genome]['Drug Class'][opt]
                    out_string = out_string + "," + str(counts)
                else:
                    out_string = out_string + ",0"
            f.write(out_string + "\n")
#------------------------------------------------------------------#
#Binary Plot

def write_binary_plot(counts_dict, color_dict, shape_dict, present_fields, carb_list, carb_highlight, subset_class, out_file):
    genome_to_hit = {}
    for genome, info in counts_dict.items():
        genome_to_hit[genome] = info['Hit']

    legend_labels = ",".join(present_fields)
    legend_colors = ",".join([color_dict[x] for x in present_fields])
    if carb_highlight:
        legend_shapes = []
        for i in present_fields:
            if i in carb_list:
                legend_shapes.append("3")
            else:
                legend_shapes.append("2")
        legend_shapes = ",".join(legend_shapes)
    else:
        legend_shapes = ",".join([str(shape_dict[x]) for x in present_fields])
    field_labels = "FIELD_LABELS," + legend_labels
    field_colors = "FIELD_COLORS," + legend_colors
    field_shapes = "FIELD_SHAPES," + legend_shapes

    with open(out_file, 'w') as f:
        f.write("DATASET_BINARY\n")
        f.write("SEPARATOR COMMA\n")
        f.write("DATASET_LABEL," + subset_class + "\n")
        f.write("LEGEND_SHAPES," + legend_shapes + "\n")
        f.write("LEGEND_COLORS," + legend_colors + "\n")
        f.write("LEGEND_LABELS," + legend_labels + "\n")
        f.write("COLOR,#00b2ff\n")
        f.write(field_labels + "\n")
        f.write(field_colors + "\n")
        f.write(field_shapes + "\n")
        f.write("DATA" + "\n")

        for genome, hit_info in genome_to_hit.items():
            out_str = str(genome)
            for gene in present_fields:
                if gene in genome_to_hit[genome]:
                    out_num = str(genome_to_hit[genome][gene])
                    if '100.0' in out_num:
                        out_str = out_str + ",1"
                    else:
                        out_str = out_str + ",0"
                else:
                    out_str = out_str + ",-1"
            f.write(out_str + "\n")



    #print genome_to_hit

#House Keeping
def check_inputs():
    pass

def check_drug_class(annotation_dict, family_subset):
    main_classes = set()
    for k,v in annotation_dict.items():
        main_classes.add(v['Drug Class'])

    if family_subset in main_classes:
        pass
    else:
        main_class_options = list(main_classes)
        print
        print "Error: Your chosen subset family (-s) is not available. Re-run with one of these options: "
        for i in sorted(main_class_options):
            print i
        print
        sys.exit()


def main():
    parser = argparse.ArgumentParser(description = "Create ITOL Annotation based upon CARD RGI output")
    parser.add_argument('-t', '--top_hits_file', help = 'Top AMR Hits File (in AMR_STATISTICS directory)', required = 'True')
    parser.add_argument("-a", '--annotation_type', help = "Which type of ITOL annotation file would you like generated? Options are 'Bar' and 'Binary'", choices = ["Bar", "Binary"], default = 'Bar')
    parser.add_argument("-s", '--subset_class', help = "Keep only one type of Drug Class")
    parser.add_argument("-o", '--out_file', help = 'Name of the file you want Annotations written to. Default is ITOL_OUT.txt', default = 'ITOL_OUT.txt')
    parser.add_argument("-r", '--remove_multidrug_genes', help = 'Remove genes that confer non specific resistance (Efflux Pumps, etc.)', action = 'store_true')
    parser.add_argument("-c", '--carbapenemase_highlight', help = 'If main class is Beta-lactam, then this flag highlights those AMR genes that confer carbapenem resistance', action = 'store_true')
    parser.add_argument("-g", '--group_alleles', help = 'Group all alleles of a gene together (With -a of Bar-- KPC-2 and KPC-3 become KPC with a count of 2; With -a of Binary-- KPC-2 and KPC-3 becomes KPC with a shading of the highest %%id', action = 'store_true')
    args = parser.parse_args()
    top_amr_hits = args.top_hits_file
    annotation_type = args.annotation_type
    class_subset = args.subset_class
    out_file = args.out_file
    rm_multidrug = args.remove_multidrug_genes
    carb_highlight_yes = args.carbapenemase_highlight
    group_alleles = args.group_alleles

    top_hits_df = load_top_hits(top_amr_hits)
    fasta_files = get_db_files()
    annotation_dict, carb_list = make_annotation_dict(fasta_files)
    column_names = list(top_hits_df.columns.values)
    counts_dict = get_counts(top_hits_df, annotation_dict, class_subset, rm_multidrug, group_alleles)
    colors = color_palette()
    shapes = [1,2,3,4,5,6]

    annotation_dict = {"Bar" : "Drug Class", "Binary": "Hit"}
    plot_to_make = annotation_dict[annotation_type]
    colors_dict, present_fields = make_annotation(counts_dict, colors, plot_to_make)
    shapes_dict, present_fields = make_annotation(counts_dict, shapes, plot_to_make)
    if annotation_type == 'Bar':
        write_bar_plot(counts_dict, colors_dict, present_fields, out_file)
    if annotation_type == "Binary":
        write_binary_plot(counts_dict, colors_dict, shapes_dict, present_fields, carb_list, carb_highlight_yes, class_subset, out_file)
if __name__ =='__main__':
  main()
