@extends('layouts.mainDoor')

@section('content')


    <div id="header">
        <p id="datetime">
        </p>
    </div>

    <div class="row text-center">
        <h2>Willkommen</h2>
        <div id="testt">
            <ul class="intern">
                testas</ul>
        </div>
        <div id="scroller">

            <ul class="carousel">
                <li class="item active">
                    <p class="name">J. Locke</p>
                    <p class="apartment">W1.3</p>
                    <div style="display:none;" class="residentId">1</div>
                </li>
                <li class="item">
                    <p class="name">H. Kate</p>
                    <p class="apartment">W1.4</p>
                    <div style="display:none;" class="residentId">2</div>
                </li>
                <li class="item">
                    <p class="name">G. Marotta</p>
                    <p class="apartment">W1.5</p>
                    <div style="display:none;" class="residentId">3</div>
                </li>
                <li class="item">
                    <p class="name">L. Messi</p>
                    <p class="apartment">W2.1</p>
                    <div style="display:none;" class="residentId">4</div>
                </li>
                <li class="item">
                    <p class="name">G. Buffon</p>
                    <p class="apartment">W2.2</p>
                    <div style="display:none;" class="residentId">5</div>
                </li>
                <li class="item">
                    <p class="name">R. Federer</p>
                    <p class="apartment">W2.3</p>
                    <div style="display:none;" class="residentId">6</div>
                </li>

                <li class="item">
                    <p class="name">I. Newton</p>
                    <p class="apartment">W2.4</p>
                    <div style="display:none;" class="residentId">7</div>
                </li>
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

    <!-- DEBUG VIEW  ******************************************************** -->
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
        use App\Http\Controllers\DoorController;
        $doorController = new DoorController();
        $doorController->setDoorId();
        ?>
    </script>
    <script src="js/rtc/door.js" ></script>

@endsection
