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
  <?php include('includes/head.php'); ?>
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
                        L'email du fillot est invalide, merci d'utiliser un mail au format @telecom-em.eu ou @it-sudparis.eu
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
                        L'email du Parrain est invalide, merci d'utiliser un mail au format @telecom-em.eu ou @it-sudparis.eu
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
        	$bdd = new PDO('mysql:host='.$hostdb.';dbname='.$namedb, $logindb, $passworddb, $pdo_options) or die('Il y a un problème de connexion à la base de données');
        }
        catch (Exception $e)
        {
        	die('Erreur : ' . $e->getMessage());
        }

    // Initialisation de la clé pour la confirmation de parrainage
    $cleF = md5(uniqid(rand(), true));
    $cleP = md5(uniqid(rand(), true));

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
        die('
            <div class="alert-box [alert]">
                Vous avez déjà fait une demande de parrainage, un parrain ne peut pas être un parrain de cœur
                <a href="/indexcoeur.php" class="close">&times;</a>
            </div>
        ');
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
            die('
            <div class="alert-box [alert]">
                Un parrain ne peut pas être un fillot
                <a href="/indexcoeur.php" class="close">&times;</a>
            </div>
            ');
    	}
    	else // On cherche si le fillot existe déjà
    	{
        	$query=$bdd->prepare('SELECT id, nom, prenom, email FROM user WHERE email= ?');
            $query->execute(array($emailFillot));

            if($answerF=$query->fetch()) // Si le Fillot existe
            {

                if($answerF['statut'] == 'parrain')
                {
                    die('
                        <div class="alert-box [alert]">
                            Un parrain ne peut pas être un fillot
                            <a href="/indexcoeur.php" class="close">&times;</a>
                        </div>
                    ');
                }
                else
                {
                    $idFillot = $answerF['id'];
                    $idParrain = $answerP['id'];

                    $query=$bdd->prepare('INSERT INTO parrainage_coeur(idFillot, nomFillot, prenomFillot, idParrain, nomParrain, prenomParrain, actifF, actifP, cleF, cleP) VALUES(:idFillot, :nomFillot, :prenomFillot, :idParrain, :nomParrain, :prenomParrain, :actifF, :actifP, :cleF, :cleP)');
                    $query->execute(
                            array(
                                'idFillot' => $idFillot,
                                'nomFillot' => $prenomFillot,
                                'prenomFillot' => $prenomFillot,
                                'idParrain' => $idParrain,
                                'nomParrain' => $nomParrain,
                                'prenomParrain' => $prenomParrain,
                                'actifF' => 0,
                                'actifP' => 0,
                                'cleF' => $cleF,
                                'cleP' => $cleP
                            ));
                }

            }
            else // Le fillot n'existe pas on le crée
            {
                $query=$bdd->prepare('INSERT INTO user(nom, prenom, email, statut) VALUES (:nom, :prenom, :email, :status)');
                $query->execute(
                        array(
                        'nom' => $nomFillot,
                        'prenom' => $prenomFillot,
                        'email' => $emailFillot,
                        'status' => $statusFillot
                        ));
                echo("Nous vous avons ajouté en tant que".$statusFillot."");

                // On sélectionne le fillot que l'on vient de créer
                $query=$bdd->prepare('SELECT id, nom, prenom, email FROM user WHERE email = ?');
                $query->execute(array($emailFillot));
                $answerF=$query->fetch();

                $idFillot = $answerF['id'];
                $idParrain = $answerP['id'];

                // On ajoute le parrainage
                $query=$bdd->prepare('INSERT INTO parrainage_coeur(idFillot, nomFillot, prenomFillot, idParrain, nomParrain, prenomParrain, actifF, actifP, cleF, cleP) VALUES (:idFillot, :nomFillot, :prenomFillot, :idParrain, :nomParrain, :prenomParrain, :actifF, :actifP, :cleF, :clefP)');
                $query->execute(
                    array(
                    'idFillot' => $idFillot,
                    'nomFillot' => $prenomFillot,
                    'prenomFillot' => $prenomFillot,
                    'idParrain' => $idParrain,
                    'nomParrain' => $nomParrain,
                    'prenomParrain' => $prenomParrain,
                    'actifF' => 0,
                    'actifP' => 0,
                    'cleF' => $cleF,
                    'cleP' => $cleP
                    ));
            }
    	}
    }
    else // Le parrain n'existe pas dans notre base de données, on l'ajoute
    {
        $query=$bdd->prepare('INSERT INTO user(nom, prenom, email, statut) VALUES(:nom, :prenom, :email, :status)');
        $query->execute(
                    array(
                    'nom' => $nomParrain,
                    'prenom' => $prenomParrain,
                    'email' => $emailParrain,
                    'status' => $statusParrain
                    ));

        // On vérifie si le fillot existe déjà
        $query=$bdd->prepare('SELECT id, nom, prenom, email FROM user WHERE email = ?');
        $query->execute(array($emailFillot));

        if($answerF = $query->fetch()) // On a crée le parrain et on a le fillot
        {
            $idFillot = $answerF['id'];
            $idParrain = $answerP['id'];

            $query=$bdd->prepare('INSERT INTO parrainage_coeur(idFillot, nomFillot, prenomFillot, idParrain, nomParrain, prenomParrain, actifF, actifP, cleF, cleP) VALUES(:idFillot, :nomFillot, :prenomFillot, :idParrain, :nomParrain, :prenomParrain, :actifF, :actifP, :cleF, :cleP)');
            $query->execute(
                    array(
                    'idFillot' => $idFillot,
                    'nomFillot' => $prenomFillot,
                    'prenomFillot' => $prenomFillot,
                    'idParrain' => $idParrain,
                    'nomParrain' => $nomParrain,
                    'prenomParrain' => $prenomParrain,
                    'actifF' => 0,
                    'actifP' => 0,
                    'cleF' => $cleF,
                    'cleP' => $cleP
                    ));

        }
        else // On crée le fillot
        {
            $query=$bdd->prepare('INSERT INTO user(nom, prenom, email, statut) VALUES(:nom, :prenom, :email, :status)');
            $query->execute(
                        array(
                        'nom' => $nomFillot,
                        'prenom' => $prenomFillot,
                        'email' => $emailFillot,
                        'status' => $statusFillot
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

            $query=$bdd->prepare('INSERT INTO parrainage_coeur(idFillot, nomFillot, prenomFillot, idParrain, nomParrain, prenomParrain, actifF, actifP, cleF, cleP) VALUES(:idFillot, :nomFillot, :prenomFillot, :idParrain, :nomParrain, :prenomParrain, :actifF, :actifP, :cleF, :cleP)');
            $query->execute(
                    array(
                    'idFillot' => $idFillot,
                    'nomFillot' => $nomFillot,
                    'prenomFillot' => $prenomFillot,
                    'idParrain' => $idParrain,
                    'nomParrain' => $nomParrain,
                    'prenomParrain' => $prenomParrain,
                    'actifF' => 0,
                    'actifP' =>0,
                    'cleF' => $cleF,
                    'cleP' => $cleP
                    ));
        }
    }
        // On initialise le traitement à faire par confirmation.php
        $idCoeur = '2';

        //Envoi du mail pour le fillot
    	$sujet = "[PARRAINAGE] Confirme que ton parrain de coeur est bien ".$prenomParrain." ".$nomParrain."!" ;
    	$headers .= 'MIME-Version: 1.0' . "\r\n" . 'Content-type: text/html; charset=UTF-8' . "\r\n";
    	$headers .= "Reply-To: Staff Showtime <contact@showtime2012.fr>\r\n";
        $headers .= "Return-Path: Staff Showtime <contact@showtime2012.fr>\r\n";
        $headers .= "From: Staff Showtime <contact@showtime2012.fr>\r\n";
        $headers .= "Organization: Showtime BDE TMSP\r\n";
    	$message="
    	<p>Salut ".$prenomFillot." ".$nomFillot.",</p>
    	<p>Pour confirmer que ton parrain de coeur est bien ".$prenomParrain." ".$nomParrain.", clique ici:</p>
    	<a href=http://www.showtime2012.com/parrains/confirmation-fillot.php?t=".$idCoeur."&p=".$idParrain."&f=".$idFillot."&c=".$cleF.">http://www.showtime2012.com/parrains/confirmation-fillot.php?t=".$idCoeur."&p=".$idParrain."&f=".$idFillot."&c=".$cleF."</a>
    	<h3>Le Staff Showtime</h3>";
    	mail($emailFillot, $sujet, $message, $headers);

    	//Envoi du mail pour le parrain
    	$sujet = "[PARRAINAGE] Confirme que ton fillot de coeur est bien ".$prenomFillot." ".$nomFillot."!" ;
    	$headers .= 'MIME-Version: 1.0' . "\r\n" . 'Content-type: text/html; charset=UTF-8' . "\r\n";
    	$headers .= "Reply-To: Staff Showtime <contact@showtime2012.fr>\r\n";
        $headers .= "Return-Path: Staff Showtime <contact@showtime2012.fr>\r\n";
        $headers .= "From: Staff Showtime <contact@showtime2012.fr>\r\n";
        $headers .= "Organization: Showtime BDE TMSP\r\n";
    	$message="
    	<p>Salut ".$prenomParrain." ".$nomParrain.",</p>
    	<p>Pour confirmer que ton fillot est bien ".strtoupper($prenomFillot)." ".strtoupper($nomFillot).", clique ici:</p>
    	<a href=http://www.showtime2012.com/parrains/confirmation-parrain.php?t=".$idCoeur."&p=".$idParrain."&f=".$idFillot."&c=".$cleP.">http://www.showtime2012.com/parrains/confirmation-parrain.php?t=".$idCoeur."&p=".$idParrain."&f=".$idFillot."&c=".$cleP." </a>
    	<h3>Le Staff Showtime<h3>";
    	mail($emailParrain, $sujet, $message, $headers);

    	echo('<div class="alert-box success">
  					Félicitation '.$prenomParrain.' '.strtoupper($nomParrain).' et '.$prenomFillot.' '.strtoupper($nomFillot).', vous avez été enregistrés comme parrain et fillot de coeur. Un email va être envoyé sur votre adresse Telecom, n\'oubliez pas d\'y répondre pour confirmer le parrainage.
  					<a href="index.php" class="close">×</a>
  					</div>
  		');
}
?>
  <!-- Included JS Files (Compressed) -->
  <script src="javascripts/jquery.js"></script>
  <script src="javascripts/foundation.min.js"></script>

  <!-- Initialize JS Plugins -->
  <script src="javascripts/app.js"></script>
</body>
</html>