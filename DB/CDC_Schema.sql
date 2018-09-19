CREATE DATABASE  IF NOT EXISTS `CDC` /*!40100 DEFAULT CHARACTER SET utf32 */;
USE `CDC`;
-- MySQL dump 10.13  Distrib 5.7.17, for macos10.12 (x86_64)
--
-- Host: cdc-1.jcvi.org    Database: CDC
-- ------------------------------------------------------
-- Server version	5.7.21

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `Actor`
--

DROP TABLE IF EXISTS `Actor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Actor` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `User_ID` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Password` varchar(100) NOT NULL DEFAULT '0',
  `First_Name` varchar(100) NOT NULL,
  `Last_Name` varchar(100) NOT NULL,
  `Affiliation` varchar(100) NOT NULL,
  `Country` varchar(100) NOT NULL DEFAULT '0',
  `Title` varchar(100) NOT NULL,
  `Occupation` varchar(100) NOT NULL,
  `Enc_Password` varchar(100) NOT NULL,
  `UserStatus` enum('Y','N') NOT NULL DEFAULT 'N',
  `Created_Date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Modified_Date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Created_By` int(11) DEFAULT '0',
  `Modified_By` int(11) DEFAULT '0',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `User_ID` (`User_ID`),
  UNIQUE KEY `Email` (`Email`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`cdc_admin`@`%`*/ /*!50003 TRIGGER `CDC`.`actor_created_date_trigger`
BEFORE INSERT ON 
CDC.Actor
FOR EACH ROW begin
SET NEW.Created_Date = CURRENT_TIMESTAMP;
end */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`cdc_admin`@`%`*/ /*!50003 TRIGGER `CDC`.`actor_modified_date_trigger`
BEFORE UPDATE ON 
CDC.Actor
FOR EACH ROW begin 
SET NEW.Modified_Date = CURRENT_TIMESTAMP; 
end */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `Antibiogram`
--

DROP TABLE IF EXISTS `Antibiogram`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Antibiogram` (
  `ID` int(11) NOT NULL,
  `Antibiotic` varchar(45) DEFAULT NULL,
  `Drug_Symbol` varchar(45) DEFAULT NULL,
  `Laboratory_Typing_Method` varchar(45) DEFAULT NULL,
  `Laboratory_Typing_Method_Version_or_Reagent` varchar(45) DEFAULT NULL,
  `Laboratory_Typing_Platform` varchar(45) DEFAULT NULL,
  `Measurement` varchar(45) DEFAULT NULL,
  `Measurement_Sign` varchar(45) DEFAULT NULL,
  `Measurement_Units` varchar(45) DEFAULT NULL,
  `Resistance_Phenotype` varchar(45) DEFAULT NULL,
  `Testing_Standard` varchar(45) DEFAULT NULL,
  `Vendor` varchar(45) DEFAULT NULL,
  `Sample_Metadata_ID` int(11) NOT NULL,
  `Created_Date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Modified_Date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Created_By` int(11) NOT NULL,
  `Modified_By` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `fk_Antibiogram_Sample_Metadata1_idx` (`Sample_Metadata_ID`),
  CONSTRAINT `fk_Antibiogram_Sample_Metadata1` FOREIGN KEY (`Sample_Metadata_ID`) REFERENCES `Sample_Metadata` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf32;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`cdc_admin`@`%`*/ /*!50003 TRIGGER `CDC`.`antibiogram_created_date_trigger`
BEFORE INSERT ON 
CDC.Antibiogram
FOR EACH ROW begin
SET NEW.Created_Date = CURRENT_TIMESTAMP;
end */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`cdc_admin`@`%`*/ /*!50003 TRIGGER `CDC`.`antibiogram_modified_date_trigger`
BEFORE UPDATE ON 
CDC.Antibiogram
FOR EACH ROW begin 
SET NEW.Modified_Date = CURRENT_TIMESTAMP; 
end */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `Assemly`
--

DROP TABLE IF EXISTS `Assemly`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Assemly` (
  `ID` int(11) NOT NULL,
  `Is_Reference` varchar(45) DEFAULT NULL,
  `Sample_Metadata_ID` int(11) DEFAULT NULL,
  `Source` varchar(45) DEFAULT NULL,
  `Source_ID` varchar(45) DEFAULT NULL,
  `PubMed_IDs` varchar(1500) DEFAULT NULL COMMENT 'Multiple PubMedIds will be separated by comma',
  `BioProject_ID` varchar(45) DEFAULT NULL,
  `Taxonomy_ID` int(11) NOT NULL,
  `Plasmid_Name` varchar(50) DEFAULT NULL,
  `Created_Date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Modified_Date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Created_By` int(11) NOT NULL,
  `Modified_By` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `fk_Assemly_Metadata1_idx` (`Sample_Metadata_ID`),
  KEY `fk_Assemly_Taxonomy1_idx` (`Taxonomy_ID`),
  CONSTRAINT `fk_Assemly_Metadata1` FOREIGN KEY (`Sample_Metadata_ID`) REFERENCES `Sample_Metadata` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Assemly_Taxonomy1` FOREIGN KEY (`Taxonomy_ID`) REFERENCES `Taxonomy` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf32;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`cdc_admin`@`%`*/ /*!50003 trigger `CDC`.`assembly_created_date_trigger`
  BEFORE INSERT
  on `CDC`.`Assemly`
  for each row begin
SET NEW.Created_Date = CURRENT_TIMESTAMP;
end */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`cdc_admin`@`%`*/ /*!50003 trigger `CDC`.`assembly_modified_date_trigger`
  BEFORE UPDATE
  on `CDC`.`Assemly`
  for each row begin
SET NEW.Modified_Date = CURRENT_TIMESTAMP;
end */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `Classification`
--

DROP TABLE IF EXISTS `Classification`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Classification` (
  `ID` int(11) NOT NULL,
  `Drug` varchar(1000) DEFAULT NULL,
  `Drug_Class` varchar(1000) DEFAULT NULL,
  `Drug_Family` varchar(1000) DEFAULT NULL,
  `Mechanism_of_Action` varchar(1000) DEFAULT NULL,
  `Identity_ID` int(11) NOT NULL,
  `Is_Active` int(11) NOT NULL,
  `Created_Date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Modified_Date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Created_By` int(11) NOT NULL,
  `Modified_By` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `fk_Classification_Identity1_idx` (`Identity_ID`),
  CONSTRAINT `fk_Classification_Identity1` FOREIGN KEY (`Identity_ID`) REFERENCES `Identity` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf32;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`cdc_admin`@`%`*/ /*!50003 TRIGGER classification_created_date_trigger
BEFORE INSERT ON 
CDC.Classification
FOR EACH ROW begin
SET NEW.Created_Date = CURRENT_TIMESTAMP;
end */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`cdc_admin`@`%`*/ /*!50003 TRIGGER `CDC`.`classification_modified_date_trigger`
BEFORE UPDATE ON 
CDC.Classification
FOR EACH ROW 
begin 
SET NEW.Modified_Date = CURRENT_TIMESTAMP; 
end */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `Drug_Abbreviations`
--

DROP TABLE IF EXISTS `Drug_Abbreviations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Drug_Abbreviations` (
  `Drug_Name` varchar(75) NOT NULL,
  `Abbreviation` varchar(75) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Identity`
--

DROP TABLE IF EXISTS `Identity`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Identity` (
  `ID` int(11) NOT NULL,
  `Gene_Symbol` varchar(250) DEFAULT NULL,
  `Gene_Alternative_Names` varchar(500) DEFAULT NULL,
  `Gene_Family` varchar(500) DEFAULT NULL,
  `Gene_Class` varchar(500) DEFAULT NULL,
  `Allele` varchar(45) DEFAULT NULL,
  `EC_Number` varchar(45) DEFAULT NULL,
  `Parent_Allele_Family` varchar(45) DEFAULT NULL,
  `Parent_Allele` varchar(45) DEFAULT NULL,
  `Source` varchar(45) DEFAULT NULL COMMENT 'Source of AMR annotation\n',
  `Source_ID` varchar(45) DEFAULT NULL COMMENT 'Source ID or accession id',
  `Protein_ID` varchar(45) DEFAULT NULL,
  `Protein_Name` varchar(250) DEFAULT NULL,
  `Protein_Alternative_Names` varchar(1000) DEFAULT NULL,
  `Pubmed_IDs` varchar(2250) DEFAULT NULL,
  `HMM` varchar(45) DEFAULT NULL,
  `Is_Active` int(11) DEFAULT NULL COMMENT 'Is Identify active. values will be 1 or 0. 1 is active and 0 is inactive.',
  `Status` varchar(16) DEFAULT NULL COMMENT 'Either Manual or Default',
  `Created_Date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Modified_Date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Created_By` int(11) NOT NULL,
  `Modified_By` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf32;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`cdc_admin`@`%`*/ /*!50003 TRIGGER `CDC`.`identity_created_date_trigger`
BEFORE INSERT ON 
CDC.Identity
FOR EACH ROW begin
SET NEW.Created_Date = CURRENT_TIMESTAMP;
end */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`cdc_admin`@`%`*/ /*!50003 TRIGGER `CDC`.`identity_modified_date_trigger`
BEFORE UPDATE ON 
CDC.Identity
FOR EACH ROW begin 
SET NEW.Modified_Date = CURRENT_TIMESTAMP; 
end */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `Identity_Assembly`
--

DROP TABLE IF EXISTS `Identity_Assembly`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Identity_Assembly` (
  `ID` int(11) NOT NULL,
  `Mol_Type` varchar(10) DEFAULT NULL,
  `Identity_Sequence_ID` int(11) NOT NULL,
  `Assemly_ID` int(11) NOT NULL,
  `Created_Date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Modified_Date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Created_By` int(11) NOT NULL,
  `Modified_By` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `fk_Identify_Assembly_Assemly1_idx` (`Assemly_ID`),
  KEY `fk_Identify_Assembly_Identity_Sequence1_idx` (`Identity_Sequence_ID`),
  CONSTRAINT `fk_Identify_Assembly_Assemly1` FOREIGN KEY (`Assemly_ID`) REFERENCES `Assemly` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Identify_Assembly_Identity_Sequence1` FOREIGN KEY (`Identity_Sequence_ID`) REFERENCES `Identity_Sequence` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf32;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`cdc_admin`@`%`*/ /*!50003 TRIGGER identity_assembly_created_date_trigger
BEFORE INSERT ON 
CDC.Identity_Assembly
FOR EACH ROW begin
SET NEW.Created_Date = CURRENT_TIMESTAMP;
end */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`cdc_admin`@`%`*/ /*!50003 TRIGGER `CDC`.`identity_assembly_modified_date_trigger`
BEFORE UPDATE ON 
CDC.Identity_Assembly
FOR EACH ROW 
begin 
SET NEW.Modified_Date = CURRENT_TIMESTAMP; 
end */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `Identity_Sequence`
--

DROP TABLE IF EXISTS `Identity_Sequence`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Identity_Sequence` (
  `ID` int(11) NOT NULL,
  `End3` varchar(45) DEFAULT NULL,
  `End5` varchar(45) DEFAULT NULL,
  `NA_Sequence` varchar(5000) NOT NULL,
  `AA_Sequence` varchar(5000) NOT NULL,
  `Feat_Type` varchar(5) NOT NULL DEFAULT 'CDC',
  `Identity_ID` int(11) NOT NULL,
  `Created_Date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Modified_Date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Created_By` int(11) NOT NULL,
  `Modified_By` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `fk_Identity_Sequence_Identity1_idx` (`Identity_ID`),
  CONSTRAINT `fk_Identity_Sequence_Identity1` FOREIGN KEY (`Identity_ID`) REFERENCES `Identity` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf32;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`cdc_admin`@`%`*/ /*!50003 TRIGGER `CDC`.`identity_Sequence_created_date_trigger`
BEFORE INSERT ON 
CDC.Identity_Sequence
FOR EACH ROW begin
SET NEW.Created_Date = CURRENT_TIMESTAMP;
end */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`cdc_admin`@`%`*/ /*!50003 TRIGGER `CDC`.`identity_Sequence_modified_date_trigger`
BEFORE UPDATE ON 
CDC.Identity_Sequence
FOR EACH ROW begin 
SET NEW.Modified_Date = CURRENT_TIMESTAMP; 
end */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `Primer`
--

DROP TABLE IF EXISTS `Primer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Primer` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `Primer` varchar(10) NOT NULL,
  `Target` varchar(100) NOT NULL,
  `FWD` varchar(50) NOT NULL,
  `REV` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf32;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Sample_Metadata`
--

DROP TABLE IF EXISTS `Sample_Metadata`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Sample_Metadata` (
  `ID` int(11) NOT NULL,
  `Source` varchar(45) DEFAULT NULL,
  `Source_ID` varchar(45) DEFAULT NULL,
  `Isolation_site` varchar(250) DEFAULT NULL,
  `Serotyping_Method` varchar(100) DEFAULT NULL,
  `Source_Common_Name` varchar(255) DEFAULT NULL,
  `Specimen_Collection_Date` varchar(45) DEFAULT NULL,
  `Specimen_Collection_Location_Country` varchar(45) DEFAULT NULL,
  `Specimen_Collection_Location` varchar(45) DEFAULT NULL,
  `Specimen_Collection_Location_Latitude` varchar(45) DEFAULT NULL,
  `Specimen_Collection_Location_Longitude` varchar(45) DEFAULT NULL,
  `Specimen_Source_Age` varchar(45) DEFAULT NULL,
  `Specimen_Source_Developmental_Stage` varchar(45) DEFAULT NULL,
  `Specimen_Source_Disease` varchar(100) DEFAULT NULL,
  `Specimen_Source_Gender` varchar(45) DEFAULT NULL,
  `Health_Status` varchar(45) DEFAULT NULL,
  `Treatment` varchar(45) DEFAULT NULL,
  `Specimen_Type` varchar(45) DEFAULT NULL,
  `Symptom` varchar(100) DEFAULT NULL,
  `Host` varchar(45) DEFAULT NULL,
  `Created_Date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Modified_Date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Created_By` int(11) NOT NULL,
  `Modified_By` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf32;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`cdc_admin`@`%`*/ /*!50003 TRIGGER sample_metadata_created_date_trigger
BEFORE INSERT ON 
CDC.Sample_Metadata
FOR EACH ROW begin
SET NEW.Created_Date = CURRENT_TIMESTAMP;
end */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`cdc_admin`@`%`*/ /*!50003 TRIGGER `CDC`.`sample_metadata_modified_date_trigger`
BEFORE UPDATE ON 
CDC.Sample_Metadata
FOR EACH ROW 
begin 
SET NEW.Modified_Date = CURRENT_TIMESTAMP; 
end */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `Taxonomy`
--

DROP TABLE IF EXISTS `Taxonomy`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Taxonomy` (
  `ID` int(11) NOT NULL,
  `Taxon_ID` varchar(100) DEFAULT NULL,
  `Taxon_Kingdom` varchar(100) DEFAULT NULL,
  `Taxon_Phylum` varchar(100) DEFAULT NULL,
  `Taxon_Bacterial_BioVar` varchar(100) DEFAULT NULL,
  `Taxon_Class` varchar(100) DEFAULT NULL,
  `Taxon_Order` varchar(100) DEFAULT NULL,
  `Taxon_Family` varchar(100) DEFAULT NULL,
  `Taxon_Genus` varchar(100) DEFAULT NULL,
  `Taxon_Species` varchar(100) DEFAULT NULL,
  `Taxon_Sub_Species` varchar(100) DEFAULT NULL,
  `Taxon_Pathovar` varchar(100) DEFAULT NULL,
  `Taxon_Serotype` varchar(100) DEFAULT NULL,
  `Taxon_Strain` varchar(100) DEFAULT NULL,
  `Taxon_Sub_Strain` varchar(100) DEFAULT NULL,
  `Created_Date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Modified_Date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Created_By` int(11) NOT NULL,
  `Modified_By` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf32;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`cdc_admin`@`%`*/ /*!50003 TRIGGER `CDC`.`taxonomy_created_date_trigger`
BEFORE INSERT ON 
CDC.Taxonomy
FOR EACH ROW begin
SET NEW.Created_Date = CURRENT_TIMESTAMP;
end */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`cdc_admin`@`%`*/ /*!50003 TRIGGER `CDC`.`taxonomy_Sequence_created_date_trigger`
BEFORE INSERT ON 
CDC.Taxonomy
FOR EACH ROW begin
SET NEW.Created_Date = CURRENT_TIMESTAMP;
end */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`cdc_admin`@`%`*/ /*!50003 TRIGGER `CDC`.`taxonomy_modified_date_trigger`
BEFORE UPDATE ON 
CDC.Taxonomy
FOR EACH ROW 
begin 
SET NEW.Modified_Date = CURRENT_TIMESTAMP; 
end */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`cdc_admin`@`%`*/ /*!50003 TRIGGER `CDC`.`taxonomy_Sequence_modified_date_trigger`
BEFORE UPDATE ON 
CDC.Taxonomy
FOR EACH ROW begin 
SET NEW.Modified_Date = CURRENT_TIMESTAMP; 
end */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `Threat_Level`
--

DROP TABLE IF EXISTS `Threat_Level`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Threat_Level` (
  `ID` int(11) NOT NULL,
  `Level` varchar(45) DEFAULT NULL,
  `Taxonomy_ID` int(11) NOT NULL,
  `Identity_ID` int(11) DEFAULT NULL,
  `Created_Date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Modified_Date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Created_By` int(11) NOT NULL,
  `Modified_By` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `fk_Threat_Level_Taxonomy1_idx` (`Taxonomy_ID`),
  KEY `fk_Threat_Level_Identity1_idx` (`Identity_ID`),
  CONSTRAINT `fk_Threat_Level_Annotation1` FOREIGN KEY (`Identity_ID`) REFERENCES `Identity` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Threat_Level_Taxonomy1` FOREIGN KEY (`Taxonomy_ID`) REFERENCES `Taxonomy` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf32;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`cdc_admin`@`%`*/ /*!50003 TRIGGER `CDC`.`threat_level_level_created_date_trigger`
BEFORE INSERT ON 
CDC.Threat_Level
FOR EACH ROW begin
SET NEW.Created_Date = CURRENT_TIMESTAMP;
end */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`cdc_admin`@`%`*/ /*!50003 TRIGGER `CDC`.`threat_level_level_modified_date_trigger`
BEFORE UPDATE ON 
CDC.Threat_Level
FOR EACH ROW 
begin 
SET NEW.Modified_Date = CURRENT_TIMESTAMP; 
end */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `Variants`
--

DROP TABLE IF EXISTS `Variants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Variants` (
  `ID` int(11) NOT NULL,
  `SNP` varchar(1000) DEFAULT NULL,
  `PubMed_IDs` varchar(1500) DEFAULT NULL,
  `Identity_Sequence_ID` int(11) NOT NULL,
  `Classification_ID` int(11) NOT NULL,
  `Is_Active` varchar(45) NOT NULL,
  `Created_Date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Modified_Date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Created_By` int(11) NOT NULL,
  `Modified_By` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `fk_Variants_Sequence1_idx` (`Identity_Sequence_ID`),
  KEY `fk_Variants_Classification1_idx` (`Classification_ID`),
  CONSTRAINT `fk_Variants_Classification1` FOREIGN KEY (`Classification_ID`) REFERENCES `Classification` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Variants_Sequence1` FOREIGN KEY (`Identity_Sequence_ID`) REFERENCES `Identity_Sequence` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf32;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`cdc_admin`@`%`*/ /*!50003 TRIGGER `CDC`.`variants_level_created_date_trigger`
BEFORE INSERT ON 
CDC.Variants
FOR EACH ROW begin
SET NEW.Created_Date = CURRENT_TIMESTAMP;
end */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`cdc_admin`@`%`*/ /*!50003 TRIGGER `CDC`.`variants_level_modified_date_trigger`
BEFORE UPDATE ON 
CDC.Variants
FOR EACH ROW 
begin 
SET NEW.Modified_Date = CURRENT_TIMESTAMP; 
end */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `sequence_data`
--

DROP TABLE IF EXISTS `sequence_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sequence_data` (
  `sequence_name` varchar(100) NOT NULL,
  `sequence_increment` int(11) unsigned NOT NULL DEFAULT '1',
  `sequence_min_value` int(11) unsigned NOT NULL DEFAULT '1',
  `sequence_max_value` bigint(20) unsigned NOT NULL DEFAULT '18446744073709551615',
  `sequence_cur_value` bigint(20) unsigned DEFAULT '1',
  `sequence_cycle` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`sequence_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf32;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping events for database 'CDC'
--

--
-- Dumping routines for database 'CDC'
--
/*!50003 DROP FUNCTION IF EXISTS `nextval` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`cdc_admin`@`%` FUNCTION `nextval`(seq_name VARCHAR(100)) RETURNS bigint(20)
BEGIN
    DECLARE cur_val bigint(20);
 
    SELECT
        sequence_cur_value INTO cur_val
    FROM
        CDC.sequence_data
    WHERE
        sequence_name = seq_name
    ;
 
    IF cur_val IS NOT NULL THEN
        UPDATE
            CDC.sequence_data
        SET
            sequence_cur_value = IF (
                (sequence_cur_value + sequence_increment) > sequence_max_value,
                IF (
                    sequence_cycle = TRUE,
                    sequence_min_value,
                    NULL
                ),
                sequence_cur_value + sequence_increment
            )
        WHERE
            sequence_name = seq_name
        ;
    END IF;
 
    RETURN cur_val;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-09-13  9:20:30
