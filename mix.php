<?php

$mdp=htmlspecialchars(addslashes($_GET['m']));
$mdp_real='woop';
include('connect_settings.php');
if($mdp==$mdp_real)
{
	echo 'Connection à la base de données...';
	$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
	$bdd = new PDO('mysql:host='.$hostdb.';dbname='.$namedb, $logindb, $passworddb, $pdo_options);
	echo 'Connecté.<br/>';
	echo 'Initialisation de la boucle fillot...';
	$queryf=$bdd->prepare('SELECT id,prenom,nom,parrain,parraincoeur,fillotv,ecole FROM fillots where fillotv=\'0\' ORDER BY RAND()');
	$queryf->execute();
	$queryp_tsp=$bdd->prepare('SELECT id,fillot,fillotcoeur,parrainv,coeurv,ecole FROM parrains WHERE ecole=\'tsp\' AND parrainv=\'0\' ORDER BY RAND()');
	$queryp_tsp->execute();
	$queryp_tem=$bdd->prepare('SELECT id,fillot,fillotcoeur,parrainv,coeurv,ecole FROM parrains WHERE ecole=\'tem\' AND parrainv=\'0\' ORDER BY RAND()');
	$queryp_tem->execute();
	$queryp_coeur=$bdd->prepare('SELECT id,fillot,fillotcoeur,parrainv,coeurv FROM parrains WHERE coeurv=\'0\' ORDER BY RAND()');
	$queryp_coeur->execute();
	$answerp_tem=$queryp_tem->fetch();
	$answerp_tsp=$queryp_tsp->fetch();
	$answerp_coeur=$queryp_coeur->fetch();
	$flagtem=1;
	$flagtsp=1;
	if($answerf=$queryf->fetch())
	{
		echo 'Initialisé.<br/>';
		do
		{
			$flag1=1;
			while($flag1)
			{
					if(($answerf['ecole']=='tsp')&&($flagtsp))
					{
						$query=$bdd->prepare('UPDATE fillots SET parrain=:r_parrain, fillotv=\'2\' where id=:r_id');
						$query->execute(array('r_parrain' => $answerp_tsp['id'],'r_id' => $answerf['id']));
						$query=$bdd->prepare('UPDATE parrains SET fillot=:r_fillot, parrainv=\'2\' where id=:r_id');
						$query->execute(array('r_fillot' => $answerf['id'],'r_id' => $answerp_tsp['id']));
						$flag1=0;
						if(!$answerp_tsp=$queryp_tsp->fetch()){$flagtsp=0;}
					}
					else if($flagtem)
					{
						$query=$bdd->prepare('UPDATE fillots SET parrain=:r_parrain, fillotv=\'2\' where id=:r_id');
						$query->execute(array('r_parrain' => $answerp_tem['id'],'r_id' => $answerf['id']));
						$query=$bdd->prepare('UPDATE parrains SET fillot=:r_fillot, parrainv=\'2\' where id=:r_id');
						$query->execute(array('r_fillot' => $answerf['id'],'r_id' => $answerp_tem['id']));
						$flag1=0;
						if(!$answerp_tem=$queryp_tem->fetch()){$flagtem=0;}
					}
					else
					{
								$query=$bdd->prepare('UPDATE fillots SET parraincoeur=:r_parrain, coeurv=\'2\' where id=:r_id');
								$query->execute(array('r_parrain' => $answerp_coeur['id'], 'r_id'=>$answerf['id']));
								$query=$bdd->prepare('UPDATE parrains SET fillotcoeur=:r_fillot, coeurv=\'2\' where id=:r_id');
								$query->execute(array('r_fillot' => $answerf['id'], 'r_id'=>$answerp_coeur['id']));
								$flag1=0;
								$answerp_coeur=$queryp_coeur->fetch();
					}
				
				
			}
		}while($answerf=$queryf->fetch());
	}
	echo 'done';
}
