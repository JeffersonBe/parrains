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
    <?php include('includes/menu.php'); ?>
    <p class="ten columns centered">Choisis ton parrain/ta marraine de coeur. Vous recevrez ensuite un lien de confirmation sur vos adresses email telecom respectives.</p>
            <div class="twelve columns">
                <form method="post" action="treatcoeur.php" class="six columns centered">
                    <label for="nomFillot">Nom du fillot</label>
                        <input type="text" name="nomFillot" required/>

                    <label for="prenomFillot">Prénom du fillot</label>
                        <input type="text" name="prenomFillot" required/>

                    <label for="emailFillot">Email du fillot</label>
                        <input type="email" name="emailFillot" required/>

                    <label for="nomParrain">Nom du parrain</label>
                        <input type="text" name="nomParrain" required/>

                    <label for="prenomParrain">Prénom du parrain</label>
                        <input type="text" name="prenomParrain" required/>

                    <label for="emailParrain">Email du Parrain</label>
                        <input type="email" name="emailParrain" required/>
                    <div class="mobile-four">
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
                    <input type="submit" class="button large" value="Match!"/>
                </form>
  </div>
  <!-- Included JS Files (Compressed) -->
  <script src="javascripts/jquery.js"></script>
  <script src="javascripts/foundation.min.js"></script>

  <!-- Initialize JS Plugins -->
  <script src="javascripts/app.js"></script>
</body>
</html>
