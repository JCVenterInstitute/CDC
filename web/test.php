<?php include 'includes/header.php';?>
<div class="container">
<?php
#echo "<pre>"; print_r($_POST); echo "</pre>"; 
$genera = !empty($_POST['genera']) ? $_POST['genera'] : ''; #echo "**$genera OO**<br>";
$feature = !empty($_POST['FeatureCodes']) ? $_POST['FeatureCodes'] : '';
$option = !empty($_POST['optionsRadios']) ? $_POST['optionsRadios'] : ''; #echo "**$option OO**<br>";
#print_r($feature); 
$body = !empty($_POST['body']) ? $_POST['body'] : ''; ##echo "**$body BB **<br>";
$city = !empty($_POST['city']) ? $_POST['city'] : ''; ##echo "**$city CI **<br>";
$state = !empty($_POST['state']) ? $_POST['state'] : ''; ##echo "**$state SS **<br>";
$country = !empty($_POST['country']) ? $_POST['country'] : ''; ##echo "**$country CU **<br>";


$bodyx = !empty($_POST['bodyx']) ? $_POST['bodyx'] : ''; ##echo "**$bodyx XBB **<br>";
$cityx = !empty($_POST['cityx']) ? $_POST['cityx'] : ''; ##echo "**$cityx XCI **<br>";
$statex = !empty($_POST['statex']) ? $_POST['statex'] : ''; ##echo "**$statex XSS **<br>";
$countryx = !empty($_POST['countryx']) ? $_POST['countryx'] : ''; ##echo "**$countryx XCU **<br>";

$site=$select=$term=$selname=$selid=$termx="";
if($option == "option1"){
	if (empty($city) && empty($state) && $country != "") { $select = $country; $term = "country"; }
	if (empty($city) && $state != "" && $country != "") { $select = $state; $term = "state";  }
	if ($city != "" && $state != "" && $country != "") { $select = $city; $term = "city";  }
$selname=$term."_name"; $selid=$term."_id";
$sql=$query="";
$sql = "SELECT body_name FROM body WHERE body_id = '$body'"; $query=mysql_query($sql);
$ids=mysql_fetch_array($query);
$site=$ids[0];
$sql=$query=$selected=$name=$snam=$selfile="";
$sql = "SELECT $selname FROM $term WHERE $selid = '$select'"; $query=mysql_query($sql);
$ids=mysql_fetch_array($query);
$selected=$ids[0];
}
if($option == "option2"){
	if (empty($cityx) && empty($statex) && $countryx != "") { $select = $countryx; $term = "country";  }
	if (empty($cityx) && $statex != "" && $countryx != "") { $select = $statex; $term = "state";  }
	if ($cityx != "" && $statex != "" && $countryx != "") { $select = $cityx; $term = "city";  }
$termx=$term."x";
$selname=$term."_name"; $selid=$term."_id";
$sql=$query="";
$sql = "SELECT body_name FROM bodyx WHERE body_id = '$bodyx'"; $query=mysql_query($sql);
$ids=mysql_fetch_array($query);
$site=$ids[0];
$sql=$query=$selected=$name=$snam=$selfile="";
$sql = "SELECT $selname FROM $termx WHERE $selid = '$select'"; $query=mysql_query($sql);
$ids=mysql_fetch_array($query);
$selected=$ids[0];
}
$feature = array_diff($feature, array($selected));
$names = implode(" ",$feature); #echo "<br>***$names***$selected******</br>";
$sql=$query="";
#echo "<br>";
                #$ran= "12981";
                $ran= rand(100, 100000);
                $dir = "/export/apache/htdocs/fmd/temp/$ran";
                $dir1 = "/export/apache/htdocs/fmd/temp/$ran/";
                exec("mkdir $dir");
                exec("chmod 777 $dir");

$names=$selected." ".$names;
$snam=str_replace(" ",", ",$names); $snam=trim($snam);
$snames = explode(" ", $names); #print_r($snames);
#if($country != ""){
$selfile=$genera."_".$term."_average"; #echo "**S $site**N $names***A $selfile**<br><br>";
#die;
#system("perl /export/apache/htdocs/fmd/data/average/transpose.pl /export/apache/htdocs/fmd/data/average/genus_country_average $site $names | egrep -v 'ID|Country' | sort -grk2 | head -20 > $dir/data.txt");
system("perl /export/apache/htdocs/fmd/data/average/transpose.pl /export/apache/htdocs/fmd/data/average/$selfile $site $names | sort -grk2 | head -20 > $dir/data.txt");
system("perl /export/apache/htdocs/fmd/data/average/transpose.pl /export/apache/htdocs/fmd/data/average/$selfile $site $names | grep '$term' | cut -f2- > $dir/names.txt");
$newname = file("$dir/names.txt"); 
$newnames=join("\t",$newname);  $newnames= trim($newnames);
$upname=explode("\t",$newnames); 
$genera=ucfirst($genera);
#echo "perl /export/apache/htdocs/fmd/data/average/transpose.pl /export/apache/htdocs/fmd/data/average/class_country_average $site $names | egrep -v 'ID|Country' | sort -nrk2 | head -20 > $dir/data.txt <br>";
system("perl /export/apache/htdocs/fmd/data/average/transpose2.pl -i $dir/data.txt -o $dir/res.txt");
		$files = file("$dir/res.txt"); 
		$name=""; $name=array_shift($files);
		$name=str_replace("\t","','",$name); $name=trim($name);
		#echo "<br>$name<br>$names<br>"; print_r($files); print_r($snames); print_r($newname);
		foreach ($files as $y){
		$data = explode("\n", $y);
			foreach ($data as $x){
			#echo "$x<br>";
			}
		}
		$fp=fopen($dir1."script.txt","w+");
fwrite ($fp,"<script type='text/javascript'>
$(function () {
    var chart;
    $(document).ready(function() {
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'container',
                type: 'column'
            },
            title: {
                text: '$genera Level Comparison of $newnames'
            },
            subtitle: {
                text: 'Source: www.fmd.jcvi.org'
            },
            xAxis: {
                                categories: ['$name']
            },
            yAxis: {
                title: {
                    text: 'Percentage composition'
                },
                labels: {
                    formatter: function() {
                        return this.value;
                    }
		  }
                },
            tooltip: {
                formatter: function() {
                    return this.series.name +' has <b>'+
                        Highcharts.numberFormat(this.y, 2) +'</b><br/>percent composition of '+ this.x;
                }
            },
            plotOptions: {
                area: {
                    pointStart: 0,
                    marker: {
                        enabled: false,
                        symbol: 'circle',
                        radius: 2,
                        states: {
                            hover: {
                                enabled: true
                            }
                        }
                    }
                }
            },
            series: [
			{");
	for($i=0;$i<count($upname);$i++){
	$na=$val=""; 
	$na=$upname[$i]; trim ($na);
	$val = isset($files[$i]) ? $files[$i] : null;
	$val=trim($val);
	$val=str_replace("\t",",",$val);
fwrite($fp, "   name: '$na',
                data: [$val]
            },{");
}
fwrite($fp, "X
            }]
        });
    });

});
                </script>
");
fclose($fp);
system("perl -pi -e 's/},{X//g' $dir/script.txt");
                echo "<meta  http-equiv='refresh' content='1;url=datacompare.php?ran=$ran&genera=$genera' />";
?>
</div>
