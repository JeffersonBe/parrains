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
        function mail_utf8($to, $subject, $message, $header)
        {
          $header_ = 'MIME-Version: 1.0' . "\r\n" . 'Content-type: text/html; charset=UTF-8' . "\r\n";
          mail($to, '=?UTF-8?B?'.base64_encode($subject).'?=', $message, $header_ . $header);
        }

require_once('recaptchalib.php');
$privatekey = "6LffA9ISAAAAAFwUYq8QA-2uyKLE3I5VGvXHTRrO";
$resp = recaptcha_check_answer ($privatekey,
							$_SERVER["REMOTE_ADDR"],
							$_POST["recaptcha_challenge_field"],
							$_POST["recaptcha_response_field"]);

if (!$resp->is_valid) {
// What happens when the CAPTCHA was entered incorrectly
    die ('
        <div class="six columns centered">
            <div class="alert-box [success alert secondary]">
                Erreur dans le captcha
            <a href="indexcoeur.php" class="close">&times;</a>
            </div>
        </div>
    ');
}

else
{
    include('includes/fonctions.php');

    $email = $_POST['emailFillot'];

    if(!mailValide($email) && !mailINT($email))
    {
    		die("
                <div class=\"six columns centered\">
                    <div class=\"alert-box [success alert secondary]\">
                        L'email du fillot est invalide, merci d\'utiliser un mail au format @telecom-em.eu ou @it-sudparis.eu
                        <a href=\"indexcoeur.php\" class=\"close\">&times;</a>
                    </div
                </div>
    		");
    }
    else
    {
        $emailFillot=$_POST['emailFillot'];
    }

    $email = $_POST['emailParrain'];

    if(!mailValide($email) && !mailINT($email))
    {
    		die("
                <div class=\"six columns centered\">
                    <div class=\"alert-box [success alert secondary\">
                        L'email du Parrain est invalide, merci d\'utiliser un mail au format @telecom-em.eu ou @it-sudparis.eu
                        <a href=\"indexcoeur.php\" class=\"close\">&times;</a>
                    </div
                </div>
    		");
    }
    else
    {
        $emailParrain=$_POST['emailParrain'];
    }

    // Récupération des champs
    $nomFillot=$_POST['nomFillot'];
    $prenomFillot=$_POST['prenomFillot'];
    $statusFillot='fillot';

    $nomParrain=$_POST['nomParrain'];
    $prenomParrain=$_POST['prenomParrain'];
    $statusParrain='parrain';

    // Connexion à la base de données
    include('connect_settings.php');

    $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
    $bdd = new PDO('mysql:host='.$hostdb.';dbname='.$namedb, $logindb, $passworddb, $pdo_options);

    // On regarde dans la base de données si les utilisateurs existent
    $query=$bdd->prepare('SELECT id, nom, prenom, email, status FROM Utilisateur WHERE nom= :rNom AND prenom= :rPrenom; AND email= ;rEmail AND status= :rStatus;');
    $query->execute(
                array(
                'rNom' => $nomParrain,
                'rPrenom' => $prenomParrain,
                'rEmail' => $emailParrain,
                'rStatus' => $statusParrain
                ));
    $flag=0;

    if($answer=$query->fetch())
    {
    	$flag=1;
    	$idParrain=$answer['id'];
    	$nomParrain=$answer['nom'];
    	$prenomParrain=$answer['prenom'];

    	$idfillotpascoeu=$answer['fillot'];
    	$fillotcoeur=$answer['fillotcoeur'];

    	$query=$bdd->prepare('SELECT id, nom, prenom, status FROM  WHERE nom= :rNom AND prenom= :rPrenom; AND status= :rStatus;');
    	$query->execute(
                array(
                'rNom' => $nomParrain,
                'rPrenom' => $prenomParrain,
                'rStatus' => $statusParrain
                ));

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

// L'utilisateur n'existe pas dans notre base de données, on l'ajoute
if($flag==0)
{

}
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
    	$query=$bdd->prepare('UPDATE ParrainnageCoeur SET parraincoeur=:r_parrain, coeurv=\'0\', clefcoeur=:r_clef WHERE id= :r_id;');
    	$query->execute(array('r_id' => $idfillot, 'r_parrain'=>$idparrain,'r_clef'=>$clef));

    	$sujet = "[PARRAINAGE] Confirme que ton parrain de coeur est bien ".$parrain_prenom." ".$parrain_nom."!" ;
    	$headers = 'From: Staff Hypnoz <contact@showtime2012.com>'."\r\n";
    	$message="<img src='http://www.showtime2012.com/parrains/img/logo_hypnoz.jpg' width='395' height='200' style='margin-right:auto;margin-left:auto;text-align:center;'/><br></br><br></br>
    	Salut ".$fillot_prenom." ".$fillot_nom.",<br></br><br></br>Pour confirmer que ton parrain de coeur est bien ".$parrain_prenom." ".$parrain_nom.", clique ici:<br></br>

    	<a href=http://www.showtime2012.com/parrains/confirmationfillotcoeur.php?p=".urlencode($idparrain)."&f=".urlencode($idfillot)."&c=".urlencode($clef).">http://www.showtime2012.com/parrains/confirmationfillotcoeur.php?p=".urlencode($idparrain)."&f=".urlencode($idfillot)."&c=".urlencode($clef)." </a><br></br><br></br>

    	Attention, si tu ne valides pas ton inscription dans les 24h, elle sera effacée.<br></br>

    	<br><br/>Bisous tout partout,<br><br/><br><br/>Le Staff Showtime";

        $find = array('à','â','ä','é','è','ê','ë','î','ï','ç','ù','ü','ô','ö');
        $replace = array('a','a','a','e','e','e','e','i','i','c','u','u','o','o');
    	$emailecole=strtolower($fillot_prenom).'.'.strtolower($fillot_nom)."@it-sudparis.eu";
    	mail_utf8($emailecole, $sujet, $message, $headers) ; // Envoi du mail sur l'adresse de l'école

    	//Update et envoi du mail pour le parrain
    	$clef = md5(microtime(NULL)*100000);
    	$query=$bdd->prepare('UPDATE parrains SET fillotcoeur=:r_fillot, coeurv=\'0\', clefcoeur=:r_clef WHERE id= :r_id;');
    	$query->execute(array('r_id' => $idparrain, 'r_fillot'=>$idfillot,'r_clef'=>$clef));

    	$sujet = "[PARRAINAGE] Confirme que ton fillot de coeur est bien ".$fillot_prenom." ".$fillot_nom."!" ;
    	$headers = 'From: Staff Hypnoz <contact@showtime2012.com>'."\r\n";
    	$message="<img src='http://www.showtime2012.com/parrains/img/logo_hypnoz.jpg' width='395' height='200' style='margin-right:auto;margin-left:auto;text-align:center;'/><br></br><br></br>
    	Salut ".$parrain_prenom." ".$parrain_nom.",<br></br><br></br>Pour confirmer que ton fillot est bien ".$fillot_prenom." ".strtoupper($fillot_nom).", clique ici:<br></br>

    	<a href=http://www.showtime2012.com/parrains/confirmationparraincoeur.php?p=".urlencode($idparrain)."&f=".urlencode($idfillot)."&c=".urlencode($clef).">http://www.showtime2012.com/parrains/confirmationparraincoeur.php?p=".urlencode($idparrain)."&f=".urlencode($idfillot)."&c=".urlencode($clef)." </a><br></br><br></br>

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