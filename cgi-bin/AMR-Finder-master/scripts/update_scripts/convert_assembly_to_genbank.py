import pandas as pd
import sys, argparse
from collections import defaultdict
from Bio import Entrez
import requests, warnings
from bs4 import BeautifulSoup

def get_hit_ids(id_file):
    out_list = []
    with open(id_file, 'r') as f:
        for row in f:
            out_list.append(row.strip())
    return out_list

def downloadGbFileFromIdList(assembly_id, email):
    Entrez.email = email
    html_output = requests.get("https://eutils.ncbi.nlm.nih.gov/entrez/eutils/esearch.fcgi?db=nucleotide&term=" + assembly_id + "&rettype=native&retmode=text")

    html = BeautifulSoup(html_output.content, 'html.parser')
    out_id = ""
    if html:
        try:
            ncbi_internal_id = html.find("id").text
            if ncbi_internal_id:
                fetch_handle = Entrez.efetch(db="nuccore", retmode="text", rettype="acc", id = ncbi_internal_id)
                data = fetch_handle.read()
                out_id = data.strip()
        except:
            pass

    return out_id

def write_out_dict(out_dict):
    with open("mapped_ids.txt", "a") as f:
        for k,v in out_dict.items():
            f.write("{}\t{}\n".format(v,k))


def main():
    parser = argparse.ArgumentParser(description = "Map Assembly ID's to GenBank Nucleotide ID's.")
    parser.add_argument("-i", '--id_file', help = "A text file of Assembly ID's, one per row.", required = True)
    #parser.add_argument("-f", '--ftp_file', help = "Assembly_summary.txt file", required = True)
    parser.add_argument("-e", "--email", required = True)

    args = parser.parse_args()
    id_file = args.id_file
    #ftp_file = args.ftp_file
    email = args.email

    assembly_ids = get_hit_ids(id_file)

    out_dict = {}
    count = 0
    for i in assembly_ids:
        with warnings.catch_warnings():
            warnings.simplefilter("ignore")
            count += 1
            print "{}\t{}".format(i, count)
            out_gb_id = downloadGbFileFromIdList(i, email)
            out_dict[i] = out_gb_id
            if count == 1000:
                print "Writing results."
                write_out_dict(out_dict)
                out_dict = {}
                count = 0



if __name__=='__main__':
    main()
