@extends('layouts.app')

@section('content')

<?php
    use App\Http\Controllers\DoorController;
    $doorsController = new DoorController();
?>

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Türe</div>
                <div class="panel-body">

                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Beschreibung</th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $doorsController->getDoorList();
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">Türe hinzufügen</div>
                <div class="panel-body">
                    <form action="/door/addDoor" method="POST" class="form-horizontal">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
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
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <form action="/door/updateDoor" method="POST">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="myModalLabel" style="text-align: center;">Türe editieren</h4>
                                </div>
                                <div class="modal-body">
                                    <?php
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
