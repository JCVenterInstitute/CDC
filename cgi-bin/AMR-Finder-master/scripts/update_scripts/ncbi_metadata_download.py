from Bio import SeqIO
from Bio import Entrez
from urllib2 import HTTPError
from collections import defaultdict
import pandas as pd
from bs4 import BeautifulSoup
from datetime import date
import time, sys, argparse, re, urllib3, os, logging, warnings
from geopy.geocoders import Nominatim
from geopy.extra.rate_limiter import RateLimiter
urllib3.disable_warnings()

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


def parseInputList(genbank_list, keep_list_ordered):
    out_dict = defaultdict(dict)
    df = pd.read_csv(genbank_list, header = 0, index_col = 0, sep = "\t").fillna("")
    #Check for Valid Column Headers
    genbank_ids = set(list(df.index.values))
    input_cols = list(df.columns.values)
    bad_columns = [x for x in input_cols if x not in keep_list_ordered]
    if bad_columns:
        bad_vals = ", ".join(bad_columns)
        print "The column[s] '{}' are not in valid output columns. Try:".format(bad_vals)
        logging.error("The column[s] '{}' are not in valid output columns. Try:".format(bad_vals))
        for i in keep_list_ordered:
            print i
        sys.exit()

    if input_cols:
        for idx, row in df.iterrows():
            sample = row.name.rsplit(".", 1)[0]
            for metadata in input_cols:
                if metadata in out_dict[sample]:
                    if out_dict[sample][metadata] == row[metadata]:
                        pass
                    else:
                        print "You gave two different {} values for the same ID ({}). Fix and try again.".format(metadata, row.name)
                        logging.error("You gave two different {} values for the same ID ({}). Fix and try again.".format(metadata, row.name))
                        sys.exit()
                else:
                    out_dict[sample][metadata] = row[metadata]
    return ",".join(list(genbank_ids)), out_dict

def recursively_check_ids(genbank_id_list, id_type):
    good_ids = []
    if len(genbank_id_list) == 1:
        try:
            search_results = Entrez.read(Entrez.epost(db = id_type, id= genbank_id_list))
            good_ids += genbank_id_list
        except:
            pass
    else:
        split_list = len(genbank_id_list) / 2
        first_half = genbank_id_list[:split_list]
        second_half = genbank_id_list[split_list:]

        for i in [first_half, second_half]:
            id_list = ",".join(i)
            try:
                search_results = Entrez.read(Entrez.epost(db = id_type, id= id_list))
                good_ids += i
            except:
                good_ids += recursively_check_ids(i, id_type)
    return good_ids

def downloadGbFileFromIdList(genbank_id_list, email, gb_file, id_type, rettype):
    Entrez.email = email
    try:
        good_id_list = genbank_id_list
        search_results = Entrez.read(Entrez.epost(db = id_type, id= good_id_list))
    except RuntimeError as ErrorMessage: # 1 or more bad ID's
        print "One or more of your given GenBank ID's were bad. Attempting to remove. Those bad ID's will be added to bad_ids.txt."
        logging.warning("One or more of your given GenBank ID's were bad. Attempting to remove. Those bad ID's will be added to bad_ids.txt.")
        id_list = genbank_id_list.split(",")
        good_ids = recursively_check_ids(id_list, id_type)
        try:
            good_id_list = ",".join(good_ids)
            search_results = Entrez.read(Entrez.epost(db = id_type, id= good_id_list))
        except:
            print
            print "Your genomes list could not be used. {}. Fix the list file and try again.".format(ErrorMessage)
            print
            logging.error("Your genomes list could not be used. {}. Fix the list file and try again.".format(ErrorMessage))
            sys.exit()

    bad_ids = [i for i in genbank_id_list.split(",") if i not in good_id_list.split(",")]
    if rettype == 'gb':
        with open("bad_{}_ids.txt".format(id_type), 'w') as f:
            for i in bad_ids:
                f.write(i + "\n")
    webenv = search_results['WebEnv']
    query_key = search_results['QueryKey']

    batch_size = 100
    out_handle = open(gb_file, "w")
    count = len(good_id_list.split(","))
    logging.info("\nGoing to download {} GenBank record {} to {}".format(id_type, 1, count))
    for start in range(0, count, batch_size):
        end = min(count, start+batch_size)
        print "\nGoing to download {} GenBank record {} to {}".format(id_type, start+1, end)
        attempt = 0
        while attempt < 3:
            attempt += 1
            try:
                fetch_handle = Entrez.efetch(db= id_type,
                                         rettype=rettype, retmode="text",
                                         retstart=start, retmax=batch_size,
                                         webenv=webenv, query_key=query_key, idtype = 'acc')
            except HTTPError as err:
                if 500 <= err.code <= 599:
                    print("Received error from server %s" % err)
                    print("Attempt %i of 3" % attempt)
                    time.sleep(15)
                else:
                    raise
        data = fetch_handle.read()
        fetch_handle.close()
        out_handle.write(data)
    out_handle.close()

