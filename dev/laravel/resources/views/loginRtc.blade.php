<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">


    <title>WebRTC Test</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

    <style>
        body {
            background: #eee;
            padding: 5% 0;
        }
    </style>

</head>
<body>

<div id = "loginPage" class = "container text-center">

    <div class = "row">
        <div class = "col-md-4 col-md-offset-4">

            <h2>WebRTC Voice Demo. Please sign in</h2>

            <label for = "usernameInput" class = "sr-only">Login</label>
            <input type = "email" id = "usernameInput"
                   class = "form-control formgroup"
                   placeholder = "Login" required = "" autofocus = "">
            <button id = "loginBtn" class = "btn btn-lg btn-primary btnblock">
                Sign in</button>
        </div>
    </div>

</div>

<div id = "callPage" class = "call-page">

    <div class = "row">

        <div class = "col-md-6 text-right">
            Local audio: <audio id = "localAudio"
                                controls autoplay></audio>
        </div>

        <div class = "col-md-6 text-left">
            Remote audio: <audio id = "remoteAudio"
                                 controls autoplay></audio>
        </div>

    </div>

    <div class = "row text-center">
        <div class = "col-md-12">
            <input id = "callToUsernameInput"
                   type = "text" placeholder = "username to call" />
            <button id = "callBtn" class = "btn-success btn">Call</button>
            <button id = "hangUpBtn" class = "btn-danger btn">Hang Up</button>
        </div>
    </div>

</div>

<script src = "js/rtc/client.js"></script>


</body>
</html>