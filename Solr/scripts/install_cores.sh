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

#Copy mysql connector lib
mkdir -p $BASE/server/solr-7.3.0/contrib/dataimporthandler/lib
cp  $BASE/git/CDC/Solr/lib/mysql-connector-java-8.0.11.jar  $BASE/server/solr-7.3.0/contrib/dataimporthandler/lib/
#Create SOLR cores and copy config files from Git
$BASE/server/solr-7.3.0/bin/solr create -c amr_map
cp $BASE/git/CDC/Solr/Solr_cores/amr_map/conf/*  $BASE/server/solr-7.3.0/server/solr/amr_map/conf/

$BASE/server/solr-7.3.0/bin/solr create -c antibiogram
cp $BASE/git/CDC/Solr/Solr_cores/antibiogram/conf/*  $BASE/server/solr-7.3.0/server/solr/antibiogram/conf/

#$BASE/server/solr-7.3.0/bin/solr create -c browse_all_data
#cp $BASE/git/CDC/Solr/Solr_cores/browse_all_data/conf/*  $BASE/server/solr-7.3.0/server/solr/browse_all_data/conf/

$BASE/server/solr-7.3.0/bin/solr create -c classification_variants
cp $BASE/git/CDC/Solr/Solr_cores/classification_variants/conf/*  $BASE/server/solr-7.3.0/server/solr/classification_variants/conf/

$BASE/server/solr-7.3.0/bin/solr create -c many_antibiogram_singlex
cp $BASE/git/CDC/Solr/Solr_cores/many_antibiogram_singlex/conf/*  $BASE/server/solr-7.3.0/server/solr/many_antibiogram_singlex/conf/

$BASE/server/solr-7.3.0/bin/solr create -c my_core_exp
cp $BASE/git/CDC/Solr/Solr_cores/my_core_exp/conf/*  $BASE/server/solr-7.3.0/server/solr/my_core_exp/conf/

$BASE/server/solr-7.3.0/bin/solr create -c primer
cp $BASE/git/CDC/Solr/Solr_cores/primer/conf/*  $BASE/server/solr-7.3.0/server/solr/primer/conf/

$BASE/server/solr-7.3.0/bin/solr create -c tax_sm_anti_relation
cp $BASE/git/CDC/Solr/Solr_cores/tax_sm_anti_relation/conf/*  $BASE/server/solr-7.3.0/server/solr/tax_sm_anti_relation/conf/


sed -i "s/replaceDBpasswordnew/${CDC_APP_PASSWORD}/" $BASE/server/solr-7.3.0/server/solr/amr_map/conf/solr-data-config.xml
sed -i "s/replaceDBpasswordnew/${CDC_APP_PASSWORD}/" $BASE/server/solr-7.3.0/server/solr/antibiogram/conf/solr-data-config.xml
sed -i "s/replaceDBpasswordnew/${CDC_APP_PASSWORD}/" $BASE/server/solr-7.3.0/server/solr/classification_variants/conf/solr-data-config.xml
sed -i "s/replaceDBpasswordnew/${CDC_APP_PASSWORD}/" $BASE/server/solr-7.3.0/server/solr/many_antibiogram_singlex/conf/solr-data-config.xml
sed -i "s/replaceDBpasswordnew/${CDC_APP_PASSWORD}/" $BASE/server/solr-7.3.0/server/solr/my_core_exp/conf/solr-data-config.xml
sed -i "s/replaceDBpasswordnew/${CDC_APP_PASSWORD}/" $BASE/server/solr-7.3.0/server/solr/primer/conf/solr-data-config.xml
sed -i "s/replaceDBpasswordnew/${CDC_APP_PASSWORD}/" $BASE/server/solr-7.3.0/server/solr/tax_sm_anti_relation/conf/solr-data-config.xml

mkdir -p $BASE/server/solr-7.3.0/CDC/scipts/
cp $BASE/git/CDC/Solr/scripts/refresh_solr.sh $BASE/server/solr-7.3.0/CDC/scipts/