def parseGbFileForMetadata(gb_file, id_type):
    invalid_ids = []
    protein_ids = set()
    count = 0
    taxa_ids = set()
    biosample_dict = {}
    genus_species_dict = defaultdict(lambda: defaultdict(dict))
    out_dict = defaultdict(lambda: defaultdict(dict))
    source_keys = set()
    try:
        for record in SeqIO.parse(gb_file, 'gb'):
            protein_ids_to_be_checked = []
            count += 1
            gb_id = record.id.split(".")[0]
            out_dict[gb_id]['GenBank ID'] = record.name
            if id_type == 'pep':
                out_dict[gb_id]["Protein ID"] = record.name
            for i in record.dbxrefs:
                if 'BioProject' in i.split(":")[0]:
                    out_dict[gb_id]['BioProject ID'] = i.split(":")[1]
                if 'BioSample' in i.split(":")[0]:
                    out_dict[gb_id]['BioSample ID'] = i.split(":")[1]
                    biosample_dict[record.id] = (i.split(":")[1])
            for k,v in record.annotations.items():
                pass_list = ['references', 'accessions', 'keywords', 'topology', 'data_file_division', 'comment', 'structured_comment']
                if k in pass_list:
                    pass
                elif k == 'db_source':
                    out_dict[gb_id]["GenBank ID"] = v.rsplit(" ", 1)[1] #For peptide accessions
                elif k == 'molecule_type':
                    out_dict[gb_id]["Molecule Type"] = str(v).translate(None, "['']")
                else:
                    string_from_list = str(v)
                    out_value = string_from_list.translate(None, "['']")
                    out_dict[gb_id][k] = out_value

            source_count = 0 #Assuming main source info is in the first entry-- Otherwise we start getting virus information if phage DNA is present
            gene_count = 0
            cds_count = 0
            protein_count = 0
            for i in record.features:
                if i.type == 'source' and source_count == 0:
                    source_count = source_count + 1
                    for k,v in i.qualifiers.items():
                        keep_list = ['organism', 'strain', 'isolation_source', 'host', 'country','collection_date', 'lat_lon','serotype', 'isolate', 'serovar', 'sub_strain', 'environmental_sample', 'plasmid', "db_xref"]
                        if k in keep_list:
                            if k == 'db_xref':
                                for xref in v:
                                    if xref.startswith('taxon'):
                                        out_dict[gb_id]["Taxon ID"] = xref.split(":")[1]
                                        taxa_ids.add(xref.split(":")[1])
                            if k == 'organism':
                                source = v[0].split(" ")
                                genus = source[0]
                                if len(source) > 1:
                                    species = source[1]
                                    genus_species_dict[gb_id]["Species"] = species
                                genus_species_dict[gb_id]["Genus"] = genus

                            if k == 'country':
                                out_dict[gb_id]['Geographic Location Country'] = v[0].split(":")[0]
                                if ":" in v[0]:
                                    out_dict[gb_id]["Geographic Location Region"] = v[0].split(":")[1]
                                else:
                                    out_dict[gb_id]["Geographic Location Region"] = ""
                            else:
                                string_from_list = str(v)
                                out_value = string_from_list.translate(None, "['']")
                                if k == 'strain':
                                    out_dict[gb_id]["Strain"] = out_value
                                if k == 'sub_strain':
                                    out_dict[gb_id]["Substrain"] = out_value
                                if k == 'lat_lon':
                                    out_dict[gb_id]["Latitude and Longitude"] = out_value
                                if k == 'isolation_source':
                                    out_dict[gb_id]["Isolation Source"] = out_value
                                if k == 'host':
                                    out_dict[gb_id]["Host"] = out_value
                                if k == 'plasmid':
                                    out_dict[gb_id]["Plasmid"] = out_value
                                if k == 'collection_date':
                                    out_dict[gb_id]["Collection Date"] = out_value
                                if k == 'isolate':
                                    if not out_dict[gb_id]["Strain"]:
                                        out_dict[gb_id]["Strain"] = out_value
                                else:
                                    out_dict[gb_id][k] = out_value

                if i.type == 'gene':
                    gene_count += 1
                    if 'gene' in i.qualifiers:
                        gene = i.qualifiers['gene'][0]
                        if "-" in gene:
                            gene, allele = gene.rsplit("-", 1)
                            out_dict[gb_id]["Allele"] = allele
                        out_dict[gb_id]["Gene Name"] =  gene


                if i.type == 'CDS':
                    cds_count += 1
                    cds = str(i.location)
                    stripped_loc = cds.split("]")[0].split("[")[1]
                    final_loc = re.sub("[^0-9:]", "", stripped_loc)
                    five_prime, three_prime = final_loc.split(":")
                    out_dict[gb_id]["5' end"] = five_prime
                    out_dict[gb_id]["3' end"] = three_prime
                    if 'protein_id' in i.qualifiers:
                         protein_id = i.qualifiers["protein_id"][0]
                         out_dict[gb_id]["Protein ID"] = protein_id
                         protein_ids_to_be_checked.append(protein_id)
                    if 'product' in i.qualifiers:
                        protein_name = i.qualifiers["product"][0]
                        out_dict[gb_id]["Protein Name"] = protein_name
                    if 'EC_number' in i.qualifiers:
                        ec_number = i.qualifiers["EC_number"][0]
                        out_dict[gb_id]["EC Number"] = ec_number
                    if 'gene' in i.qualifiers:
                        gene = i.qualifiers["gene"][0]
                        if "-" in gene:
                            gene, allele = gene.rsplit("-", 1)
                            out_dict[gb_id]["Allele"] = allele
                        out_dict[gb_id]["Gene Name"] =  gene
                    start = int(out_dict[gb_id]["5' end"]) - 1
                    if start < 0:
                        start = 0
                    end = int(out_dict[gb_id]["3' end"])
                    if id_type == 'nuc':
                        out_dict[gb_id]["Nucleotide Sequence"] = record.seq[start:end]
                    else:
                        out_dict[gb_id]["Peptide Sequence"] = record.seq[start:end]

                if i.type == "Protein":
                    protein_count += 1
                    if 'product' in i.qualifiers:
                        product_name = i.qualifiers["product"][0]
                        out_dict[gb_id]["Protein Name"] = product_name

            if 'references' in record.annotations:
            	ref_count = len(record.annotations['references'])
            	if ref_count == 1:
            		for ref in record.annotations['references']:
            			ref_dict = vars(ref)
            			if 'pubmed_id' in ref_dict.keys():
            				out_dict[gb_id]['PubMed ID'] = ref_dict['pubmed_id']
            	else:
            		pubmed_list = set()
            		for ref in record.annotations['references']:
            			ref_dict = vars(ref)
            			for k,v in ref_dict.items():
            				if k == 'pubmed_id':
            					if v != "":
            						pubmed_list.add(v)


            		out_dict[gb_id]['PubMed ID'] = ", ".join(pubmed_list)



            if cds_count > 1 or gene_count > 1 or protein_count > 1:
                print "There was more than 1 associated entry with your GenBank ID: {}. Could not fill in any gene specific information.".format(gb_id)
                logging.warning("There was more than 1 associated entry with your GenBank ID: {}. Could not fill in any gene specific information.".format(gb_id))
                cds_specific_info = ["Gene Name", "Allele", "5' end", "3' end", "Protein ID", "EC Number", "Protein Name", "Nucleotide Sequence", "Peptide Sequence", "contig"]
                for i in cds_specific_info:
                    try:
                        del out_dict[gb_id][i]
                    except KeyError:
                        pass
                invalid_ids.append(gb_id)
            else:
                try:
                    protein_ids.add(protein_ids_to_be_checked[0]) #Should be only one protein ID
                except: #No protein id
                    pass
            out_dict[gb_id]["Reviewed"] = "Unreviewed"
    except ValueError:
        print "Bad ID: ", gb_id

    return out_dict, biosample_dict, list(taxa_ids), genus_species_dict, invalid_ids, list(protein_ids)

