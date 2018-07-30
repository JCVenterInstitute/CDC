SELECT firstFourT.ID, firstFourT.Gene_Symbol,firstFourT.Gene_Family,firstFourT.Gene_Class,firstFourT.Allele,firstFourT.HMM,firstFourT.EC_Number,firstFourT.Parent_Allele_Family,firstFourT.Parent_Allele,
firstFourT.Source,firstFourT.Source_ID,firstFourT.Feat_Type,
firstFourT.Protein_ID,firstFourT.Protein_Name,firstFourT.Pubmed_IDs,firstFourT.Status,firstFourT.Created_Date,firstFourT.Modified_Date,firstFourT.Created_By,firstFourT.Modified_By 
,firstFourT.Mol_Type,firstFourT.Assemly_ID,firstFourT.End5,firstFourT.End3,firstFourT.NA_Sequence,firstFourT.AA_Sequence, 
GROUP_CONCAT(firstFourT.Classification_ID SEPARATOR ',') as Classification_ID,
GROUP_CONCAT(va.SNP SEPARATOR ',') as SNP,
tax.Taxon_ID,tax.Taxon_Kingdom,tax.Taxon_Phylum,tax.Taxon_Sub_Strain,
tax.Taxon_Bacterial_BioVar,tax.Taxon_Class,tax.Taxon_Order,tax.Taxon_Family,tax.Taxon_Genus,tax.Taxon_Species,tax.Taxon_Sub_Species,tax.Taxon_Pathovar,tax.Taxon_Serotype,tax.Taxon_Strain,
GROUP_CONCAT(va.PubMed_IDs SEPARATOR ',') as Variants_PubMed_IDs, tl.Level,
GROUP_CONCAT(firstFourT.Drug SEPARATOR ',') as Drug,
GROUP_CONCAT(firstFourT.Mechanism_of_Action SEPARATOR ',')as Mechanism_of_Action,
GROUP_CONCAT(sm.Isolation_site SEPARATOR ',')as Isolation_site ,GROUP_CONCAT(sm.Serotyping_Method SEPARATOR ',')as Serotyping_Method ,GROUP_CONCAT(sm.Source_Common_Name SEPARATOR ',')as Source_Common_Name ,
GROUP_CONCAT(sm.Specimen_Collection_Date SEPARATOR ',') as Specimen_Collection_Date ,GROUP_CONCAT(sm.Source SEPARATOR ',')as Meta_Source ,GROUP_CONCAT(sm.Source_ID SEPARATOR ',')as Meta_Source_ID 
,GROUP_CONCAT(sm.Specimen_Collection_Location_Country SEPARATOR ',') as Specimen_Collection_Location_Country ,GROUP_CONCAT(sm.Specimen_Collection_Location SEPARATOR ',')as Specimen_Collection_Location,
GROUP_CONCAT(sm.Specimen_Collection_Location_Latitude SEPARATOR ',')as Specimen_Collection_Location_Latitude,
GROUP_CONCAT(sm.Specimen_Collection_Location_Longitude SEPARATOR ',') as Specimen_Collection_Location_Longitude,
GROUP_CONCAT(sm.Specimen_Source_Age SEPARATOR ',')as Specimen_Source_Age,GROUP_CONCAT(sm.Specimen_Source_Developmental_Stage SEPARATOR ',')as Specimen_Source_Developmental_Stage,
GROUP_CONCAT(sm.Specimen_Source_Disease SEPARATOR ',')as Specimen_Source_Disease,GROUP_CONCAT(sm.Specimen_Source_Gender SEPARATOR ',')as Specimen_Source_Gender,
GROUP_CONCAT(sm.Health_Status SEPARATOR ',')as Health_Status,GROUP_CONCAT(sm.Treatment SEPARATOR ',')as Treatment,GROUP_CONCAT(sm.Specimen_Type SEPARATOR ',')as Specimen_Type,
GROUP_CONCAT(sm.Symptom SEPARATOR ',')as Symptom,GROUP_CONCAT(sm.Host SEPARATOR ',')as Host, 
GROUP_CONCAT(firstFourT.Drug_Family SEPARATOR ',')as Drug_Family,GROUP_CONCAT(firstFourT.Drug_class SEPARATOR ',')as Drug_class,GROUP_CONCAT(firstFourT.Drug_Sub_Class SEPARATOR ',')as Drug_Sub_Class,
GROUP_CONCAT(firstFourT.Is_Active SEPARATOR ',') as Is_Active, GROUP_CONCAT(anti.Antibiotic SEPARATOR ',')as Antibiotic, GROUP_CONCAT(anti.Drug_Symbol SEPARATOR ',')as Drug_Symbol, 
GROUP_CONCAT(anti.Laboratory_Typing_Method SEPARATOR ',')as Laboratory_Typing_Method, GROUP_CONCAT(anti.Laboratory_Typing_Method_Version_or_Reagent SEPARATOR ',')as Laboratory_Typing_Method_Version_or_Reagent, 
GROUP_CONCAT(anti.Laboratory_Typing_Platform SEPARATOR ',')as Laboratory_Typing_Platform, GROUP_CONCAT(anti.Measurement SEPARATOR ',')as Measurement, 
GROUP_CONCAT(anti.Measurement_Sign SEPARATOR ',')as Measurement_Sign, GROUP_CONCAT(anti.Measurement_Units SEPARATOR ',')as Measurement_Units, 
GROUP_CONCAT(anti.Resistance_Phenotype SEPARATOR ',')as Resistance_Phenotype,GROUP_CONCAT(anti.Testing_Standard SEPARATOR ',')as Testing_Standard, 
GROUP_CONCAT(anti.Vendor SEPARATOR ',')as Vendor, a.PubMed_IDs as Variants_PubMed_IDs,a.Is_Reference, a.Source as Assemly_Source, a.Source_ID as Assemly_Source_ID, a.Plasmid_Name
FROM(SELECT i.ID,cl.ID as CID,ise.ID as ISEID,i.Gene_Symbol,i.Gene_Family,i.Gene_Class,i.Allele,i.EC_Number,cl.Drug_Family,i.Parent_Allele_Family,i.Parent_Allele,i.Source,i.Source_ID,i.HMM,cl.Drug_Class,cl.Drug_Sub_Class,
i.Protein_ID,i.Protein_Name,i.Pubmed_IDs,i.Is_Active,i.Status,i.Created_Date,i.Modified_Date,i.Created_By,i.Modified_By 
,iss.Mol_Type,iss.Assemly_ID ,ise.End5,ise.End3,ise.NA_Sequence,ise.AA_Sequence,cl.ID as Classification_ID, cl.Drug, cl.Mechanism_of_Action,ise.Feat_Type
from CDC.Identity_Sequence ise,CDC.Identity i,CDC.Identity_Assembly iss,CDC.Classification cl
where i.ID=ise.Identity_ID and i.ID=cl.Identity_ID and iss.Identity_Sequence_ID=ise.ID ) as firstFourT
LEFT JOIN Variants va on va.Identity_Sequence_ID = ISEID or va.Classification_ID =CID
LEFT JOIN Assemly a on a.ID = firstFourT.Assemly_ID
LEFT JOIN Sample_Metadata sm on sm.ID = a.Sample_Metadata_ID
Left JOIN Threat_Level tl on firstFourT.ID=tl.Identity_ID
LEFT JOIN Taxonomy tax on tax.ID = a.Taxonomy_ID
LEFT JOIN Antibiogram anti on anti.Sample_Metadata_ID=sm.ID
GROUP BY firstFourT.ID, firstFourT.Gene_Symbol,firstFourT.Allele,firstFourT.EC_Number,firstFourT.Parent_Allele_Family,firstFourT.Parent_Allele,firstFourT.Source,firstFourT.Source_ID,tl.Level,
firstFourT.Protein_ID,firstFourT.Protein_Name,firstFourT.Pubmed_IDs,firstFourT.Status,firstFourT.Created_Date,firstFourT.Modified_Date,firstFourT.Created_By,firstFourT.Modified_By,firstFourT.Feat_Type 
,firstFourT.Mol_Type,firstFourT.Assemly_ID,firstFourT.End5,firstFourT.End3,firstFourT.NA_Sequence,firstFourT.AA_Sequence,tax.Taxon_ID,tax.Taxon_Kingdom,tax.Taxon_Phylum,tax.Taxon_Sub_Strain,
tax.Taxon_Bacterial_BioVar,tax.Taxon_Class,tax.Taxon_Order,tax.Taxon_Family,tax.Taxon_Genus,tax.Taxon_Species,tax.Taxon_Sub_Species,tax.Taxon_Pathovar,tax.Taxon_Serotype,tax.Taxon_Strain
,a.PubMed_IDs,a.Is_Reference,a.Source,a.Source_ID,a.Plasmid_Name



