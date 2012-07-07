<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="Identifier-URL" content=""/>
<meta name="language" content="fr"/>
<meta name="location" content="France"/>
<meta name="Author" content="Pierre-Edouard MONTABRUN"/>
<meta name="Description" content="Choisis ton parrain pour la soirée de parrainage I shot the sherif."/>
<meta name="keywords" content="Parrainage parrain soirée I shot the sherif 2011 Télécom SudParis Télécom Ecole de Management Chuck Norris"/>
<meta name="htdig-keywords" content=""/>
<meta name="subject" content=""/>
<meta name="Date-Creation-yyyymmdd" content="20110930"/>
<meta name="Audience" content="all"/>
<link rel="stylesheet" media="screen" type="text/css" href="style.css" />
<title>Confirmation de parrainage...</title>
<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript" src="com.js"></script>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-20811018-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

 var RecaptchaOptions = {
    theme : 'blackglass',
	lang: 'fr'
 };
 </script>
 
 <!--[if IE]>
		<style type="text/css">
			#menu {
				-ms-filter: "progid:DXImageTransform.Microsoft.Shadow(color=#212121,direction=180,strength=0)";
				filter: progid:DXImageTransform.Microsoft.Shadow(color=#212121,direction=180,strength=0);
			}
			#menu ul li a {
				-ms-filter:  "progid:DXImageTransform.Microsoft.Blur()";
				filter:  progid:DXImageTransform.Microsoft.Blur();
			#menu {
	                -ms-filter: "progid:DXImageTransform.Microsoft.Shadow(color=#212121,direction=180,strength=0)"; /* IE 8 */
	                filter: progid:DXImageTransform.Microsoft.Shadow(color=#212121,direction=180,strength=0); /* IE 7- */
	            }
			}
		</style>
	<![endif]-->

</head>
<body>
<div id='full'>
<div id='bg'>
<div id='menu'>
<ul>
<li class="active"><a href="index.php">Choisir son parrain</a></li>
<li><a href="indexcoeur.php">Choisir son parrain de coeur</a></li>
</ul>
</div>
<div id='content'>

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


</div>
</div>
</div>


</body>
</html>