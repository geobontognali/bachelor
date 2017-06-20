<?php
/**
 * Created by PhpStorm.
 * User: Federico
 * Date: 19.06.2017
 * Time: 17:12
 */?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Management Tool</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/clock.css">
    <script src="js/clock.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-default darkbar" role="navigation">
    <div class="navbar-header">
        <a class="navbar-brand" href="http://managementwebapp.dev">
            <img id="managementlogo" alt="Brand" src="img/managementlogo.png">
        </a>

        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#example-navbar-collapse" style="border-color: gray; margin-top: 18px;">
            <span class = "icon-bar"></span>
            <span class = "icon-bar"></span>
            <span class = "icon-bar"></span>
        </button>
    </div>

    <div class = "collapse navbar-collapse" id = "example-navbar-collapse">
        <ul class = "nav navbar-nav navbar-right">
            <li><a href="{{ url('/register') }}">(Register)</a></li>
            <li><a href="{{ url('/login') }}">Login</a></li>
        </ul>
   </div>
</nav>

<!-- OVER CONTAINER -->
<div class="container-fluid" id="overcontainer" style="margin-bottom: 27px;">
    <div class="row">
        <div class="col-sm-5 timer">

            <div id="digiClock">00:00:00</div>
            <script >
// Init DigiClock
digiClock();
            </script>

        </div>
        <div class="col-sm-2 hidden-xs">

            <div id="clockContainer">

                <article class="clock">
                    <div class="hours-container">
                        <div class="hours"></div>
                    </div>
                    <div class="minutes-container">
                        <div class="minutes"></div>
                    </div>
                    <div class="seconds-container">
                        <div class="seconds"></div>
                    </div>
                </article>
            </div>
            <script >
// Init Clock
initLocalClocks();
                setUpMinuteHands();
                moveSecondHands();
            </script>

        </div>
        <div class="col-sm-5 datum">
            <div id="showDate">00.00.00</div>
            <script >
// Init DigiClock
printDate();
            </script>
        </div>
    </div>
</div>

<!-- UNDER CONTAINER -->
<div class="container-fluid" id="undercontainer">

@yield('content')

</div>

</body>
</html>