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
		$search_q="*:*";
		$search_q_anti="*:*";
		if(trim($query)!='*'){
			$search_q='id:'.$query.'%20Or%20Allele:'.$query.'%20Or%20Antibiotic:'.$query.'%20Or%20BioProject_ID:'.$query.
                                                             '%20Or%20Drug_Class:'.$query.'%20Or%20Drug_Family:'.$query.'%20Or%20Drug_Name:'.$query.
                                                             '%20Or%20Drug_Symbol:'.$query.'%20Or%20EC_Number:'.$query.'%20Or%20Isolation_site:'.$query.
                                                             '%20Or%20Gene_Symbol:'.$query.'%20Or%20Health_Status:'.$query.
                                                             '%20Or%20Host:'.$query.'%20Or%20Identity_ID:'.$query.'%20Or%20Identity_Sequence_ID:'.$query.
                                                             '%20Or%20Laboratory_Typing_Method:'.$query.'%20Or%20Laboratory_Typing_Platform:'.$query.'%20Or%20Measurement:'.$query.'%20Or%20Measurement_Sign:'.$query.
                                                             '%20Or%20Measurement_Units:'.$query.'Or%20Mol_Type:'.$query.
                                                             '%20Or%20Parent_Allele:'.$query.'%20Or%20Parent_Allele_Family:'.$query.'%20Or%20Plasmid_Name:'.$query.
                                                             '%20Or%20Protein_ID:'.$query.'%20Or%20Protein_Name:'.$query.'%20Or%20PubMed_IDs:'.$query.'%20Or%20Pubmed_IDs:'.$query.
                                                             '%20Or%20Resistance_Phenotype:'.$query.'%20Or%20SNP:'.$query.
                                                             '%20Or%20Serotyping_Method:'.$query.'%20Or%20Source:'.$query.'%20Or%20Source_Common_Name:'.$query.
                                                             '%20Or%20Specimen_Collection_Date:'.$query.'%20Or%20Specimen_Collection_Location:'.$query.
                                                             '%20Or%20Specimen_Collection_Location_Country:'.$query.'%20Or%20Specimen_Collection_Location_Latitude:'.$query.
                                                             '%20Or%20Specimen_Collection_Location_Longitude:'.$query.'%20Or%20Specimen_Source_Age:'.$query.
                                                             '%20Or%20Specimen_Source_Developmental_Stage:'.$query.'%20Or%20Specimen_Source_Disease:'.$query.'%20Or%20Specimen_Source_Gender:'.$query.
                                                             '%20Or%20Specimen_Type:'.$query.'%20Or%20Status:'.$query.'%20Or%20Symptom:'.$query.'%20Or%20Taxon_Bacterial_BioVar:'.$query.
                                                             '%20Or%20Taxon_Class:'.$query.'%20Or%20Taxon_Family:'.$query.'%20Or%20Taxon_Genus:'.$query.'%20Or%20Taxon_ID:'.$query.
                                                             '%20Or%20Taxon_Kingdom:'.$query.'%20Or%20Taxon_Order:'.$query.'%20Or%20Taxon_Pathovar:'.$query.
                                                             '%20Or%20Taxon_Phylum:'.$query.'%20Or%20Taxon_Serotype:'.$query.'%20Or%20Taxon_Species:'.$query.'%20Or%20Taxon_Strain:'.$query.
                                                             '%20Or%20Taxon_Sub_Species:'.$query.'%20Or%20Testing_Standard:'.$query.'%20Or%20Treatment:'.$query.
                                                             '%20Or%20Vendor:'.$query;
		}


		// get the row number
		$search_rows_request='http://cdc-1.jcvi.org:8983/solr/my_core_exp/select?q='.$search_q.'&rows=1&wt=json';

		// echo "<br><pre>".$search_rows_request."</pre><br>";
		$js= json_decode(file_get_contents($search_rows_request));
		$max_row_no=$js->response->numFound;
		// echo "<br> ALL";
		// var_dump( $query);
		// die();
		$search_Most ='http://cdc-1.jcvi.org:8983/solr/my_core_exp/select?fl=id,Gene_Symbol,Gene_Family,Gene_Class,Allele,EC_Number,Parent_Allele_Family,Parent_Allele,Source,Source_ID,Protein_ID,Protein_Name,Pubmed_IDs,HMM,Is_Active,Status,Created_Date,Modified_Date,Created_By,Modified_By,%20Level,%20Taxon_ID,%20Taxon_Kingdom,%20Taxon_Pathovar,%20Taxon_Bacterial_BioVar,%20Taxon_Class,%20Taxon_Order,%20Taxon_Family,%20Taxon_Genus,%20Taxon_Species,%20Taxon_Sub_Species,%20Taxon_Pathovar,%20Taxon_Serotype,%20Taxon_Strain,Taxon_Sub_Strain,%20End3,%20End5,%20NA_Sequence,%20AA_Sequence,%20Feat_Type,%20Mol_Type,Assemly_PubMed_IDs,Assemly_Source,%20Assemly_Source_ID,Is_Reference,Plasmid_Name,%20Meta_Source_ID,Meta_Source,%20Isolation_site,Serotyping_Method,%20Source_Common_Name,Specimen_Collection_Date,Specimen_Collection_Location_Country,Specimen_Collection_Location,Specimen_Collection_Location_Longitude,Specimen_Source_Age,Specimen_Source_Developmental_Stage,Specimen_Source_Disease,%20Specimen_Source_Gender,Health_Status,Treatment,Specimen_Type,Symptom,Host&q='.$search_q.'&rows='.$max_row_no.'&wt=json';
		$js= json_decode( file_get_contents($search_Most));
		$x_x_readytowrite_MOST=$js->response->docs;
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
				$first_file.="Is_Active"."\t";
				$first_file.="Status"."\t";
				$first_file.="Created_Date"."\t";
				$first_file.="Modified_Date"."\t";
				$first_file.="Created_By"."\t";
				$first_file.="Modified_By"."\t";
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
				$first_file.="Assemly_PubMed_IDs"."\t";
				$first_file.="Assemly_Source"."\t";
				$first_file.="Assemly_Source_ID"."\t";
				$first_file.="Is_Reference"."\t";
				$first_file.="Plasmid_Name"."\t";
				$first_file.="Meta_Source_ID"."\t";
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
			$first_file.=$item->Is_Active."\t";
			$first_file.=$item->Status."\t";
			$first_file.=$item->Created_Date."\t";
			$first_file.=$item->Modified_Date."\t";
			$first_file.=$item->Created_By."\t";
			$first_file.=$item->Modified_By."\t";
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
			$first_file.=$item->Assemly_PubMed_IDs."\t";
			$first_file.=$item->Assemly_Source."\t";
			$first_file.=$item->Assemly_Source_ID."\t";
			$first_file.=$item->Is_Reference."\t";
			$first_file.=$item->Plasmid_Name."\t";
			$first_file.=$item->Meta_Source_ID."\t";
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
				$tmp_search[]="Identity_ID:".$key;
			}
			$relative_Identity_ID= implode("%20Or%20",$tmp_search);
		}else{
			$relative_Identity_ID="*:*";
		}


		//exporting second file 
		$search_rows_request="http://cdc-1.jcvi.org:8983/solr/classification_variants/select?q=".$relative_Identity_ID."&rows=1&wt=json";
		$js= json_decode( file_get_contents($search_rows_request));
		$max_row_no=$js->response->numFound;
		$search_class_variant="http://cdc-1.jcvi.org:8983/solr/classification_variants/select?fl=Source,Source_ID,ClassificationID,Gene_Class,Drug,%20Drug_Class,%20Drug_Family,%20Mechanism_of_Action,Identity_ID&q=".$relative_Identity_ID."&rows=".$max_row_no."&wt=json";
	// 	echo "<br> class <br>";
	// var_dump($search_rows_request);
	
		$js= json_decode( file_get_contents($search_class_variant));
		$x_x_readytowrite_class_variant=$js->response->docs;
		
		$sec_file="";
		$firstline=1;
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
		}
		// echo $sec_file;
		//exporting thrid file 
		$search_rows_request="http://cdc-1.jcvi.org:8983/solr/antibiogram/select?q=*:*&rows=1&wt=json";
		$js= json_decode( file_get_contents($search_rows_request));
		$max_row_no=$js->response->numFound;
		$search_class_antibiogram="http://cdc-1.jcvi.org:8983/solr/antibiogram/select?fl=Source,Source_ID,%20Protein_ID,Antibiotic,Drug_Symbol,%20Laboratory_Typing_Method,Laboratory_Typing_Method_Version_or_Reagent,Laboratory_Typing_Platform,%20Measurement,Measurement_Sign,Measurement_Units,Resistance_Phenotype,Testing_Standard,%20Vendor,Identity_ID&q=".$relative_Identity_ID."&rows=".$max_row_no."&wt=json";
		$js= json_decode( file_get_contents($search_class_antibiogram));
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
	// export_files_main('');
	
?>
