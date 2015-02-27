<?php
$mdp='plop';
if(isset($_GET['m'])){$mdp=htmlspecialchars(addslashes($_GET['m']));}
$mdp_real='woop';
include('connect_settings.php');
if($mdp==$mdp_real)
{
	echo 'Connection à la base de données...';
	$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
	$bdd = new PDO('mysql:host='.$hostdb.';dbname='.$namedb, $logindb, $passworddb, $pdo_options);
	echo 'Connecté.<br/>';
	$query=$bdd->prepare('SELECT parrains.nom AS nom_parrain, parrains.prenom AS prenom_parrain,f.nom AS nom_fillot, f.prenom AS prenom_fillot 
FROM parrains,fillots f
WHERE parrains.id=f.parrain AND f.fillotv<>\'0\' AND parrains.parrainv<>\'0\'
UNION
SELECT parrains.nom AS nom_parrain, parrains.prenom AS prenom_parrain,f.nom AS fillot_nom, f.prenom AS fillot_prenom 
FROM parrains,fillots f
WHERE parrains.id=f.parraincoeur AND f.coeurv<>\'0\' AND parrains.coeurv<>\'0\'
ORDER BY nom_parrain');
$query->execute();
$flag=1;
$flag2=1;
$i=1;
?>
<table>
<tr>
<th>Parrain</th>
<th>Fillot</th>
<th>Fillot de coeur</th>
</tr>
<?php
while($flag)
{
	if($flag2)
	{
		if(!$answer=$query->fetch())
		{
			$flag2=0;
			$flag=0;
		}
	}
	$nom=$answer['nom_parrain'];
	$prenom=$answer['prenom_parrain'];
	if($flag)
	{
		echo '<td>'.$answer['nom_parrain'].' '.$answer['prenom_parrain'].'</td><td>'.$answer['nom_fillot'].' '.$answer['prenom_fillot'].'</td>';
		$i++;
		if($answer=$query->fetch())
		{
			if(($answer['nom_parrain']==$nom)&&($answer['prenom_parrain']==$prenom))
			{
				echo '<td>'.$answer['nom_fillot'].' '.$answer['prenom_fillot'].'</td></tr>';
				$flag2=1;
			}
			else
			{
				echo '</tr>';
				$flag2=0;
			}
		}
		else
		{
			$flag=0;
			$flag2=0;
		}
	}
}
echo $i;
}
?>


