#!/usr/bin/perl
`dos2unix *.txt`;
use DBI;
my $Connection = DBI->connect("DBI:mysql:CDC:cdc-1.jcvi.org:3306", "cdc_app", "Guest649745f")or &mwserror("DB Error: " . $DBI::errstr);;
my $query = $Connection->prepare("SELECT nextval('CDC_Seq_10000')");
$query->execute() or die DBI->errstr;
@ref = $query->fetchrow_array;
$start_no=$ref[0];
#Reading metadata file
open(FA,"$ARGV[0]");
@main=<FA>; close FA;
#Writing Table using combined metadata file and removing the header line
shift(@main);
open(IW,">Identity.txt");
open(CW,">Classification.txt");
open(SW,">Identity_Sequence.txt");
open(VW,">Variants.txt");
open(TW,">Taxonomy.txt");
open(BW,">Sample_Metadata.txt");
open(AW,">Assemly.txt");
open(DW,">Identity_Assembly.txt");
$v = 1; $b = 1; $c=""; @bioid=();
$i=$start_no;
foreach $line(@main){
	chomp $line;
	@data = (); 
	@data = split("\t",$line);
	printf IW "1".'%04s',$i+1; print IW "\t$data[0]\t$data[1]\t$data[2]\t$data[3]\t$data[4]\t$data[5]\t$data[6]\t$data[7]\t$data[8]\t$data[9]\t$data[10]\t$data[11]\t$data[12]\t";  if($data[13] eq "") {printf IW "Unpublished\t";}  else {printf IW "$data[13]\t";} print IW "$data[14]\t1\tDefault\n";
	printf CW "2".'%04s',$i+1; print CW "\t$data[15]\t$data[16]\t$data[17]\t$data[18]\t"; printf CW "1".'%04s',$i+1; print CW "\n";
	printf SW "3".'%04s',$i+1; print SW "\t$data[19]\t$data[20]\t$data[21]\t$data[22]\t"; if($data[23] eq "") {printf SW "NA\t";}  else {printf SW "$data[23]\t";} printf SW "1".'%04s',$i+1; print SW "\n";
	if($data[24] ne ""){
	printf VW "4".'%04s',$v; print VW "\t$data[24]\t$data[13]\t"; printf VW "3".'%04s',$i+1; print VW "\t"; printf VW "2".'%04s',$i+1; print VW "\n"; $v++;
	}
	printf TW "5".'%04s',$i+1; print TW "\t$data[25]\t$data[26]\t$data[27]\t\t$data[28]\t$data[29]\t$data[30]\t$data[31]\t$data[32]\t$data[33]\t$data[34]\t$data[35]\t$data[36]\t$data[37]\n";
	if($data[19] ne ""){
	printf BW "6".'%04s',$b; print BW "\tBiosample\t$data[38]\t$data[39]\t$data[40]\t$data[41]\t$data[42]\t$data[43]\t$data[44]\t$data[45]\t$data[46]\t$data[47]\t$data[48]\t$data[49]\t$data[50]\t$data[51]\t$data[52]\t$data[53]\t$data[54]\t$data[55]\n"; $b++;
	push(@bioid,"$data[38],$b");
	} $c=$b-1;
	printf AW "7".'%04s',$i+1; print AW "\t1\t"; printf AW "6".'%04s',$c; print AW "\t$data[8]\t$data[9]\t$data[13]\t$data[14]\t"; printf AW "5".'%04s',$i+1; print AW "\t$data[56]\n";
	printf DW "8".'%04s',$i+1; print DW "\t"; if($data[56] eq "") {printf DW "\t";}  else {printf DW "Plasmid\t";} printf DW "3".'%04s',$i+1; print DW "\t"; printf DW "7".'%04s',$i+1; print DW "\n";
	$i++;
}
close IW;
close CW;
close SW;
close VW;
close TW;
close BW;
close AW;
close DW;

#Reading Antibiogram data and removing the header line
open(FB,"$ARGV[1]");
@meta=<FB>; close FB;
shift(@meta); 

$s=$start_no;
#Writing Antibiogram data
open(NW,">Antibiogram.txt");
foreach $linea(@meta){
	chomp $linea;
	@datamic = (); 
	@datamic = split("\t",$linea); 
		foreach $bio(@bioid) {
		@biodata=(); 
		@biodata = split(",",$bio); 
		if($datamic[0] eq $biodata[0]) {$smid=""; $smid = $biodata[1]; }
		}
	printf NW "9".'%04s',$s+1; print NW "\t$datamic[1]\t\t$datamic[6]\t$datamic[9]\t$datamic[7]\t$datamic[4]\t$datamic[3]\t$datamic[5]\t$datamic[2]\t$datamic[10]\t$datamic[8]\t"; printf NW "6".'%04s',$smid; print NW "\n";
	}
close NW;