ID,Gene_Symbol,Gene_Family,Gene_Class,Allele,EC_Number,Parent_Allele_Family,Parent_Allele,Source,Source_ID,Protein_ID,Protein_Name,Pubmed_IDs,HMM,Is_Active,Status,Created_Date,Modified_Date,Created_By,Modified_By,
Level, Taxon_ID, Taxon_Kingdom, Taxon_Pathovar, Taxon_Bacterial_BioVar, Taxon_Class, Taxon_Order, Taxon_Family, Taxon_Genus, Taxon_Species, Taxon_Sub_Species, Taxon_Pathovar, Taxon_Serotype, Taxon_Strain,Taxon_Sub_Strain,
End3, End5, NA_Sequence, AA_Sequence, Feat_Type, Mol_Type,Assemly_PubMed_IDs,Assemly_Source, Assemly_Source_ID,Is_Reference,Plasmid_Name, Meta_Source_ID,Meta_Source, Isolation_site,Serotyping_Method,
Source_Common_Name,Specimen_Collection_Date,Specimen_Collection_Location_Country,Specimen_Collection_Location,Specimen_Collection_Location_Longitude,Specimen_Source_Age,Specimen_Source_Developmental_Stage,Specimen_Source_Disease,
Specimen_Source_Gender,Health_Status,Treatment,Specimen_Type,Symptom,Host



