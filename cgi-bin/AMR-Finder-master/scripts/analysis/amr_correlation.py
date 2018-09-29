import pandas as pd
import sys, os, math, argparse
from collections import defaultdict
from scipy.stats.stats import pearsonr
from scipy.stats import linregress
from scipy import stats

def read_file(input):
    out_df = pd.read_csv(input, sep = '\t', header = 0, index_col = 0)
    return out_df

def make_presence_absence(combined_df):
    combined_df = combined_df.applymap(lambda x: 0 if pd.isnull(x) else 1)
    return combined_df

def total_sums(pa_df):
    total_df = pa_df.sum(axis =1)
    total_df.columns = ["Total Sums"]
    return total_df

def total_sum_anova(sum_df, meta_df):
    out_dict = defaultdict(dict)
    metadata_values = list(meta_df.columns.values)
    for cat in metadata_values:
        array_dict = defaultdict(list)
        meta_df_sub = meta_df[cat]
        df_list = [sum_df, meta_df_sub]
        merged_df = pd.concat(df_list, axis = 1)
        merged_df = merged_df.dropna()
        for idx, row in merged_df.iterrows():
            row_count = row[0]
            meta = row[cat]
            array_dict[meta].append(row_count)

        arrays = []
        for i in array_dict.values():
            arrays.append(i)
        F_stat, p_val = stats.f_oneway(*arrays)
        out_dict[cat] = {"F_stat": F_stat, "p_val": p_val}
    return out_dict

def subset_genes(gene_list):
    subset_genes = []
    with open(gene_list, 'r') as f:
        for row in f:
            subset_genes.append(row.strip())
    return subset_genes

def calculate_pearsonr(df, meta_df):
    out_dict = defaultdict(dict)
    metadata_values = list(meta_df.columns.values)
    for cat in metadata_values:
        array_dict = defaultdict(list)
        meta_df_sub = meta_df[cat]
        df_list = [df, meta_df_sub]
        merged_df = pd.concat(df_list, axis = 1)
        merged_df = merged_df.dropna()

        out_dict[cat] = pearsonr(merged_df.ix[:,0], merged_df[cat])
    return out_dict

def factor_categorical_data(meta_df):
    cols = list(meta_df.columns.values)
    for cat in cols:
        map_dict = defaultdict(dict)
        if not str(meta_df[cat].dtypes).startswith("int"):
            categories = meta_df[cat].unique()
            for num in range(0, len(categories)):
                map_dict[categories[num]] = num
            meta_df[cat] = meta_df[cat].map(map_dict)

    return meta_df

def print_dicts(out_dict, type, method):
    print "The {} results for {} genes.".format(method, type)
    if method == 'anova':
        print "Metadata\tF Statistic\tP Value"
        for k,v in sorted(out_dict.items()):
            print "{}\t{}\t{}".format(k, v["F_stat"], v["p_val"])
    if method == 'correlation':
        print "Metadata\tCorrelation Coefficient (R)\tP Value"
        for k,v in sorted(out_dict.items()):
            print "{}\t{}\t{}".format(k, v[0], v[1])
    print

def main():
    parser = argparse.ArgumentParser(description = "Generates AMR Correlation Stats with an indeterminate amount of Metadata")
    parser.add_argument('-c', '--combined_hits_file', help = "Matrix file of genomes x gene hits from AMR-Finder.", required = True)
    parser.add_argument("-m", '--metadata_file', help = "Metadata file with genome ID's and any number of columns with associated metadata. Rows with blanks will be ignored.", required = True)
    parser.add_argument("-i", '--individual_gene_list', help = "File of genes (one per row) to calculate subset correlation statistics.")

    args = parser.parse_args()
    combined_file = args.combined_hits_file
    metadata_file = args.metadata_file
    gene_list = args.individual_gene_list

    combined_df, metadata_df = read_file(combined_file), read_file(metadata_file)
    metadata_values = list(metadata_df.columns.values)
    pa_df = make_presence_absence(combined_df)
    total_df = total_sums(pa_df)
    metadata_df = factor_categorical_data(metadata_df)
    total_sums_dict = total_sum_anova(total_df, metadata_df)
    total_sums_correlation = calculate_pearsonr(total_df, metadata_df)
    print_dicts(total_sums_dict, 'all', 'anova')
    print_dicts(total_sums_correlation, 'all', 'correlation')

    if gene_list:
        subset_gene_list = subset_genes(gene_list)
        subset_df = pa_df[subset_gene_list]
        subset_total_df = total_sums(subset_df)
        subset_sums_dict = total_sum_anova(subset_total_df, metadata_df)
        subset_sums_correlation = calculate_pearsonr(subset_df, metadata_df)

        print_dicts(subset_sums_dict, 'subset', 'anova')
        print_dicts(subset_sums_correlation, 'subset', 'correlation')



if __name__=='__main__':
    main()
