<?php


// var_dump($_POST);
$search_q=$_POST['search_query'];
// echo "your file should be created here";
$post_fetch= `curl -X POST -H 'Content-Type: application/json' 'cdc-1.jcvi.org:8983/solr/my_core_exp/query' -d   '{  query : "all_fields:$search_q"}'`;
		$js= json_decode($post_fetch);
		$max_row_no=$js->response->numFound;
		// var_dump($query);
		// if no file found then just return.
		if($js->response->numFound==0){
			return; 
		}
		$post_fetch= `curl -X POST -H 'Content-Type: application/json' 'cdc-1.jcvi.org:8983/solr/my_core_exp/query' -d   '{  query : "all_fields:$search_q" ,fields :["Specimen_Collection_Date","Specimen_Collection_Location_Country"],limit : $max_row_no }'`;
		$js= json_decode($post_fetch);
		$x_x_readytowrite_MOST=$js->response->docs;
		// var_dump($x_x_readytowrite_MOST);

		foreach ($x_x_readytowrite_MOST as $key => $item) {
			if($item->Specimen_Collection_Date||$item->Specimen_Collection_Location_Country){
			
					// get the abviation for the country
					$year =(isset($item->Specimen_Collection_Date)&&($item->Specimen_Collection_Date!="Unknown"))?$item->Specimen_Collection_Date:"" ;
					//4-Feb-06
					//year has data consistency issue. 

					// $year = 

					$country =isset($item->Specimen_Collection_Location_Country)?$item->Specimen_Collection_Location_Country:"";
					$country = country_abv($country);
				echo "$year : $country";	
					// $map_year_country[]=isset($item->Specimen_Collection_Date)?$item->Specimen_Collection_Date:"" ;
					// 		var_dump($year);					
					// var_dump($country);
				echo "<br>";
			}
		}
