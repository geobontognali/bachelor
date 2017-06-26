<?php
/**
 * Created by PhpStorm.
 * User: Federico
 * Date: 26.06.2017
 * Time: 13:03
 */
?>

@if (count($errors) > 0)
    <!-- Form Error List -->
    <div class="alert alert-danger" >
        <strong>Folgende Angaben fehlen oder sind fehlerhaft, bitte füllen Sie das Formular nochmal aus:</strong>

        <br><br>

        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif