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

  <title>Bienvenue sur la page de confirmation du Parrainage</title>

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
        include('connect_settings.php');
        $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
        $bdd = new PDO('mysql:host='.$hostdb.';dbname='.$namedb, $logindb, $passworddb, $pdo_options);

        // On récupère le traitement à effectuer (1 pour parrainage et 2 pour parrainage coeur
        if(!empty($_GET['n']))
        {
            $idCoeur = $_GET['n'];
        }


        // On effectue le parrainage
        if($idCoeur==1)
        {
            if((!empty($_GET['p']))&&(!empty($_GET['f']))&&(!empty($_GET['c'])))
            {
                $cle=addslashes($_GET['c']);
                $idParrain=addslashes($_GET['p']);
                $idFillot=addslashes($_GET['f']);

                // On regarde si la clé, l'id du parrain et du fillot correspondent
                $query=$bdd->prepare('SELECT actif, cle FROM parrainage WHERE id=:rIdFillot AND parraincoeur=:rIdParrain;');
                $answer=$query->execute(array('rIdFillot'=>$idfillot, 'rIdParrain'=>$idparrain));

                if($answer=$query->fetch())
                {
                    $actif=$answer['actif'];
                    $cleBdd=$answer['clef'];

                    // On verifie le status du parrainage
                    if($actif>2)
                    {
                        if($cleBdd==$cle)
                        {
                            // Le parrainage n'est pas encore validé
                            if($actif==0)
                            {
                                $actif=1;
                                $query=$bdd->prepare('UPDATE parrainage SET actif= :rActif WHERE idFillot=:rIdFillot AND idParrain= :rIdParrain');
                                if($answer=$query->execute(array('rActif'=>$actif, 'rIdParrain'=>$idParrain,'rIdFillot'=>$idFillot)))
                                {
                                    echo "Ton parrainage a bien été confirmé ;-) ";
                                    $query->closeCursor();
                                }
                                else
                                {
                                    echo 'Probleme de mise à jour de la base de donnée.';
                                }
                            }
                            else if($actif==1)
                            {
                                $actif=2;
                                $query=$bdd->prepare('UPDATE parrainage SET actif= :rActif WHERE idFillot=:rIdFillot AND idParrain =:rIdParrain');
                                if($answer=$query->execute(array('rActif'=>$actif, 'rIdParrain'=>$idParrain,'rIdFillot'=>$idFillot)))
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
                                echo "La clef fournie n'est pas valide";
                            }
                        }
                        else if($actif==1)
                        {
                            echo "Tu as déjà validé le parrainage. Il ne reste plus qu'au fillot de coeur de valider.";
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
            }
            else
            {
                echo "Erreur. Ton parrainage n'a pas été confirmé.";
            }
        }
        elseif($idCoeur==2)
        {
            if((!empty($_GET['p']))&&(!empty($_GET['f']))&&(!empty($_GET['c'])))
            {
                $cle=addslashes($_GET['c']);
                $idParrain=addslashes($_GET['p']);
                $idFillot=addslashes($_GET['f']);

                $query=$bdd->prepare('SELECT actif, cle FROM parrainage_coeur WHERE id=:r_idfillot AND parraincoeur=:r_idparrain;');
                $answer=$query->execute(array('r_idfillot'=>$idfillot, 'r_idparrain'=>$idparrain));
                if($answer=$query->fetch())
                {
                    $cleBdd=$answer['clef'];
                    $actif=$answer['actif'];
                    if($actif!=2)
                    {
                        if($cleBdd==$cle)
                        {
                            if($actif==0)
                            {
                                $actif=1;
                                $query=$bdd->prepare('UPDATE parrainage_coeur SET actif= :rActif WHERE idFillot=:rIdFillot AND idParrain= :rIdParrain');
                                if($answer=$query->execute(array('rActif'=>$actif, 'rIdParrain'=>$idParrain,'rIdFillot'=>$idFillot)))
                                {
                                    echo "Ton parrainage a bien été confirmé ;-) ";
                                    $query->closeCursor();
                                }
                                else
                                {
                                echo 'Probleme de mise à jour de la base de donnée.';
                                }
                            }
                        }
                        else if($actif==1)
                        {
                            $actif=2;
                            $query=$bdd->prepare('UPDATE parrainage_coeur SET actif= :rActif WHERE idFillot=:rIdFillot AND idParrain =:rIdParrain');
                            if($answer=$query->execute(array('rActif'=>$actif, 'rIdParrain'=>$idParrain,'rIdFillot'=>$idFillot)))
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
                        echo "La clef fournie n'est pas valide";
                        }
                    }
                    else if($actif==1)
                    {
                        echo "Tu as déjà validé le parrainage. Il ne reste plus qu'au fillot de coeur de valider.";
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
        }
        else
        {
            echo("Il y a un problème dans votre lien.");
        }
?>
  <!-- Included JS Files (Compressed) -->
  <script src="javascripts/jquery.js"></script>
  <script src="javascripts/foundation.min.js"></script>

  <!-- Initialize JS Plugins -->
  <script src="javascripts/app.js"></script>
</body>
</html>