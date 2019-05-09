<?php 

// connect to sql database 
include 'includes/config.inc.php';
// refresh tables

$sql = "TRUNCATE table tmp_identity;";
$query=mysql_query($sql);
$ids=mysql_fetch_array($query);
var_dump($ids);

 $sql = "INSERT INTO tmp_identity 
        Select t.* 
        From (SELECT idt.ID,idt.Gene_Symbol,idt.Gene_Alternative_Names,idt.Protein_Alternative_Names,idt.Gene_Family,idt.Gene_Class,idt.Allele,idt.EC_Number,
            idt.Parent_Allele_Family,idt.Parent_Allele,idt.Source,idt.Source_ID,idt.HMM,idt.Protein_ID,idt.Protein_Name,idt.Pubmed_IDs,idt.Is_Active,idt.Status ,ids.ID AS IDSID ,ids.End3,ids.End5,ids.Feat_Type,ida.ID AS IDAID,ida.Mol_Type,asb.ID AS ASBID,asb.Is_Reference,asb.Source AS Assemly_Source,
            asb.Source_ID AS Assemly_Source_ID,asb.PubMed_IDs AS Assemly_PubMed_IDs,asb.BioProject_ID,asb.Plasmid_Name ,tax.ID AS TAXID,tax.Taxon_ID,tax.Taxon_Kingdom,
            tax.Taxon_Phylum,tax.Taxon_Sub_Strain,tax.Taxon_Bacterial_BioVar,tax.Taxon_Class,tax.Taxon_Order,tax.Taxon_Family,tax.Taxon_Genus,tax.Taxon_Species,
            tax.Taxon_Sub_Species,tax.Taxon_Pathovar,tax.Taxon_Serotype,tax.Taxon_Strain,sm.ID AS SMID,sm.Isolation_site,sm.Serotyping_Method,sm.Source_Common_Name,
            sm.Specimen_Collection_Date,sm.Specimen_Collection_Location_Country,sm.Specimen_Collection_Year,sm.Specimen_Collection_Location,
            sm.Specimen_Collection_Location_Latitude,sm.Specimen_Collection_Location_Longitude,sm.Specimen_Source_Age,sm.Specimen_Source_Developmental_Stage,
            sm.Specimen_Source_Disease,sm.Specimen_Source_Gender,sm.Health_Status,sm.Treatment,sm.Specimen_Type,sm.Symptom
        FROM Identity idt,Identity_Sequence ids,Identity_Assembly ida,Assemly asb,Sample_Metadata sm,Taxonomy tax
        WHERE idt.ID=ids.Identity_ID AND ids.ID=ida.Identity_Sequence_ID AND asb.ID=ida.Assemly_ID AND sm.ID=asb.Sample_Metadata_ID AND tax.ID=asb.Taxonomy_ID AND Is_Reference=1) as t;";

 $query=mysql_query($sql);
 $ids=mysql_fetch_array($query);

 var_dump($ids);



$sql = "TRUNCATE table tmp_identity_seq;";
$query=mysql_query($sql);
$ids=mysql_fetch_array($query);
var_dump($ids);

$sql = "INSERT INTO tmp_identity_seq
Select t.* 
    FROM( SELECT
            idt.ID,
            ids.NA_Sequence,
            ids.AA_Sequence
        FROM
            Identity idt,
            Identity_Sequence ids
        WHERE
            idt.ID=ids.Identity_ID) as t;";
 
 $query=mysql_query($sql);
 $ids=mysql_fetch_array($query);
 var_dump($ids);



$sql = "TRUNCATE table tmp_classification_SNP;";
$query=mysql_query($sql);
$ids=mysql_fetch_array($query);
var_dump($ids);

$sql = "SET session group_concat_max_len=15000;";
$query=mysql_query($sql);
$ids=mysql_fetch_array($query);
var_dump($ids);

