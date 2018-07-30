open(fo,"$ARGV[0]");
@aray=<fo>; chomp @aray;
#$input=shift(@aray); shift(@aray); $lenseq=shift(@aray);shift(@aray);shift(@aray);shift(@aray);
@ids=();
foreach $ln(@aray){
	chomp $ln; 
	$ln=~s/\|/ /g; $ln=~s/\s+/ /g;
	@line=(); $data = "";
	@line=split(" ",$ln);
	if($ln eq ""){ last;}
	$data =$line[0]."\t".$line[4]."\t".$line[5];
	push(@ids,"$data");
#	print "*$line[0]\t$line[4]\t$line[5]\n";
}

open(fx,"$ARGV[1]");
@meta=<fx>; chomp @meta;
@idsmeta=();

foreach $id(@ids){ 
	chomp $id; $ln="";
	@dta= ();
	@dta= split("\t",$id);
	#print "$dta[0]\n";
	
	foreach $ln(@meta){
		@rowmeta=(); $idmeta="";
		@rowmeta=split("\t",$ln); #print "$rowmeta[0]\n";
		$ln=~s/$dta[0]\t//g;
	if($dta[0] eq $rowmeta[0]) 
		{ 
		#print "$id\t**$ln\n";
		$idmeta=$id."\t".$ln; 	push(@idsmeta,"$idmeta");
		}
	}
}
print '{
        "data":[';

for($i=0;$i<=$#idsmeta;$i++){
	$idx=$idd=$len1=$len2="";
	$idx=$idsmeta[$i]; $idd=$idsmeta[$i+1];
	$len1=length($idx);	$len2=length($idd);
	@dtameta=@dtameta1=(); $ln=$aligndata="";
	@dtameta=split("\t",$idx);
	@dtameta1=split("\t",$idd);
	#if($dtameta[0] ne "" && $dtameta1[0] ne ""){	print "$dtameta[0]\t$dtameta1[0]\n\n"; }
	#if($dtameta[0] ne "" && $dtameta1[0] eq ""){	print "$dtameta[0]\t****$dtameta1[0]\n\n";}
	$blast=join("\t",@aray);
	if($dtameta[0] ne "" && $dtameta1[0] ne ""){	
		if ($blast =~ />$dtameta[0](.+)Sbjct\s+\d+\s+\w+\s+\d+\s+>$dtameta1[0]/){
			$aligndata=$1; #$aligndata=~s/\t/\n/g; 
			print "
			{
			'JCVI_ID': '$dtameta[3]',
			'Protein_ID': '$dtameta[0]',
			'Scores': '$dtameta[1]',
			'E_Value': '$dtameta[2]',
			'Gene_Symbol': '$dtameta[4]',
			'Gene_Aliases': '$dtameta[5]',
			'Drug_Family': '$dtameta[6]',
			'Organism_ID': '$dtameta[7]',
			'Species': '$dtameta[8]',
			'GenBank_ID': '$dtameta[9]',
			'Uniprot_ID': '$dtameta[10]',
			'Parent_Allele': '$dtameta[11]',
			'Parent_Allele_Family': '$dtameta[12]',
			'SNP': '$dtameta[13]',
			'Allele': '$dtameta[14]',
			'Drug_Class': '$dtameta[15]',
			'Sub_Drug_Class': '$dtameta[16]',
			'Phylum': '$dtameta[17]',
			'Class': '$dtameta[18]',
			'Order': '$dtameta[19]',
			'Family': '$dtameta[20]',
			'Genus': '$dtameta[21]',
			'Strain': '$dtameta[22]',
			'Plasmid': '$dtameta[23]',
			'Bioproject': '$dtameta[24]',
			'Biosample': '$dtameta[25]'
			},"
			#print ">$dtameta[0]$aligndata******\n";
		}
	}
	if($dtameta[0] ne "" && $dtameta1[0] eq ""){
		if ($blast =~ />$dtameta[0](.+)Sbjct\s+\d+\s+\w+\s+\d+\s+Lambda/){	
		$aligndata=$1; $aligndata=~s/\t/\n/g; 
			print "
			{
			'JCVI_ID': '$dtameta[3]',
			'Protein_ID': '$dtameta[0]',
			'Scores': '$dtameta[1]',
			'E_Value': '$dtameta[2]',
			'Gene_Symbol': '$dtameta[4]',
			'Gene_Aliases': '$dtameta[5]',
			'Drug_Family': '$dtameta[6]',
			'Organism_ID': '$dtameta[7]',
			'Species': '$dtameta[8]',
			'GenBank_ID': '$dtameta[9]',
			'Uniprot_ID': '$dtameta[10]',
			'Parent_Allele': '$dtameta[11]',
			'Parent_Allele_Family': '$dtameta[12]',
			'SNP': '$dtameta[13]',
			'Allele': '$dtameta[14]',
			'Drug_Class': '$dtameta[15]',
			'Sub_Drug_Class': '$dtameta[16]',
			'Phylum': '$dtameta[17]',
			'Class': '$dtameta[18]',
			'Order': '$dtameta[19]',
			'Family': '$dtameta[20]',
			'Genus': '$dtameta[21]',
			'Strain': '$dtameta[22]',
			'Plasmid': '$dtameta[23]',
			'Bioproject': '$dtameta[24]',
			'Biosample': '$dtameta[25]'
			
			}"
		#print ">$dtameta[0]$aligndata******\n";
		}
	}	
}
print "
  ]
  }";

	#foreach $ln(@aray){
		chomp $ln; 
		$ln=~s/\|/ /g;
		@align=();
		@align=split(" ",$ln); #print "$dtameta[0]*\t*$align[0]*\n";
		#if ($blast =~ />$dtameta[0](.+)Sbjct\s+\d+\s+\w+\s+\d+\s+>/){ print "$1\n\n";}

		#if(">$dtameta[0]" eq "$align[0]") {print "$ln\n";}
	#}
#}














