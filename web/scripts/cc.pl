open(fo,"$ARGV[0]");
@aray=<fo>; chomp @aray;
shift(@aray);
print '{
	"data":[';
for($i=0;$i<=$#aray;$i++){
	$id=$len1=$len2=$n="";
	$id=$aray[$i];
	chomp $id;
	@data=();
	@data = split("\t",$id);
	$n=$i+1;
	$len1=length($aray[$i]);	$len2=length($aray[$i+1]);
print "
	{
	'Identity_ID': '<a href=*singlex.php?id=$data[0]*>$data[0]</a>',
	'Gene_Symbol': '$data[1]',
	'Gene_Aliases': '$data[2]',
	'Drug_Family': '$data[3]',
	'Organism_ID': '$data[4]',
	'Species': '$data[5]',
	'GenBank_ID': '<a href=*https://www.ncbi.nlm.nih.gov/nuccore/$data[6]*>$data[6]</a>',
	'Protein_ID': '<a href=*https://www.ncbi.nlm.nih.gov/protein/$data[7]*>$data[7]</a>',
	'Uniprot_ID': '<a href=*http://www.uniprot.org/uniprot/$data[8]*>$data[8]</a>',
	'Parent_Allele': '$data[9]',
	'Parent_Allele_Family': '$data[10]',
	'SNP': '$data[11]',
	'Allele': '$data[12]',
	'Drug_Class': '$data[13]',
	'Sub_Drug_Class': '$data[14]',
	'Phylum': '$data[15]',
	'Class': '$data[16]',
	'Order': '$data[17]',
	'Family': '$data[18]',
	'Genus': '$data[19]',
	'Strain': '$data[20]',
	'Plasmid': '$data[21]',
	'Bioproject': '<a href=*https://www.ncbi.nlm.nih.gov/bioproject/$data[22]*>$data[22]</a>',
	'Biosample': '<a href=*https://www.ncbi.nlm.nih.gov/biosample/$data[23]*>$data[23]</a>'";
	if($len2 ne "") { 
	print "\n        },";
	}
	else {
	print "\n        }";
	}
}
print "
  ]
  }";
