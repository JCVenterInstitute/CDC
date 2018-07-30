// tabledeleterow.js version 1.2 2006-02-21
// mredkj.com

// CONFIG notes. Below are some comments that point to where this script can be customized.
// Note: Make sure to include a <tbody></tbody> in your table's HTML

//ORIGINAL CODE MODIFIED BY SANDEEP SINGH

var INPUT_NAME_PREFIX = 'inputName'; // this is being set via script
var RADIO_NAME = 'totallyrad'; // this is being set via script
var TABLE_NAME = 'table'; // this should be named in the HTML
var ROW_BASE = 1; // first number (for display)
var hasLoaded = false;

window.onload=fillInRows;

function fillInRows()
{
	hasLoaded = true;
	addRowToTable();
	addRowToTable();
	addRowToTable();
	addRowToTable();
}

// CONFIG:
// myRowObject is an object for storing information about the table rows
function myRowObject(one, two, three, four)
{
	this.one = one; // text object
	this.two = two; // input text object
	this.three = three; // input checkbox object
	this.four = four; // input radio object
}

/*
 * insertRowToTable
 * Insert and reorder
 */
function insertRowToTable(obj)
{
	if (hasLoaded) {
		var tbl = document.getElementById(TABLE_NAME);
		//var rowToInsertAt = tbl.tBodies[0].rows.length;
			var insRow = obj.parentNode.parentNode;
                	//var tbl = insRow.parentNode.parentNode;
                	var rIndex = insRow.sectionRowIndex;
			var jj = rIndex + 1;


			//assign(jj, 1);
			reorderNames(jj);
			addRowToTable(jj);
			reorderRowsI(tbl, jj);
			reassign(jj);
	}
}


function reorderNames(jj)
{
	var tbl = document.getElementById(TABLE_NAME);
	if (tbl.tBodies[0].rows[jj])
	{
		//document.write(jj);
		for (var i=tbl.tBodies[0].rows.length; i>jj; i--)
		{
			//document.write(i);
			var count_new = i + 1;
			var newaa = document.getElementById('field' + i);
			var newrestype = document.getElementById('condition' + i);
			var newsecstr = document.getElementById('query' + i);
			var newphi = document.getElementById('operator' + i);
			
			newaa.name = 'field' + count_new;
			newrestype.name = 'condition' + count_new;
			newsecstr.name = 'query' + count_new;
			newphi.name = 'operator' + count_new;
			newaa.id = 'field' + count_new;
			newrestype.id = 'condition' + count_new;
			newsecstr.id = 'query' + count_new;
			newphi.id = 'operator' + count_new;
		}
	}
}


/*
 * addRowToTable
 * Inserts at row 'num', or appends to the end if no arguments are passed in. Don't pass in empty strings.
 */
