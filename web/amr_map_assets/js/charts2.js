let selectYear = document.getElementById("select");
let selectUnknownDates = document.getElementById("unknown-date");
let selectUnknownLocations = document.getElementById("unknown-location");


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
    if (selectUnknownDates.checked && selectUnknownLocations.checked) {
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
Plotly.d3.csv("https://cdc-1.jcvi.org:8081/getMapData", function(err, rows) {
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
        Plotly.d3.csv("https://cdc-1.jcvi.org:8081/getMapData", function(err, rows) {
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
