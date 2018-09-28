export BASE=/usr/local/projdata/8500/projects/CDC/server/mysql-5.7.21
$BASE/bin/mysqld --user=cdc_user --secure-file-priv=/usr/local/projdata/8500/projects/CDC/MYSQLLoading/ --datadir=$BASE/data --basedir=$BASE --log-error=$BASE/log/mysql.err --pid-file=$BASE/mysql.pid --socket=$BASE/socket --port=3306 --tmpdir=/usr/local/projdata/8500/projects/CDC/server/mysql-5.7.21/tmp & 