function addRowToTable(num)
{
	if (hasLoaded) {
		var tbl = document.getElementById(TABLE_NAME);
		var nextRow = tbl.tBodies[0].rows.length;
		var iteration = nextRow + ROW_BASE;
		if (num == null) { 
			num = nextRow;
			var itrnxt = 1;
		} else {
			iteration = num + ROW_BASE;
		}
		
		// add the row
		var row = tbl.tBodies[0].insertRow(num);
		
		// CONFIG: requires classes named classy0 and classy1
		row.className = 'classy' + (iteration % 2);
	
		// CONFIG: This whole section can be configured
		
		// cell 0 - text
		var cell0 = row.insertCell(0);
		var textNode = document.createTextNode(iteration);
		cell0.appendChild(textNode);
	
		// cell 1 - Amino Acid
		var cell1 = row.insertCell(1);
		var optn = document.createElement('select');
		optn.name = 'field'+iteration;
  		optn.setAttribute('id','field' + iteration);
		if(iteration == 1 && itrnxt == 1){optn.options[0] = new Option('Genbank ID','pepnam',"SELECTED");optn.options[0].selected=true} else{optn.options[0] = new Option('Genbank ID','pepnam');}
		if(iteration == 0 && itrnxt == 1){optn.options[1] = new Option('PUBMED ID','pid',"SELECTED");} else {optn.options[1] = new Option('PUBMED ID','pid'); }
		if(iteration == 4 && itrnxt == 1){optn.options[2] = new Option('Drug Class','pepseq',"SELECTED"); optn.options[2].selected=true} else{optn.options[2] = new Option('Drug Class','pepseq');}
		if(iteration == 0 && itrnxt == 1){optn.options[3] = new Option('Drug Family','id',"SELECTED");} else{optn.options[3] = new Option('Drug Family','id');}
		if(iteration == 0 && itrnxt == 1){optn.options[4] = new Option('Allele','ctermod',"SELECTED");} else {optn.options[4] = new Option('Allele','ctermod');}
		if(iteration == 0 && itrnxt == 1){optn.options[5] = new Option('Allele Family','ntermod',"SELECTED");} else {optn.options[5] = new Option('Allele Family','ntermod');}
		if(iteration == 2 && itrnxt == 1){optn.options[6] = new Option('Gene Name','chiral',"SELECTED"); optn.options[6].selected=true} else {optn.options[6] = new Option('Gene Name','chiral');}
		if(iteration == 0 && itrnxt == 1){optn.options[7] = new Option('Gene Symbol','chmod',"SELECTED");} else {optn.options[7] = new Option('Gene Symbol','chmod');}
		if(iteration == 0 && itrnxt == 1){optn.options[8] = new Option('Protein ID','source',"SELECTED");} else {optn.options[8] = new Option('Protein ID','source');}
		if(iteration == 3 && itrnxt == 1){optn.options[9] = new Option('Organism','cat',"SELECTED"); optn.options[9].selected=true} else {optn.options[9] = new Option('Organism','cat');}
		if(iteration == 0 && itrnxt == 1){optn.options[10] = new Option('Uniprot ID','upef',"SELECTED");} else {optn.options[10] = new Option('Uniprot ID','upef');}
		cell1.appendChild(optn);
		

		//cell 2 - Residue Type
  		var cell2 = row.insertCell(2);
  		var optn2 = document.createElement('select');
  		optn2.id = 'condition' + iteration;
		optn2.name = 'condition' + iteration;
		if(itrnxt != 1){optn2.options[0] = new Option('=', '=',"SELECTED"); optn2.options[0].selected=true; optn2.options[1] = new Option('<', '<'); optn2.options[2] = new Option('>', '>'); optn2.options[3] = new Option('LIKE', 'LIKE');}
		if(iteration == 1 && itrnxt == 1){optn2.options[0] = new Option('=', '=',"SELECTED"); optn2.options[0].selected=true; optn2.options[1] = new Option('<', '<'); optn2.options[2] = new Option('>', '>'); optn2.options[3] = new Option('LIKE', 'LIKE');}
  		if(iteration == 3 && itrnxt == 1){optn2.options[0] = new Option('=', '='); optn2.options[1] = new Option('<', '<');  optn2.options[2] = new Option('>', '>',"SELECTED"); optn2.options[3] = new Option('LIKE', 'LIKE'); optn2.options[0].selected=true;}
  		if(iteration == 2 && itrnxt == 1){optn2.options[0] = new Option('=', '=',"SELECTED"); optn2.options[1] = new Option('<', '<'); optn2.options[2] = new Option('>', '>');  optn2.options[3] = new Option('LIKE', 'LIKE'); optn2.options[0].selected=true;}
		if(iteration == 4 && itrnxt == 1){optn2.options[0] = new Option('=', '='); optn2.options[1] = new Option('<', '<'); optn2.options[2] = new Option('>', '>'); optn2.options[3] = new Option('LIKE', 'LIKE',"SELECTED");optn2.options[3].selected=true;}
  		cell2.appendChild(optn2);


	if(itrnxt != 1)
	{
		var cell3 = row.insertCell(3);
                var optn2 = document.createElement('input');
                optn2.name = 'query'+iteration;
                optn2.setAttribute('id','query' + iteration);
		optn2.setAttribute('type', 'text');
		optn2.setAttribute('size', '18');
		optn2.setAttribute('value', '');
	}
	if(iteration == 1 && itrnxt == 1)
	{
		var cell3 = row.insertCell(3);
                var optn2 = document.createElement('input');
                optn2.name = 'query'+iteration;
                optn2.setAttribute('id','query' + iteration);
		optn2.setAttribute('type', 'text');
		optn2.setAttribute('size', '18');
		optn2.setAttribute('value', 'FJ666073.1');
	}
	if(iteration == 2 && itrnxt == 1)
	{
		var cell3 = row.insertCell(3);
                var optn2 = document.createElement('input');
                optn2.name = 'query'+iteration;
                optn2.setAttribute('id','query' + iteration);
		optn2.setAttribute('type', 'text');
		optn2.setAttribute('size', '18');
		optn2.setAttribute('value', 'PDC-10');
	}
	if(iteration == 3 && itrnxt == 1)
	{
		var cell3 = row.insertCell(3);
                var optn2 = document.createElement('input');
                optn2.name = 'query'+iteration;
                optn2.setAttribute('id','query' + iteration);
		optn2.setAttribute('type', 'text');
		optn2.setAttribute('size', '18');
		optn2.setAttribute('value', 'Pseudomonas aeruginosa');
	}
	if(iteration == 4 && itrnxt == 1)
	{
		var cell3 = row.insertCell(3);
                var optn2 = document.createElement('input');
                optn2.name = 'query'+iteration;
                optn2.setAttribute('id','query' + iteration);
		optn2.setAttribute('type', 'text');
		optn2.setAttribute('size', '18');
		optn2.setAttribute('value', 'Class_C_AmpC');
	}

		cell3.appendChild(optn2);

		// cell 4 - Phi 
		var cell4 = row.insertCell(4);
		var btnEl1 = document.createElement('select');
		btnEl1.name = 'operator' + iteration;
		btnEl1.setAttribute('id', 'operator' + iteration);
		if(iteration == 0 && itrnxt == 1){btnEl1.options[0] = new Option('AND', 'AND',"SELECTED");} else { btnEl1.options[0] = new Option('AND', 'AND');}
		if(iteration == 0 && itrnxt == 1){btnEl1.options[1] = new Option('OR', 'OR',"SELECTED");} else {btnEl1.options[1] = new Option('OR', 'OR');}
		if(iteration == 0 && itrnxt == 1){btnEl1.options[2] = new Option('NOT', 'NOT',"SELECTED");} else {btnEl1.options[2] = new Option('NOT', 'NOT');}
		cell4.appendChild(btnEl1);

		// cell 5 - Psi 
		//var cell5 = row.insertCell(5);
		//var btnEl2 = document.createElement('input');
		//btnEl2.name = 'psi' + iteration;
		//btnEl2.setAttribute('id', 'psi' + iteration);
		//btnEl2.setAttribute('type', 'text');
		//btnEl2.setAttribute('size', '6');
		//btnEl2.setAttribute('value', '');
		//cell5.appendChild(btnEl2);

		// cell 7 - Omega 
		//var cell7 = row.insertCell(7);
		//var btnEl3 = document.createElement('input');
		//btnEl3.name = 'omega' + iteration;
		//btnEl3.setAttribute('id', 'omega' + iteration);
		//btnEl3.setAttribute('type', 'text');
		//btnEl3.setAttribute('size', '6');
		//btnEl3.setAttribute('value', '');
		//cell7.appendChild(btnEl3);

		// cell 5 - Insert
		var cell5 = row.insertCell(5);
		var btnEl = document.createElement('input');
		btnEl.setAttribute('type', 'button');
		btnEl.setAttribute('value', '+');
		btnEl.onclick = function () {insertRowToTable(this)};
		cell5.appendChild(btnEl);

		// cell 6 - Delete
		var cell6 = row.insertCell(6);
		var btnEl = document.createElement('input');
		btnEl.setAttribute('type', 'button');
		btnEl.setAttribute('value', '-');
		btnEl.onclick = function () {deleteCurrentRow(this)};
		cell6.appendChild(btnEl);
	
		// Pass in the elements you want to reference later
		// Store the myRow object in each row
		row.myRow = new myRowObject(textNode, 1, 2, 3);
		//row.myRow = new myRowObject(textNode, 1,cbEl, raEl);


var selectmenustr=document.getElementById('field' + iteration);
selectmenustr.onchange=function(){change(iteration, this.options[this.selectedIndex])};


//if has loaded ends here
	}
//main function ends here
}


