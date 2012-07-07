var time_variable;
 
function lookup_nomp(inputString) {
	if(inputString.length == 0) {
		// Hide the suggestion box.
		$('#suggestions1').hide();
	} else {
		$.post("search1coeur.php", {search1: ""+inputString+""}, function(data){
			if(data.length >0) {
				$('#suggestions1').show();
				$('#autoSuggestionsList1').html(data);
			}
		});
	}
} // lookup

function lookup_prenomp(inputString) {
	if(inputString.length == 0) {
		// Hide the suggestion box.
		$('#suggestions2').hide();
	} else {
		$.post("search2coeur.php", {search2: ""+inputString+""}, function(data){
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

function lookup_nomf(inputString) {
	if(inputString.length == 0) {
		// Hide the suggestion box.
		$('#suggestions3').hide();
	} else {
		$.post("search3coeur.php", {search3: ""+inputString+""}, function(data){
			if(data.length >0) {
				$('#suggestions3').show();
				$('#autoSuggestionsList3').html(data);
			}
		});
	}
} // lookup

function lookup_prenomf(inputString) {
	if(inputString.length == 0) {
		// Hide the suggestion box.
		$('#suggestions4').hide();
	} else {
		$.post("search4coeur.php", {search4: ""+inputString+""}, function(data){
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