from Bio.Blast.Applications import NcbiblastpCommandline, NcbiblastnCommandline
from Bio.Blast import NCBIXML
from Bio import SeqIO, AlignIO
from Bio.Seq import Seq
from Bio.Align.Applications import MuscleCommandline
from collections import defaultdict
import argparse, sys, subprocess, os.path, logging
from datetime import date
from shutil import copyfile, move, rmtree
import glob

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

def fix_fasta(input_fasta):
    with open(input_fasta + ".tmp", 'w') as f:
        for record in SeqIO.parse(input_fasta, 'fasta'):
            desc = record.description.replace(" ", "_").replace("/", "-")
            f.write(">{}\n".format(desc))
            f.write("{}\n".format(str(record.seq)))
    subprocess.call(['mv', input_fasta + ".tmp", input_fasta])


def get_script_path():
    return os.path.dirname(os.path.realpath(sys.argv[0]))

def check_seq_type(input_fasta):
    seq_type = "nuc"
    for record in SeqIO.parse(input_fasta, 'fasta'):
        sequence = str(record.seq)
        not_nuc_sequence = [i for i in sequence if i not in "ACTGN"]
        if not_nuc_sequence:
            seq_type = "pep"
        break
    return seq_type

def make_dir(input_fasta_name):
    if os.path.isdir(input_fasta_name):
        print
        response = raw_input("The output directory already exists. Would you like to delete this directory and re-run from scratch? yes or no: ")
        if response.lower() == 'yes':
            rmtree(input_fasta_name)
            os.makedirs(input_fasta_name)
        else:
            print
            print "Canceling run to avoid writing over data in {} directory".format(input_fasta_name)
            print
            logging.error("Cancelling run to avoid writing over data in output directory.")
            print
            sys.exit()
    else:
        os.makedirs(input_fasta_name)


def get_dir(output, sanitized_input_fasta):
    if output:
        dir = output
    else:
        dir = sanitized_input_fasta.rsplit(".", 1)[0]
    return dir

def check_inputs(nuc_run, pep_run, blast_method, input_fasta, source_db, script_location, rRna_run, output_loc, server_run):
    if server_run:
        fix_fasta(input_fasta)
    if source_db:
        source_db = source_db
    else:
        attempted_source_db = {'blastn' : script_location + "/../../dbs/amr_dbs/amrdb_nucleotides.fasta",
                              'blastp': script_location + "/../../dbs/amr_dbs/amrdb_peptides.fasta"}

        source_db = attempted_source_db[blast_method]

    source_db_type = check_seq_type(source_db)
    if source_db_type == "pep" and blast_method == 'blastn':
        print "Cannot run BLASTN on a Peptide BLAST File."
        logging.error("Cannot run BLASTN on a Peptide BLAST DB.")
    if source_db_type == 'nuc' and blast_method == 'blastp':
        print "Cannot run BLASTP on a Nucleotide BLAST File."
        logging.error("Cannot run BLASTP on a Nucleotide BLAST DB.")

    if nuc_run and blast_method == 'blastp':
        input_fasta = translate_fasta_file(input_fasta, output_loc)
    if pep_run and blast_method == 'blastn':
        print
        print "Can't run blastn on a peptide fasta file. Change the blast method to blastp"
        logging.warning("Can't run blastn on a peptide fasta file. Change the blast method to blastp")
        print
        sys.exit()

    if nuc_run and blast_method == 'blastn':
        print "Cannot confirm SNP based resistance when searching nucleotides (except rRNA genes)."
        logging.warning("Cannot confirm SNP based resistance when searching nucleotides (except rRNA genes).")


    if os.path.isfile(source_db):
        pass
    else:
        print "Error: Can't find the reference database. Try setting the source db (-db)."
        print
        sys.exit()

    if rRna_run and nuc_run:
        rna_db = script_location + "/../../dbs/amr_dbs/amrdb_rRNA.fasta"
    elif rRna_run and pep_run:
        print
        print "Error: Can't look for rRNA genes from protein input."
        logging.warning("Error: Can't look for rRNA genes from protein input.")
        print
        rna_db = False
    else:
        rna_db = False
    return input_fasta, source_db, rna_db

def cleanup(dir, db_name, blast_type):
    print "Cleaning up {}/.".format(dir)
    logging.info("Cleaning up {}.".format(dir))
    pep_files = [db_name + '.phr', db_name + '.pin', db_name + '.psq', db_name + '.fasta']
    nuc_files = [db_name + '.nhr', db_name + '.nin', db_name + '.nsq', db_name + '.fasta']
    if blast_type == 'blastn':
        for i in nuc_files:
            os.remove(i)
    if blast_type == 'blastp':
        for i in pep_files:
            os.remove(i)

def clean_alignments(dir):
    print "Removing FASTA alignments."
    for i in glob.glob(dir + "/*_hit.fasta"):
        os.remove(i)
