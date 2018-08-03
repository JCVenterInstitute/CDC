Plotly.d3.csv("http://cdc-1.jcvi.org:8983/solr/my_core_exp/select?fl=Specimen_Collection_Date,Specimen_Collection_Location_Longitude,Specimen_Collection_Location_Latitude,Gene_Symbol&q=*:*&wt=csv", function(err, rows) {

    function unpack(rows, key) {
        return rows.map(function(row) { return row[key]; });
    }
    
    let selectYear = document.getElementById("select");
    let filteredRows = rows.filter(row => row.year == selectYear.value);

    let data = [{
        type: 'scattergeo',
        mode: 'dots',
        year: unpack(filteredRows, 'year'),
        lon: unpack(filteredRows, 'longitude'),
        lat: unpack(filteredRows, 'latitude'),
        organisms: unpack(filteredRows, 'organism')
    }];

    let layout = {
        margin: {
            l: 0,
            r: 0,
            b: 0,
            t: 0,
        },
        showlegend: false,
        geo: {
            resolution: 20,
            showland: true,
            showlakes: true,
            landcolor: 'rgb(204, 204, 204)',
            countrycolor: 'rgb(204, 204, 204)',
            lakecolor: 'rgb(255, 255, 255)',
            projection: {
                type: 'equirectangular'
            },
            coastlinewidth: 1,
            lataxis: {
                range: [ 0, 60 ],
                showgrid: false,
                tickmode: 'linear',
                dtick: 10
            },
            lonaxis:{
                range: [-100, 20],
                showgrid: false,
                tickmode: 'linear',
                dtick: 20
            }
        }
    };


    Plotly.plot('map', data, layout, {displayModeBar: false});

    function makePieChart(data) {

        let orgs = data.points[0].data.organisms;
        let lats = data.points[0].data.lat;
        let lons = data.points[0].data.lon;

        let dataList = []
        for (let i = 0; i < lats.length; i++) {
            dataList.push([orgs[i], lats[i], lons[i]]);
        }

        filteredOrgs = [];

        for (let i = 0; i < dataList.length; i++) {
            console.log(i,data.points[0].lat,dataList[i][1],dataList[i][2],data.points[0].lon)
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
            width: 500
        };
          
        Plotly.newPlot('sidebar', pieData, pieLayout, {displayModeBar: false});
    }

    selectYear.addEventListener('change', () => {
        let filteredRows = rows.filter(row => row.year == selectYear.value);

        let data = [{
            type: 'scattergeo',
            mode: 'dots',
            year: unpack(filteredRows, 'year'),
            lon: unpack(filteredRows, 'longitude'),
            lat: unpack(filteredRows, 'latitude'),
            organisms: unpack(filteredRows, 'organism')
        }];
        Plotly.newPlot('map', data, layout, {displayModeBar: false});

        document.getElementById('map').on('plotly_hover', function(data) {
            
            makePieChart(data);
        });
    });

    document.getElementById('map').on('plotly_hover', function(data) {
        makePieChart(data);
    });
});
