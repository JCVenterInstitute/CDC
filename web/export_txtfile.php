<?php
	date_default_timezone_set('America/Chicago');
	function export_files_main($query){
		//create radom file path
		// var_dump($query);
		// die();
		$random_string =generate_random_path();
		$path ="./tmp/".$random_string;
		//make dir 
		make_dir($path);
		//create file content Three files. Now just one
		$file_array =file_content($query);
		//create files
		create_files($path,$file_array);
		//create zipfiles using linux cmd to zip it
		zipfiles($path,$random_string);
		return $path."/".$random_string.".zip";
	}
	function create_files($path,$file_array){
		$file_list=['All.txt','Classification.txt','Antibiogram.txt'];
		// create a for loop do loop all
		//to do 		
		for($i=0;$i<count($file_list);$i++){
				$myfile = fopen($path.'/'.$file_list[$i], "w");
				fwrite($myfile, $file_array[$i]);
				fclose($myfile);
		}
	}
	function make_dir($my_dirname){
		mkdir($my_dirname, 0700);
	}
	/*
		Export 3 files : Identity with all 1 to 1 tables , antibiogram and Classification_Variants
		First request  the max rows then request a full fetch. 
		return an array of each output files array[0]=> Identity,array[1]=> antibiogram,array[2]=> Classification_Variants, 
	*/
	function file_content($query){
		$search_q="*";
		// $search_q_anti="*:*";
		if(trim($query)!='*'){
			// $search_q='id:'.$query.'  Or  Allele:'.$query.'  Or  Antibiotic:'.$query.'  Or  BioProject_ID:'.$query.
   //                                                           '  Or  Drug_Class:'.$query.'  Or  Drug_Family:'.$query.'  Or  Drug_Name:'.$query.
   //                                                           '  Or  Drug_Symbol:'.$query.'  Or  EC_Number:'.$query.'  Or  Isolation_site:'.$query.
   //                                                           '  Or  Gene_Symbol:'.$query.'  Or  Health_Status:'.$query.
   //                                                           '  Or  Host:'.$query.'  Or  Identity_ID:'.$query.'  Or  Identity_Sequence_ID:'.$query.
   //                                                           '  Or  Laboratory_Typing_Method:'.$query.'  Or  Laboratory_Typing_Platform:'.$query.'  Or  Measurement:'.$query.'  Or  Measurement_Sign:'.$query.
   //                                                           '  Or  Measurement_Units:'.$query.'Or  Mol_Type:'.$query.
   //                                                           '  Or  Parent_Allele:'.$query.'  Or  Parent_Allele_Family:'.$query.'  Or  Plasmid_Name:'.$query.
   //                                                           '  Or  Protein_ID:'.$query.'  Or  Protein_Name:'.$query.'  Or  PubMed_IDs:'.$query.'  Or  Pubmed_IDs:'.$query.
   //                                                           '  Or  Resistance_Phenotype:'.$query.'  Or  SNP:'.$query.
   //                                                           '  Or  Serotyping_Method:'.$query.'  Or  Source:'.$query.'  Or  Source_Common_Name:'.$query.
   //                                                           '  Or  Specimen_Collection_Date:'.$query.'  Or  Specimen_Collection_Location:'.$query.
   //                                                           '  Or  Specimen_Collection_Location_Country:'.$query.'  Or  Specimen_Collection_Location_Latitude:'.$query.
   //                                                           '  Or  Specimen_Collection_Location_Longitude:'.$query.'  Or  Specimen_Source_Age:'.$query.
   //                                                           '  Or  Specimen_Source_Developmental_Stage:'.$query.'  Or  Specimen_Source_Disease:'.$query.'  Or  Specimen_Source_Gender:'.$query.
   //                                                           '  Or  Specimen_Type:'.$query.'  Or  Status:'.$query.'  Or  Symptom:'.$query.'  Or  Taxon_Bacterial_BioVar:'.$query.
   //                                                           '  Or  Taxon_Class:'.$query.'  Or  Taxon_Family:'.$query.'  Or  Taxon_Genus:'.$query.'  Or  Taxon_ID:'.$query.
   //                                                           '  Or  Taxon_Kingdom:'.$query.'  Or  Taxon_Order:'.$query.'  Or  Taxon_Pathovar:'.$query.
   //                                                           '  Or  Taxon_Phylum:'.$query.'  Or  Taxon_Serotype:'.$query.'  Or  Taxon_Species:'.$query.'  Or  Taxon_Strain:'.$query.
   //                                                           '  Or  Taxon_Sub_Species:'.$query.'  Or  Testing_Standard:'.$query.'  Or  Treatment:'.$query.
   //                                                           '  Or  Vendor:'.$query;
			$search_q=$query;
		}

		// get the total number first then get all the records
		$post_fetch= `curl -X POST -H 'Content-Type: application/json' 'cdc-1.jcvi.org:8983/solr/my_core_exp/query' -d   '{  query : "all_fields:$search_q"  }'`;

		$js= json_decode($post_fetch);
		// var_dump($js);

		$max_row_no=$js->response->numFound;
		// var_dump($query);
		// if no file found then just return.
		if($js->response->numFound==0){
			return; 
		}
		$post_fetch= `curl -X POST -H 'Content-Type: application/json' 'cdc-1.jcvi.org:8983/solr/my_core_exp/query' -d   '{  query : "all_fields:$search_q" ,limit : $max_row_no }'`;
		$js= json_decode($post_fetch);
		$x_x_readytowrite_MOST=$js->response->docs;
// var_dump($js);

		$first_file ="";
		$firstline=1;
		foreach ($x_x_readytowrite_MOST as $key => $item) {
			if($firstline){
				$first_file.="ID"."\t";
				$first_file.="Gene_Symbol"."\t";
				$first_file.="Gene_Alternative_Names"."\t";
				$first_file.="Gene_Family"."\t";
				$first_file.="Gene_Class"."\t";
				$first_file.="Allele"."\t";
				$first_file.="EC_Number"."\t";
				$first_file.="Parent_Allele_Family"."\t";
				$first_file.="Parent_Allele"."\t";
				$first_file.="Source"."\t";
				$first_file.="Source_ID"."\t";
				$first_file.="Protein_ID"."\t";
				$first_file.="Protein_Name"."\t";
				$first_file.="Protein_Alternative_Names"."\t";
				$first_file.="Pubmed_IDs"."\t";
				$first_file.="HMM"."\t";
				$first_file.="Level"."\t";
				$first_file.="Taxon_ID"."\t";
				$first_file.="Taxon_Kingdom"."\t";
				$first_file.="Taxon_Pathovar"."\t";
				$first_file.="Taxon_Bacterial_BioVar"."\t";
				$first_file.="Taxon_Class"."\t";
				$first_file.="Taxon_Order"."\t";
				$first_file.="Taxon_Family"."\t";
				$first_file.="Taxon_Genus"."\t";
				$first_file.="Taxon_Species"."\t";
				$first_file.="Taxon_Sub_Species"."\t";
				$first_file.="Taxon_Pathovar"."\t";
				$first_file.="Taxon_Serotype"."\t";
				$first_file.="Taxon_Strain"."\t";
				$first_file.="Taxon_Sub_Strain"."\t";
				$first_file.="End3"."\t";
				$first_file.="End5"."\t";
				$first_file.="NA_Sequence"."\t";
				$first_file.="AA_Sequence"."\t";
				$first_file.="Feat_Type"."\t";
				$first_file.="Mol_Type"."\t";
				$first_file.="Plasmid_Name"."\t";
				$first_file.="Meta_Source"."\t";
				$first_file.="Isolation_site"."\t";
				$first_file.="Serotyping_Method"."\t";
				$first_file.="Source_Common_Name"."\t";
				$first_file.="Specimen_Collection_Date"."\t";
				$first_file.="Specimen_Collection_Location_Country"."\t";
				$first_file.="Specimen_Collection_Location"."\t";
				$first_file.="Specimen_Collection_Location_Longitude"."\t";
				$first_file.="Specimen_Source_Age"."\t";
				$first_file.="Specimen_Source_Developmental_Stage"."\t";
				$first_file.="Specimen_Source_Disease"."\t";
				$first_file.="Specimen_Source_Gender"."\t";
				$first_file.="Health_Status"."\t";
				$first_file.="Treatment"."\t";
				$first_file.="Specimen_Type"."\t";
				$first_file.="Symptom"."\t";
				$first_file.="Host"."\t";
				$first_file.="\n";
				$firstline--;
			}
			// echo "id: ". $item->id;
			$tmp_id[]=$item->id;
			$first_file.=$item->id."\t";
			$first_file.=$item->Gene_Symbol."\t";
			$first_file.=$item->Gene_Alternative_Names."\t";
			$first_file.=$item->Gene_Family."\t";
			$first_file.=$item->Gene_Class."\t";
			$first_file.=$item->Allele."\t";
			$first_file.=$item->EC_Number."\t";
			$first_file.=$item->Parent_Allele_Family."\t";
			$first_file.=$item->Parent_Allele."\t";
			$first_file.=$item->Source."\t";
			$first_file.=$item->Source_ID."\t";
			$first_file.=$item->Protein_ID."\t";
			$first_file.=$item->Protein_Name."\t";
			$first_file.=$item->Protein_Alternative_Names."\t";
			$first_file.=$item->Pubmed_IDs."\t";
			$first_file.=$item->HMM."\t";
			// $first_file.=$item->Is_Active."\t";
			// $first_file.=$item->Status."\t";
			// $first_file.=$item->Created_Date."\t";
			// $first_file.=$item->Modified_Date."\t";
			// $first_file.=$item->Created_By."\t";
			// $first_file.=$item->Modified_By."\t";
			$first_file.=$item->Level."\t";
			$first_file.=$item->Taxon_ID."\t";
			$first_file.=$item->Taxon_Kingdom."\t";
			$first_file.=$item->Taxon_Pathovar."\t";
			$first_file.=$item->Taxon_Bacterial_BioVar."\t";
			$first_file.=$item->Taxon_Class."\t";
			$first_file.=$item->Taxon_Order."\t";
			$first_file.=$item->Taxon_Family."\t";
			$first_file.=$item->Taxon_Genus."\t";
			$first_file.=$item->Taxon_Species."\t";
			$first_file.=$item->Taxon_Sub_Species."\t";
			$first_file.=$item->Taxon_Pathovar."\t";
			$first_file.=$item->Taxon_Serotype."\t";
			$first_file.=$item->Taxon_Strain."\t";
			$first_file.=$item->Taxon_Sub_Strain."\t";
			$first_file.=$item->End3."\t";
			$first_file.=$item->End5."\t";
			$first_file.=$item->NA_Sequence."\t";
			$first_file.=$item->AA_Sequence."\t";
			$first_file.=$item->Feat_Type."\t";
			$first_file.=$item->Mol_Type."\t";
			// $first_file.=$item->Assemly_PubMed_IDs."\t";
			// $first_file.=$item->Assemly_Source."\t";
			// $first_file.=$item->Assemly_Source_ID."\t";
			// $first_file.=$item->Is_Reference."\t";
			$first_file.=$item->Plasmid_Name."\t";
			// $first_file.=$item->Meta_Source_ID."\t";
			$first_file.=$item->Meta_Source."\t";
			$first_file.=$item->Isolation_site."\t";
			$first_file.=$item->Serotyping_Method."\t";
			$first_file.=$item->Source_Common_Name."\t";
			$first_file.=$item->Specimen_Collection_Date."\t";
			$first_file.=$item->Specimen_Collection_Location_Country."\t";
			$first_file.=$item->Specimen_Collection_Location."\t";
			$first_file.=$item->Specimen_Collection_Location_Longitude."\t";
			$first_file.=$item->Specimen_Source_Age."\t";
			$first_file.=$item->Specimen_Source_Developmental_Stage."\t";
			$first_file.=$item->Specimen_Source_Disease."\t";
			$first_file.=$item->Specimen_Source_Gender."\t";
			$first_file.=$item->Health_Status."\t";
			$first_file.=$item->Treatment."\t";
			$first_file.=$item->Specimen_Type."\t";
			$first_file.=$item->Symptom."\t";
			$first_file.=$item->Host."\t";
			$first_file.="\n";
			// echo $first_file;
		}

		if(isset($tmp_id)){
			// loop though the ID then loop thought the 
			// $search_q_class='id:'$query;
			// $search_q_class="";
			foreach ($tmp_id as $key) {
				$tmp_search[]=$key;
			}
			$relative_Identity_ID= implode(" or ",$tmp_search);
		}else{
			$relative_Identity_ID="*:*";
		}
		//exporting second file 
		//first get the number of rows

	$post_fetch= `curl -X POST -H 'Content-Type: application/json' 'cdc-1.jcvi.org:8983/solr/classification_variants/query' -d   '{  query : "*:*", filter : "Identity_ID:($relative_Identity_ID)"  }'`;
		$js= json_decode($post_fetch);
		$max_row_no=$js->response->numFound;
		$search_class_variant=`curl -X POST -H 'Content-Type: application/json' 'cdc-1.jcvi.org:8983/solr/classification_variants/query' -d   '{  query : "*:*", filter : "Identity_ID:($relative_Identity_ID)"  limit: $max_row_no}'`;
		// var_dump($search_class_variant);
	// 	echo "<br> class <br>";
	// var_dump($search_rows_request);
	
		$js= json_decode( $search_class_variant);
		$x_x_readytowrite_class_variant=$js->response->docs;
		
		$sec_file="";
		$firstline=1;
		$count_me=0; 
		foreach ($x_x_readytowrite_class_variant as $key => $item) {
			if($firstline){
				$sec_file.="Identity_ID"."\t";
				$sec_file.="ClassificationID"."\t";
				$sec_file.="Source"."\t";
				$sec_file.="Source_ID"."\t";
				$sec_file.="Drug"."\t";
				$sec_file.="Drug_Class"."\t";
				$sec_file.="Drug_Family"."\t";
				$sec_file.="Mechanism_of_Action"."\t";
				$sec_file.="\n";
				$firstline--;
			}
			// echo "writing: ".$item->Identity_ID;
			$sec_file.=$item->Identity_ID."\t";
			$sec_file.=$item->ClassificationID."\t";
			$sec_file.=$item->Source."\t";
			$sec_file.=$item->Source_ID."\t";
			$sec_file.=$item->Drug."\t";
			$sec_file.=$item->Drug_Class."\t";
			$sec_file.=$item->Drug_Family."\t";
			$sec_file.=$item->Mechanism_of_Action."\t";
			$sec_file.="\n";
			// $count_me++;
		}
		// echo "<br><h1>$count_me</h1><br>";

		//exporting thrid file 
	$search_rows_request= `curl -X POST -H 'Content-Type: application/json' 'cdc-1.jcvi.org:8983/solr/antibiogram/query' -d   '{  query : "*:*", filter : "Identity_ID:($relative_Identity_ID)"  }'`;

		$js= json_decode( $search_rows_request);
		$max_row_no=$js->response->numFound;
		$search_class_antibiogram=`curl -X POST -H 'Content-Type: application/json' 'cdc-1.jcvi.org:8983/solr/antibiogram/query' -d   '{  query : "*:*", filter : "Identity_ID:($relative_Identity_ID)"  limit: $max_row_no}'`;

		$js= json_decode($search_class_antibiogram);
		$x_x_readytowrite_anti=$js->response->docs;
		$thd_file="";
		//creating third file 
		$firstline=1;
		foreach ($x_x_readytowrite_class_variant as $key => $item) {
			if($firstline){
				$thd_file.="Identity_ID"."\t";
				$thd_file.="Source"."\t";
				$thd_file.="Source_ID"."\t";
				$thd_file.="Protein_ID"."\t";
				$thd_file.="Antibiotic"."\t";
				$thd_file.="Drug_Symbol"."\t";
				$thd_file.="Laboratory_Typing_Method"."\t";
				$thd_file.="Laboratory_Typing_Method_Version_or_Reagent"."\t";
				$thd_file.="Laboratory_Typing_Platform"."\t";
				$thd_file.="Measurement"."\t";
				$thd_file.="Measurement_Sign"."\t";
				$thd_file.="Measurement_Units"."\t";
				$thd_file.="Resistance_Phenotype"."\t";
				$thd_file.="Testing_Standard"."\t";
				$thd_file.="Vendor"."\t";
				$thd_file.="\n";
				$firstline--;
			}
			$thd_file.=$item->Identity_ID."\t";
			$thd_file.=$item->Source."\t";
			$thd_file.=$item->Source_ID."\t";
			$thd_file.=$item->Protein_ID."\t";
			$thd_file.=$item->Antibiotic."\t";
			$thd_file.=$item->Drug_Symbol."\t";
			$thd_file.=$item->Laboratory_Typing_Method."\t";
			$thd_file.=$item->Laboratory_Typing_Method_Version_or_Reagent."\t";
			$thd_file.=$item->Laboratory_Typing_Platform."\t";
			$thd_file.=$item->Measurement."\t";
			$thd_file.=$item->Measurement_Sign."\t";
			$thd_file.=$item->Measurement_Units."\t";
			$thd_file.=$item->Resistance_Phenotype."\t";
			$thd_file.=$item->Testing_Standard."\t";
			$thd_file.=$item->Vendor."\t";
			$thd_file.="\n";
		}

		$file_array[]=$first_file;
		$file_array[]=$sec_file;
		$file_array[]=$thd_file;
		return $file_array;
	}
	function generate_random_path(){
		$date_rand='export_'.date("Y_m_d_H_i_s");
		$path=$date_rand.rand(0, 10000);
		return $path;
	}
	function zipfiles($path,$random_string){
		// zip 
		$zipcmd= "zip -r ".$path."/".$random_string.".zip ".$path; 
		
		shell_exec($zipcmd);
	}
	
?>
