<?php



function main(){



// $db_json

//  first populate db database with 0s 
	$mydata = get_Solr_data();
$db_json = init_empty_data($mydata);
// var_dump($db_json);
echo "<br>";
// die;

// die;


		$myfile = fopen("country-codes", "r") or die("Unable to open file!");
			// Output one line until end-of-file
			while(!feof($myfile)) {
			  // echo fgets($myfile) . "<br>";
			  $line = fgets($myfile);
			  // echo "$line<br>";
			  // $abv_db[]= 
		 		$line = preg_split('/\t/', $line);
				$abv_db[$line[0]]=$line[1];
			  // echo "$line[0]<br>";
			}
			fclose($myfile);
 // array_key_exists($country, $abv_db)?[$abv_db[$country],$country]:['None','None'];




		foreach ($mydata as $key => $item) {
			if($item->Specimen_Collection_Location_Country){
					// get the abviation for the country
					$year =isset($item->Specimen_Collection_Year)?$item->Specimen_Collection_Year:"" ;
					$country =isset($item->Specimen_Collection_Location_Country)?$item->Specimen_Collection_Location_Country:"";
					$country = ucwords(trim(strtolower($country)));
					$country = array_key_exists($country, $abv_db)?[$abv_db[$country],$country]:['None','None'];
					$city =isset($item->Specimen_Collection_Location)?$item->Specimen_Collection_Location:"";
					$lat =isset($item->Specimen_Collection_Location_Latitude)?$item->Specimen_Collection_Location_Latitude:"0";
					$long =isset($item->Specimen_Collection_Location_Longitude)?$item->Specimen_Collection_Location_Longitude:"0";
					// echo "$year : $country[0] => $country[1] ";	
					// count where the year is the year. 
					// split by | take the first and see if its in the solr year 
					$element_copy = $country[0];

					// $xx = in_array($element_copy,$solr_data_copy[$year])?'yes ': ' no ';
					// echo "$element_copy  in $year array ?   in_ ".$xx."<br>";
					// existed then do not add
					if(!in_array(trim($element_copy),$solr_data_copy[$year])&&$country[0]!="None"){

						$solr_data_copy[$year][] = trim($element_copy);
						$repeats=get_same_year_country_count($year,$country[1]);

						$element=$country[0].'|'.$country[1].'|'.$repeats.'|'.$lat.'|'.$long.'|'.$city;
						$solr_data[$year][] = $element;
						$tmp = trim($country[0]);
						$db_json[$year][$tmp]['value']= $db_json[$year][$tmp]['value']+$repeats;

						// find the city then add 1 to the corresponding location. 
						// echo "abv: $country[0]<br>";

						// $solr_data_plots[$country[1]]=$country[1].'|'.$lat.'|'$long;
						// stand alone file. describe the plots 
						if(trim($city)!=""){
							$solr_data_plots[$country[1]]="\"$city\":{\n\"latitude\":$lat,"."\"longitude\":$long,\n\"text\": {\n\"position\":\"left\",\n".
										                        "\"content\":\"\"".
										                    "},\n".
										                    "\"href\":\"\"\n}";
				$plots_total[$year][]="\"$city\":{\n\"value\":$db_json[$year][$tmp]['value'],\n\"tooltip\":{\n\"content\": \"<span style=font-weight:bold;>$city</span><br />Population: ".$db_json[$year][$tmp]["value"]."\"}\n}";
						}
						
					}

			}
		}
// die;


//  for each year have area and plots




		// var_dump($solr_data_plots);
		// start to write the file.
		// for ($i=0; $i <sizeof($solr_data); $i++) { 
		// 	print_r($solr_data[$i]);
		// 	echo "<br>";
		// }




//  uncomment this. 
		// $date_rand='map_'.date("Y_m_d_H_i_s");
		// $random_string=$date_rand.rand(0, 10000);
		// $create_path =getcwd()."/tmp/".$random_string;
		// $path = "./tmp/".$random_string;
		// // echo "<br>";
		// // echo getcwd();
		// // echo "$path";
		// // echo "<br>";
		// mkdir($path, 0700);

	 // $mapfile1 = fopen("$path/map_file_area_plot.json", "w") or die("Unable to open file!");
	 // $mapfile2 = fopen("$path/map_file_polts.json", "w") or die("Unable to open file!");

	// Output one line until end-of-file
	 $mapfile1 = fopen("Dummy_map_file_area_plot.json", "w") or die("Unable to open file!");

	$writer1="{\n";
		foreach ($db_json as $year => $value) {
			$areas="\"areas\":{\n";
			$plots="\"plots\":{\n";

			foreach ($value as $abv => $i) {
// $solr_data[$year][];
				// 0=> coutnry abv, 1=> country, 2=>count 3> latitude 4=> longtitude 5 => city

				$area[]= "\"".$abv."\": \n{\"value\": ".$i['value'].",\n\"tooltip\": {\"content\": \"".$i['country_val']."\"}\n}";

    //               if(trim($details[5])!=""){
				// $plot[]="\"$details[5]\":{\n\"value\":$details[2],\n\"tooltip\":{\n\"content\": \"<span style=font-weight:bold;>$details[5]</span><br />Population: $details[2]\"}\n}";
				// 	}
			}

			$areas.=implode(",\n", $area)."\n}\n";
			$plots.=implode(",\n", $plots_total[$year])."\n}\n";
			// print_r($plot);
			// $eachYear[]=;
			$eachYear[]="\"".$year."\": {\n".$areas.",\n".$plots."}\n";
			// echo "<br>";
}
	 	$writer1.=implode(",\n", $eachYear)."\n}\n";
		fwrite($mapfile1, $writer1);
		fclose($mapfile1);

// var_dump($writer1);


		die;
	 	$writer1="{\n";
		foreach ($solr_data as $year => $ele) {
			// echo "$year => ";
			$areas="\"areas\":{\n";
			$plots="\"plots\":{\n";
			$plot="";
			unset($area);
			unset($plot);
			foreach ($ele as $value) {
				// 0=> coutnry abv, 1=> country, 2=>count 3> latitude 4=> longtitude 5 => city
				$details = explode('|', $value);
				// $details = $db_json[$year];
				// echo "Year: $year<br><br>";
				// var_dump($details);
				// echo "$year : ";
				// print_r($details);
				// echo "<br>";
				$area[]= "\"".trim($details[0])."\": \n{\"value\": $details[2],\n\"href\": \"http://en.wikipedia.org/w/index.php?search=$details[1]\",\n\"tooltip\": {
                                \"content\": \"<span style=font-weight:bold;>$details[1]</span><br/>Population : $details[2]\"}\n}";

                  if(trim($details[5])!=""){
				$plot[]="\"$details[5]\":{\n\"value\":$details[2],\n\"tooltip\":{\n\"content\": \"<span style=font-weight:bold;>$details[5]</span><br />Population: $details[2]\"}\n}";
					}
			}

			$areas.=implode(",\n", $area)."\n}\n";
			$plots.=implode(",\n", $plot)."\n}\n";
			// print_r($plot);
			// $eachYear[]=;
			$eachYear[]="\"".$year."\": {\n".$areas.",\n".$plots."}\n";
			// echo "<br>";
		}




die;

	 	$writer1.=implode(",\n", $eachYear)."\n}\n";
	 	$writer2="{".implode(",\n", $solr_data_plots)."}";

		fwrite($mapfile1, $writer1);
		fclose($mapfile1);
		fwrite($mapfile2, $writer2);
		fclose($mapfile2);
		
// echo "$writer1";


}

