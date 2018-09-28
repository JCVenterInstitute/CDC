#Create SOLR cores and copy config files from Git
#Update mysql cdc_app user password before running the script, use only alphnumeric for password.
CDC_APP_PASSWORD=changethis

#!/bash/bin
if [[ ! $BASE ]];
then
  echo "ERROR: $BASE environment is not set required for installation";
  echo "ERROR: Exiting the installation. Please contact support. ";
  exit 0;
fi

#Create SOLR cores and copy config files from Git
$BASE/server/solr-7.3.0/bin/solr create -c amr_map
$BASE/Git/CDC/Solr/Solr_cores/amr_map/conf/*  $BASE/server/solr-7.3.0/solr/amr_map/conf/

$BASE/server/solr-7.3.0/bin/solr create -c antibiogram
$BASE/Git/CDC/Solr/Solr_cores/antibiogram/conf/*  $BASE/server/solr-7.3.0/solr/antibiogram/conf/

$BASE/server/solr-7.3.0/bin/solr create -c browse_all_data
$BASE/Git/CDC/Solr/Solr_cores/browse_all_data/conf/*  $BASE/server/solr-7.3.0/solr/browse_all_data/conf/

$BASE/server/solr-7.3.0/bin/solr create -c classification_variants
$BASE/Git/CDC/Solr/Solr_cores/classification_variants/conf/*  $BASE/server/solr-7.3.0/solr/classification_variants/conf/

$BASE/server/solr-7.3.0/bin/solr create -c many_antibiogram_singlex
$BASE/Git/CDC/Solr/Solr_cores/many_antibiogram_singlex/conf/*  $BASE/server/solr-7.3.0/solr/many_antibiogram_singlex/conf/

$BASE/server/solr-7.3.0/bin/solr create -c my_core_exp
$BASE/Git/CDC/Solr/Solr_cores/my_core_exp/conf/*  $BASE/server/solr-7.3.0/solr/my_core_exp/conf/

$BASE/server/solr-7.3.0/bin/solr create -c primer
$BASE/Git/CDC/Solr/Solr_cores/primer/conf/*  $BASE/server/solr-7.3.0/solr/primer/conf/

$BASE/server/solr-7.3.0/bin/solr create -c tax_sm_anti_relation
$BASE/Git/CDC/Solr/Solr_cores/tax_sm_anti_relation/conf/*  $BASE/server/solr-7.3.0/solr/tax_sm_anti_relation/conf/


sed -i '' 's/replaceDBpassword/$CDC_APP_PASSWORD/' $BASE/server/solr-7.3.0/solr/amr_map/conf/solr-data-config.xml
sed -i '' 's/replaceDBpassword/$CDC_APP_PASSWORD/' $BASE/server/solr-7.3.0/solr/antibiogram/conf/solr-data-config.xml
sed -i '' 's/replaceDBpassword/$CDC_APP_PASSWORD/' $BASE/server/solr-7.3.0/solr/classification_variants/conf/solr-data-config.xml
sed -i '' 's/replaceDBpassword/$CDC_APP_PASSWORD/' $BASE/server/solr-7.3.0/solr/many_antibiogram_singlex/conf/solr-data-config.xml
sed -i '' 's/replaceDBpassword/$CDC_APP_PASSWORD/' $BASE/server/solr-7.3.0/solr/my_core_exp/conf/solr-data-config.xml
sed -i '' 's/replaceDBpassword/$CDC_APP_PASSWORD/' $BASE/server/solr-7.3.0/solr/primer/conf/solr-data-config.xml
sed -i '' 's/replaceDBpassword/$CDC_APP_PASSWORD/' $BASE/server/solr-7.3.0/solr/tax_sm_anti_relation/conf/solr-data-config.xml

mkdir -p $BASE/server/solr-7.3.0/solr/CDC/scipts/
cp $BASE/Git/CDC/Solr/scripts/refresh_solr.sh $BASE/server/solr-7.3.0/solr/CDC/scipts/