SELECT anti.* from (select sm.* from CDC.Identity_Sequence ise, CDC.Identity ide ,CDC.Identity i,CDC.Identity_Assembly iss, CDC.Assemly a 
LEFT JOIN CDC.Sample_Metadata sm ON sm.ID=a.Sample_Metadata_ID where 1=1 and i.ID ='$'  and i.ID=ise.Identity_ID and ise.ID=iss.Identity_Sequence_ID and iss.Assemly_ID=a.ID )
as sm_t  LEFT JOIN CDC.Antibiogram anti ON sm_t.ID=anti.Sample_Metadata_ID


SELECT temptable.Source,temptable.Source_ID, temptable.Protein_ID,anti.Antibiotic,anti.Drug_Symbol,
anti.Laboratory_Typing_Method,anti.Laboratory_Typing_Method_Version_or_Reagent,anti.Laboratory_Typing_Platform,
anti.Measurement,anti.Measurement_Sign,anti.Measurement_Units,anti.Resistance_Phenotype,anti.Testing_Standard,
anti.Vendor
FROM (SELECT asm.Sample_Metadata_ID,idn.Source,idn.Source_ID, idn.Protein_ID 
		FROM  CDC.Identity idn, CDC.Identity_Sequence ids, CDC.Identity_Assembly ida, CDC.Assemly asm
		where idn.ID=ids.Identity_ID AND ids.ID=ida.Identity_Sequence_ID AND asm.ID=ida.Assemly_ID) as temptable
LEFT JOIN CDC.Sample_Metadata sm ON sm.ID=temptable.Sample_Metadata_ID 
LEFT JOIN CDC.Antibiogram anti ON anti.Sample_Metadata_ID=sm.ID


