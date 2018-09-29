import os, sys, json, shutil, urllib3, subprocess, argparse

def get_script_path():
    return os.path.dirname(os.path.realpath(sys.argv[0]))

def downloadCardDatabase(http, temp_dir):
    card_filename = temp_dir + "card.tar.bz2"
    if not os.path.exists(os.path.dirname(temp_dir)):
        os.makedirs(os.path.dirname(temp_dir))
        r = http.request("GET", 'https://card.mcmaster.ca/latest/data')
        with open(card_filename, 'w') as f:
            f.write(r.data)
        subprocess.call(['tar', 'jxf', card_filename, '--directory', temp_dir])
    else:
        subprocess.call(["rm", "-rf", temp_dir])
        downloadCardDatabase(http, temp_dir)

def compareCard(card_dir, temp_dir):
    out_dict = {}
    old_card = card_dir + "card.json"
    new_card = temp_dir + "card.json"

    with open(old_card, 'r') as o:
        oldCard = json.load(o)
    with open(new_card, 'r') as n:
        newCard = json.load(n)
    for k,v in newCard.items():
        if k not in oldCard:
            out_dict[k] = v
    return out_dict

def formatCardtoFasta(cardEntries, amrid_count):
    AMR_Categories = ["Aminocoumarin", "Aminoglycoside", "Beta-lactam", "Bicyclomycin", "Colistin", "Elfamycin", "Fluoroquinolone", "Fosfomycin", "Fusidic Acid", "Glycopeptide",
                        "Lincosamide", "Macrolide", "Nitroimidazole", "Oxazolidinone", "Phenicol", "Pleuromutilin", "Polymixin", "Rifamycin", "Streptogramin",
                        'Streptothricin', "Sulphonamide", "Tetracycline", "Triclosan", "Trimethoprim", "Daptomycin", "Ethambutol", "Ethionamide", "Isoniazid","Pyrazinamide",
                        "Rifamycin", "Mupirocin"]
    nuc = {}
    pep = {}
    pep_id_dict = {}
    rrna = {}
    count = 0
    next_amr_id = amrid_count
    source_db = "New_CARD"
    for k,v in cardEntries.items():
        main_class = set()
        if v['model_type'] == 'protein variant model':
            next_amr_id += 1
            count = count + 1
            drug_class = set()
            snps = ",".join(v['model_param']['snp']['param_value'].values())
            gene_description = v["ARO_name"]
            for i in AMR_Categories:
                if i.lower() in gene_description.lower():
                    main_class.add(i.replace(" ", "_"))
                elif i.lower() in v["ARO_description"].lower():
                    main_class.add(i.replace(" ", "_"))
                else:
                     for a in v["ARO_category"].values():
                        if i.lower() in a["category_aro_description"].lower():
                            main_class.add(i.replace(" ", "_"))
                        if a["category_aro_class_name"] == "Drug Class":
                            drug_class.add(a["category_aro_name"])
            for a,b in v["model_sequences"]["sequence"].items():
                gen_and_spec = b["NCBI_taxonomy"]["NCBI_taxonomy_name"]
                nuc_id = b["dna_sequence"]["accession"]
                cds = b['dna_sequence']["fmin"] + "-" + b["dna_sequence"]["fmax"]
                nuc_seq = b["dna_sequence"]["sequence"]
                try:
                    pep_id = b["protein_sequence"]["accession"]
                    pep_seq = b["protein_sequence"]["sequence"]
                except:
                    pass
            if gene_description.split(" ")[0:2] == gen_and_spec.split(" ")[0:2] and "23S" not in gene_description:
                gene_name = gene_description.split(" ")[2] + "_(" + "_".join(gen_and_spec.split(" ")[0:2]) + ")"
            else:
                gene_name = "_".join(gene_description.split(" "))
            main_class = list(main_class)
            drugs = ",".join(list(drug_class))
            header = ">" + source_db + "|" + str(next_amr_id) + "|" + nuc_id + ":" + cds + "|" + gene_name + "||" + "/".join(main_class) +"||" + drugs + "|||" + snps
            nuc[header] = nuc_seq
            if pep_seq:
                pep[header] = pep_seq
                pep_id_dict[header] = pep_id

        if v['model_type'] == 'rRNA gene variant model':
            next_amr_id += 1
            drug_class = set()
            count = count + 1
            snps = ",".join(v['model_param']['snp']['param_value'].values())
            gene_description = v["ARO_name"]
            for i in AMR_Categories:
                if i.lower() in gene_description.lower():
                    main_class.add(i.replace(" ", "_"))
                elif i.lower() in v["ARO_description"].lower():
                    main_class.add(i.replace(" ", "_"))
                else:
                     for a in v["ARO_category"].values():
                        if i.lower() in a["category_aro_description"].lower():
                            main_class.add(i.replace(" ", "_"))
                        if a["category_aro_class_name"] == "Drug Class":
                            drug_class.add(a["category_aro_name"])
            for a,b in v["model_sequences"]["sequence"].items():
                gen_and_spec = b["NCBI_taxonomy"]["NCBI_taxonomy_name"]
                nuc_id = b["dna_sequence"]["accession"]
                cds = b['dna_sequence']["fmin"] + "-" + b["dna_sequence"]["fmax"]
                nuc_seq = b["dna_sequence"]["sequence"]

            if gene_description.split(" ")[0:2] == gen_and_spec.split(" ")[0:2] and "23S" not in gene_description:
                gene_name = gene_description.split(" ")[2] + "_(" + "_".join(gen_and_spec.split(" ")[0:2]) + ")"
            else:
                gene_name = "_".join(gene_description.split(" "))
            main_class = list(main_class)
            drugs = ",".join(list(drug_class))
            header = ">" + source_db + "|" + str(next_amr_id) + "|" + nuc_id + ":" + cds + "|" + gene_name + "||" + "/".join(main_class) +"||" + drugs + "|||" + snps
            rrna[header] = nuc_seq

        if v['model_type'] == 'protein homolog model':
            next_amr_id += 1
            count = count + 1
            drug_class = set()
            #pprint.pprint(v)
            gene_name = v["ARO_name"]
            for i in AMR_Categories:
                if i.lower() in gene_name.lower():
                    main_class.add(i.replace(" ", "_"))
                elif i.lower() in v["ARO_description"].lower():
                    main_class.add(i.replace(" ", "_"))
                else:
                     for a in v["ARO_category"].values():
                        if i.lower() in a["category_aro_description"].lower():
                            main_class.add(i.replace(" ", "_"))
                        if a["category_aro_class_name"] == "Drug Class":
                            drug_class.add(a["category_aro_name"])
            for a,b in v["model_sequences"]["sequence"].items():
                gen_and_spec = b["NCBI_taxonomy"]["NCBI_taxonomy_name"]
                nuc_id = b["dna_sequence"]["accession"]
                cds = b['dna_sequence']["fmin"] + "-" + b["dna_sequence"]["fmax"]
                nuc_seq = b["dna_sequence"]["sequence"]
                try:
                    pep_id = b["protein_sequence"]["accession"]
                    pep_seq = b["protein_sequence"]["sequence"]
                except:
                    pass

            drugs = ",".join(list(drug_class))
            main_class = list(main_class)
            if ('Beta-lactam' in main_class or "Aminoglycoside" in main_class) and "-" in gene_name and "ef-tu" not in gene_name.lower() and 'beta-lactamase' not in gene_name.lower():
                gene_symbol = "bla" + gene_name.split("-")[0]
                gene_symbol = gene_symbol[0:4] + gene_symbol[4].upper() + gene_symbol[5:]
                allele = gene_name.split("-")[1]
                parent_allele = gene_name.split("-")[0]
                header = ">" + source_db + "|" + str(next_amr_id) + "|" + nuc_id + ":" + cds + "|" + "_".join(gene_symbol.split(" ")) + "|" + allele + "|" + "/".join(main_class) + "||" + drugs + "|" + parent_allele + "|||"
            else:
                header = ">" + source_db + "|" + str(next_amr_id) + "|" + nuc_id + ":" + cds + "|" + "_".join(gene_name.split(" ")) + "||" + "/".join(main_class) +"||"+ drugs + "||||"
            nuc[header] = nuc_seq
            if pep_seq:
                pep[header] = pep_seq
                pep_id_dict[header] = pep_id
    print
    print "There are " + str(count) + " new entries in CARD since the last AMR-DB update."
    print
    if count == 0:
        print "No new entries. No need to update."
        print
        sys.exit()
    return nuc, pep, pep_id_dict, rrna

