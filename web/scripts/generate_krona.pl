`mysql -h cdc-1 -u cdc_admin -pxjCS6ufdVaWHMHYJ -e "SELECT * from CDC.Classification" | cut -f3,4,5 | grep -v "Drug_Family" > x.txt`;
`sort -u x.txt > xx.txt`;

open(fo,"x.txt");
@alldata =<fo>; close fo;
open(fa,"xx.txt");
@unqdata =<fa>; close fa;
open(fw,">cdc.txt");
foreach $datau(@unqdata){
    chomp $datau;
    $i=0;
    foreach $dataa(@alldata){
        chomp $dataa;
        if($dataa eq $datau){ $i++;}
    }
    print fw "$i\t$datau\n";
} 
close fa;

`/usr/local/projdata/8500/projects/CDC/server/KronaTools-2.7/bin/ktImportText cdc.txt -u http://marbl.github.io/Krona/ -o ../drug-classification.krona.html -n all`;
`perl -pi -e "s^http://marbl.github.io/Krona//src^js^g" ../drug-classification.krona.html`;
`rm x.txt xx.txt cdc.txt`;


`mysql -h cdc-1 -u cdc_admin -pxjCS6ufdVaWHMHYJ -e "SELECT * from CDC.Taxonomy" | cut -f3,4,6,7,8,9,10 | grep -v "Taxon_Phylum" > x.txt`;
`sort -u x.txt > xx.txt`;

open(fo,"x.txt");
@alldata =<fo>; close fo;
open(fa,"xx.txt");
@unqdata =<fa>; close fa;
open(fw,">cdc.txt");
foreach $datau(@unqdata){
    chomp $datau;
    $i=0;
    foreach $dataa(@alldata){
        chomp $dataa;
        if($dataa eq $datau){ $i++;}
    }
    print fw "$i\t$datau\n";
} 
close fa;

`/usr/local/projdata/8500/projects/CDC/server/KronaTools-2.7/bin/ktImportText cdc.txt -u http://marbl.github.io/Krona/ -o ../species-classification.krona.html -n all`;
`perl -pi -e "s^http://marbl.github.io/Krona//src^js^g" ../species-classification.krona.html`;
`rm x.txt xx.txt cdc.txt`;