#----------------------------------------------------------------------------------#
#AMR FINDER SPECIFIC FUNCTIONS
def translate_fasta_file(fasta_file, output_loc):
    fasta_file_name = "/".join(fasta_file.split("/")[-2:]).rsplit(".", 1)[0]
    if output_loc:
        out_file_name = output_loc + "/" + fasta_file.rsplit("/", 1)[1].rsplit(".", 1)[0] + "_translated.fa"
    else:
        out_file_name = fasta_file_name + "_translated.fa"
    if os.path.isfile(out_file_name):
        print
        print "A translated fasta file is present. Using this file. If you don't want this-- delete {}_translated.fa".format(fasta_file_name)
        logging.info("A translated fasta file is present. Using this file. If you don't want this-- delete {}_translated.fa".format(fasta_file_name))
        print
    else:
        print
        print "\nTranslating the input file using prodigal."
        logging.info("\nTranslating the input file using prodigal.")
        try:
            sp = subprocess.Popen(['prodigal', '-i', fasta_file, '-a', out_file_name, '-q', '-o', '/dev/null'], stdout=subprocess.PIPE, stderr=subprocess.PIPE)
            out, err = sp.communicate()
            if err:
                subprocess.call(['prodigal', '-i', fasta_file, '-a', out_file_name, '-p', 'meta', '-q', '-o', '/dev/null'])
                print "\nSequence was found to be too short for Prodigal translation. Running with the -p meta option."
                logging.info("\nSequence was found to be too short for Prodigal translation. Running with the -p meta option.")
        except:
            print "\nCouldn't run prodigal. Check your inputs and try again."
            logging.error("\nCouldn't run prodigal. Check your inputs and try again.")
            sys.exit()
    return out_file_name

def make_blast_db(blast_type, source_db, fasta_file_name, output_loc, server_run):
    if output_loc:
        out_db_loc = output_loc + "/"
    elif fasta_file_name.count("/") > 1:
        out_db_loc = fasta_file_name.rsplit("/", 1)[1].split("_rRNA")[0] + "/"
    else:
        out_db_loc = os.getcwd() + "/" + fasta_file_name.split("_rRNA")[0] + "/"
    copy_file_loc = out_db_loc + source_db.split("/")[-1]
    copyfile(source_db, copy_file_loc)
    gene_seq_dict = {}
    gene_lengths_dict = {}
    for record in SeqIO.parse(source_db, 'fasta'):
        entry = AMRentry(record.description)
        if server_run:
            gene_lengths_dict[entry.amrdb_id] = len(str(record.seq))
            gene_seq_dict[entry.amrdb_id] = str(record.seq)
        else:
            gene_lengths_dict[entry.gene_name()] = len(str(record.seq))
            gene_seq_dict[entry.gene_name()] = str(record.seq)
    db_name = copy_file_loc.rsplit(".", 1)[0]
    if blast_type == 'blastn':
        dbtype = 'nucl'
    if blast_type == 'blastp':
        dbtype = 'prot'

    subprocess.call(['makeblastdb', '-in', copy_file_loc, "-dbtype", dbtype, '-out', db_name])
    return db_name, gene_lengths_dict, gene_seq_dict

def run_blast(input_fasta_file, blast_db, threads, blast_type):
    out_xml_file = input_fasta_file.strip()+'.xml'
    if os.path.isfile(out_xml_file):
        print
        print "\nUsing the already made xml file -- {}. If you want a different blast method, delete the xml file and run again.".format(out_xml_file)
        logging.info("\nUsing the already made xml file -- {}. If you want a different blast method, delete the xml file and run again.".format(out_xml_file))
        print
    else:
        print
        print "\nRunning {} on {}.".format(blast_type, input_fasta_file)
        logging.info("\nRunning {} on {}.".format(blast_type, input_fasta_file))
        print
        if blast_type == 'blastn':
            cline = NcbiblastnCommandline(query= input_fasta_file, db = blast_db, evalue = 1e-10, outfmt =5, out = input_fasta_file + '.xml', max_target_seqs = 10, num_threads = threads)
            stdout, stderr = cline()
        if blast_type == 'blastp':
            cline = NcbiblastpCommandline(query = input_fasta_file, db = blast_db, evalue = 1e-10, outfmt = 5, out = input_fasta_file+'.xml', max_target_seqs = 10, num_threads = threads)
            stdout, stderr = cline()