$sql = "INSERT INTO  tmp_classification_SNP 
        select t.* FROM (
        select i.ID    ,IFNULL(GROUP_CONCAT(DISTINCT cl.Drug SEPARATOR ' '),'') as Drug
                ,IFNULL(GROUP_CONCAT(DISTINCT cl.Drug_Class SEPARATOR ' '),'') as Drug_Class
                ,IFNULL(GROUP_CONCAT(DISTINCT cl.Drug_Family SEPARATOR ' '),'') as Drug_Family
                ,IFNULL(GROUP_CONCAT(DISTINCT cl.Mechanism_of_Action SEPARATOR ' '),'') as Mechanism_of_Action
                ,IFNULL(GROUP_CONCAT(DISTINCT va.SNP SEPARATOR ' '),'') as SNP
                ,IFNULL(GROUP_CONCAT(DISTINCT va.Pubmed_IDs SEPARATOR ' '),'') as SNP_Pubmed_IDs
        from CDC.tmp_identity i
        LEFT JOIN  Classification cl ON cl.Identity_ID=i.ID
        LEFT JOIN  Variants va ON va.Classification_ID = cl.ID OR i.IDSID= va.Identity_Sequence_ID
        group by i.ID) as t ; ";
 
 $query=mysql_query($sql);
 $ids=mysql_fetch_array($query);
 var_dump($ids);




 $sql = "TRUNCATE tmp_Antibiogram; ";
$query=mysql_query($sql);
$ids=mysql_fetch_array($query);
var_dump($ids);

 $sql = "SET session group_concat_max_len=50000; ";
$query=mysql_query($sql);
$ids=mysql_fetch_array($query);
var_dump($ids);

  $sql = "INSERT into tmp_Antibiogram
            select  sm.ID as Sample_Metadata_ID
            , IFNULL(GROUP_CONCAT(anti.Antibiotic SEPARATOR ' '),'') as Antibiotic
                            ,IFNULL(GROUP_CONCAT(anti.Drug_Symbol SEPARATOR ' '),'') as Drug_Symbol
                            ,IFNULL(GROUP_CONCAT(anti.Laboratory_Typing_Method SEPARATOR ' '),'') as Laboratory_Typing_Method
                            , IFNULL(GROUP_CONCAT(anti.Laboratory_Typing_Method_Version_or_Reagent SEPARATOR ' '),'') as Laboratory_Typing_Method_Version_or_Reagent
                            ,IFNULL(GROUP_CONCAT(anti.Laboratory_Typing_Platform SEPARATOR ' '),'') as Laboratory_Typing_Platform
                            ,IFNULL(GROUP_CONCAT(anti.Measurement SEPARATOR ' '),'') as Measurement
                            ,IFNULL(GROUP_CONCAT(Measurement_Sign SEPARATOR ' '),'') as Measurement_Sign
                            ,IFNULL(GROUP_CONCAT(anti.Measurement_Units SEPARATOR ' '),'') as Measurement_Units
                            ,IFNULL(GROUP_CONCAT(anti.Resistance_Phenotype SEPARATOR ' '),'') as Resistance_Phenotype
                            ,IFNULL(GROUP_CONCAT(anti.Testing_Standard  SEPARATOR ' '),'') as Testing_Standard
                            ,IFNULL(GROUP_CONCAT(anti.Vendor SEPARATOR ' '),'') as Vendor
            from CDC.Sample_Metadata sm
            LEFT JOIN CDC.Antibiogram anti on sm.ID=anti.sample_metadata_id
            group by sm.ID; ";
 
 $query=mysql_query($sql);
 $ids=mysql_fetch_array($query);
 var_dump($ids);

  $sql = "TRUNCATE tmp_Threat_Level; 
        INSERT INTO tmp_Threat_Level
        select i.ID,a.Level,a.Taxonomy_ID
        from CDC.Identity i left join Threat_Level a on a.Identity_ID=i.ID";
 
 $query=mysql_query($sql);
 $ids=mysql_fetch_array($query);
 var_dump($ids);

?>

