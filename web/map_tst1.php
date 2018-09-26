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
       console.log(plots);
       console.log(data);



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
                                max: 3000,
                                attrs: {
                                    fill: "#6ECBD4"
                                },
                                label: "Less than 5M"
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
                                max: 3000,
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
                            }

                        ]
                    }
                },
                plots: $.extend(true, {}, data[2003]['plots'], plots),
                areas: data[2003]['areas']
            });




 slider = noUiSlider.create($(".slider")[0], {
                start: [0, 2050],
                step: 1,
                connect: true,
                orientation: 'horizontal',
                range: {
                    'min': 0,
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
  <h1>Map with a legend for plotted cities and areas</h1>
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
<!--             <div class="areaLegend"></div>
            <div class="plotLegend"></div> -->
<!--              <input type="button" value="Hide/Show all legends" id="button-all"/>
 -->        </div>
        <div class="map"></div>
        <div style="clear: both;"></div>
    </div>
    <p style="text-align:center;">This dataviz is built on <a href="https://www.vincentbroute.fr/mapael/">Mapael</a> and
        <a href="http://anthonyterrien.com/knob/">Knob</a>. This is an example with fake data. </p>
</div>

</body>
</html>