function country_abv($country){
	 $countrydb=array( 'afghanistan' => 'AF', 'Aland Islands' => 'AX', 'Albania' => 'AL', 'Algeria' => 'DZ', 'American Samoa' => 'AS', 'Andorra' => 'AD', 'Angola' => 'AO', 'Anguilla' => 'AI', 'Antarctica' => 'AQ', 'Antigua and Barbuda' => 'AG', 'Argentina' => 'AR', 'Armenia' => 'AM', 'Aruba' => 'AW', 'Australia' => 'AU', 'Austria' => 'AT', 'Azerbaijan' => 'AZ', 'Bahamas the' => 'BS', 'Bahrain' => 'BH', 'Bangladesh' => 'BD', 'Barbados' => 'BB', 'Belarus' => 'BY', 'Belgium' => 'BE', 'Belize' => 'BZ', 'Benin' => 'BJ', 'Bermuda' => 'BM', 'Bhutan' => 'BT', 'Bolivia' => 'BO', 'Bosnia and Herzegovina' => 'BA', 'Botswana' => 'BW', 'Bouvet Island (Bouvetoya)' => 'BV', 'Brazil' => 'BR', 'British Indian Ocean Territory (Chagos Archipelago)' => 'IO', 'British Virgin Islands' => 'VG', 'Brunei Darussalam' => 'BN', 'Bulgaria' => 'BG', 'Burkina Faso' => 'BF', 'Burundi' => 'BI', 'Cambodia' => 'KH', 'Cameroon' => 'CM', 'Canada' => 'CA', 'Cape Verde' => 'CV', 'Cayman Islands' => 'KY', 'Central African Republic' => 'CF', 'Chad' => 'TD', 'Chile' => 'CL', 'China' => 'CN', 'Christmas Island' => 'CX', 'Cocos (Keeling) Islands' => 'CC', 'Colombia' => 'CO', 'Comoros the' => 'KM', 'Congo' => 'CD', 'Congo the' => 'CG', 'Cook Islands' => 'CK', 'Costa Rica' => 'CR', 'Cote d Ivoire' => 'CI', 'Croatia' => 'HR', 'Cuba' => 'CU', 'Cyprus' => 'CY', 'Czech Republic' => 'CZ', 'Denmark' => 'DK', 'Djibouti' => 'DJ', 'Dominica' => 'DM', 'Dominican Republic' => 'DO', 'Ecuador' => 'EC', 'Egypt' => 'EG', 'El Salvador' => 'SV', 'Equatorial Guinea' => 'GQ', 'Eritrea' => 'ER', 'Estonia' => 'EE', 'Ethiopia' => 'ET', 'Faroe Islands' => 'FO', 'Falkland Islands (Malvinas)' => 'FK', 'Fiji the Fiji Islands' => 'FJ', 'Finland' => 'FI', 'France, French Republic' => 'FR', 'French Guiana' => 'GF', 'French Polynesia' => 'PF', 'French Southern Territories' => 'TF', 'Gabon' => 'GA', 'Gambia the' => 'GM', 'Georgia' => 'GE', 'Germany' => 'DE', 'Ghana' => 'GH', 'Gibraltar' => 'GI', 'Greece' => 'GR', 'Greenland' => 'GL', 'Grenada' => 'GD', 'Guadeloupe' => 'GP', 'Guam' => 'GU', 'Guatemala' => 'GT', 'Guernsey' => 'GG', 'Guinea' => 'GN', 'Guinea-Bissau' => 'GW', 'Guyana' => 'GY', 'Haiti' => 'HT', 'Heard Island and McDonald Islands' => 'HM', 'Holy See (Vatican City State)' => 'VA', 'Honduras' => 'HN', 'Hong Kong' => 'HK', 'Hungary' => 'HU', 'Iceland' => 'IS', 'India' => 'IN', 'Indonesia' => 'ID', 'Iran' => 'IR', 'Iraq' => 'IQ', 'Ireland' => 'IE', 'Isle of Man' => 'IM', 'Israel' => 'IL', 'Italy' => 'IT', 'Jamaica' => 'JM', 'Japan' => 'JP', 'Jersey' => 'JE', 'Jordan' => 'JO', 'Kazakhstan' => 'KZ', 'Kenya' => 'KE', 'Kiribati' => 'KI', 'South Korea' => 'KR','North Korea' => 'KP', 'Kuwait' => 'KW', 'Kyrgyz Republic' => 'KG', 'Lao' => 'LA', 'Latvia' => 'LV', 'Lebanon' => 'LB', 'Lesotho' => 'LS', 'Liberia' => 'LR', 'Libyan Arab Jamahiriya' => 'LY', 'Liechtenstein' => 'LI', 'Lithuania' => 'LT', 'Luxembourg' => 'LU', 'Macao' => 'MO', 'Macedonia' => 'MK', 'Madagascar' => 'MG', 'Malawi' => 'MW', 'Malaysia' => 'MY', 'Maldives' => 'MV', 'Mali' => 'ML', 'Malta' => 'MT', 'Marshall Islands' => 'MH', 'Martinique' => 'MQ', 'Mauritania' => 'MR', 'Mauritius' => 'MU', 'Mayotte' => 'YT', 'Mexico' => 'MX', 'Micronesia' => 'FM', 'Moldova' => 'MD', 'Monaco' => 'MC', 'Mongolia' => 'MN', 'Montenegro' => 'ME', 'Montserrat' => 'MS', 'Morocco' => 'MA', 'Mozambique' => 'MZ', 'Myanmar' => 'MM', 'Namibia' => 'NA', 'Nauru' => 'NR', 'Nepal' => 'NP', 'Netherlands Antilles' => 'AN', 'Netherlands' => 'NL', 'New Caledonia' => 'NC', 'New Zealand' => 'NZ', 'Nicaragua' => 'NI', 'Niger' => 'NE', 'Nigeria' => 'NG', 'Niue' => 'NU', 'Norfolk Island' => 'NF', 'Northern Mariana Islands' => 'MP', 'Norway' => 'NO', 'Oman' => 'OM', 'Pakistan' => 'PK', 'Palau' => 'PW', 'Palestinian Territory' => 'PS', 'Panama' => 'PA', 'Papua New Guinea' => 'PG', 'Paraguay' => 'PY', 'Peru' => 'PE', 'Philippines' => 'PH', 'Pitcairn Islands' => 'PN', 'Poland' => 'PL', 'Portugal, Portuguese Republic' => 'PT','Portugal' => 'PT', 'Puerto Rico' => 'PR', 'Qatar' => 'QA', 'Reunion' => 'RE', 'Romania' => 'RO', 'Russian Federation' => 'RU', 'Rwanda' => 'RW', 'Saint Barthelemy' => 'BL', 'Saint Helena' => 'SH', 'Saint Kitts and Nevis' => 'KN', 'Saint Lucia' => 'LC', 'Saint Martin' => 'MF', 'Saint Pierre and Miquelon' => 'PM', 'Saint Vincent and the Grenadines' => 'VC', 'Samoa' => 'WS', 'San Marino' => 'SM', 'Sao Tome and Principe' => 'ST', 'Saudi Arabia' => 'SA', 'Senegal' => 'SN', 'Serbia' => 'RS', 'Seychelles' => 'SC', 'Sierra Leone' => 'SL', 'Singapore' => 'SG', 'Slovakia (Slovak Republic)' => 'SK', 'Slovenia' => 'SI', 'Solomon Islands' => 'SB', 'Somalia, Somali Republic' => 'SO', 'South Africa' => 'ZA', 'South Georgia and the South Sandwich Islands' => 'GS', 'Spain' => 'ES', 'Sri Lanka' => 'LK', 'Sudan' => 'SD', 'Suriname' => 'SR', 'Svalbard & Jan Mayen Islands' => 'SJ', 'Swaziland' => 'SZ', 'Sweden' => 'SE', 'Switzerland, Swiss Confederation' => 'CH', 'Syrian Arab Republic' => 'SY', 'Taiwan' => 'TW', 'Tajikistan' => 'TJ', 'Tanzania' => 'TZ', 'Thailand' => 'TH', 'Timor-Leste' => 'TL', 'Togo' => 'TG', 'Tokelau' => 'TK', 'Tonga' => 'TO', 'Trinidad and Tobago' => 'TT', 'Tunisia' => 'TN', 'Turkey' => 'TR', 'Turkmenistan' => 'TM', 'Turks and Caicos Islands' => 'TC', 'Tuvalu' => 'TV', 'Uganda' => 'UG', 'Ukraine' => 'UA', 'United Arab Emirates' => 'AE', 'United Kingdom' => 'GB', 'United States of America' => 'US', 'United States Minor Outlying Islands' => 'UM', 'United States Virgin Islands' => 'VI', 'Uruguay' => 'UY', 'Uzbekistan' => 'UZ', 'Vanuatu' => 'VU', 'Venezuela' => 'VE', 'Vietnam' => 'VN', 'Wallis and Futuna' => 'WF', 'Western Sahara' => 'EH', 'Yemen' => 'YE', 'Zambia' => 'ZM', 'Zimbabwe' => 'ZW', 'Usa' => 'US','Russia'=>'RU');
	 
	 	// echo "here";

	 $country = ucwords(trim(strtolower($country)));
	 return array_key_exists($country, $countrydb)?$countrydb[$country]:$country;
	 // return $country;
}
// country_abv('');
?>