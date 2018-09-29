import sys, os
import subprocess
import pandas as pd
from collections import defaultdict
import argparse
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

def get_results_list(file_list):
    files = []
    with open(file_list, 'r') as f:
        for row in f:
            results_file = row.strip()
            files.append(results_file)
    return files

def make_out_dict(results_files):
    all_results_dict = defaultdict(lambda: defaultdict(dict))
    for i in results_files:
        try:
            df = pd.read_csv(i, sep = "\t", index_col = 0)
            results_dict = df.to_dict('index')
            for k,v in results_dict.items():
                all_results_dict[k] = v
        except:
            pass #No blast results
    return all_results_dict

def get_script_path():
    return os.path.dirname(os.path.realpath(sys.argv[0]))

def check_inputs(level, term):
    if (level and not term) or (term and not level):
        print
        print "If you want to subset the output files, you must specify both a level and term to subset."
        print
        sys.exit()


def make_annotation_dict(database_folder):
    out_dict = defaultdict(lambda: defaultdict(list))
    not_out_dict = defaultdict(dict)
    annotation_files = []
    for root, directory, files in os.walk(database_folder):
        for file in files:
            if '.fa' in file:
                annotation_files.append(os.path.join(root, file))
    for fasta_file in annotation_files:
        for record in SeqIO.parse(fasta_file, 'fasta'):
            amr_record = AMRentry(record.description)
            out_dict[amr_record.gene_name()] = {"Drug Class": amr_record.drug_class, "Drug Family": amr_record.drug_family, "Drugs": amr_record.drugs}

    return out_dict

def annotate(col, annotation_dict):
    annotations = annotation_dict[col.name]
    col["Drug Family"] = annotations["Drug Family"]
    col["Drug Class"] = annotations["Drug Class"]
    col["Drugs"] = annotations["Drugs"]
    return col

def filter_df(df, annotation_dict, level, subset):
    df = df.apply(lambda x: annotate(x, annotation_dict), axis = 0).transpose()
    if level and subset:
        choices = df[level]
        out_list = []
        for i in choices:
            if i != "":
                for x in i.replace("/", " ").replace(",", " ").split(" "):
                    if x not in out_list:
                        out_list.append(x)
        keep_index = []
        count = 0
        for idx, row in df.iterrows():
            count = count + 1
            if subset in row[level].split("/") or subset in row[level] or subset in row[level].split(","):
                #print subset, row[level], row.name, count
                keep_index.append(count - 1)
        if keep_index:
            out_df = df.iloc[keep_index, :]
        if not keep_index:
            print
            print 'There were no matches to the term "{}" in the "{}" level. Here are your options:'.format(subset, level)
            print
            for i in sorted(out_list):
                if i != "":
                    print i
            print
            sys.exit()
        else:
            return out_df
    else:
        return df

def genome_stats(top_hits_df, out_file):
    genomes = [x for x in list(top_hits_df) if x not in ["Drug Class", "Drug Family", "Drugs"]]
    out_df = top_hits_df[genomes].notnull().sum()
    out_df.to_csv(out_file + "_genome_stats.txt", sep = '\t', header = ["AMR Counts"])
    #top_hits_df.transpose().apply(lambda x: x.notnull().sum(), axis = 'columns')

    #genome_df = top_hits_df.drop(['Drug Class', "Drug Family", "Drugs"], axis = 1).transpose().apply(lambda x: x.notnull().sum(), axis = 'columns')
    #genome_df.to_csv(out_file + "_genome_stats.txt", sep = '\t', header = ["AMR Counts"])

def gene_stats(top_hits_df, out_file):
    out_dict = {}
    gene_df = top_hits_df.fillna("")
    genome_list = {x[0]: x[1] for x in enumerate(list(top_hits_df)) if x[1] not in ["Drug Class", "Drug Family", "Drugs"]}
    for idx, row in gene_df.iterrows():
        genomes = []
        row_count = 0
        for i in range(0, len(row)):
            if i in genome_list:
                if row[i] != "" :
                    row_count = row_count + 1
                    genomes.append(genome_list[i])
        if row_count == 1:
            out_dict[row.name] = {"Gene Status": "Singleton", "Count": row_count, "Singleton Genome": genomes[0]}
        elif row_count == len(genome_list):
            out_dict[row.name] = {"Gene Status": "Core", "Count": row_count, "Singleton Genome" : ""}
        else:
            out_dict[row.name] = {"Gene Status": "Shared", "Count": row_count, "Singleton Genome": ""}

    out_gene_df = pd.DataFrame.from_dict(out_dict, orient = 'index')
    out_gene_df.index.name = "Gene"
    out_gene_df.to_csv(out_file + "_gene_stats.txt", sep = '\t')


def main():
    parser = argparse.ArgumentParser(description = "Combine summary files from multiple amr finder runs into one output matrix.")
    parser.add_argument("-r", '--results_list', help = "List of results files used as input in multiple amr-finder runs. (ls */*summary_file.txt > results.list )")
    parser.add_argument("-o", '--out_file', help = "Output prefix. Default is combined.", default = 'combined')
    parser.add_argument("-db", '--database_folder', help = "Path to the file your BLAST database was made from. Must have AMR-DB Headers. Default is ~AMR-Finder/dbs/amr_dbs/")
    parser.add_argument('-l', '--level', help = "Level to subset genes at.", choices = ['Drug Class', "Drug Family", "Drugs"])
    parser.add_argument("-t", '--term', help = "Term to subset level with.")

    args = parser.parse_args()
    database_folder = args.database_folder
    level = args.level
    term = args.term
    file_list = args.results_list
    out_file = args.out_file

    check_inputs(level, term)
    if not database_folder:
        script_location = get_script_path()
        database_folder = script_location + "/../../dbs/amr_dbs/"

    results_files = get_results_list(file_list)
    results_dict = make_out_dict(results_files)
    out_df = pd.DataFrame.from_dict(results_dict, orient = 'index')
    out_df.index.name = "Genome"
    annotation_dict = make_annotation_dict(database_folder)
    top_hits_df = filter_df(out_df, annotation_dict, level, term)
    out_genomes = [x for x in list(top_hits_df.columns.values) if x not in ["Drug Class", "Drug Family", "Drugs"]]
    out_columns = ["Drug Class", "Drug Family", "Drugs"] + out_genomes
    top_hits_df = top_hits_df[out_columns]
    top_hits_df.sort_values("Drug Class").to_csv(out_file + "_results.txt", sep = '\t')
    if level and term:
        top_hits_df.drop(["Drug Class", "Drug Family", "Drugs"], axis = 1).transpose().to_csv(out_file + "_subset.txt", sep = '\t')
        genome_stats(top_hits_df, out_file + "_subset")
        gene_stats(top_hits_df, out_file + "_subset")
    genome_stats(top_hits_df, out_file)
    gene_stats(top_hits_df, out_file)


if __name__ == '__main__':
    main()
