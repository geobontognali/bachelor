@extends('layouts.mainClient')

@section('content')


        <!-- Modal -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel" style="text-align: center;">Something went wrong...</h4>
                    </div>
                    <div class="modal-body">
                        Failed to connect to the selected Intercom.<br /><br />
                        Error: <i><span id="connectionErrorLog">Unknown</span></i>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Abort</button>
                        <button type="button" onclick="location.reload()" class="btn btn-primary">Retry</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">

            <div class="container text-center" id="topContainer">
                <div class="row">
                    <h1>Eingang Nord</h1>
                </div>
                <div class="row">

                    <div id="videofeed">
                        <div id="videoContent">
                            <video id="remoteVideo" width="100%" autoplay ></video>
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
                                <p id="connectionStatus">Connecting...</p>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="row row-centered" id="buttons">
                    <div class="col-xs-6 ">
                        <div id="btnLeft" onClick="triggerMic();">
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
            </div>

            <div class="container-fluid navbar-fixed-bottom" id="bottomContainer">

                <div id="naviCont" class="row">
                    <?php
                        use App\Http\Controllers\ClientController;
                        $clientController = new ClientController();
                        $clientController->generateNavi();
                    ?>
                </div>

            </div>


        </div>



        <script>
            startConnection();
        </script>


@endsection
