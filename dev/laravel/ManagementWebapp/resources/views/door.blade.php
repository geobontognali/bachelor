@extends('layouts.app')

@section('content')

<?php
    use App\Http\Controllers\DoorController;
    $doorsController = new DoorController();
?>

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">

            <!-- Display Validation Errors -->
            @include('errors.errors')

            <!-- Show confirmation if needed -->
            <?php
            if(isset($_GET['doorSuccessfullyDeleted']) and $_GET['doorSuccessfullyDeleted'] == "true")
            {
                echo '
                    <div class="alert alert-info">
                        Türe wurde erfolgreich entfernt.</strong>
                    </div>
                    ';
            }

            if(isset($_GET['doorSuccessfullyAdded']) and $_GET['doorSuccessfullyAdded'] == "true")
            {
                echo '
                    <div class="alert alert-info">
                        Türe wurde erfolgreich hinzugefügt.</strong>
                    </div>
                    ';
            }
            if(isset($_GET['doorSuccessfullyUpdated']) and $_GET['doorSuccessfullyUpdated'] == "true")
            {
                echo '
                    <div class="alert alert-info">
                        Türe wurde erfolgreich modifiziert.</strong>
                    </div>
                    ';
            }
            ?>

            <div class="panel panel-default">
                <div class="panel-heading">Türe</div>
                <div class="panel-body">
                    <!-- Doors list -->
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Türe Id</th>
                                <th>Name</th>
                                <th class="visible-md visible-lg">Beschreibung</th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        //generate the table content
                        $doorsController->getDoorList();
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">Türe hinzufügen</div>
                <div class="panel-body">
                    <!-- Form for adding new doors -->
                    <form action="/door/addDoor" method="POST" class="form-horizontal">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Türe Id</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control" name="inputDoorId" id="inputDoorId" placeholder="z.B 58">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="inputName" id="inputName" placeholder="z.B Türe 1">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Beschreibung</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="inputDescription" id="inputDescription" placeholder="z.B Eingang Süd">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-primary btn-xl page-scroll">Türe hinzufügen</button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- Modal -->
                <div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document" >
                        <div class="modal-content" style="max-width: 100%">
                            <form action="/door/updateDoor" method="POST">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="myModalLabel" style="text-align: center;">Türe editieren</h4>
                                </div>
                                <div class="modal-body">
                                    <?php
                                    //generate the content of the modal
                                    if(isset($_GET['editDoor']))
                                    {
                                        $doorsController->editDoor($_GET['editDoor']);
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
