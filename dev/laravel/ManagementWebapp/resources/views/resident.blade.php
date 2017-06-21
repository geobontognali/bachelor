@extends('layouts.app')

@section('content')

<?php
    use App\Http\Controllers\ResidentController;
    $residentsController = new ResidentController();
?>

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Einwohner</div>
                <div class="panel-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Vorname</th>
                                <th>Nachname</th>
                                <th>Angezeigte Name</th>
                                <th>Wohnung</th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $residentsController->getResidentList();
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">Einwohner hinzufügen</div>
                <div class="panel-body">
                    <form action="/resident/addResident" method="POST" class="form-horizontal">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Vorname</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="inputFirstname" id="inputFirstname" placeholder="z.B Hans">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Nachname</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="inputSecondname" id="inputSecondname" placeholder="z.B Muster">
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
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-primary btn-xl page-scroll">Einwohner hinzufügen</button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- Modal -->
                <div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <form action="/resident/updateResident" method="POST">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="myModalLabel" style="text-align: center;">Einwohner editieren</h4>
                                </div>
                                <div class="modal-body">
                                    <?php
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
