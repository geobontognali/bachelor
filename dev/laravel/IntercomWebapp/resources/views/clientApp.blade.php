@extends('layouts.main')

@section('content')


        <div class="row">

            <div class="container text-center" id="topContainer">
                <div class="row">
                    <h1>Eingang Nord</h1>
                </div>
                <div class="row">

                    <div id="videofeed">
                        <div id="videoContent">
                            <video id="remoteVideo" width="400" height="285" autoplay ></video>
                        </div>

                        <div id="audioStatus">
                            <div id="audioOff"></div>
                            <div id="audioWave">
                                <div class="spinner2">
                                    <div class="rect1"></div>
                                    <div class="rect2"></div>
                                    <div class="rect3"></div>
                                    <div class="rect4"></div>
                                    <div class="rect5"></div>
                                </div>
                            </div>
                        </div>

                        <div id="loadingSpinner">
                            <div class="spinner">
                                <div class="double-bounce1"></div>
                                <div class="double-bounce2"></div>
                                <p>Loading video feed...</p>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="row row-centered" id="buttons">
                    <div class="col-xs-6 ">
                        <div id="btnLeft" onClick="triggerCall();">
                            <div id="iconLeft"></div>
                            <p>MIC ON/OFF</p>
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div id="btnRight" onClick="openDoor()">
                            <div id="iconRight"></div>
                            <p>OPEN DOOR</p>
                        </div>
                    </div>
                </div>
                <audio id="remoteAudio" controls autoplay></audio>
            </div>

            <div class="container-fluid navbar-fixed-bottom" id="bottomContainer">


            </div>


        </div>

        <script>
            startConnection();
        </script>


@endsection