def check_seq(seq):
    stripped_seq = [s for s in seq if s in "ACTG"]
    if stripped_seq:
        return False
    else:
        return True

def check_metadata(metadata_dict):
    suggested_accessions = {}
    for k,v in metadata_dict.items():
        seq_check = check_seq(v["Nucleotide Sequence"])
        if seq_check:
            del metadata_dict[k]["Nucleotide Sequence"]
            try:
                suggested_id = v['contig'].split("join(")[1].rsplit(")", 1)[0].replace("complement(", "").replace(")", "")
                suggested_accessions[suggested_id] = {"Original":k, "Complement": False}
                orig_id = k
                print
                print "There was a problem getting sequence information for {}. Check that record and try again. Maybe try:\n{}".format(orig_id, suggested_id)
                logging.warning("There was a problem getting sequence information for {}. Check that record and try again. Maybe try:\n{}".format(orig_id, suggested_id))

            except:
                print
                print "There was a problem getting sequence information for {}. Check that record and try again.\n".format(k)
                logging.warning("There was a problem getting sequence information for {}. Check that record and try again.".format(k))
        else:
            pass
    return suggested_accessions

def try_suggested_id(accessions_dict, email):
    out_dict = defaultdict()
    gb_ids = set()
    cds_dict = defaultdict()
    for k,v in accessions_dict.items():
        acc = k.split(":")[0]
        if v["Complement"]:
            cds = "complement(" + k.split(":")[1] + ")"
        else:
            cds = k.split(":")[1]
        cds_dict[acc]= cds
        gb_ids.add(acc)

    redo_list = ",".join(list(gb_ids))
    downloadGbFileFromIdList(redo_list, email, "suggested_seqs.fa", 'nuccore', 'fasta_cds_na')
    for record in SeqIO.parse("suggested_seqs.fa", 'fasta'):
        id = record.description.split("|")[1].split("_")[0]
        loc = record.description.split("location=")[1].split("]")[0].replace('complement(', "").replace(")","")
        try:
            our_start, our_end = cds_dict[id].split("..")
            our_start = "".join([i for i in our_start if i in "0123456789"])
            our_end = "".join([i for i in our_end if i in "0123456789"])
            downloaded_start = loc.split("..")[0]

            downloaded_start = "".join([i for i in downloaded_start if i in "0123456789"])

            if (our_start == downloaded_start) or (abs(int(downloaded_start) - int(our_start)) <= 100): #GenBank gives -100 on 5' end and + 100 on 3' end-- must give wiggle room
                acc = id + ":" + our_start + ".." + our_end
                out_dict[id+ ":" + our_start + "-" + our_end] = {"Original Accession": accessions_dict[acc]["Original"] , "Nucleotide Sequence": str(record.seq)}
        except:
            pass
    return out_dict