function init_empty_data($solr_d){

$find_min_year = 0;
$find_max_year = 0; 
foreach ($solr_d as $key => $item) {
	# code...
	if(isset($item->Specimen_Collection_Year)&&$find_min_year==0){
		$find_min_year = $item->Specimen_Collection_Year;
	}
	if(isset($item->Specimen_Collection_Year)){
		if($find_max_year<$item->Specimen_Collection_Year){
			$find_max_year= $item->Specimen_Collection_Year;
		}
	}
}

// $mapfile1 = fopen("$path/map_file_area_plot.json", "w") or die("Unable to open file!");
for ($i=$find_min_year; $i <=$find_max_year ; $i++) { 
	// initialize the data
	$areas= init_a_year_area_data();
// var_dump($areas);
	// die;
	$db_json[$i]= $areas;
}

return $db_json;
}


function get_same_year_country_count($year,$country){
	$search_q=$_POST['search_query'];
	$post_fetch= `curl -X POST -H 'Content-Type: application/json' 'cdc-1.jcvi.org:8983/solr/my_core_exp/query' -d   '{  query : "Specimen_Collection_Year:$year AND Specimen_Collection_Location_Country:\"$country\""}'`;
		$js= json_decode($post_fetch);
		$max_row_no=isset($js->response->numFound)? $js->response->numFound : 0;
		// echo "rept : $max_row_no<br>";
		// echo "Specimen_Collection_Year: $year,Specimen_Collection_Location_Country :$country";
		// echo "return: $max_row_no";
		// echo "<br>";
		return $max_row_no;
}
function get_Solr_data(){
	$search_q=$_POST['search_query'];
	$post_fetch= `curl -X POST -H 'Content-Type: application/json' 'cdc-1.jcvi.org:8983/solr/my_core_exp/query' -d   '{  query : "all_fields:$search_q"}'`;
		$js= json_decode($post_fetch);
		$max_row_no=$js->response->numFound;
		if($js->response->numFound==0){
			return; 
		}
		$post_fetch= `curl -X POST -H 'Content-Type: application/json' 'cdc-1.jcvi.org:8983/solr/my_core_exp/query' -d   '{  query : "all_fields:$search_q" ,fields :["Specimen_Collection_Year","Specimen_Collection_Location_Country","Specimen_Collection_Location_Latitude","Specimen_Collection_Location_Longitude","Specimen_Collection_Location"],sort:"Specimen_Collection_Year asc", limit : $max_row_no }'`;
		$js= json_decode($post_fetch);
		$x_x_readytowrite_MOST=$js->response->docs;
		return $x_x_readytowrite_MOST;
}




