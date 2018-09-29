import sys, argparse, subprocess, os
from os.path import isfile, join
from Bio import SeqIO
import pandas as pd
from Bio.Blast.Applications import NcbiblastnCommandline
from collections import defaultdict, OrderedDict

def downloadPlasmidfinderDB():
    if os.path.exists("ResFinder"):
        print
        print "ResFinder folder already exists. Using the databases in there. "
        print
    else:
        subprocess.call(['git', 'clone', 'https://bitbucket.org/genomicepidemiology/plasmidfinder_db/get/master.zip', 'ResFinder'])
        subprocess.call(['makeblastdb', '-in', 'ResFinder/enterobacteriaceae.fsa', '-dbtype', 'nucl', "-out", 'ResFinder/enterobacteriaceae'])
        subprocess.call(['makeblastdb', '-in', 'ResFinder/gram_positive.fsa', '-dbtype', 'nucl', "-out", 'ResFinder/gram_positive'])

def make_dir(input_fasta):
    dir = input_fasta.rsplit(".", 1)[0]
    try:
        os.makedirs(dir)
    except OSError:
        pass
    return dir + "/"

def runBlast(input_fasta, blast_db, percent_identity, dir):
    if "/" in input_fasta:
        output_file_name = dir + input_fasta.rsplit("/", 1)[1].rsplit(".", 1)[0] + "_plasmidfinder.results"
    else:
        output_file_name = dir + input_fasta.rsplit(".", 1)[0] + "_plasmidfinder.results"
    print "Plasmdifinder output in {}".format(output_file_name)
    blastn_cline = NcbiblastnCommandline(query = input_fasta, db = blast_db, perc_identity = percent_identity, outfmt = '"6 qseqid sseqid pident qlen slen length mismatch evalue bitscore qstart qend"', out = output_file_name, max_target_seqs = 1, num_threads = 1)
    stdout, stderr = blastn_cline()

def readBlastResults(dir, input_fasta):
    try:
        df = pd.read_csv(dir + input_fasta.rsplit("/", 1)[1].rsplit(".", 1)[0] + "_plasmidfinder.results", sep = '\t', index_col = False, header = None)
        df.columns = ["Contig", "Plasmid", "Percent Identity", " Query Length", "Reference Length", "Alignment Length", "Mismatches", "E-Value", "Bit-Score", "Query Start", "Query End"]
        df.loc[:, "Genome"] = str(input_fasta.rsplit("/", 1)[1].rsplit(".", 1)[0])
        return df
    except:
        print "No Plasmidfinder BLAST Results."
        sys.exit()

def lengthBlastAddition(row):
    length_perc = float(row["Alignment Length"]) / float(row["Reference Length"]) * 100
    return round(length_perc, 2)

def createTopHits(dataframe):
    out_dict = defaultdict(lambda: defaultdict(dict))
    dataframe = dataframe.loc[:, ["Genome", "Plasmid", "Percent Identity"]]
    df_dict = pd.DataFrame.to_dict(dataframe, orient = 'index')
    for k,v in df_dict.items():
        out_dict[v["Genome"]].update({v["Plasmid"] : v["Percent Identity"]})
    return out_dict

def main():
    parser = argparse.ArgumentParser(description = "Run BLAST with plasmidfinder db on a directory of fasta files.")
    parser.add_argument("-db", '--blast_db', help = "Plasmidfinder Database -- If left blank, the latest version will be downloaded and used.")
    parser.add_argument("-p", '--plasmidfinder_db', help = "Choose 1 for the enterobacteriaceae database, 2 for the gram positive database.", choices = ["1","2"])
    parser.add_argument("-i", '--input_fasta', help = "Input fasta file. Must be nucleotide sequences.", required = True)
    parser.add_argument("-c", '--percent_identity', help = "Percent identity cut off -- 0 to 100 (Default of 95)", default = 95)
    parser.add_argument("-l", '--length_cutoff', help = "Minimum alignment length cutoff -- 0 to 100 (Default of 60)", default = 60)
    parser.add_argument("-o", '--out_directory', help = "Directory for ouput files")
    args= parser.parse_args()

    blast_db = args.blast_db
    input_fasta = args.input_fasta
    plasmidfinder_db = args.plasmidfinder_db
    percent_identity = args.percent_identity
    length_cutoff = args.length_cutoff
    out_dir = args.out_directory

    if not out_dir:
        out_dir = make_dir(input_fasta)
    if not blast_db:
        downloadPlasmidfinderDB()
        if not plasmidfinder_db:
            print "Must choose a plasmidfinder database to blast against."
            sys.exit()
        if plasmidfinder_db == "1":
            blast_db = "ResFinder/enterobacteriaceae"
        if plasmidfinder_db == '2':
            blast_db = "ResFinder/gram_positive"
    print
    print "Running Blast with {}.".format(blast_db)
    print

    runBlast(input_fasta, blast_db, percent_identity, out_dir)
    blast_df = readBlastResults(out_dir, input_fasta)
    blast_df["Alignment Percentage"] = blast_df.apply(lambda x: lengthBlastAddition(x), axis = 1)
    blast_df = blast_df[blast_df["Alignment Percentage"] > float(length_cutoff)]
    blast_dict = createTopHits(blast_df)
    blast_df.to_csv(out_dir + "plasmidfinder_filtered_matrix.txt", sep = "\t", header = 1, index = False)
    if blast_dict:
        out_df = pd.DataFrame.from_dict(blast_dict, orient = 'index').fillna(0)
        out_df.to_csv(out_dir + "plasmidfinder_top_hits.txt", sep = '\t', header = 0)

if __name__=='__main__':
    main()
