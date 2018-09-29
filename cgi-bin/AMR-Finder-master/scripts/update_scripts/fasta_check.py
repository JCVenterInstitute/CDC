import argparse
from Bio import SeqIO

class AMRentry:
    """A class that loads the standard AMR-DB FASTA entry."""
    def __init__(self, header):
        header_split = header.split("|")
        self.source = header_split[0]
        self.id = header_split[1].split(":")[0]
        self.cds = header_split[1].split(":")[1]
        self.gene_symbol = header_split[2]
        self.allele = header_split[3]
        self.drug_class = header_split[4]
        self.drug_family = header_split[5]
        self.drugs = header_split[6]
        self.parent_allele = header_split[7]
        self.parent_allele_family = header_split[8]
        self.snp_info = header_split[9]
        #self.seq = seq

    def gene_name(self):
        if self.allele == "":
            name = self.gene_symbol
        else:
            name = self.gene_symbol + "-" + self.allele
        return name

    def __getitem__(self, item):
        return getattr(self, item)

    def __eq__(self, other):
        if self.__class__ != other.__class__:
            return False

        if self.__dict__ == other.__dict__:
            return True
        else:
            categories = ["source", "id", "cds", 'gene_symbol', "allele", "drug_class", "drug_family", "drugs", "parent_allele", "parent_allele_family", "snp_info"]
            this = vars(self)
            compare = vars(other)
            for i in categories:
                if this[i].strip() == compare[i].strip():
                    pass
                else:
                    print i
                    print "Nucleotide: ", this[i]
                    print "Peptide: ", compare[i]
            return False

def parse_fasta(input_file):
    headers = {}
    seqs = []
    for record in SeqIO.parse(input_file, 'fasta'):
        try:
            header = AMRentry(record.description)
            headers[header.gene_name()] = header
        except:
            print "{}'s header is not formatted properly."
        seqs.append(str(record.seq))

    return headers, seqs


def check_headers_match(nuc_headers, pep_headers):
    for i in nuc_headers:
        if i in pep_headers:
            if not nuc_headers[i] == pep_headers[i]:
                print nuc_headers[i].gene_name(), pep_headers[i].gene_name()

def main():
    parser = argparse.ArgumentParser(description = "Update AMR-DB GenBank Metadata given list of GenBank ID's.")
    parser.add_argument("-n", '--nuc_input_file', help = "A nucleotide input file to be merged into AMR-DB.")
    parser.add_argument("-p", '--pep_input_file', help = "A peptide input file to be merged into AMR-DB.")
    args = parser.parse_args()
    nuc_file = args.nuc_input_file
    pep_file = args.pep_input_file


    if nuc_file:
        nuc_headers, nuc_seqs = parse_fasta(nuc_file)
    if pep_file:
        pep_headers, pep_seqs = parse_fasta(pep_file)
    if nuc_file and pep_file:
        check_headers_match(nuc_headers, pep_headers)




if __name__=="__main__":
    main()
