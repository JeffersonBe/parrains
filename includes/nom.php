<?php
// si on reçoit une donnée
if(isset($_GET['q'])) {
    $q = htmlentities($_GET['q']); // protection

    include('../connect_settings.php');
    try
    {
        $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
        $bdd = new PDO('mysql:host='.$hostdb.';dbname='.$namedb, $logindb, $passworddb, $pdo_options);
    }
    catch (Exception $e)
    {
        die('Erreur : ' . $e->getMessage());
    }

    // écriture de la requête
    $requete = "SELECT nom FROM user WHERE nom LIKE '". $q ."%' LIMIT 0, 10";

    // exécution de la requête
    $resultat = $bdd->query($requete) or die(print_r($bdd->errorInfo()));

    // affichage des résultats
    while($donnees = $resultat->fetch(PDO::FETCH_ASSOC)) {
        echo $donnees['nom'] ."\n";
    }
}
?>