def parse_blast_results(input_fasta_file, percent_identity, query_cover, blast_type, gene_lengths_dict, rRNA, server_run):
    print "\nParsing the blast results."
    logging.info("\nParsing the blast results.")
    if blast_type == 'blastn' and not rRNA:
        print "\nYou chose to run blastn. SNP based resistance genes cannot be checked and will not be reported."
        logging.warning("\nYou chose to run blastn. SNP based resistance genes cannot be checked and will not be reported.")

    out_dict = defaultdict(lambda: defaultdict(dict))
    single_alleles = defaultdict(lambda: defaultdict(dict))
    multi_alleles = defaultdict(lambda: defaultdict(dict))
    xml_file = input_fasta_file.strip() + ".xml"
    result_handle = open(xml_file)
    blast_records = NCBIXML.parse(result_handle)
    try:
        for record in blast_records:
            hits_dict = {}
            seq_hits = {} #Hits to Query Sequence
            query = record.query.split(" ")[0]
            if record.alignments:
                for alignment in record.alignments:
                    hit_allele = alignment.title.split(" ")[1]
                    hit_allele = AMRentry(hit_allele)
                    for hsp in alignment.hsps:
                        query_length = int(hsp.query_end - hsp.query_start + 1)
                        loc = str(hsp.query_start) + "-" + str(hsp.query_end)
                        query_id = query + ":" + loc
                        if server_run:
                            ref_length = gene_lengths_dict[hit_allele.amrdb_id]
                        else:
                            ref_length = gene_lengths_dict[hit_allele.gene_name()]
                        calculated_query_cover = round( float(query_length) / float(ref_length) * 100, 2)
                        calculated_perc_id = round(float(hsp.positives) / float(hsp.align_length) * 100, 2)
                        if (calculated_perc_id >= percent_identity) and (calculated_query_cover > query_cover):
                            if blast_type == 'blastp':
                                if hit_allele.snp_info:
                                    snps_present = determine_snp_presence(hsp, hit_allele, blast_type)
                                    if snps_present:
                                        hits_dict[hit_allele] = {"Query": query_id, "Percent Identity": str(calculated_perc_id), "SNP": ",".join(snps_present), "Query Cover": str(calculated_query_cover), "E-Value": str(hsp.expect),"Bit Score": str(hsp.bits)}
                                        seq_hits[hit_allele] = hsp.query
                                else:
                                    hits_dict[hit_allele] = {"Query": query_id, "Percent Identity": str(calculated_perc_id), "SNP": "", "Query Cover": str(calculated_query_cover), "E-Value": str(hsp.expect),"Bit Score": str(hsp.bits)}
                                    seq_hits[hit_allele] = hsp.query
                            else: #Blast Type BLASTN
                                if rRNA:
                                    if hit_allele.snp_info:
                                        snps_present = determine_snp_presence(hsp, hit_allele, blast_type)
                                        if snps_present:
                                            hits_dict[hit_allele] = {"Query": query_id, "Percent Identity": str(calculated_perc_id), "SNP": ",".join(snps_present), "Query Cover": str(calculated_query_cover), "E-Value": str(hsp.expect),"Bit Score": str(hsp.bits)}
                                            seq_hits[hit_allele] = hsp.query

                                else:
                                    if hit_allele.snp_info:
                                        print "\nFound {}, which confers resistance based on SNP presence. Cannot confirm SNP presence because you are searching Nucleotides.".format(hit_allele.gene_name())
                                        logging.warning("\nFound {}, which confers resistance based on SNP presence. Cannot confirm SNP presence because you are searching Nucleotides.".format(hit_allele.gene_name()))
                                        hits_dict[hit_allele] = {"Query": query_id, "Percent Identity": str(calculated_perc_id), "SNP": "WARNING", "Query Cover": str(calculated_query_cover), "E-Value": str(hsp.expect),"Bit Score": str(hsp.bits)}
                                        seq_hits[hit_allele] = hsp.query
                                    else:
                                        hits_dict[hit_allele] = {"Query": query_id, "Percent Identity": str(calculated_perc_id), "SNP": "", "Query Cover": str(calculated_query_cover), "E-Value": str(hsp.expect),"Bit Score": str(hsp.bits)}
                                        seq_hits[hit_allele] = hsp.query

            if hits_dict:
                best_hits = get_best_hit(hits_dict)
                for i in best_hits:
                    for k,v in i.items():
                        if len(best_hits) > 1:
                            v["Query"] = v["Query"] + "*"
                            multi_alleles[v["Query"]][k] = seq_hits[k]
                        else:
                            single_alleles[v["Query"]][k] = seq_hits[k]
                        if not out_dict[k][input_fasta_file.rsplit(".", 1)[0]]:
                            out_dict[k][input_fasta_file.rsplit(".", 1)[0]] = v
                        else:#Gene already found in genome as different best hit
                            current_values = out_dict[k][input_fasta_file.rsplit(".", 1)[0]]
                            new_values = v
                            if float(current_values['Bit Score'].replace("*", "").split("/")[0]) > float(new_values["Bit Score"].replace("*", "")):
                                if "*" not in current_values["Bit Score"]:
                                    current_values['Bit Score'] = current_values['Bit Score'] + "*"
                            elif float(current_values["Bit Score"].replace("*", "").split("/")[0]) == float(new_values['Bit Score'].replace("*", "")):
                                print float(current_values["Bit Score"].replace("*", "").split("/")[0]), float(new_values['Bit Score'].replace("*", ""))
                                combined_values = {}
                                combined_values["Query"] = current_values['Query'] + "/" + new_values["Query"]
                                combined_values["Percent Identity"] = current_values['Percent Identity'] + "/" + new_values["Percent Identity"]
                                combined_values["Query Cover"] = current_values['Query Cover'] + "/" + new_values["Query Cover"]
                                combined_values["E-Value"] = current_values['E-Value'] + "/" + new_values["E-Value"]
                                combined_values["Bit Score"] = current_values['Bit Score'] + "/" + new_values["Bit Score"]
                                if current_values["SNP"]:
                                    combined_values["SNP"] = current_values["SNP"] + "/" + new_values["SNP"]
                                else:
                                    combined_values["SNP"] = ""
                                out_dict[k][input_fasta_file.rsplit(".", 1)[0]] = combined_values
                            else: #New values bigger than old
                                if "*" not in new_values["Bit Score"]:
                                    new_values["Bit Score"] = new_values["Bit Score"] + "*"
                                    out_dict[k][input_fasta_file.rsplit(".", 1)[0]] = new_values
    except ValueError:
        print "\nYour BLAST xml file was empty."
        logging.warning("\nYour BLAST xml file was empty.")
        out_dict = {}
        out_alleles = {}

    return out_dict, multi_alleles, single_alleles

