#Create Users
#Make sure you change **** with appropriate password
CREATE USER 'cdc_app'@'%' IDENTIFIED BY '****'

CREATE USER 'cdc_admin'@'%' IDENTIFIED BY '****'

#Grant priviledges
GRANT ALL PRIVILEGES ON *.* TO 'cdc_admin'@'%' WITH GRANT OPTION;
GRANT ALL PRIVILEGES ON *.* TO 'cdc_app'@'%' WITH GRANT OPTION;
GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, ALTER, EXECUTE, CREATE VIEW, SHOW VIEW, CREATE ROUTINE ON `CDC`.* TO 'cdc_app'@'%';
GRANT ALL PRIVILEGES ON *.* TO 'cdc_admin'@'localhost' WITH GRANT OPTION;


