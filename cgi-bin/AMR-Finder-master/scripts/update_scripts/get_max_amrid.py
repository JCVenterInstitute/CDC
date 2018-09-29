import sys, argparse, os
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

def get_script_path():
    return os.path.dirname(os.path.realpath(sys.argv[0]))

def parse_inputs(nuc_file, pep_file, ribosome_file):
    if not nuc_file or not pep_file or not ribosome_file:
        script_location = get_script_path()
        nuc_file = script_location + "/../../dbs/amr_dbs/amrdb_nucleotides.fasta"
        pep_file = script_location + "/../../dbs/amr_dbs/amrdb_peptides.fasta"
        ribosome_file = script_location + "/../../dbs/amr_dbs/amrdb_rRNA.fasta"

    return nuc_file, pep_file, ribosome_file

def parse_fasta(nuc_file, pep_file, ribosome_file):
    max_id = 0
    file_list = [nuc_file, pep_file, ribosome_file]
    for i in file_list:
        for record in SeqIO.parse(i, 'fasta'):
            header = AMRentry(record.description)
            max_id = max(header.amrdb_id, max_id)

    return max_id


def main():
    parser = argparse.ArgumentParser(description = "Generate Lagest AMR-ID")
    parser.add_argument('-n', '--nuc', help = "AMR-DB nucleotide FASTA file.")
    parser.add_argument('-p', '--pep', help = "AMR-DB peptide FASTA file.")
    parser.add_argument('-r', '--rRNA', help = "AMR-DB rRNA FASTA file.")

    args = parser.parse_args()
    nuc_file = args.nuc
    pep_file = args.pep
    ribosome_file = args.rRNA

    nuc_file, pep_file, ribosome_file = parse_inputs(nuc_file, pep_file, ribosome_file)

    max_id = parse_fasta(nuc_file, pep_file, ribosome_file)
    print max_id





if __name__=='__main__':
    main()
