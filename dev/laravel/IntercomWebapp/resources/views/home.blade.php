@extends('layouts.main')

@section('content')

        <!--  -->

        <!-- Desktop HTML -->
        <div class="container-fluid hidden-sm hidden-xs hidden-md" >
            <div class="col-lg-1" style="max-width: 145px;">
                <h2>Time</h2>
                <div class="panel panel-default rawspacing invisiblepanel"><div class="panel-body">08:15<br /> 09:50</div></div>
                <div class="panel panel-default rawspacing invisiblepanel"><div class="panel-body">10:10<br /> 11:45</div></div>
                <div id="breakbar"></div>
                <div class="panel panel-default rawspacing invisiblepanel"><div class="panel-body">13:15<br /> 14:50</div></div>
                <div class="panel panel-default rawspacing invisiblepanel"><div class="panel-body">15:15<br /> 16:50</div></div>
                <div class="panel panel-default rawspacing invisiblepanel"><div class="panel-body">17:00<br /> 19:45</div></div>
            </div>

            <div class="col-lg-11">
                <!-- 1st raw on schedule -->
                <div class="row">
                    <div class="col-lg-2 rawscroll panelspacing"><h2>Mon</h2><div class="panel panel-default rawspacing"><div class="panel-body modulepane" id="d1r1"></div></div></div>
                    <div class="col-lg-2 rawscroll panelspacing"><h2>Tue</h2><div class="panel panel-default rawspacing"><div class="panel-body modulepane" id="d2r1"></div></div></div>
                    <div class="col-lg-2 rawscroll panelspacing"><h2>Wed</h2><div class="panel panel-default rawspacing"><div class="panel-body modulepane" id="d3r1"></div></div></div>
                    <div class="col-lg-2 rawscroll panelspacing"><h2>Thu</h2><div class="panel panel-default rawspacing"><div class="panel-body modulepane" id="d4r1"></div></div></div>
                    <div class="col-lg-2 rawscroll panelspacing"><h2>Fri</h2><div class="panel panel-default rawspacing"><div class="panel-body modulepane" id="d5r1"></div></div></div>
                    <div class="col-lg-2 rawscroll panelspacing"><h2 style="color: #72b1ff;" >Sat</h2><div class="panel panel-default rawspacing"><div class="panel-body modulepane" id="d6r1"></div></div></div>
                </div>
                <!-- 2nd raw on schedule -->
                <div class="row">
                    <div class="col-md-2 rawscroll panelspacing"><div class="panel panel-default rawspacing"><div class="panel-body modulepane" id="d1r2"></div></div></div>
                    <div class="col-md-2 rawscroll panelspacing"><div class="panel panel-default rawspacing"><div class="panel-body modulepane" id="d2r2"></div></div></div>
                    <div class="col-md-2 rawscroll panelspacing"><div class="panel panel-default rawspacing"><div class="panel-body modulepane" id="d3r2"></div></div></div>
                    <div class="col-md-2 rawscroll panelspacing"><div class="panel panel-default rawspacing"><div class="panel-body modulepane" id="d4r2"></div></div></div>
                    <div class="col-md-2 rawscroll panelspacing"><div class="panel panel-default rawspacing"><div class="panel-body modulepane" id="d5r2"></div></div></div>
                    <div class="col-md-2 rawscroll panelspacing"><div class="panel panel-default rawspacing"><div class="panel-body modulepane" id="d6r2"></div></div></div>
                </div>

                <!-- midday spacer -->
                <div id="breakbar"></div>

                <!-- 3rd raw on schedule -->
                <div class="row">
                    <div class="col-md-2 rawscroll panelspacing"><div class="panel panel-default rawspacing"><div class="panel-body modulepane" id="d1r3"></div></div></div>
                    <div class="col-md-2 rawscroll panelspacing"><div class="panel panel-default rawspacing"><div class="panel-body modulepane" id="d2r3"></div></div></div>
                    <div class="col-md-2 rawscroll panelspacing"><div class="panel panel-default rawspacing"><div class="panel-body modulepane" id="d3r3"></div></div></div>
                    <div class="col-md-2 rawscroll panelspacing"><div class="panel panel-default rawspacing"><div class="panel-body modulepane" id="d4r3"></div></div></div>
                    <div class="col-md-2 rawscroll panelspacing"><div class="panel panel-default rawspacing"><div class="panel-body modulepane" id="d5r3"></div></div></div>
                    <div class="col-md-2 rawscroll panelspacing"><div class="panel panel-default rawspacing"><div class="panel-body modulepane" id="d6r3"></div></div></div>
                </div>

                <!-- 4th raw on schedule -->
                <div class="row">
                    <div class="col-md-2 rawscroll panelspacing"><div class="panel panel-default rawspacing"><div class="panel-body modulepane" id="d1r4"></div></div></div>
                    <div class="col-md-2 rawscroll panelspacing"><div class="panel panel-default rawspacing"><div class="panel-body modulepane" id="d2r4"></div></div></div>
                    <div class="col-md-2 rawscroll panelspacing"><div class="panel panel-default rawspacing"><div class="panel-body modulepane" id="d3r4"></div></div></div>
                    <div class="col-md-2 rawscroll panelspacing"><div class="panel panel-default rawspacing"><div class="panel-body modulepane" id="d4r4"></div></div></div>
                    <div class="col-md-2 rawscroll panelspacing"><div class="panel panel-default rawspacing"><div class="panel-body modulepane" id="d5r4"></div></div></div>
                    <div class="col-md-2 rawscroll panelspacing"><div class="panel panel-default rawspacing"><div class="panel-body modulepane" id="d6r4"></div></div></div>
                </div>

                <!-- 5th raw on schedule -->
                <div class="row">
                    <div class="col-md-2 rawscroll panelspacing"><div class="panel panel-default rawspacing"><div class="panel-body modulepane" id="d1r5"></div></div></div>
                    <div class="col-md-2 rawscroll panelspacing"><div class="panel panel-default rawspacing"><div class="panel-body modulepane" id="d2r5"></div></div></div>
                    <div class="col-md-2 rawscroll panelspacing"><div class="panel panel-default rawspacing"><div class="panel-body modulepane" id="d3r5"></div></div></div>
                    <div class="col-md-2 rawscroll panelspacing"><div class="panel panel-default rawspacing"><div class="panel-body modulepane" id="d4r5"></div></div></div>
                    <div class="col-md-2 rawscroll panelspacing"><div class="panel panel-default rawspacing"><div class="panel-body modulepane" id="d5r5"></div></div></div>
                    <div class="col-md-2 rawscroll panelspacing"><div class="panel panel-default rawspacing"><div class="panel-body modulepane" id="d6r5"></div></div></div>
                </div>
            </div>
        </div>


        <!-- Mobile HTML -->
        <div class="hidden-lg">
            <div class="container monday">
                <h2>Mon</h2>
                <table class="scheduleTable">
                    <tr>
                        <td class="timeCol">08:15 09:50</td>
                        <td><div class="panel panel-default rawspacing"><div class="panel-body modulepane" id="m1r1"></div></div></td>
                    </tr>
                    <tr>
                        <td class="timeCol">08:15 09:50</td>
                        <td><div class="panel panel-default rawspacing"><div class="panel-body modulepane" id="m1r2"></div></div></td>
                    </tr>
                    <tr>
                        <td class="timeCol">08:15 09:50</td>
                        <td><div class="panel panel-default rawspacing"><div class="panel-body modulepane" id="m1r3"></div></div></td>
                    </tr>
                    <tr>
                        <td class="timeCol">08:15 09:50</td>
                        <td><div class="panel panel-default rawspacing"><div class="panel-body modulepane" id="m1r4"></div></div></td>
                    </tr>
                    <tr>
                        <td class="timeCol">17:00 19:45</td>
                        <td><div class="panel panel-default rawspacing"><div class="panel-body modulepane" id="m1r5"></div></div></td>
                    </tr>
                </table>
            </div>

            <div class="container monday">
                <h2>Tue</h2>
                <table class="scheduleTable">
                    <tr>
                        <td class="timeCol">08:15 09:50</td>
                        <td><div class="panel panel-default rawspacing"><div class="panel-body modulepane" id="m2r1"></div></div></td>
                    </tr>
                    <tr>
                        <td class="timeCol">08:15 09:50</td>
                        <td><div class="panel panel-default rawspacing"><div class="panel-body modulepane" id="d2r2"></div></div></td>
                    </tr>
                    <tr>
                        <td class="timeCol">08:15 09:50</td>
                        <td><div class="panel panel-default rawspacing"><div class="panel-body modulepane" id="m2r3"></div></div></td>
                    </tr>
                    <tr>
                        <td class="timeCol">08:15 09:50</td>
                        <td><div class="panel panel-default rawspacing"><div class="panel-body modulepane" id="m2r4"></div></div></td>
                    </tr>
                    <tr>
                        <td class="timeCol">17:00 19:45</td>
                        <td><div class="panel panel-default rawspacing"><div class="panel-body modulepane" id="m2r5"></div></div></td>
                    </tr>
                </table>
            </div>

            <div class="container monday">
                <h2>Wed</h2>
                <table class="scheduleTable">
                    <tr>
                        <td class="timeCol">08:15 09:50</td>
                        <td><div class="panel panel-default rawspacing"><div class="panel-body modulepane" id="m3r1"></div></div></td>
                    </tr>
                    <tr>
                        <td class="timeCol">08:15 09:50</td>
                        <td><div class="panel panel-default rawspacing"><div class="panel-body modulepane" id="m3r2"></div></div></td>
                    </tr>
                    <tr>
                        <td class="timeCol">08:15 09:50</td>
                        <td><div class="panel panel-default rawspacing"><div class="panel-body modulepane" id="m3r3"></div></div></td>
                    </tr>
                    <tr>
                        <td class="timeCol">08:15 09:50</td>
                        <td><div class="panel panel-default rawspacing"><div class="panel-body modulepane" id="m3r4"></div></div></td>
                    </tr>
                    <tr>
                        <td class="timeCol">17:00 19:45</td>
                        <td><div class="panel panel-default rawspacing"><div class="panel-body modulepane" id="m3r5"></div></div></td>
                    </tr>
                </table>
            </div>

            <div class="container monday">
                <h2>Thu</h2>
                <table class="scheduleTable">
                    <tr>
                        <td class="timeCol">08:15 09:50</td>
                        <td><div class="panel panel-default rawspacing"><div class="panel-body modulepane" id="m4r1"></div></div></td>
                    </tr>
                    <tr>
                        <td class="timeCol">08:15 09:50</td>
                        <td><div class="panel panel-default rawspacing"><div class="panel-body modulepane" id="m4r2"></div></div></td>
                    </tr>
                    <tr>
                        <td class="timeCol">08:15 09:50</td>
                        <td><div class="panel panel-default rawspacing"><div class="panel-body modulepane" id="m4r3"></div></div></td>
                    </tr>
                    <tr>
                        <td class="timeCol">08:15 09:50</td>
                        <td><div class="panel panel-default rawspacing"><div class="panel-body modulepane" id="m4r4"></div></div></td>
                    </tr>
                    <tr>
                        <td class="timeCol">17:00 19:45</td>
                        <td><div class="panel panel-default rawspacing"><div class="panel-body modulepane" id="m4r5"></div></div></td>
                    </tr>
                </table>
            </div>

            <div class="container monday">
                <h2>Fri</h2>
                <table class="scheduleTable">
                    <tr>
                        <td class="timeCol">08:15 09:50</td>
                        <td><div class="panel panel-default rawspacing"><div class="panel-body modulepane" id="m5r1"></div></div></td>
                    </tr>
                    <tr>
                        <td class="timeCol">08:15 09:50</td>
                        <td><div class="panel panel-default rawspacing"><div class="panel-body modulepane" id="m5r2"></div></div></td>
                    </tr>
                    <tr>
                        <td class="timeCol">08:15 09:50</td>
                        <td><div class="panel panel-default rawspacing"><div class="panel-body modulepane" id="m5r3"></div></div></td>
                    </tr>
                    <tr>
                        <td class="timeCol">08:15 09:50</td>
                        <td><div class="panel panel-default rawspacing"><div class="panel-body modulepane" id="m5r4"></div></div></td>
                    </tr>
                    <tr>
                        <td class="timeCol">17:00 19:45</td>
                        <td><div class="panel panel-default rawspacing"><div class="panel-body modulepane" id="m5r5"></div></div></td>
                    </tr>
                </table>
            </div>

            <div class="container monday">
                <h2>Sat</h2>
                <table class="scheduleTable">
                    <tr>
                        <td class="timeCol">08:15 09:50</td>
                        <td><div class="panel panel-default rawspacing"><div class="panel-body modulepane" id="m6r1"></div></div></td>
                    </tr>
                    <tr>
                        <td class="timeCol">08:15 09:50</td>
                        <td><div class="panel panel-default rawspacing"><div class="panel-body modulepane" id="m6r2"></div></div></td>
                    </tr>
                    <tr>
                        <td class="timeCol">08:15 09:50</td>
                        <td><div class="panel panel-default rawspacing"><div class="panel-body modulepane" id="m6r3"></div></div></td>
                    </tr>
                    <tr>
                        <td class="timeCol">08:15 09:50</td>
                        <td><div class="panel panel-default rawspacing"><div class="panel-body modulepane" id="m6r4"></div></div></td>
                    </tr>
                    <tr>
                        <td class="timeCol">17:00 19:45</td>
                        <td><div class="panel panel-default rawspacing"><div class="panel-body modulepane" id="m6r5"></div></div></td>
                    </tr>
                </table>
            </div>

        </div>

        <?php
        use App\Http\Controllers\HomeController;
        $testObject=new HomeController();
        $test = $testObject->getModules();
        ?>
        <script>
            timeHighlighting();
        </script>


@endsection
