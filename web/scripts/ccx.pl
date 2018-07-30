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
	'Identity_ID': '$data[0]',
	'Gene_Symbol': '$data[1]',
	'Gene_Aliases': '$data[2]',
	'Drug_Family': '$data[3]',
	'Organism_ID': '$data[4]',
	'Species': '$data[5]',
	'GenBank_ID': '$data[6]',
	'Protein_ID': '$data[7]',
	'Uniprot_ID': '$data[8]',
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
	'Bioproject': '$data[22]',
	'Biosample': '$data[23]'";
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
