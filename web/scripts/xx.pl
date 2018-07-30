open(FA,"$ARGV[0]");
@main=<FA>; close FA;
$aa=shift(@main); print "$aa\n";

for($i=0;$i<=$#main;$i++){
        chomp $main[$i];
        @data = ();
        @data = split("\t",$main[$i]);
	$ax=shift(@data);
        printf "1".'%04s',$i+1; print "\t$main[$i]\n";

}