function init_a_year_area_data(){

	// $countrydb=array(''=>'');
	 // read the local file to generate array. then use this array to do the following task. 
	 $myfile = fopen("country-codes", "r") or die("Unable to open file!");
	// Output one line until end-of-file
	while(!feof($myfile)) {
	  // echo fgets($myfile) . "<br>";
	  $line = fgets($myfile);
	  // echo "$line<br>";
	  // $countrydb[]= 
 		$line = preg_split('/\t/', $line);
 		// echo "$line[1]";
 		$tmp_abv = trim($line[1]);
		$countrydb[$tmp_abv]=["value"=>0,"tooltip"=>"{\"content\": \"<span style=\"font-weight:bold;\">$line[0]</span><br />", "country_val"=>"$line[0]" ];

	  // echo "$line[0]<br>";
	}
	fclose($myfile);
	 return $countrydb;
	 // return $country;
}


// get_country_abv('');
main();
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>AMR Map</title>
    <meta charset="utf-8"/>
    <style type="text/css">

        h1 {
            font-size: 30px;
            color: #613b1e;
            margin: 20px auto 20px auto;
            display: block;
            text-align: center;
        }

        h2 {
            margin: 0;
            padding: 0;
            font-size: 22px;
            color: #343434;

        }

        .container {
            width: 90%;
            overflow: hidden;
            min-width: 700px;
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
        }

        .knobContainer {
            text-align: center;
            margin: 10px;
        }

        .knobContainer canvas {
            cursor: pointer;
        }

        .rightPanel {
            float: right;
            width: 223px;
            border-radius: 5px;
            margin-left: 5px;
        }

        /* Specific mapael css class are below
         * 'mapael' class is added by plugin
        */
        
        .mapael .mapTooltip {
            position: absolute;
            background-color: #fff;
            moz-opacity: 0.80;
            opacity: 0.80;
            filter: alpha(opacity=80);
            border-radius: 4px;
            padding: 10px;
            z-index: 1000;
            max-width: 200px;
            display: none;
            color: #232323;
        }
        
        .mapael .map {
            margin-right: 228px;
            overflow: hidden;
            position: relative;
            background-color: #232323;
            border-radius: 5px;
        }

        /* For all zoom buttons */
        .mapael .zoomButton {
            background-color: #fff;
            border: 1px solid #ccc;
            color: #000;
            width: 15px;
            height: 15px;
            line-height: 15px;
            text-align: center;
            border-radius: 3px;
            cursor: pointer;
            position: absolute;
            top: 0;
            font-weight: bold;
            left: 10px;

            -webkit-user-select: none;
            -khtml-user-select : none;
            -moz-user-select: none;
            -o-user-select : none;
            user-select: none;
        }

        /* Reset Zoom button first */
        .mapael .zoomReset {
            top: 10px;
        }

        /* Then Zoom In button */
        .mapael .zoomIn {
            top: 30px;
        }

        /* Then Zoom Out button */
        .mapael .zoomOut {
            top: 50px;
        }
        
    </style>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/8.2.1/nouislider.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/8.2.1/nouislider.min.js" charset="utf-8"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js" charset="utf-8"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-mousewheel/3.1.13/jquery.mousewheel.min.js"
            charset="utf-8"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.2.7/raphael.min.js" charset="utf-8"></script>
    <script src="./jQuery-Mapael-master/js/jquery.mapael.js" charset="utf-8"></script>
    <script src="https://rawgit.com/aterrien/jQuery-Knob/master/dist/jquery.knob.min.js" charset="utf-8"></script>
    <script src="./jQuery-Mapael-master/js/maps/world_countries.js" charset="utf-8"></script>
    <script type="text/javascript">
        var plots ="";
        var data ="";
          $.ajax({url: "map_file_polts.json", success: function(result){
       plots =result;
    }});
          $.ajax({url: "map_file_area_plot.json", success: function(result){
       data =result;
    }});
