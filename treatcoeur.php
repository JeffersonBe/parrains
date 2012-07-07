﻿<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
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
<title>Enregistrement du couple parrain de coeur/fillot de coeur...</title>
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
<li class="active"><a href="#">Choisir son parrain</a></li>
<li><a href="indexcoeur.php">Choisir son parrain de coeur</a></li>
</ul>
</div>
<div id='content'>

<?php 

function mail_utf8($to, $subject, $message, $header) 
{
  $header_ = 'MIME-Version: 1.0' . "\r\n" . 'Content-type: text/html; charset=UTF-8' . "\r\n";
  mail($to, '=?UTF-8?B?'.base64_encode($subject).'?=', $message, $header_ . $header);
}

require_once('recaptchalib.php');
$privatekey = "6LcqnMgSAAAAAIZ7gLOAGZN12XB4KDSK8w6Ki5ch ";
$resp = recaptcha_check_answer ($privatekey,
							$_SERVER["REMOTE_ADDR"],
							$_POST["recaptcha_challenge_field"],
							$_POST["recaptcha_response_field"]);

if (!$resp->is_valid) {
// What happens when the CAPTCHA was entered incorrectly
die ("Erreur dans la saisie du captcha, veuillez réessayer.");
}
 else
{



$parrain_nom=addslashes($_POST['parrain_nom']);
$parrain_prenom=addslashes($_POST['parrain_prenom']);
$fillot_nom=addslashes($_POST['fillot_nom']);
$fillot_prenom=addslashes($_POST['fillot_prenom']);
include('connect_settings.php');

$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
$bdd = new PDO('mysql:host='.$hostdb.';dbname='.$namedb, $logindb, $passworddb, $pdo_options);

$query=$bdd->prepare('SELECT id,coeurv,fillot,fillotcoeur FROM parrains WHERE nom= :r_nom AND prenom= :r_prenom;');
$query->execute(array('r_nom' => $parrain_nom, 'r_prenom' => $parrain_prenom));
$flag=0;
if($answer=$query->fetch())
{
	$flag=1;
	$idparrain=$answer['id'];
	$parrainv=$answer['coeurv'];
	$idfillotpascoeur=$answer['fillot'];
	$fillotcoeur=$answer['fillotcoeur'];
	$query=$bdd->prepare('SELECT id,coeurv,parrain,parraincoeur FROM fillots WHERE nom= :r_nom AND prenom= :r_prenom;');
	$query->execute(array('r_nom' => $fillot_nom, 'r_prenom' => $fillot_prenom));
	if(($answer=$query->fetch())&&($parrainv!=2))
	{
		$fillotv=$answer['coeurv'];
		$idparrainpascoeur=$answer['parrain'];
		if($fillotv!=2)
		{
			$flag=2;
			$idfillot=$answer['id'];
			$parraincoeur=$answer['parraincoeur'];
		}
	}
}

if($flag==0){echo "Erreur dans le choix du parrain de coeur";}
else if ($flag==1){echo "Erreur dans le choix du fillot de coeur";}
else if($idparrainpascoeur==$idparrain){echo "Le parrain de coeur doit être différent du parrain.";}
else if($idfillotpascoeur==$idfillot){echo "Le fillot de coeur doit être différent du fillot.";}
else
{
if($fillotcoeurv==1)
{
	$query=$bdd->prepare('UPDATE fillots SET parraincoeur=\'0\', coeurv=\'0\' WHERE id= :r_fillot;');
	$query->execute(array( 'r_fillot'=>$fillotcoeur));

}

if($parraincoeurv==1)
{
	$query=$bdd->prepare('UPDATE parrains SET fillotcoeur=\'0\', coeurv=\'0\' WHERE id= :r_parrain;');
	$query->execute(array( 'r_parrain'=>$parraincoeur));
}


	//Update et envoi du mail pour le fillot
		$clef = md5(microtime(NULL)*100000);
	$query=$bdd->prepare('UPDATE fillots SET parraincoeur=:r_parrain, coeurv=\'0\', clefcoeur=:r_clef WHERE id= :r_id;');
	$query->execute(array('r_id' => $idfillot, 'r_parrain'=>$idparrain,'r_clef'=>$clef));
	
	

	$sujet = "[PARRAINAGE] Confirme que ton parrain de coeur est bien ".$parrain_prenom." ".$parrain_nom."!" ;
	$headers = 'From: Staff Hypnoz <contact@hypnoz2011.com>'."\r\n";
	$message="<img src='http://www.hypnoz2011.com/parrains/img/logo_hypnoz.jpg' width='395' height='200' style='margin-right:auto;margin-left:auto;text-align:center;'/><br></br><br></br>
	Salut ".$fillot_prenom." ".$fillot_nom.",<br></br><br></br>Pour confirmer que ton parrain de coeur est bien ".$parrain_prenom." ".$parrain_nom.", clique ici:<br></br>

	<a href=http://www.hypnoz2011.com/parrains/confirmationfillotcoeur.php?p=".urlencode($idparrain)."&f=".urlencode($idfillot)."&c=".urlencode($clef).">http://www.hypnoz2011.com/parrains/confirmationfillotcoeur.php?p=".urlencode($idparrain)."&f=".urlencode($idfillot)."&c=".urlencode($clef)." </a><br></br><br></br>

	Attention, si tu ne valides pas ton inscription dans les 24h, elle sera effacée.<br></br>

	<br><br/>Bisous tout partout,<br><br/><br><br/>Le Staff Hypnoz";
	
$find = array('à','â','ä','é','è','ê','ë','î','ï','ç','ù','ü','ô','ö');
$replace = array('a','a','a','e','e','e','e','i','i','c','u','u','o','o');
	$emailecole=strtolower($fillot_prenom).'.'.strtolower($fillot_nom)."@it-sudparis.eu";
	mail_utf8($emailecole, $sujet, $message, $headers) ; // Envoi du mail sur l'adresse de l'école	
	
	//Update et envoi du mail pour le parrain
	$clef = md5(microtime(NULL)*100000);
	$query=$bdd->prepare('UPDATE parrains SET fillotcoeur=:r_fillot, coeurv=\'0\', clefcoeur=:r_clef WHERE id= :r_id;');
	$query->execute(array('r_id' => $idparrain, 'r_fillot'=>$idfillot,'r_clef'=>$clef));


	$sujet = "[PARRAINAGE] Confirme que ton fillot de coeur est bien ".$fillot_prenom." ".$fillot_nom."!" ;
	$headers = 'From: Staff Hypnoz <contact@hypnoz2011.com>'."\r\n";
	$message="<img src='http://www.hypnoz2011.com/parrains/img/logo_hypnoz.jpg' width='395' height='200' style='margin-right:auto;margin-left:auto;text-align:center;'/><br></br><br></br>
	Salut ".$parrain_prenom." ".$parrain_nom.",<br></br><br></br>Pour confirmer que ton fillot est bien ".$fillot_prenom." ".strtoupper($fillot_nom).", clique ici:<br></br>

	<a href=http://www.hypnoz2011.com/parrains/confirmationparraincoeur.php?p=".urlencode($idparrain)."&f=".urlencode($idfillot)."&c=".urlencode($clef).">http://www.hypnoz2011.com/parrains/confirmationparraincoeur.php?p=".urlencode($idparrain)."&f=".urlencode($idfillot)."&c=".urlencode($clef)." </a><br></br><br></br>

	Attention, si tu ne valides pas ton inscription dans les 24h, elle sera effacée.<br></br>

	<br><br/>Bisous tout partout,<br><br/><br><br/>Le Staff Hypnoz";
	$emailecole=str_replace($find,$replace,strtolower($parrain_prenom)).'.'.str_replace($find,$replace,strtolower($parrain_nom))."@it-sudparis.eu";
	mail_utf8($emailecole, $sujet, $message, $headers) ; // Envoi du mail sur l'adresse de l'école

		echo "Félicitation ".$parrain_prenom." ".strtoupper($parrain_nom)." et ".$fillot_prenom." ".$fillot_nom.", vous avez été enregistrés comme parrain et fillot. Un email va être envoyé sur votre adresse Telecom, n'oubliez pas d'y répondre pour confirmer le parrainage.";
		
}
}

?>


</div>
</div>
</div>


</body>
</html>