def setupCDHit(nuc_dict, pep_dict, amrdbLocation, script_location):#,  rrna_dict):
    new_cd_hit_folder = script_location + "/CD-Hit/"
    if os.path.exists(new_cd_hit_folder):
        subprocess.call(['rm', '-r', new_cd_hit_folder])
    os.makedirs(new_cd_hit_folder)

    shutil.copy2(amrdbLocation + "amrdb_nucleotides.fasta", new_cd_hit_folder)
    shutil.copy2(amrdbLocation + "amrdb_peptides.fasta", new_cd_hit_folder)
    #shutil.copy2(amrdbLocation + "amrdb_rRNA.fasta", new_cd_hit_folder)
    with open( new_cd_hit_folder + '/amrdb_nucleotides.fasta', 'a') as nuc_out:
        for k,v in nuc_dict.items():
            nuc_out.write(k + "\n")
            nuc_out.write(v + "\n")

    with open(new_cd_hit_folder + "/amrdb_peptides.fasta", 'a') as pep_out:
        for k,v in pep_dict.items():
            pep_out.write(k + "\n")
            pep_out.write(v + "\n")


def runCDHit_est(input_file, output_file):
    subprocess.call(["cd-hit-est", "-i", input_file, "-o", output_file, "-d", "0", "-s", ".8", "-c", "1", "-g", "1"])
    out_list = []
    with open(output_file+".clstr", 'r') as f:
        cluster_members = []
        count = 0
        for row in f:
            if row.startswith(">"): #Cluster header
                if count == 0:
                    pass
                else:
                    if len(cluster_members) == 1:
                        if cluster_members[0].startswith(">New_CARD"):
                            out_list.append(cluster_members[0])
                count += 1
                cluster_members = [] # Reset List
            else:
                if not row.startswith(">"):
                    cluster_members.append(row.split("nt, ")[1].split("...")[0])
    return out_list