def write_suggested_info(suggested_dict):
    suggested_df = pd.DataFrame.from_dict(suggested_dict, orient = 'index').fillna("")
    suggested_df.index.name = "New Accession"
    reorder = ["Original Accession", "Nucleotide Sequence"]
    suggested_df = suggested_df[reorder]
    suggested_df.to_csv("Suggested_Sequences.txt", sep = '\t')

def addSuggestedInfo(row, suggested_dict):
    for k, v in suggested_dict.items():
        if v["Original Accession"] == row["GenBank ID"]:
            gb_id, cds = k.split(":")
            five_end, three_end = cds.split("-")
            row["GenBank ID"] = gb_id
            row["5' end"] = five_end
            row["3' end"] = three_end
            row["Nucleotide Sequence"] = v["Nucleotide Sequence"].strip()
    return row

def downloadTaxaInfo(taxa_id_list, email, out_file):
    print "\nDownloading Taxa Information."
    logging.info("\nDownloading Taxa Information.")
    input_ids = ",".join(taxa_id_list)
    Entrez.email = email
    search_results = Entrez.read(Entrez.epost("taxonomy", id= input_ids, usehistory = 'y'))
    webenv = search_results['WebEnv']
    query_key = search_results['QueryKey']
    batch_size = 2000 #Can't do more than this. Will cause XML file to have two headers, breaking BioPython Parser.
    out_handle = open(out_file, "w")
    count = len(input_ids.split(","))
    logging.info("\nDownloading record {} to {}".format(1, count))
    for start in range(0, count, batch_size):
        end = min(count, start+batch_size)
        print "Going to download record {} to {}".format(start+1, end)
        attempt = 0
        while attempt < 3:
            attempt += 1
            try:
                fetch_handle = Entrez.efetch(db="taxonomy",
                                         retmode="xml",
                                         retstart=start, retmax=batch_size,
                                         webenv=webenv, query_key=query_key,
                                        )
            except HTTPError as err:
                if 500 <= err.code <= 599:
                    print("Received error from server %s" % err)
                    print("Attempt %i of 3" % attempt)
                    time.sleep(15)
                else:
                    raise
        data = fetch_handle.read()
        fetch_handle.close()
        out_handle.write(data)
    out_handle.close()

