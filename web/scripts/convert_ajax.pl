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
	'id':'$n',
	'Input_Seq': '$data[0]',
	'Cut_Off': '$data[5]',
	'Pass_Bitscore': '$data[6]',
	'Best_Hit_Bitscore': '$data[7]',
	'Best_Hit_ARO': '$data[8]',
	'Best_Identities': '$data[9]',
	'SNPs_in_Best_Hit_ARO': '$data[12]',
	'Drug_Class': '$data[14]',
	'Resistance_Mechanism': '$data[15]',
	'AMR_Gene_Family': '$data[16]',
	'Predicted_Protein': '$data[18]',
	'CARD_Protein_Sequence': '$data[19]'";

	if($len2 ne "") { 
	print '},';
	}
	else {
	print '}';
	}
}
print "
  ]
  }";
