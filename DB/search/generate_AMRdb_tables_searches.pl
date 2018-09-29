#!/usr/bin/perl
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
open(BW,">Sample_Metadata.txt");
open(AW,">Assemly.txt");
open(TW,">Taxonomy.txt");
open(DW,">Identity_Assembly.txt");
$v = 1; $b = 1; $c=$d=""; @bioid=$idt=();
$i=$start_no;
$h=$start_no;
foreach $line(@main){
        chomp $line;
	@dataids = @data = @datax= (); $idx1=$idx2=$idx3="";
	@data = split("\t",$line);
	$idx1=`grep -w '$data[0]' $ARGV[2] | cut -f2-`; chomp $idx1;
	@dataids = split("\t",$idx1); 
	$z=12877+$i; chomp $data[25]; chomp $i;
	printf BW "6".'%04s',$z; print BW "\tGenBank, Biosample\t$data[8], $data[25]\t$data[40]\t$data[41]\t$data[42]\t$data[43]\t$data[44]\t$data[45]\t$data[46]\t\t$data[47]\t$data[48]\t$data[49]\t$data[50]\t$data[51]\t$data[52]\t$data[53]\t$data[54]\n"; 
	push(@bioid,"$data[25],$z"); $b++; #print "$data[25]\t$b\n";
	$c=$b-1; 
	printf AW "7".'%04s',$z; print AW "\t0\t"; printf AW "6".'%04s',$z; print AW "\tGenBank\t$data[8]\t$data[12]\t$data[24]\t"; printf AW "5".'%04s',$z; print AW "\t$data[37]\n";
	printf TW "5".'%04s',$z; print TW "\t$data[26]\t$data[27]\t$data[28]\t\t$data[29]\t$data[30]\t$data[31]\t$data[32]\t$data[33]\t$data[34]\t$data[39]\t$data[38]\t$data[35]\t$data[36]\n";
	for($g=0;$g<=$#dataids;$g++){
		push(@idt,$c); $nid = $idt[-1];  $c=$h+$#idt;
		$idx2=`grep -w '$dataids[$g]\$' $ARGV[3] | cut -f1`; chomp $idx2;
		#printf "8".'%04s',$c; print "****\n"; 
		printf DW "8".'%04s',$c; print DW "\t"; if($data[37] eq "") {printf DW "\t";}  else {printf DW "Plasmid\t";} printf DW "$idx2\t"; printf DW "7".'%04s',$z; print DW "\n";
	}
}
close TW;
close BW;
close AW;
close DW;

#Reading Antibiogram data and removing the header line
open(FB,"$ARGV[1]");
@meta=<FB>; close FB;
shift(@meta); 

$nid=$c=$j=""; @idt = ();
$s=$start_no;
#Writing Antibiogram data
open(NW,">Antibiogram.txt");
foreach $linea(@meta){
	chomp $linea;
	@datamic =  (); 
	@datamic = split("\t",$linea); 
	$y=12877+$s; 
		foreach $bio(@bioid) {
		@biodata=(); 
		#push(@idt,$c); $nid = $idt[-1]; $c=$h+$#idt; 
		@biodata = split(",",$bio);
			if($datamic[0] eq $biodata[0]) {$smid=""; $smid = $biodata[1]; push(@idt,$c); $nid = $idt[-1]; $c=$h+$#idt;
				printf NW "9".'%04s',$c; print NW "\t$datamic[1]\t\t$datamic[6]\t$datamic[9]\t$datamic[7]\t$datamic[4]\t$datamic[3]\t$datamic[5]\t$datamic[2]\t$datamic[10]\t$datamic[8]\t"; printf NW "6".'%04s',$smid; print NW "\n";
			}
   }
}
close NW;