def parse_taxa_xml(xml_file):
    out_dict = defaultdict(lambda: defaultdict(dict))
    keep_list_ordered = ["Kingdom", "Phylum", "Class", "Family", "Order", "Genus", "Species", "Subspecies"]
    with open(xml_file, 'r') as taxa_data:
        records = Entrez.parse(taxa_data)
        for record in records:
            tax_id = record["TaxId"]
            source = record['ScientificName']
            genus = source.split(" ")[0]
            if len(source.split(" ")) > 1:
                species = source.split(" ")[1]
                out_dict[tax_id]["Species"] = species
            else:
                out_dict[tax_id]["Species"] = ""
            scientific_names = record['LineageEx']
            for i in scientific_names:
                rank = i["Rank"]
                rank = rank[0].upper() + rank[1:]
                skip_keeping = ['No rank']
                if rank in skip_keeping:
                    pass
                else:
                    if out_dict[tax_id][rank]:
                        pass
                    else:
                        if rank == 'Superkingdom':
                            rank = "Kingdom"
                        name = i["ScientificName"]
                        if rank == 'Species':
                            name = name.split(" ")[1]
                        if rank == 'Subspecies':
                            name = name.split("subsp.")[1]
                        out_dict[tax_id][rank] = name

    out_df = pd.DataFrame.from_dict(out_dict, orient = 'index').fillna("")
    for category in keep_list_ordered:
        if category not in list(out_df.columns.values):
            out_df[category] = ""
    out_df= out_df[keep_list_ordered]
    return out_df

def add_missing_taxonomy(row, genus_species_dict):
    if row['Genus'] == "" and row['Species'] == "" or row['plasmid'] != "":
        if genus_species_dict[row['GenBank ID']]:
            row['Genus'] = genus_species_dict[row['GenBank ID']]['Genus']
            row['Species'] = genus_species_dict[row['GenBank ID']]['Species']
    if row['Genus'] == "" and row['plasmid'] != "":
        row['Species'] == ""
    if row["Taxon ID"] != "":
        row["Taxon ID"]= int(row["Taxon ID"])
    return row

def createMetadataDF(mapped_dict, output_file, taxa_df, genus_species_dict, keep_list_ordered):
    metadata_df = pd.DataFrame.from_dict(mapped_dict, orient = 'index').sort_index(axis = 1).fillna("")
    df = pd.merge(metadata_df, taxa_df, left_on="Taxon ID", right_index = True, how = "left").fillna("")
    df.index.name = "Index"
    for category in keep_list_ordered:
        if category not in list(df.columns.values):
            df[category] = ""
    df = df[keep_list_ordered]
    df = df.apply(lambda x: add_missing_taxonomy(x, genus_species_dict), axis = 1)
    return df

def BioSamplesFromGbInput(df):
    biosample_list = pd.unique(df["BioSample ID"])
    return biosample_list

def getBioSampleAntibiogram(biosample_list):
    urllib3.disable_warnings()
    http = urllib3.PoolManager()
    out_dict = defaultdict(lambda: defaultdict(dict))
    header_line = ["BioSampleID"]
    body_lines = []
    print "\nChecking BioSamples for Metadata:"
    logging.info("\nChecking BioSamples for Metadata.")
    for biosample in biosample_list:
    	print biosample
    	r = http.request("GET", 'https://www.ncbi.nlm.nih.gov/biosample/' + biosample, timeout = 10)
    	html = r.data

    	parsed_html = BeautifulSoup(html, 'html.parser')
    	#Biosample Metadata
    	try:
            div = parsed_html.find("table", {'class': "docsum"})
            info = div.find_all("tr")
            meta_headers = []
            meta_info = []
            for i in info:
                headers, met_info = i.find_all("th"), i.find_all("td")
                for ind in range(0, len(headers)):
                    out_dict[biosample][headers[ind].get_text(strip = True)] =met_info[ind].get_text(strip = True)
    	except:
    		pass

    	#Antibiogram data
    	try:
    		div = parsed_html.find("div", {"class": 'table_container'})
    		header = div.find_all("tr")[0]
    		body = div.find_all("tr")[1:]
    		header_categories = header.find_all("th")
    		for category in header_categories:
    			if category.get_text("\t", strip = True) in header_line:
    				pass
    			else:
    				header_line.append(category.get_text("\t", strip = True))

    		for r in body:
    			row = []
    			row.append(biosample)
    			body_row = r.find_all("td")
    			for val in body_row:
    				row.append(val.get_text("\t", strip = True))
    			body_lines.append(row)

    	except:
    		pass


    return header_line, body_lines, out_dict

