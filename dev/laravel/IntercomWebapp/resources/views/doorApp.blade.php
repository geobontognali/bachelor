@extends('layouts.mainDoor')

@section('content')
<?php
    use App\Http\Controllers\DoorController;
    $doorController = new DoorController();
    ?>

    <div id="header">
        <p id="datetime">
        </p>
    </div>

    <div class="row text-center">
        <h2>Willkommen</h2>
        <div id="scroller">

            <ul class="carousel">
                <?php
                    $doorController->listResidents();
                ?>
            </ul>

        </div>

        <div class="row" id="controls">

            <div class="col-sm-4 btnIcon">
                <img src="img/left-arrow.png" />
            </div>

            <div class="col-sm-4 btnIconMiddle">
                <img src="img/alarm_noring.png" />
            </div>

            <div class="col-sm-4 btnIcon">
                <img src="img/right-arrow.png" />
            </div>

            </div>
        </div>

    </div>

    <!-- DEBUG VIEW  (DISPLAY: NONE;) ******************************************************** -->
    <div id="debugView" class="text-center" style="display: none;">
        <div class = "row" >
            <br /><br />
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
        </div>
    </div>
    <!-- ****************************************************************** -->

    <script>
        var date = new Date();
        var format = date.getDate()+"."+(date.getMonth()+1)+"."+date.getFullYear()+" - "+date.getHours()+":"+date.getMinutes()+" ";
        document.getElementById("datetime").innerHTML = format;
    </script>


    <script src="js/carousel/jquery.circular-carousel.js"></script>
    <script src="js/carousel/carousel-config.js"></script>


    <script>
        <?php
        $doorController->setDoorId();
        $doorController->setConfig();
        ?>
    </script>
    <script src="js/rtc/door.js" ></script>

@endsection
