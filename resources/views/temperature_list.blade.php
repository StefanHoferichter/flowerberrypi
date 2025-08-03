@extends('flowerberrypi')
 
@section('title', 'Sensoren')
@section('url', 'https://www.rezeptexperte.de/show_categories') 

@section('content')
        <h1>Sensors</h1>

		<div class="grid-container">
            <div class="grid-item">
        	<h2>Kategorien</h2>
            @foreach($readings as $reading) 
              Sensor: {{ $reading->name }}<br>
              Temperatur {{ $reading->temperature }} °C<br>
              Feuchtigkeit {{ $reading->humidity }} %<br><br>
            @endforeach
        	</div>
        	

@endsection