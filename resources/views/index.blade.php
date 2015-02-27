@extends('layouts.master')
    @section('title')
        <title>Parrainage - Showtime BDE TMSP</title>
    @stop

    @section('content')

    <div class="col-md-12">
        <p class="lead text-center">Choose your protégé or godfather and live your life together !</p>
    </div>

    <div id="box-parrains" class="col-md-6 col-md-offset-3" role="tabpanel">
        <ul id="box-selection" class="nav nav-tabs nav-justified" role="tablist">
            <li role="presentation" class="active">
                <a href="#form-godfather" aria-controls="form-godfather" role="tab" data-toggle="tab">
                    I need a protégé
                </a>
            </li>
            <li role="presentation">
                <a href="#form-protege" aria-controls="form-protege" role="tab" data-toggle="tab">
                    I need a godfather
                </a>
            </li>
        </ul>

        <div class="tab-content col-md-12">
            <div id="form-godfather" class="tab-pane active" role="tabpanel">
                <?= Former::vertical_open('myform')
                    ->class("col-md-10 col-md-offset-1"); ?>
                <?= Former::email('Godfather') ?>
                <?= Former::email('Protege') ?>
                <?= Former::actions()
                    ->class("input_rose text-center")
                    ->large_primary_submit('Submit'); ?>
                <?= Former::close() ?>
            </div>
            <div id="form-protege" class="tab-pane" role="tabpanel">
                <?= Former::vertical_open('myform')
                    ->class("col-md-10 col-md-offset-1"); ?>
                <?= Former::email('Protege') ?>
                <?= Former::email('Godfather') ?>
                    <?= Former::actions()
                    ->class("input_rose text-center")
                    ->large_primary_submit('Submit'); ?>
                <?= Former::close() ?>
            </div>
        </div>
    </div>
    @stop
