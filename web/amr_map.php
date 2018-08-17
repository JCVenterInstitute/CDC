<?php 
  include 'includes/header.php';
?>

  <title>Taxonomy Map</title>
  
  <link rel="stylesheet" href="amr_map_assets/css/styles.css">
  <script src="https://cdn.plot.ly/plotly-latest.min.js"></script>

<div class="wrapper">
  <h3>Organism Map</h2>
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
    <input type="range" name="select" id="select" value="" min="1980" max="2018" step="1" value="2008"><span id="range_value"></span> 
    <input type="checkbox" id="unknown-date" name="others"/> Show samples with unknown dates
    <input type="checkbox" id="unknown-location" name="others"/> Show samples with unknown locations
  </div>
  <div id="amr_map"></div>
  <div id="sidebar"></div>
</div>

<script src="amr_map_assets/js/charts2.js"></script>
<script src="amr_map_assets/js/slider_value.js"></script>
<?php
  include 'includes/footer.php';
?>
