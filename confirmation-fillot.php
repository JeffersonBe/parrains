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

  <title>Page de confirmation du Parrainage - Showtime BDE 2012</title>
  <?php include('includes/head.php'); ?>
</head>
<body>
  <div class="row">
    <?php
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

        // On récupère le traitement à effectuer (1 pour parrainage et 2 pour parrainage coeur
        if(!empty($_GET['t']))
        {
            $idCoeur = $_GET['t'];
        }

        // On effectue le parrainage
        if($idCoeur==1)
        {
            if((!empty($_GET['p']))&&(!empty($_GET['f']))&&(!empty($_GET['c'])))
            {
                $cle=$_GET['c'];
                $idParrain=$_GET['p'];
                $idFillot=$_GET['f'];

                // On regarde si la clé, l'id du parrain et du fillot correspondent
                $query=$bdd->prepare('SELECT actifF, actifP, cleF FROM parrainage WHERE idFillot=:rIdFillot AND idParrain=:rIdParrain');
                $answer=$query->execute(array('rIdFillot'=>$idFillot, 'rIdParrain'=>$idParrain));

                if($answer=$query->fetch())
                {
                    $actifP=$answer['actifP'];
                    $actifF=$answer['actifF'];
                    $cleB=$answer['cleF'];

                    // On verifie le status du parrainage
                    if($actifP==0&&$actifF==0)
                    {
                        if($cleB==$cle)
                        {
                            // Le parrainage n'est pas encore validé
                            if($actifF==0)
                            {
                                $actif=1;
                                $query=$bdd->prepare('UPDATE parrainage SET actifF= :rActif WHERE idFillot=:rIdFillot AND idParrain= :rIdParrain');
                                if($answer=$query->execute(array('rActif'=>$actif, 'rIdParrain'=>$idParrain,'rIdFillot'=>$idFillot)))
                                {
                                    echo '
                                        <div class="alert-box [success]">
                                            Ton parrainage a bien été confirmé ;-)
                                        <a href="index.php" class="close">×</a>
                                        </div>
                                        ';
                                    $query->closeCursor();
                                }
                                else
                                {
                                    echo '
                                        <div class="alert-box [alert]">
                                            Probleme de mise à jour de la base de donnée.
                                            <a href="index.php" class="close">&times;</a>
                                        </div>
                                    ';
                                }
                            }
                            else
                            {
                                echo 'Probleme de mise à jour de la base de donnée.
                                    <div class="alert-box [alert]">
                                        Probleme de mise à jour de la base de donnée.
                                        <a href="index.php" class="close">&times;</a>
                                    </div>
                                ';
                            }
                        }
                        else
                        {
                            echo '
                            <div class="alert-box [alert]">
                                La clef fournie n\'est pas valide
                                <a href="index.php" class="close">&times;</a>
                            </div>
                            ';
                        }
                    }
                    elseif($actifP==0&&$actifF==1)
                    {
                        if($cleBdd==$cle)
                        {
                            $actif=1;
                            $query=$bdd->prepare('UPDATE parrainage SET actifF= :rActif WHERE idFillot=:rIdFillot AND idParrain= :rIdParrain');
                            if($answer=$query->execute(array('rActif'=>$actif, 'rIdParrain'=>$idParrain,'rIdFillot'=>$idFillot)))
                            {
                                echo '

                               <div class="alert-box [success]">
                                    Ton parrainage a bien été confirmé ;-)
                                    <a href="index.php" class="close">×</a>
                                </div>
                                ';
                                $query->closeCursor();
                            }
                            else
                            {
                                echo '
                                <div class="alert-box [alert]">
                                Probleme de mise à jour de la base de donnée.
                                <a href="index.php" class="close">&times;</a>
                            </div>
                                ';
                            }
                        }
                        else
                        {
                            echo '
                            <div class="alert-box [alert]">
                                La clef fournie n\'est pas valide
                                <a href="index.php" class="close">&times;</a>
                            </div>
                            ';
                        }
                    }
                    else
                    {
                        echo('
                        <div class="alert-box [sucess]">
                        Ton parrainage est déjà confirmé
                                <a href="index.php" class="close">&times;</a>
                            </div>
                        ');
                    }
                }
                else
                {
                    echo '
                    <div class="alert-box [alert]">
                        Problème de connexion à la base de donnée
                        <a href="index.php" class="close">&times;</a>
                    </div>
                    ';
                }
            }
            else
            {
                echo '
                <div class="alert-box [alert]">
                Erreur. Ton parrainage n\'a pas été confirmé
                        <a href="index.php" class="close">&times;</a>
                    </div>
                ';
            }
        }
        elseif($idCoeur==2)
        {
            if((!empty($_GET['p']))&&(!empty($_GET['f']))&&(!empty($_GET['c'])))
            {
                $cle=addslashes($_GET['c']);
                $idParrain=addslashes($_GET['p']);
                $idFillot=addslashes($_GET['f']);

                // On regarde si la clé, l'id du parrain et du fillot correspondent
                $query=$bdd->prepare('SELECT actifF, actifP, cleF FROM parrainage_coeur WHERE idFillot=:rIdFillot AND idParrain=:rIdParrain;');
                $answer=$query->execute(array('rIdFillot'=>$idFillot, 'rIdParrain'=>$idParrain));

                if($answer=$query->fetch())
                {
                    $actifP=$answer['actifP'];
                    $actifF=$answer['actifF'];
                    $cleBdd=$answer['cleF'];

                    // On verifie le status du parrainage
                    if($actifP==0&&$actifF==0)
                    {
                        if($cleBdd==$cle)
                        {
                            // Le parrainage n'est pas encore validé
                            if($actifF==0)
                            {
                                $actif=1;
                                $query=$bdd->prepare('UPDATE parrainage_coeur SET actifF= :rActif WHERE idFillot=:rIdFillot AND idParrain= :rIdParrain');
                                if($answer=$query->execute(array('rActif'=>$actif, 'rIdParrain'=>$idParrain,'rIdFillot'=>$idFillot)))
                                {
                                    echo '
                                    <div class="alert-box [success]">
                                        "Ton parrainage a bien été confirmé, il manque plus que ton parrain pour valider votre parrainage"
                                    <a href="indexcoeur.php" class="close">&times;</a>
                                    </div>
                                    ';
                                    $query->closeCursor();
                                }
                                else
                                {
                                    echo '
                                        <div class="alert-box [alert]">
                                            Probleme de mise à jour de la base de donnée.
                                            <a href="indexcoeur.php" class="close">&times;</a>
                                        </div>
                                    ';
                                }
                            }
                            else
                            {
                                echo '
                                <div class="alert-box [alert]">
                                    Probleme de mise à jour de la base de donnée.
                                    <a href="indexcoeur.php" class="close">&times;</a>
                                </div>
                                ';
                            }
                        }
                        else
                        {
                            echo '
                            <div class="alert-box [alert]">
                                    La clef fournie n\'est pas valide
                                    <a href="indexcoeur.php" class="close">&times;</a>
                                </div>
                            ';
                        }
                    }
                    elseif($actifP==0&&$actifF==1)
                    {
                        if($cleBdd==$cle)
                        {
                            $actif=1;
                            $query=$bdd->prepare('UPDATE parrainage_coeur SET actifF= :rActif WHERE idFillot=:rIdFillot AND idParrain= :rIdParrain');
                            if($answer=$query->execute(array('rActif'=>$actif, 'rIdParrain'=>$idParrain,'rIdFillot'=>$idFillot)))
                            {
                                echo '
                                    <div class="alert-box [success]">
                                        Ton parrainage a bien été confirmé et maintenant actif !
                                    <a href="indexcoeur.php" class="close">&times;</a>
                                    </div>
                                ';
                                $query->closeCursor();
                            }
                            else
                            {
                                echo '
                                <div class="alert-box [alert]">
                                    Probleme de mise à jour de la base de donnée.
                                <a href="indexcoeur.php" class="close">&times;</a>
                                </div>
                                ';
                            }
                        }
                        else
                        {
                            echo '
                            <div class="alert-box [alert]">
                                    La clef fournie n\'est pas valide.
                                <a href="indexcoeur.php" class="close">&times;</a>
                                </div>
                            ';
                        }
                    }
                    else
                    {
                        echo'
                        <div class="alert-box [success]">
                            Ton parrainage est déjà confirmé
                        <a href="indexcoeur.php" class="close">&times;</a>
                        </div>
                        ';
                    }
                }
                else
                {
                    echo '
                    <div class="alert-box [alert]">
                        Problème de connexion à la base de donnée.
                    <a href="indexcoeur.php" class="close">&times;</a>
                    </div>
                    ';
                }
            }
            else
            {
                echo '
                <div class="alert-box [alert]">
                        Erreur. Ton parrainage n\'a pas été confirmé
                    <a href="indexcoeur.php" class="close">&times;</a>
                    </div>
                ';
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