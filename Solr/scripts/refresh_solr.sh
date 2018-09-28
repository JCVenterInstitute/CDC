#!/bin/bash
#Scripts to refresh SOLR cores
export JAVA_HOME=/usr/local/projdata/8500/projects/CDC/server/jdk1.8.0_171
curl http://cdc-1.jcvi.org:8983/solr/my_core_exp/update?commit=true -H "Content-Type: text/xml" --data-binary '<delete><query>*:*</query></delete>' &>>/usr/local/projdata/8500/projects/CDC/scripts/log/SOLR_Refresh.log
sleep 1 
curl http://cdc-1.jcvi.org:8983/solr/my_core_exp/dataimport?command=full-import &>>/usr/local/projdata/8500/projects/CDC/scripts/log/SOLR_Refresh.log
sleep 2
curl http://cdc-1.jcvi.org:8983/solr/classification_variants/update?commit=true -H "Content-Type: text/xml" --data-binary '<delete><query>*:*</query></delete>' &>>/usr/local/projdata/8500/projects/CDC/scripts/log/SOLR_Refresh.log
sleep 1
curl http://cdc-1.jcvi.org:8983 /solr/antibiogram/update?commit=true -H "Content-Type: text/xml" --data-binary '<delete><query>*:*</query></delete>' &>>/usr/local/projdata/8500/projects/CDC/scripts/log/SOLR_Refresh.log
sleep 1 
curl http://cdc-1.jcvi.org:8983/solr/amr_map/update? commit=true -H "Content-Type: text/xml" --data-binary '<delete><query>*:*</query></delete>' &>>/usr/local/projdata/8500/projects/CDC/scripts/log/SOLR_Refresh.log
sleep 2
curl http://cdc-1.jcvi.org:8983/solr/classification_variants/dataimport?command=full-import &>>/usr/local/projdata/8500/projects/CDC/scripts/log/SOLR_Refresh.log
sleep 2
curl http://cdc-1.jcvi.org:8983/solr/antibiogram/dataimport?command=full-import  &>>/usr/local/projdata/8500/projects/CDC/scripts/log/SOLR_Refresh.log
sleep 2
curl http://cdc-1.jcvi.org:8983/solr/amr_map/dataimport?command=full-import &>>/usr/local/projdata/8500/projects/CDC/scripts/log/SOLR_Refresh.log
sleep 2
curl http://cdc-1.jcvi.org:8983/solr/primer/update?commit=true -H "Content-Type: text/xml" --data-binary '<delete><query>*:*</query></delete>' &>>/usr/local/projdata/8500/projects/CDC/scripts/log/SOLR_Refresh.log
sleep 2
curl http://cdc-1.jcvi.org:8983/solr/primer/dataimport?command=full-import &>>/usr/local/projdata/8500/projects/CDC/scripts/log/SOLR_Refresh.log
sleep 2
curl http://cdc-1.jcvi.org:8983/solr/antibiogram/dataimport?command=full-import   &>>/usr/local/projdata/8500/projects/CDC/scripts/log/SOLR_Refresh.log
