mysql -h cdc-1 -u cdc_admin -pxjCS6ufdVaWHMHYJ --local-infile  CDC --execute=" LOAD DATA LOCAL INFILE 'Identity_Sequence.txt' INTO TABLE  Identity_Sequence CHARACTER SET UTF8 FIELDS TERMINATED BY '\t' OPTIONALLY ENCLOSED BY '\"' LINES TERMINATED BY '\n' (ID, End3, End5, NA_Sequence, AA_Sequence, Feat_Type, Identity_ID) ; SHOW WARNINGS;"
