﻿<!DOCTYPE html>
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

if (!$resp->is_valid)
{
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

    if($_POST['emailFillot']==$_POST['emailParrain'])
    {
        die('L\'email du parrain est identique à celui du parrain');
    }
    elseif(!mailValide($_POST['emailFillot']) && !mailINT($_POST['emailFillot']))
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
    elseif(!mailValide($_POST['emailParrain']) && !mailINT($_POST['emailParrain']))
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
        $emailFillot=$_POST['emailFillot'];
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

    try
    {
        $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
        $bdd = new PDO('mysql:host='.$hostdb.';dbname='.$namedb, $logindb, $passworddb, $pdo_options);
    }
    catch (Exception $e)
    {
        die('Erreur : ' . $e->getMessage());
    }

    // Initialisation de la clé
    $cle = md5(uniqid(rand(), true));
    $cleFillot = md5(uniqid(rand(), true));
    $cleParrain = md5(uniqid(rand(), true));

    // On regarde si le parrain et le fillot sont déjà actif et validé dans la table parrainage
    $query=$bdd->prepare('SELECT nomFillot, prenomFillot, nomParrain, prenomParrain FROM parrainage WHERE nomFillot= :rNomFillot AND prenomFillot= :rPrenomFillot AND nomParrain= :rNomParrain AND prenomParrain= :rPrenomParrain');
    $query->execute(
        array(
            'rNomFillot' => $nomFillot,
            'rPrenomFillot' => $prenomFillot,
            'rNomParrain' => $nomParrain,
            'rPrenomParrain' => $prenomParrain
        ));

    if($verifP=$query->fetch())
    {
        die('Vous avez déjà fait une demande de parrainage, un parrain ne peut pas être un parrain de cœur');
    }
    $query = $query->closeCursor();

    // On n'a pas trouvé de parrainage
    // On cherche dans la base de données si le parrain existe
    $query=$bdd->prepare('SELECT id, nom, prenom, email, statut FROM user WHERE email = ?');
    $query->execute(array($emailParrain));

    if($answerP=$query->fetch()) // Si le parrain existe
    {

    	if($answerP['statut'] == 'fillot')
    	{
            die('Un parrain ne peut pas être un fillot');
    	}
    	else // On cherche si le fillot existe déjà
    	{
        	$query=$bdd->prepare('SELECT id, nom, prenom, email FROM user WHERE email= ?');
            $query->execute(array($emailFillot));

            if($answerF=$query->fetch()) // Si le Fillot existe
            {

                if($answerF['statut'] == 'parrain')
                {
                    die('Un parrain ne peut pas être un fillot');
                }
                // On regarde si le fillot ou le parrain a déjà un parrainage en cours
                $query=$bdd->prepare('SELECT nomFillot, prenomFillot, nomParrain, prenomParrain FROM parrainage_coeur WHERE nomFillot= :rNomFillot AND prenomFillot= :rPrenomFillot AND actif = :actif OR nomParrain= :rNomParrain AND prenomParrain= :rPrenomParrain AND actif = :actif');
                $query->execute(
                    array(
                    'rNomFillot' => $nomFillot,
                    'rPrenomFillot' => $prenomFillot,
                    'rNomParrain' => $nomParrain,
                    'rPrenomParrain' => $prenomParrain,
                    'actif' => 2
                    ));

                if($answerPc=$query->fetch())
                {
                    $idFillot = $answerF['id'];
                    $idParrain = $answerP['id'];

                    $query=$bdd->prepare('INSERT INTO parrainage_coeur(idFillot, nomFillot, prenomFillot, idParrain, nomParrain, prenomParrain, actif, cle) VALUES(:idFillot, :nomFillot, :prenomFillot, :idParrain, :nomParrain, :prenomParrain, :actif, :cle)');
                    $query->execute(
                            array(
                                'idFillot' => $idFillot,
                                'nomFillot' => $prenomFillot,
                                'prenomFillot' => $prenomFillot,
                                'idParrain' => $idParrain,
                                'nomParrain' => $nomParrain,
                                'prenomParrain' => $prenomParrain,
                                'actif' => 0,
                                'cle' => $cle
                            ));
                }
                else
                {
                    if($answerPc['actif']==0)
                    {
                        die("Vous avez une demande de cœur, mais elle n'est pas validé");
                    }
                    elseif($answerPc['actif']==1)
                    {
                        die("Vous avez une demande de cœur, mais il manque une validation");
                    }
                    elseif($answerPc['actif']==2)
                    {
                        die("Votre demande de cœur est déjà validé");
                    }
                }
            }
            else // Le fillot n'existe pas on le crée
            {
                $query=$bdd->prepare('INSERT INTO user(nom, prenom, email, statut, actif, cle) VALUES (:nom, :prenom, :email, :status, :actif, :cle)');
                $query->execute(
                        array(
                        'nom' => $nomFillot,
                        'prenom' => $prenomFillot,
                        'email' => $emailFillot,
                        'status' => $statusFillot,
                        'actif' => 0,
                        'cle' => $cleFillot
                        ));
                echo("Nous vous avons ajouté en tant que".$statusFillot."");

                // On sélectionne le fillot que l'on vient de créer
                $query=$bdd->prepare('SELECT id, nom, prenom, email FROM user WHERE email = ?');
                $query->execute(array($emailFillot));
                $answerF=$query->fetch();

                $idFillot = $answerF['id'];
                $idParrain = $answerP['id'];

                // On ajoute le parrainage
                $query=$bdd->prepare('INSERT INTO parrainage_coeur(idFillot, nomFillot, prenomFillot, idParrain, nomParrain, prenomParrain, actif, cle) VALUES (:idFillot, :nomFillot, :prenomFillot, :idParrain, :nomParrain, :prenomParrain, :actif, :cle)');
                $query->execute(
                    array(
                    'idFillot' => $idFillot,
                    'nomFillot' => $prenomFillot,
                    'prenomFillot' => $prenomFillot,
                    'idParrain' => $idParrain,
                    'nomParrain' => $nomParrain,
                    'prenomParrain' => $prenomParrain,
                    'actif' => 0,
                    'cle' => $cle
                    ));
            }
    	}
    }
    else // Le parrain n'existe pas dans notre base de données, on l'ajoute
    {
        $query=$bdd->prepare('INSERT INTO user(nom, prenom, email, statut, actif, cle) VALUES(:nom, :prenom, :email, :status, :actif, :cle)');
        $query->execute(
                    array(
                    'nom' => $nomParrain,
                    'prenom' => $prenomParrain,
                    'email' => $emailParrain,
                    'status' => $statusParrain,
                    'actif' => 0,
                    'cle' => $cleParrain
                    ));

        // On vérifie si le fillot existe déjà
        $query=$bdd->prepare('SELECT id, nom, prenom, email FROM user WHERE email = ?');
        $query->execute(array($emailFillot));

        if($answerP = $query->fetch()) // On a crée le parrain et on a le fillot
        {

            // On vérifie si le parrain ou le fillot a déjà un fillot de cœur "validé" dans la table Parrainage coeur
            $query=$bdd->prepare('SELECT nomFillot, prenomFillot, nomParrain, prenomParrain FROM parrainage_coeur WHERE nomFillot= :rNomFillot AND prenomFillot= :rPrenomFillot AND actif = :actif OR nomParrain= :rNomParrain AND prenomParrain= :rPrenomParrain AND actif = :actif');
            $query->execute(
                    array(
                    'rNomFillot' => $nomFillot,
                    'rPrenomFillot' => $prenomFillot,
                    'rNomParrain' => $nomParrain,
                    'rPrenomParrain' => $prenomParrain,
                    'actif' => 2
                    ));
            $verificationC=$query->fetch();

            if($verificationC=$query->fetch())
            {
                die('Vous avez déjà un fillot de cœur et vous ne pouvez avoir qu\'un seul parrain ou fillot de coeur');
            }

            $idFillot = $answerF['id'];
            $idParrain = $answerP['id'];

            $query=$bdd->prepare('INSERT INTO parrainage_coeur(idFillot, nomFillot, prenomFillot, idParrain, nomParrain, prenomParrain, actif, cle) VALUES(:idFillot, :nomFillot, :prenomFillot, :idParrain, :nomParrain, :prenomParrain, :actif, :cle)');
            $query->execute(
                    array(
                    'idFillot' => $idFillot,
                    'nomFillot' => $prenomFillot,
                    'prenomFillot' => $prenomFillot,
                    'idParrain' => $idParrain,
                    'nomParrain' => $nomParrain,
                    'prenomParrain' => $prenomParrain,
                    'actif' => 0,
                    'cle' => $cle
                    ));

        }
        else // On crée le fillot
        {
            $query=$bdd->prepare('INSERT INTO user(nom, prenom, email, statut, actif, cle) VALUES(:nom, :prenom, :email, :status, :actif, :cle)');
            $query->execute(
                        array(
                        'nom' => $nomFillot,
                        'prenom' => $prenomFillot,
                        'email' => $emailFillot,
                        'status' => $statusFillot,
                        'actif' => 0,
                        'cle' => $cleFillot
                        ));

            // On sélectionne le parrain que l'on vient de créer précedemment
            $query=$bdd->prepare('SELECT id, nom, prenom, email FROM user WHERE email = ?');
            $query->execute(array($emailFillot));
            $answerF=$query->fetch();

            // On sélectionne le fillot que l'on vient de créer
            $query=$bdd->prepare('SELECT id, nom, prenom, email FROM user WHERE email = ?');
            $query->execute(array($emailParrain));
            $answerP=$query->fetch();

            $idFillot = $answerF['id'];
            $idParrain = $answerP['id'];

            $query=$bdd->prepare('INSERT INTO parrainage_coeur(idFillot, nomFillot, prenomFillot, idParrain, nomParrain, prenomParrain, actif, cle) VALUES(:idFillot, :nomFillot, :prenomFillot, :idParrain, :nomParrain, :prenomParrain, :actif, :cle)');
            $query->execute(
                    array(
                    'idFillot' => $idFillot,
                    'nomFillot' => $nomFillot,
                    'prenomFillot' => $prenomFillot,
                    'idParrain' => $idParrain,
                    'nomParrain' => $nomParrain,
                    'prenomParrain' => $prenomParrain,
                    'actif' => 0,
                    'cle' => $cle
                    ));
        }
    }
        // On initialise le traitement à faire par confirmation.php
        $idCoeur = '2';

        //Envoi du mail pour le fillot
    	$sujet = "[PARRAINAGE] Confirme que ton parrain de coeur est bien ".$prenomParrain." ".$nomParrain."!" ;
    	$headers = 'From: Staff Showtime <contact@showtime2012.com>'."\r\n";
    	$message="<img src='http://www.showtime2012.com/parrains/img/logo.jpg' width='395' height='200' style='margin-right:auto;margin-left:auto;text-align:center;'/><br></br><br></br>
    	Salut ".$prenomFillot." ".$nomFillot.",<br></br><br></br>Pour confirmer que ton parrain de coeur est bien ".$prenomParrain." ".$nomParrain.", clique ici:<br></br>

    	<a href=http://www.showtime2012.com/parrains/confirmation.php?t=".urlencode($idCoeur)."&?p=".urlencode($idFillot)."&f=".urlencode($idParrain)."&c=".urlencode($cle).">http://www.showtime2012.com/parrains/confirmationfillotcoeur.php?p=".urlencode($idFillot)."&f=".urlencode($idParrain)."&c=".urlencode($cle)." </a><br></br><br></br>

    	Attention, si tu ne valides pas ton inscription dans les 24h, elle sera effacée.<br></br>

    	<br><br/>Bisous tout partout,<br><br/><br><br/>Le Staff Showtime";
    	mail_utf8($emailFillot, $sujet, $message, $headers);

    	//Envoi du mail pour le parrain
    	$sujet = "[PARRAINAGE] Confirme que ton fillot de coeur est bien ".$prenomFillot." ".$nomFillot."!" ;
    	$headers = 'From: Staff Showtime <contact@showtime2012.com>'."\r\n";
    	$message="<img src='http://www.showtime2012.com/parrains/img/logo.jpg' width='395' height='200' style='margin-right:auto;margin-left:auto;text-align:center;'/><br></br><br></br>
    	Salut ".$prenomParrain." ".$nomParrain.",<br></br><br></br>Pour confirmer que ton fillot est bien ".strtoupper($prenomFillot)." ".strtoupper($nomFillot).", clique ici:<br></br>

    	<a href=http://www.showtime2012.com/parrains/confirmationparraincoeur.php?p=".urlencode($answer['id'])."&f=".urlencode($answerF['id'])."&c=".urlencode($cle).">http://www.showtime2012.com/parrains/confirmationparraincoeur.php?p=".urlencode($id)."&f=".urlencode($idfillot)."&c=".urlencode($cle)." </a><br></br><br></br>

    	Attention, si tu ne valides pas ton inscription dans les 24h, elle sera effacée.<br></br>

    	<br><br/>Bisous tout partout,<br><br/><br><br/>Le Staff Showtime";
    	mail_utf8($emailParrain, $sujet, $message, $headers);

    	echo("Félicitation ".$prenomParrain." ".strtoupper($nomParrain)." et ".$prenomFillot." ".strtoupper($nomFillot).", vous avez été enregistrés comme parrain et fillot. Un email va être envoyé sur votre adresse Telecom, n'oubliez pas d'y répondre pour confirmer le parrainage.");
}
?>
  <!-- Included JS Files (Compressed) -->
  <script src="javascripts/jquery.js"></script>
  <script src="javascripts/foundation.min.js"></script>

  <!-- Initialize JS Plugins -->
  <script src="javascripts/app.js"></script>
</body>
</html>