def add_hit_info(blast_results_dict, server_run):
    gene_dict = defaultdict(lambda: defaultdict(list))
    for k,v in blast_results_dict.items():
        gene_key = k.gene_name()
        if server_run:
            gene_key = k.amrdb_id
        for a,b in v.items():
            genome = a.split("/")[-1]
            gene_dict[genome][gene_key].append(blast_results_dict[k][a])

    out_dict = defaultdict(dict)
    for genome, hit_and_info in gene_dict.items():
        for hit, dicts in hit_and_info.items():
            top_stats = {}
            queries = []
            current_bit_score = 0.0
            for i in dicts:
                if i["Bit Score"] > current_bit_score:
                    current_bit_score = float(i["Bit Score"])
                    queries = [i["Query"]]
                    top_stats = i
                elif i["Bit Score"] == current_bit_score:
                    queries.append(i["Query"])
                else:
                    pass
            if len(queries) > 1:
                top_stats["Query"] = '/'.join(queries)
            out_dict[genome][hit] = top_stats

    return out_dict

#Blast Parsing Specific Functions
def determine_snp_presence(hsp, hit_allele, blast_type):
    snpsPresent = set()
    snps = hit_allele.snp_info
    #print hit_allele
    for snp in snps.split(","):
        orig = snp[0]
        pos = int(snp[1:-1]) #One based
        subject_gaps_inserted = hsp.sbjct[0:pos].count("-")
        real_query_pos = pos + subject_gaps_inserted #Zero based
        mut = snp[-1]
        if blast_type == 'blastn':
            mut = mut.replace("U", "T") #CARD transcribes some entries
        try:
            if hsp.query[real_query_pos - 1].encode("utf-8") == mut: #Account for difference in base system
                snpsPresent.add(snp.strip())
        except IndexError:
            pass #No snps found
    return list(snpsPresent)

def get_best_hit(hit_dictionary):
    list_of_hits = []
    best_results = {}
    for new_hit , new_scores in hit_dictionary.items():
        contig = new_scores['Query'].split(":")[0]
        hit_bit_score = new_scores['Bit Score']
        hit_perc_id = new_scores["Percent Identity"]
        if not best_results: #Save First entry-- might be only one: by default best hit
            best_results[new_hit] = {'Bit Score': hit_bit_score, "Percent Identity": hit_perc_id}
        else: #First check bit score, then check % ID
            for current_best_hit, current_scores in best_results.items():
                if float(current_scores['Bit Score']) < float(hit_bit_score):
                    best_results = {}
                    best_results[new_hit] = {'Bit Score': hit_bit_score, "Percent Identity": hit_perc_id}
                elif float(current_scores["Bit Score"]) == float(hit_bit_score):
                    #Check Percent ID's if bit scores are identical
                    if float(current_scores['Percent Identity']) < float(hit_perc_id):
                        best_results = {}
                        best_results[new_hit] = {'Bit Score': hit_bit_score, "Percent Identity": hit_perc_id}
                    elif float(current_scores["Percent Identity"]) == float(hit_perc_id):
                        best_results[new_hit] = {'Bit Score': hit_bit_score, "Percent Identity": hit_perc_id}
                    else: #Current perc id is higher than hits' percent identity
                        pass
                else: #Current bit score is higher than hit's bit score
                    pass


    for hit, blast_info in best_results.items():
        list_of_hits.append({hit : hit_dictionary[hit]})

    if len(best_results) > 1:
        print "\nThere were multiple best hits for part of the sequence within {}.\nCheck {}_multiple_alignments.phy for a full sequence alignment between the hit query sequence and each best hit.\nCheck {}_multiple_hits.fasta for the hit query allele sequences.\n".format(contig, contig, contig)
        logging.warning("There were multiple best hits for part of the sequence within {}.\nCheck {}_multiple_alignments.phy for a full sequence alignment between the hit query sequence and each best hit.\nCheck {}_multiple_hits.fasta for the hit query allele sequences.\n".format(contig, contig, contig))
        for hit, blast_info in best_results.items():
            print "Hit {}, with a bit score of {} and percent identity of {}.".format(hit.gene_name(), blast_info['Bit Score'], blast_info['Percent Identity'])
            logging.warning("Hit to {}, with a bit score of {} and percent identity of {}.".format(hit.gene_name(), blast_info['Bit Score'], blast_info['Percent Identity']))
    return list_of_hits

