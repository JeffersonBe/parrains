@extends('layouts.master')
    @section('title')
        <title>Parrainage - Showtime BDE TMSP</title>
    @stop

    @section('content')
    <div class="col-md-12">
        <p class="lead text-center">Choose your protégé or godfather and live your life together !</p>
    </div>
    <div id="box-selection" class="col-md-8 col-md-offset-2">
        <ul>
            <li class="text-center active">I need a protégé</li>
            <li class="text-center">I need a godfather</li>
        </ul>
        <hr class="hr-style"/>
        <form class="col-md-8 col-md-offset-2">
          <div class="form-group">
            <label for="exampleInputEmail1">Enter your adress mail</label>
            <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
          </div>
          <div class="form-group">
            <label for="exampleInputEmail1">Enter your protégé’s mail</label>
            <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
          </div>
        </form>
    </div>
    <div class="col-md-12">
        <p class="text-center">
            <button type="button" class="btn btn-primary btn-lg bg-color-rose">Match</button>
        </p>
    </div>
    @stop
