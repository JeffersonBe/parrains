<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width" />
    @yield('title')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.2/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="css/main.css"/>
</head>

<body>
    <div class="container">
        <main class="col-md-8 col-md-offset-2">
            <img id="logo" src="img/logo.svg" class="img-responsive" alt="Responsive image"/>
            @yield('content')
        </main>
    </div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
<script src="js/main.js"></script>
</body>
</html>
