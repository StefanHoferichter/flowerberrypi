@extends('flowerberrypi')
@section('submenu')
@include ('include_dummy_menu')     
@endsection
@section('title', 'Impressum')
@section('url', env('APP_URL').'/impressum')

@section('content')
        <h1>Impressum</h1>
       
Stefan Hoferichter Software Solutions<br>
Topsstr. 13<br>
10437 Berlin<br>
info@hoferichter.net<br>
<a target="_blank" href="https://www.hoferichter.net">hoferichter.net</a>
<br>
@endsection