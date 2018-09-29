<?php
session_start();
/* Process information passed by submit_search_qb using XML http request 
    then use echo to pass it back to submit for datatabe to generate output

// Not a single thing after echo !!!
// Not a thing after echo not even comments.!!!!!
*/
//-----------------------------------------------------------------------------------
echo $_SESSION['search_qb_sess'];
//--------------------------------------------------------------------------------
?>
