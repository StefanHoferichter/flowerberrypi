@extends('flowerberrypi')
 
@section('title', 'Sensoren')
@section('url', 'https://www.rezeptexperte.de/show_categories') 

@section('content')
        <h1>Distances</h1>

		<div class="grid-container">
            <div class="grid-item">
        	<h2>Kategorien</h2>
            @foreach($readings as $reading) 
              Sensor: {{ $reading->name }}<br>
              Distance {{ $reading->value }} cm<br>
            @endforeach
        	</div>
        	

@endsection