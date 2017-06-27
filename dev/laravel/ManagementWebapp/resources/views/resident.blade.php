@extends('layouts.app')

@section('content')

<?php
    use App\Http\Controllers\ResidentController;
    $residentsController = new ResidentController();
?>

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">

            <!-- Display Validation Errors -->
            @include('errors.errors')

            <!-- Show confirmation if needed -->
            <?php
                if(isset($_GET['residentSuccessfullyDeleted']) and $_GET['residentSuccessfullyDeleted'] == "true")
                {
                    echo '
                        <div class="alert alert-info">
                            Einwohner wurde erfolgreich entfernt.</strong>
                        </div>
                        ';
                }
                if(isset($_GET['residentSuccessfullyAdded']) and $_GET['residentSuccessfullyAdded'] == "true")
                {
                    echo '
                            <div class="alert alert-info">
                                Einwohner wurde erfolgreich hinzugefügt.</strong>
                            </div>
                            ';
                }
                if(isset($_GET['residentSuccessfullyUpdated']) and $_GET['residentSuccessfullyUpdated'] == "true")
                {
                    echo '
                                <div class="alert alert-info">
                                    Einwohner wurde erfolgreich modifiziert.</strong>
                                </div>
                                ';
                }
            ?>

            <div class="panel panel-default">
                <div class="panel-heading">Einwohner</div>
                <div class="panel-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th class="visible-md visible-lg">Benutzername</th>
                                <th class="visible-md visible-lg">Angezeigte Name</th>
                                <th class="visible-md visible-lg">Wohnung</th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            //generate the table content
                            $residentsController->getResidentList();
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">Einwohner hinzufügen</div>
                <div class="panel-body">
                    <!-- Form for adding new residents -->
                    <form action="/resident/addResident" method="POST" class="form-horizontal">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="inputName" id="inputName" placeholder="z.B Hans Muster">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Benutzername</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="inputUsername" id="inputUsername" placeholder="muster">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Angezeigte Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="inputDisplayedname" id="inputDisplayedname" placeholder="z.B H. Muster">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Wohnung</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="inputApartment" id="inputApartment" placeholder="z.B 4 OG Links">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Passwort</label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" name="inputPassword" id="inputPassword" placeholder="passwort">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Passwort bestätigen</label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" name="inputPasswordConfirmation" id="inputPasswordConfirmation" placeholder="passwort">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-primary btn-xl page-scroll">Einwohner hinzufügen</button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- Modal -->
                <div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content"  style="max-width: 100%">
                            <form action="/resident/updateResident" method="POST">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="myModalLabel" style="text-align: center;">Einwohner editieren</h4>
                                </div>
                                <div class="modal-body">
                                    <?php
                                    //generate the content of the modal
                                    if(isset($_GET['editResident']))
                                        {
                                        $residentsController->editResident($_GET['editResident']);
                                        echo "<script type='text/javascript'>
                                        $(document).ready(function(){
                                        $('#edit').modal('show');
                                        });
                                        </script>";
                                        }
                                    ?>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Schliessen</button>
                                    <button type="submit" class="btn btn-primary">Speichern</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
