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
	$query=$bdd->prepare('SELECT fillots.nom AS nom_fillot, fillots.prenom AS prenom_fillot,p.nom AS nom_parrain, p.prenom AS prenom_parrain 
FROM fillots,parrains p, parrains pc 
WHERE fillots.id=p.fillot AND p.parrainv<>\'0\' AND fillots.fillotv<>\'0\'
UNION
SELECT fillots.nom AS nom_fillot, fillots.prenom AS prenom_fillot,p.nom AS parrain_nom, p.prenom AS parrain_prenom 
FROM fillots,parrains p
WHERE fillots.id=p.fillotcoeur AND p.coeurv<>\'0\' AND fillots.coeurv<>\'0\'
ORDER BY nom_fillot');
$query->execute();
$flag=1;
$flag2=1;
$i=1;
?>
<table>
<tr>
<th>Fillot</th>
<th>Parrain</th>
<th>Parrain de coeur</th>
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
	$nom=$answer['nom_fillot'];
	$prenom=$answer['prenom_fillot'];
	if($flag)
	{
		echo '<td>'.$answer['nom_fillot'].' '.$answer['prenom_fillot'].'</td><td>'.$answer['nom_parrain'].' '.$answer['prenom_parrain'].'</td>';
		$i++;
		if($answer=$query->fetch())
		{
			if(($answer['nom_fillot']==$nom)&&($answer['prenom_fillot']==$prenom))
			{
				echo '<td>'.$answer['nom_parrain'].' '.$answer['prenom_parrain'].'</td></tr>';
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


