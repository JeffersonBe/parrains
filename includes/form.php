<label for="nomFillot">Nom du fillot</label>
    <input type="text" name="nomFillot" class="nom" required/>

<label for="prenomFillot">Prénom du fillot</label>
    <input type="text" name="prenomFillot" class="prenom" required/>

<label for="emailFillot">Email du fillot</label>
    <input type="email" name="emailFillot" class="email" required/>

<label for="nomParrain">Nom du parrain</label>
    <input type="text" name="nomParrain" class="nom" required/>

<label for="prenomParrain">Prénom du parrain</label>
    <input type="text" name="prenomParrain" class="prenom" required/>

<label for="emailParrain">Email du Parrain</label>
    <input type="email" name="emailParrain" class="email" required/>
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