function change(k, chosenoption)
{

	var newq = document.getElementById('query' + k);
	var newc = document.getElementById('condition' + k);
	//var chosenoption=this.options[this.selectedIndex]; //this refers to "selectmenu"
	if (chosenoption.value=="disease")
	{
		var new0 = document.createElement('select');
		new0.name = 'query' + k;
		new0.setAttribute('id', 'query' + k);
		new0.options[0] = new Option('Malaria','Malaria');
		new0.options[1] = new Option('Leishmania','LeishMania');
		new0.options[2] = new Option('African sleeping sickness','African sleeping sickness');
		new0.options[3] = new Option('Chagas','Chagas');
		new0.options[4] = new Option('Babesiosis','Babesiosis');
		new0.options[5] = new Option('Schistosomiasis','Schistosomiasis');
		new0.options[6] = new Option('Toxoplasmosis','Toxoplasmosis');
		new0.options[7] = new Option('Amoebiasis','Amoebiasis');
		new0.options[8] = new Option('Besnoitiosis','Besnoitiosis');
		new0.options[9] = new Option('Cryptosporidiosis','Cryptosporidiosis');
		new0.options[10] = new Option('Hemorragic cecal coccidiosis','Hemorragic cecal coccidiosis');
		new0.options[11] = new Option('Neosporosis','Neosporosis');
		new0.options[12] = new Option('Pyogranulomatous dermatitis','Pyogranulomatous dermatitis');
		newq.parentNode.replaceChild(new0, newq);
		new0.focus();

		var newc0 = document.createElement('select');
		newc0.name = 'condition' + k;
		newc0.setAttribute('id', 'condition' + k);
		newc0.options[0] = new Option('=','=');
		newc0.options[1] = new Option('<','<');
		newc0.options[2] = new Option('>','>');
		newc0.options[3] = new Option('LIKE','LIKE',"SELECTED");
		newc0.options[3].selected=true;
		newc.parentNode.replaceChild(newc0, newc);
		newc0.focus();
	}
	else if (chosenoption.value == "par_type")
	{
		var new0 = document.createElement('select');
		new0.name = 'query' + k;
		new0.setAttribute('id', 'query' + k);
		new0.options[0] = new Option('Plasmodium','Plasmodium');
		new0.options[1] = new Option('Leishmania','Leishmania');
		new0.options[2] = new Option('Trypanosoma','Trypanosoma');
		new0.options[3] = new Option('Babesia','Babesia');
		new0.options[4] = new Option('Schistosoma','Schistosoma');
		new0.options[5] = new Option('Toxoplasma','Toxoplasma');
		new0.options[6] = new Option('Besnoitia','Besnoitia');
		new0.options[7] = new Option('Cryptosporidium','Cryptosporidium');
		new0.options[8] = new Option('Eimeria','Eimeria');
		new0.options[9] = new Option('Neospora','Neospora');
		new0.options[10] = new Option('Entamoeba','Entamoeba');
		new0.options[11] = new Option('Caryospora','Caryospora');
		newq.parentNode.replaceChild(new0, newq);
		new0.focus();

		var newc0 = document.createElement('select');
		newc0.name = 'condition' + k;
		newc0.setAttribute('id', 'condition' + k);
		newc0.options[0] = new Option('=','=');
		newc0.options[1] = new Option('<','<');
		newc0.options[2] = new Option('>','>');
		newc0.options[3] = new Option('LIKE','LIKE',"SELECTED");
		newc0.options[3].selected=true;
		newc.parentNode.replaceChild(newc0, newc);
		newc0.focus();
	}
 else if (chosenoption.value == "lin_cyc")
 {
	var new0 = document.createElement('select');
	new0.name = 'query' + k;
	new0.setAttribute('id', 'query' + k);
	new0.options[0] = new Option('Linear','Linear');
	new0.options[1] = new Option('Cyclic','Cyclic');
	newq.parentNode.replaceChild(new0, newq);
	new0.focus();

		var newc0 = document.createElement('select');
		newc0.name = 'condition' + k;
		newc0.setAttribute('id', 'condition' + k);
		newc0.options[0] = new Option('=','=',"SELECTED");
		newc0.options[1] = new Option('<','<');
		newc0.options[2] = new Option('>','>');
		newc0.options[3] = new Option('LIKE','LIKE');
		newc0.options[0].selected=true;
		newc.parentNode.replaceChild(newc0, newc);
		newc0.focus();
 }
 else if (chosenoption.value == "seq")
 {
		var new00 = document.createElement('input');
                new00.name = 'query' + k;
                new00.setAttribute('id','query' + k);
                new00.setAttribute('type', 'text');
                new00.setAttribute('size', '18');
		newq.parentNode.replaceChild(new00, newq);
		new00.focus();

		var newc0 = document.createElement('select');
		newc0.name = 'condition' + k;
		newc0.setAttribute('id', 'condition' + k);
		newc0.options[0] = new Option('=','=');
		newc0.options[1] = new Option('<','<');
		newc0.options[2] = new Option('>','>');
		newc0.options[3] = new Option('LIKE','LIKE',"SELECTED");
		newc0.options[3].selected=true;
		newc.parentNode.replaceChild(newc0, newc);
		newc0.focus();
 }
 else if (chosenoption.value == "chiral")
 {
	var new0 = document.createElement('select');
	new0.name = 'query' + k;
	new0.setAttribute('id', 'query' + k);
	new0.options[0] = new Option('L','L');
	new0.options[1] = new Option('D','D');
	new0.options[2] = new Option('Mix','Mix');
	newq.parentNode.replaceChild(new0, newq);
	new0.focus();

		var newc0 = document.createElement('select');
		newc0.name = 'condition' + k;
		newc0.setAttribute('id', 'condition' + k);
		newc0.options[0] = new Option('=','=',"SELECTED");
		newc0.options[1] = new Option('<','<');
		newc0.options[2] = new Option('>','>');
		newc0.options[3] = new Option('LIKE','LIKE');
		newc0.options[0].selected=true;
		newc.parentNode.replaceChild(newc0, newc);
		newc0.focus();
 }
 else if (chosenoption.value == "vivo-vitro")
 {
	var new0 = document.createElement('select');
	new0.name = 'query' + k;
	new0.setAttribute('id', 'query' + k);
	new0.options[0] = new Option('Invivo','Invivo');
	new0.options[1] = new Option('Invitro','Invitro');
	newq.parentNode.replaceChild(new0, newq);
	new0.focus();

		var newc0 = document.createElement('select');
		newc0.name = 'condition' + k;
		newc0.setAttribute('id', 'condition' + k);
		newc0.options[0] = new Option('=','=',"SELECTED");
		newc0.options[1] = new Option('<','<');
		newc0.options[2] = new Option('>','>');
		newc0.options[3] = new Option('LIKE','LIKE');
		newc0.options[0].selected=true;
		newc.parentNode.replaceChild(newc0, newc);
		newc0.focus();
 }
 else if (chosenoption.value == "assay")
        {
                var new0 = document.createElement('select');
                new0.name = 'query' + k;
                new0.setAttribute('id', 'query' + k);
                new0.options[0] = new Option('Alamar Blue assay','Alamar Blue assay');
                new0.options[1] = new Option('ELISA','ELISA');
                new0.options[2] = new Option('FACScan','FACScan');
                new0.options[3] = new Option('Fluorimetry','Fluorimetry');
                new0.options[4] = new Option('Gemini XPS spectrofluorometer','Gemini XPS spectrofluorometer');
                new0.options[5] = new Option('Luminescence-Steady-Glo Assay system','Luminescence-Steady-Glo Assay system');
                new0.options[6] = new Option('MTS assay','MTS assay');
                new0.options[7] = new Option('MTT assay','MTT assay');
                new0.options[8] = new Option('Microscopy','Microscopy');
                new0.options[9] = new Option('Scintillation proximation assay (SPA)','Scintillation proximation assay (SPA)');
                new0.options[10] = new Option('Standard plasmid relaxation assay','Standard plasmid relaxation assay');
                new0.options[11] = new Option('[3H] Hypoxanthine incorporation assay','[3H] Hypoxanthine incorporation assay');
                new0.options[12] = new Option('[3H] Phenylalanine incorporation assay','[3H] Phenylalanine incorporation assay');
                new0.options[13] = new Option('[3H] Uracil incorporation assay','[3H] Uracil incorporation assay');
                new0.options[14] = new Option('pLDH assay','pLDH assay');
                newq.parentNode.replaceChild(new0, newq);
                new0.focus();

		var newc0 = document.createElement('select');
		newc0.name = 'condition' + k;
		newc0.setAttribute('id', 'condition' + k);
		newc0.options[0] = new Option('=','=');
		newc0.options[1] = new Option('<','<');
		newc0.options[2] = new Option('>','>');
		newc0.options[3] = new Option('LIKE','LIKE',"SELECTED");
		newc0.options[3].selected=true;
		newc.parentNode.replaceChild(newc0, newc);
		newc0.focus();
        }
	//else if(chosenoption.value == "lin_cyc")
	//{
	//	var new0 = document.createElement('select');
	//	new0.name = 'lin_cyc' + k;
	//	new0.setAttribute('id', 'lin_cyc' + k);
	//	new0.options[0] = new Option('Linear','Linear');
	//	new0.options[1] = new Option('Cyclic','Cyclic');
	//	newq.parentNode.replaceChild(new0, newq);
	//	new0.focus();
	//}
	//else if(chosenoption.value == "chiral")
	//{
	//	var new0 = document.createElement('select');
        //        new0.name = 'chiral' + k;
        //        new0.setAttribute('id', 'chiral' + k);
        //        new0.options[0] = new Option('L','L');
        //        new0.options[1] = new Option('D','D');
        //        new0.options[2] = new Option('Mix','Mix');
        //        newq.parentNode.replaceChild(new0, newq);
        //        new0.focus();
	//}
	else
	{
		var new00 = document.createElement('input');
                new00.name = 'query' + k;
                new00.setAttribute('id','query' + k);
                new00.setAttribute('type', 'text');
                new00.setAttribute('size', '18');
		newq.parentNode.replaceChild(new00, newq);
		new00.focus();

		var newc0 = document.createElement('select');
		newc0.name = 'condition' + k;
		newc0.setAttribute('id', 'condition' + k);
		newc0.options[0] = new Option('=','=',"SELECTED");
		newc0.options[1] = new Option('<','<');
		newc0.options[2] = new Option('>','>');
		newc0.options[3] = new Option('LIKE','LIKE');
		newc0.options[0].selected=true;
		newc.parentNode.replaceChild(newc0, newc);
		newc0.focus();
	}

}

