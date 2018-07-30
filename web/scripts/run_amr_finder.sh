#!/bin/sh
set -o allexport
source /usr/local/projdata/8500/projects/CDC/server/amr_db_python_env/bin/activate
export PATH=/usr/local/projdata/8500/projects/CDC/server/ncbi-blast-2.2.31+/bin/:/usr/local/projdata/8500/projects/CDC/server/Prodigal/:/usr/local/projdata/8500/projects/CDC/server/Muscle/:$PATH
nohup python /usr/local/projdata/8500/projects/CDC/server/AMR-Finder/scripts/amr-finder/amr-finder.py -i /usr/local/projdata/8500/projects/CDC/server/apache/htdocs/tmp/$1/input_user.fasta -$2 -c 40 -b blast$2 -db /usr/local/projdata/8500/projects/CDC/server/apache/cgi-bin/AMR-Finder-master/dbs/amr_dbs/amrdb_$3 --alignments -o /usr/local/projdata/8500/projects/CDC/server/apache/htdocs/tmp/$1/outx
#nohup python /usr/local/projdata/8500/projects/CDC/server/AMR-Finder/scripts/amr-finder/amr-finder.py -i /usr/local/projdata/8500/projects/CDC/server/apache/htdocs/tmp/$1/input_user.fasta -p -c 40 -b blastp -db /usr/local/projdata/8500/projects/CDC/server/apache/cgi-bin/AMR-Finder-master/dbs/amr_dbs/amrdb_peptides_id.fasta --alignments -o /usr/local/projdata/8500/projects/CDC/server/apache/htdocs/tmp/$1/outx