#---------------------------------------------------------------------------------#
#Output File Writing
def write_out_dict(out_dict, dir, input_fasta, Type):
    gene_order = []
    genome = []
    gene_info = []
    for k,v in out_dict.items():
        genome.append(k)
        for a,b in v.items():
            gene_order.append(a)
            for metadata in b.values():
                map(lambda x: x.encode("utf-8").strip(), metadata)
            gene_info.append(b["Percent Identity"] + "|" + b["Query Cover"] +  "|" + b["Query"].replace("|", "_") + "|" + b["E-Value"] + "|" + b["Bit Score"] + "|" + b["SNP"] )
        if Type == 'normal':
            out_file = dir + "/" + k + "_summary_file.txt"
        if Type == 'rRNA':
            out_file = dir + "/" + k + "_rRNA_summary_file.txt"
        if Type == 'combined':
            out_file = dir + "/" + input_fasta.rsplit(".", 1)[0] + "_combined_summary_file.txt"
        if os.path.isfile(out_file):
            print "\nResults have already been calculated. Check {} or delete it before running again.".format(out_file)
            logging.warning("\nResults have already been calculated. Check {} or delete it before running again.".format(out_file))
        else:
            with open(out_file, 'w') as f:
                f.write("Genome" + '\t' + '\t'.join(gene_order) + "\n")
                f.write(genome[0] + "\t" + "\t".join(gene_info) + "\n")

def write_single_hits(blast_dict, gene_seq_dict, alignment_dir, server_run):
    single_hits_files = []
    seen_genes = set()
    for contig, hit_info in blast_dict.items():
        count = 1
        for hit, seq in hit_info.items():
            if server_run:
                ref_seq = gene_seq_dict[hit.amrdb_id]
                out_fasta_file = alignment_dir + "/" + hit.amrdb_id + "_hit.fasta"
                gff_file = open(alignment_dir + "/" + hit.amrdb_id + "_hit.gff3", 'w')
                snps = set()
                if hit.snp_info != "":
                    for i in hit.snp_info.split(","):
                        snps.add(i[1:-1])
                gff_file.write("##gff-version 3\n")
                if snps:
                    for snp in list(snps):
                        gff_file.write("{}\t{}\t{}\t{}\t{}\t{}\t{}\t{}\t{}\n".format("Variants", ".", "SNP", snp, snp, ".", ".", ".", "Name=;Color=red;Fontsize=8"))
                if not snps:
                    gff_file.write("{}\t{}\t{}\t{}\t{}\t{}\t{}\t{}\t{}\n".format("Variants", ".", "SNP", ".", ".", ".", ".", ".", "Name=;Color=red;Fontsize=8"))
                gff_file.close()
            else:
                ref_seq = gene_seq_dict[hit.gene_name()]
                out_fasta_file = alignment_dir + "/" + hit.gene_name() + "_hit.fasta"
            with open(out_fasta_file, 'a') as out_fasta:
                gene_name = hit.gene_name()
                out_fasta.write(">Query_{}|".format(count) + contig + "\n")
                count = count + 1
                out_fasta.write(seq + "\n")
                if gene_name not in seen_genes: #Multiple Hits to same gene
                    out_fasta.write(">Hit|" + gene_name + "\n")
                    out_fasta.write(ref_seq + "\n")
                    seen_genes.add(gene_name)
                single_hits_files.append(out_fasta_file)
    return single_hits_files


def write_multiple_hits(multiple_hits_dict, gene_seq_dict, dir, server_run):
    multiple_hits_files = []
    contig = 1
    for contig, query_info in multiple_hits_dict.items():
        try:
            real_contig_name = contig.split(":")[0]
            os.makedirs(dir + "/" + real_contig_name)
        except OSError:
            pass
        out_fasta_file = dir + "/" + real_contig_name + "/"+ real_contig_name  + "_hit.fasta"
        out_gff_file = dir + "/" + real_contig_name + "/"+ real_contig_name  + "_hit.gff3"
        out_html_file =  dir + "/" + real_contig_name + "/"+ real_contig_name  + "_hit.html"
        gff_file = open(out_gff_file, 'w')
        html_file = open(out_html_file, 'w')
        with open(out_fasta_file, 'w') as out_fasta:
            out_fasta.write(">Query_{}|".format(count) + real_contig_name + "\n")
            count = count + 1
            if server_run:
                snps = set()
                out_fasta.write(gene_seq_dict[query_info.keys()[0].amrdb_id] + "\n")
                if query_info.keys()[0].snp_info != "":
                    for i in query_info.keys()[0].snp_info.split(","):
                        snps.add(i[1:-1].strip())
                gff_file.write("##gff-version 3\n")
                if snps:
                    for snp in list(snps):
                        gff_file.write("{}\t{}\t{}\t{}\t{}\t{}\t{}\t{}\t{}\n".format("Variants", ".", "SNP", snp, snp, ".", ".", ".", "Name=;Color=red;Fontsize=8"))
                if not snps:
                    gff_file.write("{}\t{}\t{}\t{}\t{}\t{}\t{}\t{}\t{}\n".format("Variants", ".", "SNP", ".", ".", ".", ".", ".", "Name=;Color=red;Fontsize=8"))
                gff_file.close()
            else:
                out_fasta.write(gene_seq_dict[query_info.keys()[0].gene_name()] + "\n")
            gene_names = []
            gene_name_count = 1
            for hit, seq in query_info.items():
                gene_name = hit.gene_name().replace("(", "").replace(")", "").replace(" ", '_')
                if gene_name in gene_names:
                    gene_name = gene_name + "_" + str(gene_name_count)
                    gene_name_count = gene_name_count + 1
                gene_names.append(gene_name)
                out_fasta.write(">Hit|" + gene_name + "\n")
                out_fasta.write(str(seq) + "\n")
                multiple_hits_files.append(out_fasta_file)
    return multiple_hits_files