def addBiosampleDataToGbData(row, biosample_dict):
    if row["BioSample ID"]:
        biosample_id = row["BioSample ID"]
        if biosample_id in biosample_dict:
            loc_dict = {}
            for i in biosample_dict[biosample_id]:
                try:
                    if i.lower().startswith('lat'):
                        loc_dict["Latitude"] = str(biosample_dict[biosample_id][i])
                    if i.lower().startswith("long"):
                        loc_dict["Longitude"] = str(biosample_dict[biosample_id][i])
                    cat_name = i[0].upper() + i[1:]
                    if cat_name in row and row[cat_name] == "":
                        row[cat_name] = biosample_dict[biosample_id][i]
                except:
                    pass
            if len(loc_dict) == 2:
                row["Latitude and Longitude"] = loc_dict["Latitude"] + " " + loc_dict["Longitude"]
    return row

def writeAntibiogramFile(header, rows, out_file):
    if not rows:
        print "\nNo available Antibiogram data."
        logging.warning("\nNo available Antibiogram data.")
    else:
        header = "ID" + ("\t").join(header)
        with open(out_file + "_antibiogram.txt", 'w') as f:
            f.write(header + "\n")
            for i in rows:
                f.write("\t".join(i).encode('utf-8').strip() + "\n")

def writeInvalidIds(invalid_ids):
    with open("Invalid_Ids.txt", 'w') as f:
        for i in invalid_ids:
            f.write(i + "\n")

def getPeptideGB(protein_id_list, peptide_file, email):
    protein_ids = ",".join(protein_id_list)
    downloadGbFileFromIdList(protein_ids, email, peptide_file, 'protein', 'gb')

def peptide_mapping(peptide_file):
    out_dict = {}
    for record in SeqIO.parse(peptide_file, 'gb'):
        out_dict[record.id] = str(record.seq)
    return out_dict

def addPeptideSequences(row, pep_dict):
    if row["Protein ID"]:
        row["Peptide Sequence"] = pep_dict[row["Protein ID"]]
    return row

def addUserMetadata(row, input_metadata_dict):
    row["Source"] = "GenBank"
    if row.name in input_metadata_dict:
        for metadata, metadata_info in input_metadata_dict[row.name].items():
            if metadata_info:
                row[metadata] = metadata_info
    return row

def write_fasta_files(df, output_file, db_format, count):
    nuc_file = open(output_file + "_nucleotides.fasta", 'w')
    pep_file = open(output_file + "_peptides.fasta", 'w')
    snp_mistake_pep_file = open(output_file + "_snp_mistake_peptides.fasta", 'w')
    snp_mistake_nuc_file = open(output_file + "_snp_mistake_nucleotides.fasta", 'w')
    valid_entry = False
    keep_mistake_files = False
    nuc_count = 0
    pep_count = 0
    next_id = count + 1
    for idx, row in df.iterrows():
        cds = "{}-{}".format(row["5' end"], row["3' end"])
        if row["Gene Name"] != "" and row["Drug Class"] != "":
            valid_entry = True
            if not db_format:
                amr_id = ""
            else:
                amr_id = next_id
                next_id += 1

            header = AMRentry("{}|{}|{}:{}|{}|{}|{}|{}|{}|{}|{}|{}|".format(row["Source"], amr_id, row["GenBank ID"], cds, row["Gene Name"], row["Allele"], row["Drug Class"], row["Drug Family"], row["Drugs"], row["Parent Allele"], row["Parent Allele Family"], row["snp"]))
            snp_validated = True
            if header.snp_info:
                snp_validated = False
                for snp in header.snp_info.split(","):
                    snp_type, snp_info = snp.split(".")
                    orig = snp_info[0]
                    mut = snp_info[-1]
                    pos = int(snp_info[1:-1])
                    if snp_type == 'c':
                        if row["Nucleotide Sequence"]:
                            if str(row["Nucleotide Sequence"])[pos-1] == orig or str(row["Nucleotide Sequence"])[pos-1] == mut:
                                snp_validated = True
                    if snp_type == 'p':
                        if row["Peptide Sequence"]:
                            if str(row["Peptide Sequence"])[pos-1] == orig or str(row["Peptide Sequence"])[pos-1] == mut:
                                snp_validated = True

            if not snp_validated:
                keep_mistake_files = True
                if row["Peptide Sequence"]:
                    snp_mistake_pep_file.write(header.new_header() + "\n")
                    snp_mistake_pep_file.write(str(row['Peptide Sequence']) + "\n")
                if row["Nucleotide Sequence"]:
                    snp_mistake_nuc_file.write(header.new_header() + "\n")
                    snp_mistake_nuc_file.write(str(row['Nucleotide Sequence']) + "\n")

            if not header.snp_info or snp_validated:
                    if row["Peptide Sequence"]:
                        pep_count += 1
                        pep_file.write(header.new_header() + "\n")
                        pep_file.write(str(row['Peptide Sequence']) + "\n")
                    if row["Nucleotide Sequence"]:
                        nuc_count += 1
                        nuc_file.write(header.new_header() + "\n")
                        nuc_file.write(str(row['Nucleotide Sequence']) + "\n")

    print "\nCreated {} nucleotide entries and {} peptide entries.".format(nuc_count, pep_count)
    logging.info("Created {} nucleotide entries and {} peptide entries.".format(nuc_count, pep_count))
    if not valid_entry:
        os.remove(output_file + "_nucleotides.fasta")
        os.remove(output_file + "_peptides.fasta")
    if not keep_mistake_files:
        os.remove(output_file + "_snp_mistake_peptides.fasta")
        os.remove(output_file + "_snp_mistake_nucleotides.fasta")