def runCDHit(input_file, output_file):
    subprocess.call(["cd-hit", "-i", input_file, "-o", output_file, "-d", "0", "-s", ".8", "-c", "1", "-g", "1"])
    out_list = []
    with open(output_file + ".clstr", 'r') as f:
        cluster_members = []
        count = 0
        for row in f:
            if row.startswith(">"): #Cluster header
                if count == 0:
                    pass
                else:
                    if len(cluster_members) == 1:
                        if cluster_members[0].startswith(">New_CARD"):
                            out_list.append(cluster_members[0])
                count += 1
                cluster_members = [] # Reset List
            else:
                if not row.startswith(">"):
                    cluster_members.append(row.split("aa, ")[1].split("...")[0])
    return out_list


def generate_new_fasta_fields(sequence_dict, header_list):
    out_dict = {}
    for i in header_list:
        if i in sequence_dict:
            out_dict[i] = sequence_dict[i]
    return out_dict

def write_fasta_files(out_dict, outfile):
    with open(outfile, 'w') as out:
        count = 0
        for k,v in out_dict.items():
            count += 1
            k = k.replace(">New_CARD|", ">CARD|")
            out.write(k + "\n")
            out.write(v + "\n")
    print "Wrote {} entries to {}.".format(count, outfile)

def cleanup_directory_structure(script_location):
    subprocess.call(['mv', script_location + "/New_Card/card.json", script_location + "/../../dbs/source_dbs/"])
    subprocess.call(['rm', '-rf', script_location + '/CD-Hit/'])
    subprocess.call(['rm', '-rf', script_location + "/New_Card/"])

def main():
    parser = argparse.ArgumentParser(description = "Update AMR-DB from CARD Database.")
    parser.add_argument("-c", '--count', help = "Integer at which to start AMR-ID increment.", required = True)
    args = parser.parse_args()

    amrid_count = int(args.count)

    script_location = get_script_path()
    tempDir = script_location  + "/New_Card/"
    dbDir = script_location + "/../../dbs/source_dbs/"
    amrdbDir = script_location + "/../../dbs/amr_dbs/"
    urllib3.disable_warnings()
    http = urllib3.PoolManager()

    downloadCardDatabase(http, tempDir)
    new_card_dict = compareCard(dbDir, tempDir)
    nuc_dict, pep_dict, pep_id_dict, rrna_dict = formatCardtoFasta(new_card_dict, amrid_count)
    setupCDHit(nuc_dict, pep_dict, amrdbDir, script_location)#, rrna_dict)
    new_nuc_headers = runCDHit_est(script_location + '/CD-Hit/amrdb_nucleotides.fasta', script_location + "/CD-Hit/cd-nucleotide-results")
    #new_rrna_headers = runCDHit_est(script_location + "/CD-Hit/amrdb_rRNA.fasta", script_location + "/CD-Hit/cd-rrna-results")
    new_pep_headers = runCDHit(script_location + "/CD-Hit/amrdb_peptides.fasta", script_location + "/CD-Hit/cd-peptide-results")
    nuc_fasta_entries_dict = generate_new_fasta_fields(nuc_dict, new_nuc_headers)
    pep_fasta_entries_dict = generate_new_fasta_fields(pep_dict, new_pep_headers)
    #rrna_fasta_entries_dict = generate_new_fasta_fields(rrna_dict, new_rrna_headers)

    if nuc_fasta_entries_dict:
        write_fasta_files(nuc_fasta_entries_dict, "new_nucleotide_sequences.fasta")
    if pep_fasta_entries_dict:
        write_fasta_files(pep_fasta_entries_dict, "new_peptide_sequences.fasta")

    if rrna_dict: #Cant run CD-Hit because many rRNA give resistance to different drugs with same sequence
        rrna_count = 0
        with open("new_rRNA_sequences.fasta", 'w') as f:
            for k,v in rrna_dict.items():
                rrna_count += 1
                f.write(k + "\n")
                f.write(v + "\n")
        print "Wrote {} entries to new_rRNA_sequences.fasta".format(rrna_count)


    cleanup_directory_structure(script_location)

if __name__ == '__main__':
    main()
