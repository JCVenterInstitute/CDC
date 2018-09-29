import sys, os, argparse
import pandas as pd
import urllib3
from bs4 import BeautifulSoup

pd.set_option('max_columns', None)


def read_file(input_file):
    df = pd.read_csv(input_file, sep = "\t", header = 0, index_col = 0)
    df.dropna(subset = ["Gene Name"], inplace = True)
    df = df.fillna("")
    return df

def check_pubmed(row):
    if row["PubMed ID"] == "":
        row["PubMed ID"] = "Unpublished"
    return row

def check_ecNumber(df):
    ec_dict = {}
    for idx, row in df.iterrows():
        if row["EC Number"] != "":
            print row["EC Number"], row["Gene Name"]
            if row["EC Number"] not in ec_dict:
                #print ec_dict
                urllib3.disable_warnings()
                http = urllib3.PoolManager()
                r = http.request("GET", 'https://enzyme.expasy.org/EC/' + row["EC Number"])
                html = r.data

                parsed_html = BeautifulSoup(html, 'html.parser')
                try:
                    table = parsed_html.find("table", {"class" : 'enzyme1'})
                    rows = table.find_all("tr")
                    count = 0
                    for i in rows:
                        count += 1
                        if i.get_text(strip = True) == 'Accepted Name':
                            prot_name = rows[count].get_text(strip = True)
                            prot_name = "".join([x for x in prot_name if x not in "."])
                            row["Protein Name"] = prot_name
                            ec_dict[row["EC Number"]] = prot_name
                except:
                    pass
            else:
                row["Protein Name"] = ec_dict[row["EC Number"]]
    return df



def check_gene(row):
    if "_(" in row["Gene Name"]:
        row["Gene Name"] = row["Gene Name"].split("_(")[0]
    if len(row["Gene Name"]) > 3:
        row["Gene Name"] = row["Gene Name"][0:3].lower() + row["Gene Name"][3:]
        if len(row["Gene Name"]) < 8:
            row["Gene Name"] = row["Gene Name"][0:3].lower() + row["Gene Name"][3].upper() + row["Gene Name"][4:]
    return row


def replace_underscores(row):
    row["Drug Family"] = row["Drug Family"].replace("_", " ")
    row["Drug Class"] = row["Drug Class"].replace("_", " ")
    row["Drugs"] = row["Drugs"].replace("_", " ").replace(",", ", ")
    return row

def consistency_check(row):
    check_pubmed(row)
    check_gene(row)
    replace_underscores(row)
    return row

def main():
    parser = argparse.ArgumentParser(description = "Update AMR-DB GenBank Metadata given list of GenBank ID's.")
    parser.add_argument("-f", '--input_file', help = "A text file of GenBank ID's, one per row.", required = True)
    parser.add_argument("-o", "--output_name", help = "Output File Name. Default is input file name + _sanitized.")
    args = parser.parse_args()

    input = args.input_file
    output = args.output_name

    if not output:
      output = input.split(".")[0] + "_sanitized.txt"

    df = read_file(input)
    df =  check_ecNumber(df)
    df = df.apply(lambda x: consistency_check(x), axis = 1)
    print df[10526]

if __name__=='__main__':
  main()
