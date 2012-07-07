<?php
include('connect_settings.php');

$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
$bdd = new PDO('mysql:host='.$hostdb.';dbname='.$namedb, $logindb, $passworddb, $pdo_options);

if(!$bdd)
{
    // Show error if we cannot connect.
    echo 'ERROR: Could not connect to the database.';
}
else 
{
	$ecole='no';
    if((isset($_POST['search1']))&&(isset($_POST['search2'])))
	{
		if((!empty($_POST['search1']))&&(!empty($_POST['search2'])))
		{
			$nom = addslashes($_POST['search1']);
			$prenom = addslashes($_POST['search2']);
			$query=$bdd->prepare('SELECT ecole FROM parrains WHERE nom=:r_nom AND prenom=:r_prenom;');
			$query->execute(array('r_nom' => $nom,'r_prenom' => $prenom));
			if($answer=$query->fetch()){$ecole=$answer['ecole'];}
		}
	}

    // Is there a posted query string?
    if(isset($_POST['search4']))
	{
        $search = addslashes($_POST['search4']);
        // Is the string length greater than 0?
        if(strlen($search) >0) 
		{
			// Run the query: We use LIKE '$queryString%'
			// The percentage sign is a wild-card, in my example of countries it works like this...
			// $queryString = 'Uni';
			// Returned data = 'United States, United Kindom';
		
		
			if($ecole=='no')
			{
				$query=$bdd->prepare('SELECT nom,prenom FROM fillots WHERE fillotv<>\'2\' AND prenom LIKE :r_search LIMIT 5;');
				$query->execute(array('r_search' => $search.'%'));
			}
			else
			{
				$query=$bdd->prepare('SELECT nom,prenom FROM fillots WHERE fillotv<>\'2\' AND prenom LIKE :r_search AND ecole=:r_ecole LIMIT 5;');
				$query->execute(array('r_search' => $search.'%','r_ecole'=>$ecole));
			}
			
			while($answer=$query->fetch())
			{
				// While there are results loop through them - fetching an Object (i like PHP5 btw!).
                // Format the results, im using <li> for the list, you can change it.
                // The onClick function fills the textbox with the result.
                echo utf8_encode('</li><li onclick="fill2(\''.strtoupper($answer['nom']).'\',\''.strtolower($answer['prenom']).'\',4);">'.strtolower($answer['prenom']).' '.strtoupper($answer['nom']).'</li>');
            }
        }
		else 
		{
            echo 'Oops: pépin!.';
        }
    } 
	else 
	{
        // Dont do anything.
    } // There is a queryString.
}
 
?>