function reassign(ind)
{
	selectmenustr=document.getElementById('field' + 1); selectmenustr.onchange=function(){change(1, this.options[this.selectedIndex])};
	selectmenustr=document.getElementById('field' + 2); selectmenustr.onchange=function(){change(2, this.options[this.selectedIndex])};
	selectmenustr=document.getElementById('field' + 3); selectmenustr.onchange=function(){change(3, this.options[this.selectedIndex])};
	selectmenustr=document.getElementById('field' + 4); selectmenustr.onchange=function(){change(4, this.options[this.selectedIndex])};
	selectmenustr=document.getElementById('field' + 5); selectmenustr.onchange=function(){change(5, this.options[this.selectedIndex])};
	selectmenustr=document.getElementById('field' + 6); selectmenustr.onchange=function(){change(6, this.options[this.selectedIndex])};
	selectmenustr=document.getElementById('field' + 7); selectmenustr.onchange=function(){change(7, this.options[this.selectedIndex])};
	selectmenustr=document.getElementById('field' + 8); selectmenustr.onchange=function(){change(8, this.options[this.selectedIndex])};
	selectmenustr=document.getElementById('field' + 9); selectmenustr.onchange=function(){change(9, this.options[this.selectedIndex])};
	selectmenustr=document.getElementById('field' + 10); selectmenustr.onchange=function(){change(10, this.options[this.selectedIndex])};
	selectmenustr=document.getElementById('field' + 11); selectmenustr.onchange=function(){change(11, this.options[this.selectedIndex])};
	selectmenustr=document.getElementById('field' + 12); selectmenustr.onchange=function(){change(12, this.options[this.selectedIndex])};
	selectmenustr=document.getElementById('field' + 13); selectmenustr.onchange=function(){change(13, this.options[this.selectedIndex])};
	selectmenustr=document.getElementById('field' + 14); selectmenustr.onchange=function(){change(14, this.options[this.selectedIndex])};
	selectmenustr=document.getElementById('field' + 15); selectmenustr.onchange=function(){change(15, this.options[this.selectedIndex])};
	selectmenustr=document.getElementById('field' + 16); selectmenustr.onchange=function(){change(16, this.options[this.selectedIndex])};
	selectmenustr=document.getElementById('field' + 17); selectmenustr.onchange=function(){change(17, this.options[this.selectedIndex])};
	selectmenustr=document.getElementById('field' + 18); selectmenustr.onchange=function(){change(18, this.options[this.selectedIndex])};
	selectmenustr=document.getElementById('field' + 19); selectmenustr.onchange=function(){change(19, this.options[this.selectedIndex])};
	selectmenustr=document.getElementById('field' + 20); selectmenustr.onchange=function(){change(20, this.options[this.selectedIndex])};
	selectmenustr=document.getElementById('field' + 21); selectmenustr.onchange=function(){change(21, this.options[this.selectedIndex])};
	selectmenustr=document.getElementById('field' + 22); selectmenustr.onchange=function(){change(22, this.options[this.selectedIndex])};
	selectmenustr=document.getElementById('field' + 23); selectmenustr.onchange=function(){change(23, this.options[this.selectedIndex])};
	selectmenustr=document.getElementById('field' + 24); selectmenustr.onchange=function(){change(24, this.options[this.selectedIndex])};
	selectmenustr=document.getElementById('field' + 25); selectmenustr.onchange=function(){change(25, this.options[this.selectedIndex])};
	selectmenustr=document.getElementById('field' + 26); selectmenustr.onchange=function(){change(26, this.options[this.selectedIndex])};
	selectmenustr=document.getElementById('field' + 27); selectmenustr.onchange=function(){change(27, this.options[this.selectedIndex])};
	selectmenustr=document.getElementById('field' + 28); selectmenustr.onchange=function(){change(28, this.options[this.selectedIndex])};
	selectmenustr=document.getElementById('field' + 29); selectmenustr.onchange=function(){change(29, this.options[this.selectedIndex])};
	selectmenustr=document.getElementById('field' + 30); selectmenustr.onchange=function(){change(30, this.options[this.selectedIndex])};

}

