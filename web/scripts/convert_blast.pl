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

@identiy=();
for($i=0;$i<=$#ids;$i++){
	$idx=$idd=$identiyadd="";
	$idx=$ids[$i]; $idd=$ids[$i+1];
	$len1=length($idx);	$len2=length($idd);
	@idmeta1=@idmeta2=(); $precen=$aligndata="";
	@idmeta1=split("\t",$idx);
	@idmeta2=split("\t",$idd);
	#if($idmeta1[0] ne "" && $idmeta2[0] ne ""){	print "$idmeta1[0]\t$idmeta1[0]\n"; }
	#if($idmeta1[0] ne "" && $idmeta2[0] eq ""){	print "$idmeta1[0]**\t$idmeta1[0]\n"; }
	$blast=join("\t",@aray);
	if($idmeta1[0] ne "" && $idmeta2[0] ne ""){	
		if ($blast =~ />$idmeta1[0].+Identities\s+=\s+\d+\/\d+(.+),\s+Positives.+>$idmeta2[0]/){ $percen = $1;
		$percen=~s/\(//g;$percen=~s/\)//g;
		$identiyadd=$idx."\t".$percen;
		push(@identiy,"$identiyadd");
		}
	}
	if($idmeta1[0] ne "" && $idmeta2[0] eq ""){
		if ($blast =~ />$idmeta1[0].+Identities\s+=\s+\d+\/\d+(.+),\s+Positives.+Lambda/){ $percen = $1; 
		$percen=~s/\(//g;$percen=~s/\)//g;		
		$identiyadd=$idx."\t".$percen;
		push(@identiy,"$identiyadd");
		}
	}
}

#foreach $x(@identiy){ print "$x\n";} die;

open(fx,"$ARGV[1]");
@meta=<fx>; chomp @meta;
@idsmeta=();

foreach $id(@identiy){ 
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
#foreach $x(@idsmeta){ print "$x\n";} die;
print '{
        "data":[';

for($i=0;$i<=$#idsmeta;$i++){
	$idx=$idd=$len1=$len2="";
	$idx=$idsmeta[$i]; $idd=$idsmeta[$i+1];
	$len1=length($idx);	$len2=length($idd);
	@dtameta=@dtameta1=(); $ln=$aligndata="";
	@dtameta=split("\t",$idx);
	@dtameta1=split("\t",$idd);
#	if($dtameta[0] ne "" && $dtameta1[0] ne ""){	print "$idx\t$dtameta[0]\t$dtameta1[0]\n\n"; }
#	if($dtameta[0] ne "" && $dtameta1[0] eq ""){	print "$dtameta[0]\t****$dtameta1[0]\n\n";}
	$blast=join("\t",@aray);
	if($dtameta[0] ne "" && $dtameta1[0] ne ""){	
		if ($blast =~ />$dtameta[0](.+)Sbjct\s+\d+\s+[\w-]+\s+\d+\s+>$dtameta1[0]/){
			$aligndata=$1; $aligndata=~s/\t/\n/g; 
			print "
			{
                        'Identity_ID': '<a href=*singlex.php?id=$dtameta[4]*>$dtameta[4]</a>',
			'Protein_ID': '$dtameta[0]',
			'Scores': '$dtameta[1]',
			'E_Value': '$dtameta[2]',
			'Identities': '$dtameta[3]',
			'Gene_Symbol': '$dtameta[5]',
			'Gene_Aliases': '$dtameta[6]',
			'Drug_Family': '$dtameta[7]',
			'Organism_ID': '$dtameta[8]',
			'Species': '$dtameta[9]',
			'GenBank_ID': '$dtameta[10]',
			'Uniprot_ID': '$dtameta[11]',
			'Parent_Allele': '$dtameta[12]',
			'Parent_Allele_Family': '$dtameta[13]',
			'SNP': '$dtameta[14]',
			'Allele': '$dtameta[15]',
			'Drug_Class': '$dtameta[16]',
			'Sub_Drug_Class': '$dtameta[17]',
			'Phylum': '$dtameta[18]',
			'Class': '$dtameta[19]',
			'Order': '$dtameta[20]',
			'Family': '$dtameta[21]',
			'Genus': '$dtameta[22]',
			'Strain': '$dtameta[23]',
			'Plasmid': '$dtameta[24]',
			'Bioproject': '$dtameta[25]',
			'Biosample': '$dtameta[26]'
			},"
			#print ">$dtameta[0]$aligndata******\n";
		}
	}
	if($dtameta[0] ne "" && $dtameta1[0] eq ""){
		if ($blast =~ />$dtameta[0](.+)Sbjct\s+\d+\s+[\w-]+\s+\d+\s+Lambda/){	
		$aligndata=$1; $aligndata=~s/\t/\n/g; 
			print "
			{
			'Identity_ID': '<a href=*singlex.php?id=$dtameta[4]*>$dtameta[4]</a>',
			'Protein_ID': '$dtameta[0]',
			'Scores': '$dtameta[1]',
			'E_Value': '$dtameta[2]',
			'Identities': '$dtameta[3]',
			'Gene_Symbol': '$dtameta[5]',
			'Gene_Aliases': '$dtameta[6]',
			'Drug_Family': '$dtameta[7]',
			'Organism_ID': '$dtameta[8]',
			'Species': '$dtameta[9]',
			'GenBank_ID': '$dtameta[10]',
			'Uniprot_ID': '$dtameta[11]',
			'Parent_Allele': '$dtameta[12]',
			'Parent_Allele_Family': '$dtameta[13]',
			'SNP': '$dtameta[14]',
			'Allele': '$dtameta[15]',
			'Drug_Class': '$dtameta[16]',
			'Sub_Drug_Class': '$dtameta[17]',
			'Phylum': '$dtameta[18]',
			'Class': '$dtameta[19]',
			'Order': '$dtameta[20]',
			'Family': '$dtameta[21]',
			'Genus': '$dtameta[22]',
			'Strain': '$dtameta[23]',
			'Plasmid': '$dtameta[24]',
			'Bioproject': '$dtameta[25]',
			'Biosample': '$dtameta[26]'
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














