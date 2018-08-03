<?php 
  include 'includes/header.php';
?>

  <title>Taxonomy Map</title>
  
  <link rel="stylesheet" href="amr_map_assets/css/styles.css">
  <script src="https://cdn.plot.ly/plotly-latest.min.js"></script>

<div class="wrapper">
  <h3>Organism Map</h2>
  <p>The Anti-Microbial Database (AMRdb) is a anti microbial analysis resource of AMR genes along with their details.</p>
  <p>Hover over a point on the map to see a pie chart showing the organisms, while hovering over sections of the pie chart shows the count of each organism.</p>
  <p><span class="bold">Note: </span>Read piechart key carefully. Colors may change between data points.</p>

  <span>Select Year (2008-2018):</span>
  <input id="select" type="range" min="2008" max="2018" steps="10" />

  <div id="map"></div>
  <div id="sidebar"></div>
</div>


<script src="amr_map_assets/js/charts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-rc.2/js/materialize.min.js"></script>

<?php
  include 'includes/footer.php';
?>