def create_alignment(out_fasta_files, server_run):
    for i in out_fasta_files:
        records = (r for r in SeqIO.parse(i, 'fasta'))
        muscle_cline = MuscleCommandline()
        child = subprocess.Popen(str(muscle_cline), stdin = subprocess.PIPE, stdout = subprocess.PIPE, stderr = subprocess.PIPE)
        SeqIO.write(records, child.stdin, 'fasta')
        child.stdin.close()
        align = AlignIO.parse(child.stdout, 'fasta')
        phy_file = i.rsplit("_", 1)[0] + "_alignments.phy"
        AlignIO.write(align, phy_file, 'phylip-relaxed')
        if server_run:
            align = AlignIO.parse(phy_file, 'phylip-relaxed')
            out_file = i.rsplit("_", 1)[0] + "_alignments.fa"
            AlignIO.write(align, out_file, 'fasta')
            html_file = open(i.rsplit("_", 1)[0] + "_hit.html", 'w')
            with open(out_file, 'r+') as f:
                content = f.read()
                f.seek(0,0)
                f.write(">Variants\n" + content)
            html_file.write(generate_html(i.rsplit("_", 1)[0].rsplit("/", 1)[1]))
            html_file.close()


def make_orf_dict(prot_fasta_file):
    out_dict = {}
    for record in SeqIO.parse(prot_fasta_file, 'fasta'):
        out_list = record.description.strip().split("#")
        contig = out_list[0].strip().split(":")[0]
        start = str(int(out_list[1].strip()) - 1)
        end = out_list[2].strip()
        orient = out_list[3].strip()
        out_dict[contig] = [int(start), int(end), orient]
    return out_dict

def write_nuc_from_orf(seqs_dir, hit_alleles, orf_dict, nuc_fasta_file):
    contig_to_nucSeq_dict = defaultdict()
    for record in SeqIO.parse(nuc_fasta_file, 'fasta'):
        contig = record.description.split(" ")[0]
        contig_to_nucSeq_dict[contig] = {record.description : str(record.seq)}

    for contig, hit_info in hit_alleles.items():
        contig = contig.split(":")[0]
        contig_only = contig.rsplit("_", 1)[0]
        for hit_info, pep_seq in hit_info.items():
            gene_hit = hit_info.gene_name() #File Name Prefix
            orientation = orf_dict[contig][2]
            if orientation.startswith("-"):
                seq = Seq(contig_to_nucSeq_dict[contig_only].values()[0][orf_dict[contig][0] : orf_dict[contig][1]])
                seq = str(seq.reverse_complement())
                start = orf_dict[contig][1]
                end = orf_dict[contig][0]
            else:
                seq = contig_to_nucSeq_dict[contig_only].values()[0][orf_dict[contig][0] : orf_dict[contig][1]]
                start = orf_dict[contig][0]
                end = orf_dict[contig][1]
            with open(seqs_dir + gene_hit + "_nuc.fasta", 'w') as f:
                f.write(">" + contig + ":"  + str(start) + "-" + str(end) + "\n")
                f.write(seq + "\n")

def write_seqs(seqs_dir, hit_alleles, blast_type):
    output_suffix = {"blastp" : "_pep", "blastn" : "_nuc"}
    for contig, hit_info in hit_alleles.items():
        for header, seq in hit_info.items():
            gene_name = header.gene_name()

            with open(seqs_dir + gene_name + output_suffix[blast_type] + ".fasta", 'w') as f:
                f.write(">" + contig + "\n")
                f.write(seq + "\n")



def combine_results(amr_dict, rna_dict):
    for genome, gene_info in rna_dict.items():
        for gene, info in gene_info.items():
            amr_dict[genome][gene] = info
    return amr_dict

def run_plasmidfinder(dir, plasmidfinder, input_fasta, script_location):
    plasmidfinder_exe = script_location + "/../analysis/plasmidfinder.py"
    subprocess.call(["python", plasmidfinder_exe, "-p", plasmidfinder, "-i", input_fasta, "-o", dir + "/"])

def merge_amr_and_plasmidfinder(dir, script_location):
    merge_script_exe = script_location + "/../analysis/find_plasmid_amr_genes.py"
    subprocess.call(["python", merge_script_exe, "-d", dir, '-o', dir])

