import sys, argparse, os, glob
import pandas as pd
from collections import defaultdict
from Bio import SeqIO

def check_input(dir, summary_file, plasmid_matrix):

    if not summary_file:
        try:
            summary_file = glob.glob(dir + "/*_summary_file.txt")[0]
            #print "Using {} as the amr_summary_file (-s)".format(summary_file)
        except:
            print "Couldn't find an AMR Summary File. There were no AMR-Finder results.".format(summary_file)
            sys.exit()
    if not plasmid_matrix:
        try:
            plasmid_matrix = glob.glob(dir + "/plasmidfinder_filtered_matrix.txt")[0]
            #print "Using {} as the plasmidfinder_matrix (-p)".format(plasmid_matrix)
            #print
        except:
            print "Couldn't find a plasmidfinder matrix file. There were no plasmidfinder results.".format(plasmid_matrix)
            sys.exit()
    return summary_file, plasmid_matrix

def get_plasmids(plasmid_matrix):
    out_dict = {}
    plasmid_df = pd.read_csv(plasmid_matrix, sep = "\t", header = 0, index_col = 0)
    for idx, row in plasmid_df.iterrows():
        out_dict[row.name] = row["Plasmid"]
    return out_dict

def get_amr_genes(amr_matrix):
    out_dict = defaultdict(list)
    amr_df = pd.read_csv(amr_matrix, sep = '\t', header =0, index_col = 0)
    gene_list = list(amr_df.columns.values)
    for idx, row in amr_df.iterrows():
        for i in gene_list:
            if row[i]:
                gene = i
                contig = row[gene].rsplit("_", 1)[0].split("|")[-1]
                out_dict[contig].append(gene)
    return out_dict


def merge_plasmid_and_amr(plasmid_dict, amr_dict):
    out_genes = defaultdict(list)
    for contig, plasmid in plasmid_dict.items():
        if contig in amr_dict:
            out_genes[plasmid] = amr_dict[contig]
    return out_genes

def main():
    parser = argparse.ArgumentParser(description = "Identifty AMR genes from AMR-Finder on plasmids identified by Plasmidfinder.")
    parser.add_argument("-d", '--results_directory', help = "Directory with plasmidfinder/amr-finder results for a fasta file.", required = True)
    parser.add_argument("-s", '--amr_summary_file', help = "Summary file from AMR-Finder. Should be in the results directory. Default is results_directory/*_summary_file.txt")
    parser.add_argument("-p", '--plasmidfinder_matrix', help = "Output file from plasmidfinder run. Should be in the results directory. Default is results_directory/plasmidfinder_filtered_matrix.txt")
    parser.add_argument("-o", '--output_dir', help = "Output directory to write hits to. Default is the results directory (-d).")
    args= parser.parse_args()

    dir = args.results_directory
    summary_file = args.amr_summary_file
    plasmid_matrix = args.plasmidfinder_matrix
    output_dir = args.output_dir
    if not output_dir:
        output_dir = dir

    summary_file, plasmid_matrix = check_input(dir, summary_file, plasmid_matrix)
    if not summary_file:
        print "Couldn't find Summary AMR File. Check that you have AMR Results for this genome."
    if not plasmid_matrix:
        print "Couldn't find Plasmidfinder Filtered Matrix file. Check that you have Plasmidfinder Results for this genome."

    plasmidfinder_dict = get_plasmids(plasmid_matrix) # contig to list of plasmids
    amr_dict = get_amr_genes(summary_file)
    out_list = merge_plasmid_and_amr(plasmidfinder_dict, amr_dict)

    if out_list:
        for plasmid, gene_list  in out_list.items():
            for gene in gene_list:
                print "{} was found on {}.".format(gene, plasmid)
                with open(output_dir + "/amr_plasmid_hits.txt", 'w') as f:
                    f.write(gene + "\t" + plasmid + "\n")

    else:
        print "No AMR genes were on plasmids found via Plasmidfinder."

if __name__=='__main__':
    main()
