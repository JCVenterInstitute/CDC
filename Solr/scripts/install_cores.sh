#Create SOLR cores and copy config files from Git 
#!/bash/bin
if [[ ! $BASE ]];
then
  echo "ERROR: $BASE environment is not set required for installation";
  echo "ERROR: Exiting the installation. Please contact support. ";
  exit 0;
fi

$BASE/solr-7.3.0/bin/solr create -c amr_map
$BASE/Git/CDC/Solr/Solr_cores/amr_map/conf/*  $BASE/solr-7.3.0/solr/amr_map/conf/

$BASE/solr-7.3.0/bin/solr create -c antibiogram
$BASE/Git/CDC/Solr/Solr_cores/antibiogram/conf/*  $BASE/solr-7.3.0/solr/antibiogram/conf/

$BASE/solr-7.3.0/bin/solr create -c browse_all_data
$BASE/Git/CDC/Solr/Solr_cores/browse_all_data/conf/*  $BASE/solr-7.3.0/solr/browse_all_data/conf/

$BASE/solr-7.3.0/bin/solr create -c classification_variants
$BASE/Git/CDC/Solr/Solr_cores/classification_variants/conf/*  $BASE/solr-7.3.0/solr/classification_variants/conf/

$BASE/solr-7.3.0/bin/solr create -c many_antibiogram_singlex
$BASE/Git/CDC/Solr/Solr_cores/many_antibiogram_singlex/conf/*  $BASE/solr-7.3.0/solr/many_antibiogram_singlex/conf/

$BASE/solr-7.3.0/bin/solr create -c my_core_exp
$BASE/Git/CDC/Solr/Solr_cores/my_core_exp/conf/*  $BASE/solr-7.3.0/solr/my_core_exp/conf/

$BASE/solr-7.3.0/bin/solr create -c primer
$BASE/Git/CDC/Solr/Solr_cores/primer/conf/*  $BASE/solr-7.3.0/solr/primer/conf/

$BASE/solr-7.3.0/bin/solr create -c tax_sm_anti_relation
$BASE/Git/CDC/Solr/Solr_cores/tax_sm_anti_relation/conf/*  $BASE/solr-7.3.0/solr/tax_sm_anti_relation/conf/