// alert();
       // console.log(plots);
       // console.log(data);



    </script>
    <script type="text/javascript">
     setTimeout(function(){ $(function () {
            // Fake data for countries and cities from 2003 to 2013
            // Default plots params
            
            // Knob initialisation (for selecting a year)
            $(".knob").knob({
                release: function (value) {
                	// console.log( Object.assign(data[value],data[2008]));
                    $(".world").trigger('update', [{
                        mapOptions: data[value],
                        animDuration: 300
                    }]);
                }
            });

            // Mapael initialisation
            $world = $(".world");
            $world.mapael({
                map: {
                    name: "world_countries",
                    defaultArea: {
                        attrs: {
                            fill: "#fff",
                            stroke: "#232323",
                            "stroke-width": 0.3
                        }
                    },
                    defaultPlot: {
                        text: {
                            attrs: {
                                fill: "#b4b4b4",
                                "font-weight": "normal"
                            },
                            attrsHover: {
                                fill: "#fff",
                                "font-weight": "bold"
                            }
                        }
                    }
                    , zoom: {
                        enabled: true
                        , step: 0.25
                        , maxLevel: 20
                    }
                },
                legend: {
                    area: {
                        display: true,
                        title: "Country Count",
                        marginBottom: 7,
                        slices: [
                            {
                                max: 5000000,
                                attrs: {
                                    fill: "#6ECBD4"
                                },
                                label: "Less than 5M"
                            },
                            {
                                min: 5000000,
                                max: 10000000,
                                attrs: {
                                    fill: "#3EC7D4"
                                },
                                label: "Between 5M and 10M"
                            },
                            {
                                min: 10000000,
                                max: 50000000,
                                attrs: {
                                    fill: "#028E9B"
                                },
                                label: "Between 10M and 50M"
                            },
                            {
                                min: 50000000,
                                attrs: {
                                    fill: "#01565E"
                                },
                                label: "More than 50M"
                            }
                        ]
                    },
                    plot: {
                        display: true,
                        title: "Count",
                        marginBottom: 6,
                        slices: [
                            {
                                type: "circle",
                                max: 500000,
                                attrs: {
                                    fill: "#FD4851",
                                    "stroke-width": 1
                                },
                                attrsHover: {
                                    transform: "s1.5",
                                    "stroke-width": 1
                                },
                                label: "Less than 500 000",
                                size: 10
                            },
                            {
                                type: "circle",
                                min: 500000,
                                max: 1000000,
                                attrs: {
                                    fill: "#FD4851",
                                    "stroke-width": 1
                                },
                                attrsHover: {
                                    transform: "s1.5",
                                    "stroke-width": 1
                                },
                                label: "Between 500 000 and 1M",
                                size: 20
                            },
                            {
                                type: "circle",
                                min: 1000000,
                                attrs: {
                                    fill: "#FD4851",
                                    "stroke-width": 1
                                },
                                attrsHover: {
                                    transform: "s1.5",
                                    "stroke-width": 1
                                },
                                label: "More than 1M",
                                size: 30
                            }
                        ]
                    }
                },
                plots: $.extend(true, {}, data[2003]['plots'], plots),
                areas: data[2003]['areas']
            });




 slider = noUiSlider.create($(".slider")[0], {
                start: [1990, 2050],
                step: 1,
                connect: true,
                orientation: 'horizontal',
                range: {
                    'min': 1990,
                    'max': 2050
                },
                pips: {
                    mode: 'range',
                    density: 2
                }
            });

            slider.on('set', function(values){
                var opt = {
                    animDuration: 500,
                    hiddenOpacity: 0.1,
                    ranges: {
                        area: {
                            min: parseInt(values[0]),
                            max: parseInt(values[1])
                        }
                    }
                };

                $(".world").trigger("showElementsInRange", [opt]);
                $(".values").text("Show area with a count between " + parseInt(values[0]) + " and " + parseInt(values[1]) + " xxx");
            });


            $(slider).trigger("set"); 
             var all_hidden = 'show';
      $('#button-all').on('click', function () {
                all_hidden = (all_hidden == 'show') ? 'hide' : 'show';

                $(".world").trigger('update', [{
                        setLegendElemsState: all_hidden,
                        animDuration: 1000
                    }]);
            });
           
        });
    //do what you need here
}, 1000);
       
    </script>