def generate_html(prefix):
    alignment_file = prefix + "_alignments.fa"
    gff_file = prefix + "_hit.gff3"

    html_string = """<!DOCTYPE html>
    <html>
    <head>
    <meta name="description" content="Example of loading MSAViewer from a string">
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width">
      <title>JS Bin</title>
    </head>
    <body>
    <div id="yourDiv"></div>
    <script src="https://cdn.bio.sh/msa/latest/msa.min.gz.js"></script>
    <!--<script src="./../dist/msa.js"></script>-->
    <script>
    /* global yourDiv */
    var clustal = msa.io.clustal;
    var gffParser = msa.io.gff;
    var xhr = msa.io.xhr;
    var menuDiv = document.createElement('div');
    var msaDiv = document.createElement('div');
    yourDiv.appendChild(menuDiv);
    yourDiv.appendChild(msaDiv);

    var opts = {
      el: msaDiv,
      importURL: "./%s",
    };

    var m = msa(opts);

    // add features
    xhr("./%s", function(err, request, body) {
      var features = gffParser.parseSeqs(body);
      m.seqs.addFeatures(features);
      m.render();
    });

    // the menu is independent to the MSA container
    var defMenu = new msa.menu.defaultmenu({
      el: menuDiv,
      msa: m
    });
    defMenu.render();
    </script>
    </body>
    </html>
    """
    out_string = html_string % (alignment_file, gff_file)
    return out_string

