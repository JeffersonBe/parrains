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
<title>Choisis ton parrain!</title>
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
<form method="post" action="treat.php" id="form1">
	Choisis ton parrain/ta marraine. Il/Elle doit être dans la même école que toi. Vous recevrez ensuite un lien de confirmation sur vos adresses email telecom respectives.<br/><br/>
	<label for="nom">Nom du parrain: </label><input id="parrain_nom" name="parrain_nom" onkeyup="lookup_nomp(getElementById('fillot_nom').value,getElementById('fillot_prenom').value,this.value);" onFocus="$('#suggestions2').hide();$('#suggestions3').hide();$('#suggestions4').hide();" size="20" type="text" autocomplete="off" />
	<label for="nom">Prénom du parrain: </label><input id="parrain_prenom" name="parrain_prenom" onkeyup="lookup_prenomp(getElementById('fillot_nom').value,getElementById('fillot_prenom').value,this.value);" onFocus="$('#suggestions1').hide();$('#suggestions3').hide();$('#suggestions4').hide();" size="20" type="text" autocomplete="off" />	<br/><br/>
	<label for="nom">Nom du fillot: </label><input id="fillot_nom" name="fillot_nom" onkeyup="lookup_nomf(getElementById('parrain_nom').value,getElementById('parrain_prenom').value,this.value);" onFocus="$('#suggestions4').hide();$('#suggestions1').hide();$('#suggestions2').hide();" size="20" type="text" autocomplete="off" />
	<label for="nom">Prénom du fillot: </label><input id="fillot_prenom" name="fillot_prenom" onkeyup="lookup_prenomf(getElementById('parrain_nom').value,getElementById('parrain_prenom').value,this.value);" onFocus="$('#suggestions3').hide();$('#suggestions1').hide();$('#suggestions2').hide();" size="20" type="text" autocomplete="off" /><br/><br/>


	<div class="suggestionsBox1" id="suggestions1" style="display: none;">
		<img style="position: relative; top: -12px; left: 30px;" src="img/upArrow.png" alt="upArrow" />
		<div class="suggestionList" id="autoSuggestionsList1"></div>
	</div>
	<div class="suggestionsBox2" id="suggestions2" style="display: none;">
		<img style="position: relative; top: -12px; left: 30px;" src="img/upArrow.png" alt="upArrow" />
		<div class="suggestionList" id="autoSuggestionsList2"></div>
	</div>
		<div class="suggestionsBox3" id="suggestions3" style="display: none;">
		<img style="position: relative; top: -12px; left: 30px;" src="img/upArrow.png" alt="upArrow" />
		<div class="suggestionList" id="autoSuggestionsList3"></div>
	</div>
	<div class="suggestionsBox4" id="suggestions4" style="display: none;">
		<img style="position: relative; top: -12px; left: 30px;" src="img/upArrow.png" alt="upArrow" />
		<div class="suggestionList" id="autoSuggestionsList4"></div>
	</div>

	<div id='captcha'>
        <?php
            require_once('recaptchalib.php');

            // Get a key from https://www.google.com/recaptcha/admin/create
            $publickey = "6LffA9ISAAAAABgPEMiOjUIGQW0yb5evtd2frIqu";
            $privatekey = "6LffA9ISAAAAAFwUYq8QA-2uyKLE3I5VGvXHTRrO";

            # the response from reCAPTCHA
            $resp = null;
            # the error code from reCAPTCHA, if any
            $error = null;

            # was there a reCAPTCHA response?
            if ($_POST["recaptcha_response_field"]) {
                    $resp = recaptcha_check_answer ($privatekey,
                                                    $_SERVER["REMOTE_ADDR"],
                                                    $_POST["recaptcha_challenge_field"],
                                                    $_POST["recaptcha_response_field"]);

                    if ($resp->is_valid) {
                            echo "You got it!";
                    } else {
                            # set the error code so that we can display it
                            $error = $resp->error;
                    }
            }
            echo recaptcha_get_html($publickey, $error);
        ?>
  </div>

<input type="submit" class="submit" id="submit" value="Match!" /><br/>

</form>
</div>
</div>
</div>


</body>
</html>