</head>

<body>
<div class="container">
  <h1>Map</h1>
    <div class="slider">
    </div>
    <p class="values"></p>
    <!-- <h1>Dataviz example with <a href="https://github.com/neveldo/jQuery-Mapael">jQuery Mapael</a></h1> -->
    <!-- <a href="" id="zoom-northamerica">North America</a> <a href="" id="zoom-southamerica">South America</a> <a href="" id="zoom-europe">Europe</a> <a href="" id="zoom-asia">Asia</a> <a href="" id="zoom-africa">Africa</a>  <a href="" id="zoom-oceania">Oceania</a>-->
    <div class="world">
        <div class="rightPanel">
            <h2>Select a year</h2>
            <div class="knobContainer">
                <input class="knob" data-width="80" data-height="80" data-min="2003" data-max="2013" data-cursor=true
                       data-fgColor="#454545" data-thickness=.45 value="2003" data-bgColor="#c7e8ff"/>
            </div>
            <div class="areaLegend"></div>
            <div class="plotLegend"></div>
             <input type="button" value="Hide/Show all legends" id="button-all"/>
        </div>
        <div class="map"></div>
        <div style="clear: both;"></div>
    </div>
    <p style="text-align:center;">This dataviz is built on <a href="https://www.vincentbroute.fr/mapael/">Mapael</a> and
        <a href="http://anthonyterrien.com/knob/">Knob</a>. This is an example with fake data. </p>
</div>

</body>
</html>