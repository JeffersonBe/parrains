<?php
function mailValide($email)
{
	// Auteur : bobocop (arobase) bobocop (point) cz
	// Traduction des commentaires par mathieu

	// Le code suivant est la version du 2 mai 2005 qui respecte les RFC 2822 et 1035
	// http://www.faqs.org/rfcs/rfc2822.html
	// http://www.faqs.org/rfcs/rfc1035.html

	$atom   = '[-a-z0-9!#$%&\'*+\\/=?^_`{|}~]';   // caractères autorisés avant l'arobase
	$domain = '([a-z0-9]([-a-z0-9]*[a-z0-9]+)?)'; // caractères autorisés après l'arobase (nom de domaine)

	$regex = '/^' . $atom . '+' .   // Une ou plusieurs fois les caractères autorisés avant l'arobase
	'(\.' . $atom . '+)*' .         // Suivis par zéro point ou plus
									// séparés par des caractères autorisés avant l'arobase
	'@' .                           // Suivis d'un arobase
	'(' . $domain . '{1,63}\.)+' .  // Suivis par 1 à 63 caractères autorisés pour le nom de domaine
									// séparés par des points
	$domain . '{2,63}$/i';          // Suivi de 2 à 63 caractères autorisés pour le nom de domaine

	// test de l'adresse e-mail
	if (preg_match($regex, $email)) {
		return true;
	} else {
		return false;
	}
}

// teste si un mail est en @telecom-sudparis.eu ou @telecom-em.eu ou @it-sudparis.eu
function mailINT($mail)
	{
		$extensions_autorisees = array('telecom-sudparis.eu', 'telecom-em.eu', 'it-sudparis.eu');
		$separation = explode('@', $mail);
		$extension = $separation[1];
		if(in_array($extension, $extensions_autorisees))
			return true;
		else
			return false;
	}
?>