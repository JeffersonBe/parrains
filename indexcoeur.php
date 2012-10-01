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
    <header class="twelve columns">
        <?php include('includes/menu.php'); ?>
    </header>
    <div id="main" class="twelve columns">
        <p class="ten columns centered lead">Choisis ton parrain/ta marraine de coeur. Vous recevrez ensuite un lien de confirmation sur vos adresses email telecom respectives.</p>
        <div class="twelve columns">
            <form method="post" action="demandecoeur.php" class="six columns centered">
            <?php include('includes/form.php'); ?>
            </form>
        </div>
    </div>
  <?php include('includes/script.php'); ?>
</body>
</html>