def check_ecNumber(df):
    ec_dict = {}
    for idx, row in df.iterrows():
        if row["EC Number"] != "":
            if row["EC Number"] not in ec_dict:
                #print ec_dict
                urllib3.disable_warnings()
                http = urllib3.PoolManager()
                r = http.request("GET", 'https://enzyme.expasy.org/EC/' + row["EC Number"], timeout = 10)
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

def add_geolocation_info(row):
    if row["Latitude and Longitude"]:
        pass
    else:
        if row["Geographic Location Country"] or row["Geographic Location Region"]:
            #try:
            with warnings.catch_warnings(): #SSL Warning
                warnings.simplefilter("ignore")
                loc = row["Geographic Location Region"].split(",")[0] + " " + row["Geographic Location Country"]
                geolocator = Nominatim(user_agent="ncbi_metadata_download", scheme = "http", timeout = 5)
                try:
                    geocode = geolocator.geocode(loc)
                    if geocode:
                        lat = geocode.latitude
                        long = geocode.longitude
                        if lat and long:
                            if lat > 0:
                                lat = str(lat) + " N"
                            if lat < 0:
                                lat = str(abs(lat)) + " S"
                            if long > 0:
                                long = str(long) + " E"
                            if long < 0:
                                long = str(abs(long)) + " W"
                            row["Latitude and Longitude"] = lat + ", " + long
                except:
                    pass
                #print row["Geographic Location Region"] + " " + row["Geographic Location Country"]
    return row

def cleanup():
    def try_remove(filename):
        try:
            os.remove(filename)
        except:
            pass
    try_remove("suggested_seqs.fa")
    try_remove("taxa_info.xml")
    try_remove("genbank_metadata.gb")
    try_remove("peptide_sequences.gb")

