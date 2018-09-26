<?php 
  include 'includes/header.php';
?>
  <title>Taxonomy Map</title>
  <link rel="stylesheet" href="amr_map_assets/css/styles.css">
  <script src="https://cdn.plot.ly/plotly-latest.min.js"></script>
  <script type="text/javascript" src="http://ionden.com/a/plugins/ion.rangeSlider/static/js/ion-rangeSlider/ion.rangeSlider.js"></script>
  <link rel="stylesheet" type="text/css" href="http://ionden.com/a/plugins/ion.rangeSlider/static/css/ion.rangeSlider.css">
  <link rel="stylesheet" type="text/css" href="http://ionden.com/a/plugins/ion.rangeSlider/static/css/ion.rangeSlider.skinHTML5.css">
  <style type="text/css">
body {
  height: 100%;
  overflow: hidden;
  margin: 40px;
  font-family: Arial, sans-serif;
  font-size: 12px;
}
.range-slider {
  position: relative;
  height: 80px;
}
.extra-controls {
  position: relative;
  border-top: 3px solid #000;
  padding: 10px 0 0;
}
</style>
<script type="text/javascript">
$(function(){
var $range = $(".js-range-slider");
$range.ionRangeSlider({
type: "single",
min: 0,
max: 10000,
from: 0,
grid: true,
grid_num: 20,
prettify: function(num) {
if (num > 0 && num <= 5000) {
  num = Math.round(num / 500);
  num = num * 500;
}
if (num > 5000) {
  num = Math.round(num / 1000);
  num = num * 1000;
}
return num;
}
});
});
</script>
<!-- Page contents -->
<div style="position: relative; padding: 200px;">

    <div class="range-slider">
  <input type="text" class="js-range-slider" value="" />
</div>
</div>


<?php
  include 'includes/footerx.php';
?>
