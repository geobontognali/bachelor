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

<div id = "callPage" class = "call-page text-center">
    <h2>WebRTC Voice Prototype (Client App)</h2>
    <br />
    <div>
        Local audio
    </div>
    <div>
        <audio id = "localAudio" controls></audio>
    </div>
    <br />
    <br />
    <div>
        Remote audio
    </div>
    <div>
        <audio id="remoteAudio" controls autoplay></audio>
    </div>
    <br />


    <div>
        <div class = "col-md-12">
            <button id = "callBtn" class = "btn-success btn">Call</button>
            <button id = "hangUpBtn" class = "btn-danger btn">Hang Up</button>
        </div>
    </div>






</div>

<script src = "js/rtc/client.js"></script>


</body>
</html>