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
  <div class="row">
    <?php
require_once('recaptchalib.php');
$privatekey = "6LffA9ISAAAAAFwUYq8QA-2uyKLE3I5VGvXHTRrO";
$resp = recaptcha_check_answer ($privatekey,
							$_SERVER["REMOTE_ADDR"],
							$_POST["recaptcha_challenge_field"],
							$_POST["recaptcha_response_field"]);

if (!$resp->is_valid) {
//What happens when the CAPTCHA was entered incorrectly
die ("The reCAPTCHA wasn't entered correctly. Go back and try it again." .
         "(reCAPTCHA said: " . $resp->error . ")");
}

else
{
include('connect_settings.php');

function mail_utf8($to, $subject, $message, $header)
{
  $header_ = 'MIME-Version: 1.0' . "\r\n" . 'Content-type: text/html; charset=UTF-8' . "\r\n";
  mail($to, '=?UTF-8?B?'.base64_encode($subject).'?=', $message, $header_ . $header);
}

// Récupération des valeurs du formulaire

$parrainNom=addslashes($_POST['parrain_nom']);
$parrainPrenom=addslashes($_POST['parrain_prenom']);
$parrainEmail=str_replace($find,$replace,strtolower($parrain_prenom)).'.'.str_replace($find,$replace,strtolower($parrain_nom))."@it-sudparis.eu";

$fillotNom=addslashes($_POST['fillot_nom']);
$fillotPrenom=addslashes($_POST['fillot_prenom']);
$fillotEmail=str_replace($find,$replace,strtolower($fillotPrenom)).'.'.str_replace($find,$replace,strtolower($fillotNom))."@it-sudparis.eu";

$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
$bdd = new PDO('mysql:host='.$hostdb.';dbname='.$namedb, $logindb, $passworddb, $pdo_options);
$query=$bdd->prepare('SELECT id,fillot,fillotcoeur,parrainv,ecole FROM parrains WHERE nom= :r_nom AND prenom= :r_prenom;');
$query->execute(array('r_nom' => $parrain_nom, 'r_prenom' => $parrainPrenom));

$flag=0;
    if($answer=$query->fetch())
    {
    	$flag=1;

    	$idparrain=$answer['id'];
    	$ecolep=$answer['ecole'];
    	$parrainv=$answer['parrainv'];
    	$fillotCoeur=$answer['fillotcoeur'];
    	$fillot=$answer['fillot'];
    	$query=$bdd->prepare('SELECT id,parraincoeur,fillotv,ecole,parrain FROM fillots WHERE nom= :r_nom AND prenom= :r_prenom;');
    	$query->execute(array('r_nom' => $fillotNom, 'r_prenom' => $fillotPrenom));

    	if(($answer=$query->fetch())&&($parrainv!=2))
    	{
    		$fillotV=$answer['fillotv'];
    		$ecoleF=$answer['ecole'];
    		$parrainCoeur=$answer['parraincoeur'];

    		if(($fillotv!=2)&&($ecolef==$ecolep))
    		{
    			$flag=2;
    			$idfillot=$answer['id'];
    			$parrain=$answer['parrain'];
    		}
    	}
    }

if($flag==0){
    $query=$bdd->prepare('INSERT INTO parrains(nom, prenom, email) VALUES (:nom, :prenom, :email');
    $query->execute(array(
                'nom' => $parrainNom,
                'prenom' => $parrainPrenom,
                'email' => $parrainEmail
                ));
}

else if ($flag==1){echo "Erreur dans le choix du fillot";}

else if($parraincoeur==$idparrain){echo "Le parrain de coeur doit être différent du parrain.";}

else if($fillotcoeur==$idfillot){echo "Le fillot de coeur doit être différent du fillot.";}

else
{
    if($fillotv==1)
    {
    	$query=$bdd->prepare('UPDATE fillots SET parrain=\'0\', fillotv=\'0\' WHERE id= :r_fillot;');
    	$query->execute(array( 'r_fillot'=>$fillot));

    }

    if($parrainv==1)
    {
    	$query=$bdd->prepare('UPDATE parrains SET parrainv=\'0\', fillot=\'0\' WHERE id= :r_parrain;');
    	$query->execute(array( 'r_parrain'=>$parrain));
    }

    	//Update et envoi du mail pour le fillot
    	$clef = md5(microtime(NULL)*100000);
    	$query=$bdd->prepare('UPDATE fillots SET parrain=:r_parrain, fillotv=\'0\', clef=:r_clef WHERE id= :r_id;');
    	$query->execute(array('r_id' => $idfillot, 'r_parrain'=>$idparrain,'r_clef'=>$clef));

    	$sujet = "[PARRAINAGE] Confirme que ton parrain est bien ".$parrain_prenom." ".$parrain_nom."!" ;
    	$headers = 'From: Staff Hypnoz <contact@hypnoz2011.com>'."\r\n";
    	$message="<img src='http://www.hypnoz2011.com/parrains/img/logo_hypnoz.jpg' width='395' height='200' style='margin-right:auto;margin-left:auto;text-align:center;'/><br></br><br></br>
    	Salut ".$fillot_prenom." ".$fillot_nom.",<br></br><br></br>Pour confirmer que ton parrain est bien ".$parrain_prenom." ".$parrain_nom.", clique ici:<br></br>

    	<a href=http://www.hypnoz2011.com/parrains/confirmationfillot.php?p=".urlencode($idparrain)."&f=".urlencode($idfillot)."&c=".urlencode($clef).">http://www.hypnoz2011.com/parrains/confirmationfillot.php?p=".urlencode($idparrain)."&f=".urlencode($idfillot)."&c=".urlencode($clef)." </a><br></br><br></br>

    	Attention, si tu ne valides pas ton inscription dans les 24h, elle sera effacée.<br></br>

    	<br><br/>Bisous tout partout,<br><br/><br><br/>Le Staff Hypnoz";
    	$find = array('à','â','ä','é','è','ê','ë','î','ï','ç','ù','ü','ô','ö');
    	$replace = array('a','a','a','e','e','e','e','i','i','c','u','u','o','o');
    	$emailecole=str_replace($find,$replace,strtolower($fillot_prenom)).'.'.str_replace($find,$replace,strtolower($fillot_nom))."@it-sudparis.eu";
    	mail_utf8($emailecole, $sujet, $message, $headers) ; // Envoi du mail sur l'adresse de l'école

    	//Update et envoi du mail pour le parrain
    	$clef = md5(microtime(NULL)*100000);
    	$query=$bdd->prepare('UPDATE parrains SET fillot=:r_fillot, parrainv=\'0\', clef=:r_clef WHERE id= :r_id;');
    	$query->execute(array('r_id' => $idparrain, 'r_fillot'=>$idfillot,'r_clef'=>$clef));

    	$sujet = "[PARRAINAGE] Confirme que ton fillot est bien ".$fillot_prenom." ".$fillot_nom."!" ;
    	$headers = 'From: Staff Hypnoz <contact@hypnoz2011.com>'."\r\n";
    	$message="<img src='http://www.hypnoz2011.com/parrains/img/logo_hypnoz.jpg' width='395' height='200' style='margin-right:auto;margin-left:auto;text-align:center;'/><br></br><br></br>
    	Salut ".$parrain_prenom." ".$parrain_nom.",<br></br><br></br>Pour confirmer que ton fillot est bien ".$fillot_prenom." ".strtoupper($fillot_nom).", clique ici:<br></br>

    	<a href=http://www.hypnoz2011.com/parrains/confirmationparrain.php?p=".urlencode($idparrain)."&f=".urlencode($idfillot)."&c=".urlencode($clef).">http://www.hypnoz2011.com/parrains/confirmationparrain.php?p=".urlencode($idparrain)."&f=".urlencode($idfillot)."&c=".urlencode($clef)." </a><br></br><br></br>

    	Attention, si tu ne valides pas ton inscription dans les 24h, elle sera effacée.<br></br>

    	<br><br/>Bisous tout partout,<br><br/><br><br/>Le Staff Hypnoz";
    	$emailecole=str_replace($find,$replace,strtolower($parrain_prenom)).'.'.str_replace($find,$replace,strtolower($parrain_nom))."@it-sudparis.eu";
    	mail_utf8($emailecole, $sujet, $message, $headers) ; // Envoi du mail sur l'adresse de l'école

    		echo "Félicitation ".$parrain_prenom." ".strtoupper($parrain_nom)." et ".$fillot_prenom." ".$fillot_nom.", vous avez été enregistrés comme parrain et fillot. Un email va être envoyé sur votre adresse Telecom, n'oubliez pas d'y répondre pour confirmer le parrainage.";
    }
}
?>
  <!-- Included JS Files (Compressed) -->
  <script src="javascripts/jquery.js"></script>
  <script src="javascripts/foundation.min.js"></script>

  <!-- Initialize JS Plugins -->
  <script src="javascripts/app.js"></script>
</body>
</html>