// If there isn't an element with an onclick event in your row, then this function can't be used.
function deleteCurrentRow(obj)
{
	if (hasLoaded) {
		var delRow = obj.parentNode.parentNode;
		var tbl = delRow.parentNode.parentNode;
		var rIndex = delRow.sectionRowIndex;
		var rowArray = new Array(delRow);
		deleteRows(rowArray);
		//assign(rIndex, 2);
		reorderRows(tbl, rIndex);
		//document.write("Hello");
		reassign(rIndex);
			//var kkk = rIndex + 1;
			//reassign(kkk);
	}
}



function reorderRows(tbl, startingIndex)
{
	if (hasLoaded) {
	     if (tbl.tBodies[0].rows[startingIndex]) {
		var count = startingIndex + ROW_BASE;
		
		for (var i=startingIndex; i<tbl.tBodies[0].rows.length; i++) {
			tbl.tBodies[0].rows[i].myRow.one.data = count; // text
			tbl.tBodies[0].rows[i].myRow.two.name = INPUT_NAME_PREFIX + count; // input text
			tbl.tBodies[0].rows[i].className = 'classy' + (count % 2);
			count++;
		}
		//count_new = startingIndex + 1;
		for (var j=startingIndex; j<tbl.tBodies[0].rows.length; j++) {
			var count1 = j + 1;
			var count_new = j;
			var newaa = document.getElementById('field' + count1);
			var newrestype = document.getElementById('condition' + count1);
			var newsecstr = document.getElementById('query' + count1);
			var newphi = document.getElementById('operator' + count1);
			newaa.name = 'field' + count_new;
			newrestype.name = 'condition' + count_new;
			newsecstr.name = 'query' + count_new;
			newphi.name = 'operator' + count_new;
			newaa.id = 'field' + count_new;
			newrestype.id = 'condition' + count_new;
			newsecstr.id = 'query' + count_new;
			newphi.id = 'operator' + count_new;
			count_new = count_new + 1;
		}
	     }
	}
}

function deleteRows(rowObjArray)
{
	if (hasLoaded) {
		var tbl = document.getElementById(TABLE_NAME);
                var len = tbl.tBodies[0].rows.length;
		for (var i=0; i<rowObjArray.length; i++) {
			var rIndex = rowObjArray[i].sectionRowIndex;
			if(len > 1)
			rowObjArray[i].parentNode.deleteRow(rIndex);
		}
	}
}

function reorderRowsI(tbl, startingIndex)
{
	if (hasLoaded)
	{
	if (tbl.tBodies[0].rows[startingIndex])
	{
		var count = startingIndex + ROW_BASE;
		for (var i=startingIndex; i<tbl.tBodies[0].rows.length; i++) {
		tbl.tBodies[0].rows[i].myRow.one.data = count; // text
		tbl.tBodies[0].rows[i].myRow.two.name = INPUT_NAME_PREFIX + count; // input text
		tbl.tBodies[0].rows[i].className = 'classy' + (count % 2);
		count++;
		}
	}
	}
}
