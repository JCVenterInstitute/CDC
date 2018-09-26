<?php 
  include 'includes/header.php';
?>
<?php
// generate path 
    $search_q = $_POST['search_query'];
//  write files 
    $date_rand='amr_map_'.date("Y_m_d_H_i_s");
    $random_string=$date_rand.rand(0, 10000);
    $create_path =getcwd()."/tmp/".$random_string;
    $path = "./tmp/".$random_string;
    mkdir($path, 0700);
    $mapfile = fopen("$path/map_file.csv", "w") or die("Unable to open file!");
    $post_fetch= `curl -X POST -H 'Content-Type: application/json' 'http://cdc-1.jcvi.org:8983/solr/my_core_exp/query' -d   '{  query : "all_fields:$search_q"  }'`;
    $js= json_decode($post_fetch);
    $max_row_no=$js->response->numFound;
    // if no file found then just return.
    if($js->response->numFound==0){
      return; 
    }
    $post_fetch= `curl "http://cdc-1.jcvi.org:8983/solr/my_core_exp/select?fl=Specimen_Collection_Date,Specimen_Collection_Location,Specimen_Collection_Location_Country,Specimen_Collection_Location_Longitude,Gene_Symbol,Specimen_Collection_Location_Latitude&q=all_fields:$search_q&rows=$max_row_no&wt=csv"`;
    fwrite($mapfile, $post_fetch);
    fclose($mapfile);
?>

  <title>Taxonomy Map</title>
  <link rel="stylesheet" href="amr_map_assets2/css/styles.css">
  <script src="https://cdn.plot.ly/plotly-latest.min.js"></script>
<div class="wrapper">
  <h3>AMR Map</h2>
  <p>The Anti-Microbial Database (AMRdb) is a anti microbial analysis resource of AMR genes along with their details. <a id="show-btn">Show Instructions </a></p>
  <div id="notes-container" class="hidden">
      <h3>Instructions</h3>
      <div id="notes">
          <p>Hover over a point on the map to see a pie chart showing the organisms. Hovering over sections of the pie chart shows the count of each organism.</p>
          <p>Read piechart key carefully. Colors may change between data points.</p>
          <p>Entries in the database with a year value of "undefined" will not be shown on the map.</p>  
          <p>If unknown location data is shown, the dot will appear in the middle of the pacific ocean.</p>
      </div>
      <a id="close-btn">Close</a>
  </div> 
  <span>Select Year (1980-2018):</span>
  <div>  
    <input type="range" name="select" id="select" value="" min="1970" max="2018" step="1" value="2008"><span id="range_value"></span><br> 
    <input type="checkbox" id="show-all" name="others" checked="checked" /> Show All<br>
    <input type="checkbox" id="unknown-date" name="others"/> Show samples with unknown dates<br>
    <input type="checkbox" id="unknown-location" name="others"/> Show samples with unknown locations
  </div>
  <div id="amr_map"></div>
  <div id="sidebar"></div>
</div>
<!-- <script src="amr_map_assets/js/charts2.js"></script> -->
<script src="amr_map_assets/js/slider_value.js"></script>
<script type="text/javascript">
let file_location = "<?php echo "$path/map_file.csv"; ;?>";
let selectYear = document.getElementById("select");
let selectUnknownDates = document.getElementById("unknown-date");
let selectUnknownLocations = document.getElementById("unknown-location");
let showall = document.getElementById("show-all");



// SUB-FUNCTIONS
function unpack(rows, key) {
    return rows.map(function (row) { return row[key]; });
}
function unpack_location_country(rows, location,country) {
    return rows.map(function (row) { return row[location]+', '+row[country]; });
}

function isEmptyDate(row, date) {
    return date === "" || row.Specimen_Collection_Date === "Unknown Date";
}

function changeUnknownCoords(filteredRows) {
   for (i = 0; i < 10; i++) {
       if (filteredRows[i].Specimen_Collection_Location_Longitude === "None") {
            filteredRows[i].Specimen_Collection_Location_Longitude = "190.0";
            filteredRows[i].Specimen_Collection_Location_Latitude = "8.78";
       }
   }
   return filteredRows;
}