def main():
    parser = argparse.ArgumentParser(description = "Update AMR-DB GenBank Metadata given list of GenBank ID's.")
    parser.add_argument("-l", '--input_list', help = "A text file of GenBank ID's, one per row.", required = True)
    parser.add_argument("-e", '--email', help = "Provide an email to NCBI", required = True)
    parser.add_argument("-t", '--type_of_id', help = "Type of NCBI ID provided. Choices are nuc or pep.", choices = ["nuc", "pep"], required = True)
    parser.add_argument("-db", '--db_format', help = "Output FASTA files in AMR-DB Format (Sequentially adding AMR-ID)", action = "store_true")
    parser.add_argument("-c", '--count', help = "Integer at which to increment up (Only if outputting in db_format)")

    parser.add_argument("-o", '--output_prefix', help = "Prefix of metadata file-- default 'New'. <Prefix>_genbank_metadata.txt", default = "New")
    args = parser.parse_args()
    email = args.email
    output_file = args.output_prefix
    input_list = args.input_list
    id_type = args.type_of_id
    db_format = args.db_format
    amrid_count =args.count
    gb_file = "genbank_metadata.gb"
    taxa_file = "taxa_info.xml"



    log_file = "ncbi-download.log"
    logging.basicConfig(format='%(message)s', filename = log_file, filemode = 'w', level = logging.INFO)
    logging.info("Date: {}".format(date.today()))

    if db_format and not amrid_count:
        print
        print
        print "Chose database format without providing a count to start AMR-ID's. Try again."
        logging.error("Chose database format without providing a count to start AMR-ID's. Try again.")
        print
        sys.exit()
    if not amrid_count:
        amrid_count = 0
    amrid_count = int(amrid_count)
    id_dict = {"nuc": "nuccore", "pep": "protein"}
    gb_id = id_dict[id_type]

    keep_list_ordered = ["Gene Name", "Allele", "EC Number", "Molecule Type",
                        "Parent Allele", "Parent Allele Family", "Source", "GenBank ID",
                        "Protein ID", "Protein Name", "Alternative Protein Name", "PubMed ID", "Drug Class",
                        "Drug Family", "Drugs", "Mechanism of Action", "Gene Class", "Gene Family", "snp", "3' end", "5' end",
                        "Nucleotide Sequence", "Peptide Sequence", "BioProject ID",
                        "BioSample ID", "Taxon ID", "Kingdom", "Phylum", "Class", "Order",
                        "Family", "Genus", "Species", "Subspecies", "Strain", "Substrain",
                        "plasmid", "serotype", "serovar", "Isolation Source", "Serotyping Method",
                        "Sample Name", "Collection Date", "Geographic Location Country",
                        "Geographic Location Region", "Latitude and Longitude", "Host Disease State",
                        "Host Diesease", "Host Sex", "Host Health State", "Treatment", "Specimen Type",
                        "Symptom", "Host", "Reviewed", "Evidence Type", "AMRdb ID"]


    gb_list, user_metadata_dict = parseInputList(input_list, keep_list_ordered)
    downloadGbFileFromIdList(gb_list, email, gb_file, gb_id, 'gb')
    metadata_dict, biosample_list, taxa_id_list, genus_species_dict, invalid_ids, protein_ids = parseGbFileForMetadata(gb_file, id_type)

    if invalid_ids:
        writeInvalidIds(invalid_ids)
    if not metadata_dict:
        print
        print "No metadata parsed. Try again with a different Accession ID."
        print
        logging.error("No metadata parsed. Try again with a different Accession ID.")
        sys.exit()
    downloadTaxaInfo(taxa_id_list, email, taxa_file)
    taxa_df = parse_taxa_xml(taxa_file)
    gb_df = createMetadataDF(metadata_dict, output_file, taxa_df, genus_species_dict, keep_list_ordered)
    biosamples = BioSamplesFromGbInput(gb_df)

    if id_type == 'nuc' and protein_ids:
        peptide_file = "peptide_sequences.gb"
        getPeptideGB(protein_ids, peptide_file, email)
        pep_dict = peptide_mapping(peptide_file)
        gb_df = gb_df.apply(lambda x: addPeptideSequences(x, pep_dict), axis =1)

    if biosamples.size == 0:
        print
        print "No biosamples to check."
        print
        logging.warning("No biosamples to check.")
    else:
        antibiogram_header, antibiogram_body, biosample_dict = getBioSampleAntibiogram(biosamples)
        writeAntibiogramFile(antibiogram_header, antibiogram_body, output_file)
        if biosample_dict:
            gb_df = gb_df.apply(lambda x: addBiosampleDataToGbData(x, biosample_dict), axis = 1)

    gb_df = check_ecNumber(gb_df)

    if id_type == "nuc":
        suggested_accessions = check_metadata(metadata_dict)
        if suggested_accessions:
            suggested_dict = try_suggested_id(suggested_accessions, email)
            if suggested_dict:
                write_suggested_info(suggested_dict)
                gb_df = gb_df.apply(lambda x: addSuggestedInfo(x, suggested_dict), axis = 1)

    if user_metadata_dict:
        gb_df = gb_df.apply(lambda x: addUserMetadata(x, user_metadata_dict), axis = 1)


    gb_df = gb_df.apply(lambda x: add_geolocation_info(x), axis = 1)

    gb_df.to_csv(output_file + "_metadata.txt", sep = "\t")
    out_written_list = write_fasta_files(gb_df, output_file, db_format, amrid_count)
    cleanup()

if __name__ == "__main__":
    main()
