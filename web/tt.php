<?php
$ran = $_GET["ran"];


$dir="tmp/$ran";
    $cur = getcwd();
    echo getcwd();
    chdir("tmp/56085/outx/alignments/");
    include("10321_hit.html");
    echo getcwd();
    #include("tmp/56085/outx/alignments/10321_hit.html");
    chdir($cur);