function getData(filteredRows) {
    let data = [{
        type: 'scattergeo',
        mode: 'dots',
        marker: {
            size: 10
        },
        year: unpack(filteredRows, 'Specimen_Collection_Date'),
        lon: unpack(filteredRows, 'Specimen_Collection_Location_Longitude'),
        lat: unpack(filteredRows, 'Specimen_Collection_Location_Latitude'),
        text: unpack_location_country(filteredRows, 'Specimen_Collection_Location','Specimen_Collection_Location_Country'),
        organisms: unpack(filteredRows, 'Gene_Symbol')
    }];
    // console.log(data);
    return data;
}

function getFilteredRows(rows) {
    if(showall.checked){
      return filteredRows = rows;
    }else if (selectUnknownDates.checked && selectUnknownLocations.checked) {
        filteredRows = rows.filter(row => row.Specimen_Collection_Date.match(/\d{4}/) == selectYear.value || isEmptyDate(row, row.Specimen_Collection_Date));
        return changeUnknownCoords(filteredRows);
    } else if (selectUnknownLocations.checked && !selectUnknownDates.checked) {
        filteredRows = rows.filter(row => row.Specimen_Collection_Date.match(/\d{4}/) == selectYear.value);
        return changeUnknownCoords(filteredRows);
    } else if (selectUnknownDates.checked && !selectUnknownLocations.checked) {
        return rows.filter(row => row.Specimen_Collection_Date.match(/\d{4}/) == selectYear.value || isEmptyDate(row, row.Specimen_Collection_Date));
    } else {
        return rows.filter(row => row.Specimen_Collection_Date.match(/\d{4}/) == selectYear.value);
    }
}

function bakePieChart(data) {
    let orgs = data.points[0].data.organisms;
    let lats = data.points[0].data.lat;
    let lons = data.points[0].data.lon;
    let dataList = []

    for (let i = 0; i < lats.length; i++) {
        dataList.push([orgs[i], lats[i], lons[i]]);
    }

    filteredOrgs = [];

    for (let i = 0; i < dataList.length; i++) {
        if (dataList[i][1] == data.points[0].lat && dataList[i][2] == data.points[0].lon) {
            filteredOrgs.push(dataList[i][0]);
        } 
    }

    let pieData = [{
        values: filteredOrgs.length,
        labels: filteredOrgs,
        type: 'pie'
    }];

    let pieLayout = {
        height: 400,
        margin: {
             l: 10,
             r: 10,
             b: 10,
             t: 10
        },
        legend: {"orientation": "h"}
    };

    Plotly.newPlot('sidebar', pieData, pieLayout, {displayModeBar: false});
}


// MAIN
//  change this
Plotly.d3.csv(file_location, function(err, rows) {
    filteredRows = getFilteredRows(rows);
    data = getData(filteredRows);
    // console.log(data);
    // console.log(filteredRows);

    let layout = {
        //autosize: true,
        //width: 1000,
        //height: 500,
        padding: {
            l: 0,
            r: 0,
            b: 0,
            t: 0,
        },
        margin: {
            l: 0,
            r: 0,
            b: 0,
            t: 0,
        },
        yaxis: {
            fixedrange: true
        },
        xaxis: {
            fixedrange: true
        },
        showlegend: false,
        geo: {
            showcountries: true,
            showland: true,
            showlakes: true,
            landcolor: 'rgb(224, 224, 224)',
            countrycolor: 'rgb(150, 150, 150)',
            countrywidth: 1,
            lakecolor: 'rgb(255, 255, 255)',
            projection: {
                type: 'equirectangular'
            },
            coastlinewidth: .5,
            lataxis: {
                showgrid: false,
                tickmode: 'linear',
                dtick: 10,
            },
            lonaxis:{
                showgrid: false,
                tickmode: 'linear',
                dtick: 20,
            }
        }
    };

    Plotly.plot('amr_map', data, layout, {displayModeBar: false});

    document.addEventListener('change', function(e){
        Plotly.d3.csv(file_location, function(err, rows) {
            filteredRows = getFilteredRows(rows);
            data = getData(filteredRows)
            // console.log(data);

            Plotly.newPlot('amr_map', data, layout, {displayModeBar: false});
            document.getElementById('amr_map').on('plotly_hover', function(data) {
                bakePieChart(data);
            });
        });
    });

    document.getElementById('amr_map').on('plotly_hover', function(data) {
        bakePieChart(data);
    });
});

</script>
<?php
  include 'includes/footer.php';
?>
