<!DOCTYPE html>

<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
  <meta charset="utf-8" />

  <!-- Set the viewport width to device width for mobile -->
  <meta name="viewport" content="width=device-width" />

  <title>Welcome to Foundation</title>

  <!-- Included CSS Files (Uncompressed) -->
  <!--
  <link rel="stylesheet" href="stylesheets/foundation.css">
  -->

  <!-- Included CSS Files (Compressed) -->
  <link rel="stylesheet" href="stylesheets/foundation.min.css">
  <link rel="stylesheet" href="stylesheets/app.css">

  <script src="javascripts/modernizr.foundation.js"></script>

  <!-- IE Fix for HTML5 Tags -->
  <!--[if lt IE 9]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->

</head>
<body>
<?php
include('connect_settings.php');
$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
$bdd = new PDO('mysql:host='.$hostdb.';dbname='.$namedb, $logindb, $passworddb, $pdo_options);

if((!empty($_GET['p']))&&(!empty($_GET['f']))&&(!empty($_GET['c'])))
{
	$clef=addslashes($_GET['c']);
	$idparrain=addslashes($_GET['p']);
	$idfillot=addslashes($_GET['f']);

	$query=$bdd->prepare('SELECT clef,fillotv FROM fillots WHERE id=:r_idfillot AND parrain=:r_idparrain;');
	$answer=$query->execute(array('r_idfillot'=>$idfillot, 'r_idparrain'=>$idparrain));
	if($answer=$query->fetch())
	{
		$bdd_clef=$answer['clef'];
		$fillotv=$answer['fillotv'];
		if($fillotv==0)
		{
			if($bdd_clef==$clef)
			{

				$query=$bdd->prepare('SELECT parrainv FROM parrains WHERE fillot=:r_idfillot AND id=:r_idparrain;');
				$answer=$query->execute(array('r_idfillot'=>$idfillot, 'r_idparrain'=>$idparrain));
				if($answer=$query->fetch())
				{
					$parrainv=$answer['parrainv'];
					if($parrainv==0)
					{
						$fillotv=1;

						$query=$bdd->prepare('UPDATE fillots SET fillotv= :r_fillotv WHERE id=:r_idfillot AND parrain=:r_idparrain;');
						if($answer=$query->execute(array('r_idfillot'=>$idfillot, 'r_idparrain'=>$idparrain,'r_fillotv'=>$fillotv)))
						{
							echo "Ton parrainage a bien été confirmé. Il ne reste plus qu'à attendre que ton parrain confirme.";
							$query->closeCursor();
						}
						else
						{
							echo 'Probleme de mise à jour de la base de donnée.';
						}
					}
					else if($parrainv==1)
					{
						$fillotv=2;

						$query=$bdd->prepare('UPDATE fillots SET fillotv= :r_fillotv WHERE id=:r_idfillot AND parrain=:r_idparrain;');

							if($answer=$query->execute(array('r_idfillot'=>$idfillot, 'r_idparrain'=>$idparrain,'r_fillotv'=>$fillotv)))
							{
								$query=$bdd->prepare('UPDATE parrains SET parrainv= :r_fillotv WHERE fillot=:r_idfillot AND id=:r_idparrain;');
								if($answer=$query->execute(array('r_idfillot'=>$idfillot, 'r_idparrain'=>$idparrain,'r_fillotv'=>$fillotv)))
								{

									echo "Ton parrainage a bien été confirmé ;-) ";
									$query->closeCursor();
								}
								else
								{
									echo 'Probleme de mise à jour de la base de donnée.';
								}
							}
							else
							{
								echo 'Parrainage déjà confirmé.';
							}
					}
					else
					{
						echo 'Probleme de mise à jour de la base de donnée.';
					}


				}
				else
				{
				echo 'Erreur concernant la base de donnée.';
				}

			}
			else
			{
				echo "La clef fournie n'est pas valide";
			}
		}
		else if($fillotv==1)
		{
			echo "Tu as déjà validé le parrainage. Il ne reste plus qu'au parrain de valider.";
		}
		else
		{
			echo "Le parrainage a déjà été totalement validé.";
		}
	}
	else
	{
		echo "Problème de connexion à la base de donnée.";
	}

}
else
{
	echo "Erreur. Ton parrainage n'a pas été confirmé.";

}

?>
  <!-- Included JS Files (Compressed) -->
  <script src="javascripts/jquery.js"></script>
  <script src="javascripts/foundation.min.js"></script>

  <!-- Initialize JS Plugins -->
  <script src="javascripts/app.js"></script>
</body>
</html>