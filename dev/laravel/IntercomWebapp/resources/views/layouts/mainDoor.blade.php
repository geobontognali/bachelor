<!DOCTYPE html>
<html lang="en">
<head>
    <title>Intercom App</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <!-- Fallback application metadata for legacy browsers -->
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="application-name" content="Intercom App">
    <link rel="icon" sizes="100x100" href="img/keyic.png">
    <link rel="manifest" href="manifest.json">

    <!-- Latest compiled and minified Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>



    <link rel="stylesheet" href="css/jquery.circular-carousel.css">
    <link rel="stylesheet" href="css/styleDoor.css">

</head>

<body style="background-color: #e7e7e7;">

<!-- UNDER CONTAINER -->
<div class="mainContainer" >

    @yield('content')

</div>

</body>
</html>