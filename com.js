var time_variable;
 
function lookup_nomp(nf,pf,inputString) {
	if(inputString.length == 0) {
		// Hide the suggestion box.
		$('#suggestions1').hide();
	} else {
		$.post("search1.php", {search1: ""+inputString+"",search3: ""+nf+"",search4: ""+pf+""}, function(data){
			if(data.length >0) {
				$('#suggestions1').show();
				$('#autoSuggestionsList1').html(data);
			}
		});
	}
} // lookup

function lookup_prenomp(nf,pf,inputString) {
	if(inputString.length == 0) {
		// Hide the suggestion box.
		$('#suggestions2').hide();
	} else {
		$.post("search2.php", {search2: ""+inputString+"",search3: ""+nf+"",search4: ""+pf+""}, function(data){
			if(data.length >0) {
				$('#suggestions2').show();
				$('#autoSuggestionsList2').html(data);
			}
		});
	}
} // lookup

function fill1(x,y,z) {
	$('#parrain_nom').val(x);
	$('#parrain_prenom').val(y);
	setTimeout("$('#suggestions"+z+"').hide();", 200);
}

function lookup_nomf(np,pp,inputString) {
	if(inputString.length == 0) {
		// Hide the suggestion box.
		$('#suggestions3').hide();
	} else {
		$.post("search3.php", {search1: ""+np+"",search2: ""+pp+"",search3: ""+inputString+""}, function(data){
			if(data.length >0) {
				$('#suggestions3').show();
				$('#autoSuggestionsList3').html(data);
			}
		});
	}
} // lookup

function lookup_prenomf(np,pp,inputString) {
	if(inputString.length == 0) {
		// Hide the suggestion box.
		$('#suggestions4').hide();
	} else {
		$.post("search4.php", {search1: ""+np+"",search2: ""+pp+"",search4: ""+inputString+""}, function(data){
			if(data.length >0) {
				$('#suggestions4').show();
				$('#autoSuggestionsList4').html(data);
			}
		});
	}
} // lookup

function fill2(x,y,z) {
	$('#fillot_nom').val(x);
	$('#fillot_prenom').val(y);
	setTimeout("$('#suggestions"+z+"').hide();", 200);
}