def main():
   #------------------------------------------------------------------------------#
   #Gather Inputs
    parser = argparse.ArgumentParser(description = "Generates AMR Summary File")
    group = parser.add_mutually_exclusive_group(required = True)
    parser.add_argument('-i', '--input_fasta', help = "Input fasta file.", required = True)
    group.add_argument("-n", '--nuc', help = "Nucleotide fasta file", action = 'store_true')
    group.add_argument("-p", "--pep", help = "Peptide fasta file.", action = 'store_true')
    parser.add_argument('-c', '--percent_identity_cutoff', help = 'Minimum Percent Identity. Default of 35 Percent.', default = 35)
    parser.add_argument('-q', '--query_cover', help = 'Minimum Percent of Query Sequence length that the Alignment length must be. Default is 60 Percent.', default = 60)
    parser.add_argument('-t', '--threads', help = "Number of Threads to use in BLAST run. Default is 1.", default = 1)
    parser.add_argument("-b", '--blast_type', help = "Choose Blast Method. Default is BLASTP", choices = ['blastn', 'blastp'], default = 'blastp')
    parser.add_argument("-db", '--source_db', help = "FASTA file to use as Blast DB. If not given, the script attempts to find within the AMR-Finder directory (/AMR-Finder/dbs/amr_dbs/)")
    parser.add_argument("-rRNA", '--run_rRNA_blast', help = "Run BLASTN on rRNA database. Only if providing a Nucleotide fasta file. Default is no. Will use /AMR-Finder/dbs/amr_dbs/amrdb_rRNA.fasta", action = 'store_true')
    parser.add_argument('--alignments', help = "Generate alignment files for each reference gene and corresponding BLAST hit. Default is no.", action = 'store_true')
    parser.add_argument("--plasmidfinder", help = "Run plasmidfinder (with default options of 95 percent identity and 60 percent length cutoff) and output which AMR genes are on plasmids. Choose 1 for enterobacteriacae or 2 for gram positive.", choices = ["1", "2"])
    parser.add_argument("--keep_hit_seqs", help = "Keep query sequences (Nucleotide and Peptide-- whatever is available) for BLAST Hits. Default is no.", action = "store_true")
    parser.add_argument('--server', help = "Run with output as AMR-ID instead of gene name.", action = 'store_true')
    parser.add_argument("-o", '--output', help = "Output folder to write to. Default is the input files prefix.")

    args = parser.parse_args()
    input_fasta = args.input_fasta

    nuc_run = args.nuc
    pep_run = args.pep
    perc_id = float(args.percent_identity_cutoff)
    query_cover = float(args.query_cover)
    threads = args.threads
    blast_method = args.blast_type
    source_db = args.source_db
    input_fasta = args.input_fasta
    rRNA_run = args.run_rRNA_blast
    alignment_generation = args.alignments
    plasmidfinder = args.plasmidfinder
    keep_hit_seqs = args.keep_hit_seqs
    server_run = args.server
    output = args.output

    #-------------------------------------------------------------------------------#
    #Book Keeping
    full_path_input_fasta = input_fasta
    sanitized_input_fasta = input_fasta.split("/")[-1] #Removes preceding path info
    seq_type = check_seq_type(input_fasta)
    script_location = get_script_path()
    dir = get_dir(output, sanitized_input_fasta)
    make_dir(dir)

    if alignment_generation:
        alignment_dir = dir + "/alignments/"
        make_dir(alignment_dir)

    if keep_hit_seqs:
        seqs_dir = dir + "/seqs/"
        make_dir(seqs_dir)



    log_file = dir + "/amr-finder.log"
    logging.basicConfig(format='%(message)s', filename = log_file, filemode = 'w', level = logging.INFO)
    logging.info("Date: {}".format(date.today()))
    copyfile(full_path_input_fasta, dir + "/" + sanitized_input_fasta) # Need full path for initial file, but not the second

    #Initialize Output Dicts for combining later
    out_dict = {}
    rrna_out_dict = {}

    #Input Checking
    fasta_to_use, source_db, rna_db = check_inputs(nuc_run, pep_run, blast_method, dir + "/" + sanitized_input_fasta, source_db, script_location, rRNA_run, output, server_run)
    #fasta_to_use is either the copied fasta file or the translated fasta file-- full path
    #---------------------------------------------------------------------------------------#
    #AMR Finder Run
    logging.info("Running {} on {} with a percent identity cutoff of {} and query cover cutoff of {} on the blast database: {}".format(blast_method, fasta_to_use, perc_id, query_cover, source_db))
    db_name, gene_lengths_dict, gene_seq_dict = make_blast_db(blast_method, source_db, dir, output, server_run)
    run_blast(fasta_to_use, db_name, threads, blast_method)
    blast_result_dict, multiple_hit_alleles, single_hit_alleles = parse_blast_results(fasta_to_use, perc_id, query_cover, blast_method, gene_lengths_dict, False, server_run)



    out_dict = add_hit_info(blast_result_dict, server_run)
    cleanup(dir, db_name, blast_method)

    if not out_dict: #No Results
        print "There are no BLAST results that meet the cutoffs.\nEnsure that your inputs are correct (Nucleotide or Peptide File)"
        logging.error("There are no BLAST results that meet the cutoffs. Ensure that your inputs are correct (Nucleotide or Peptide File)")
    else:
        if alignment_generation:
            single_hits_files = write_single_hits(single_hit_alleles, gene_seq_dict, alignment_dir, server_run)
            print
            print "Creating alignment files."
            create_alignment(single_hits_files, server_run)
            clean_alignments(alignment_dir)
        if keep_hit_seqs:
            if blast_method == 'blastp' and nuc_run: #Also get nucleotide sequences
                orf_dict = make_orf_dict(fasta_to_use)
                orig_fasta_file = dir + "/" + sanitized_input_fasta
                write_nuc_from_orf(seqs_dir, single_hit_alleles, orf_dict, orig_fasta_file)
            print
            print "Writing FASTA files."
            write_seqs(seqs_dir, single_hit_alleles, blast_method)

        multi_fasta_files = write_multiple_hits(multiple_hit_alleles, gene_seq_dict, dir, server_run)
        create_alignment(multi_fasta_files, server_run)


    if rna_db:
        new_input_fasta = sanitized_input_fasta.split(".")[0] + "_rRNA." + input_fasta.split(".")[1]
        copyfile(input_fasta, dir + "/" + new_input_fasta)
        db_name, gene_lengths_dict, gene_seq_dict = make_blast_db('blastn', rna_db, dir, output, server_run)
        run_blast(dir + "/" + new_input_fasta, db_name, threads, 'blastn')
        blast_result_dict, multiple_hit_alleles, single_hit_alleles = parse_blast_results(dir + "/" + new_input_fasta, perc_id, query_cover, 'blastn', gene_lengths_dict, True, server_run)
        rrna_out_dict = add_hit_info(blast_result_dict, server_run)
        cleanup(dir, db_name, 'blastn')
        if not rrna_out_dict:
            print "There are no rRNA BLAST results that meet the cutoffs.\n"
            logging.info("There are no rRNA BLAST results that meet the cutoffs.")
        else:
            if alignment_generation:
                single_hits_files = write_single_hits(single_hit_alleles, gene_seq_dict, alignment_dir, server_run)
                create_alignment(single_hits_files, server_run)
            if keep_hit_seqs:
                write_seqs(seqs_dir, single_hit_alleles, 'blastn')
            multi_fasta_files = write_multiple_hits(multiple_hit_alleles, gene_seq_dict, dir, server_run)
            create_alignment(multi_fasta_files, server_run)

    if out_dict and not rrna_out_dict:
        write_out_dict(out_dict, dir, sanitized_input_fasta, 'normal')
    if rrna_out_dict and not out_dict:
        write_out_dict(rrna_out_dict, dir, sanitized_input_fasta, 'rRNA')
    if out_dict and rrna_out_dict:
        combined_dict = combine_results(out_dict, rrna_out_dict)
        write_out_dict(combined_dict, dir, sanitized_input_fasta, 'combined')
    if not out_dict and not rrna_out_dict:
        if output:
            with open(dir + "/" + dir.split("/")[0] + "_summary_file.txt", 'w') as f:
                f.write("Genome\n")
                f.write(dir)

    if plasmidfinder:
        print
        print "Running plasmidfinder."
        logging.info("Running plasmidfinder")
        print
        run_plasmidfinder(dir, plasmidfinder, input_fasta, script_location)
        merge_amr_and_plasmidfinder(dir, script_location)

if __name__=="__main__":
    main()
