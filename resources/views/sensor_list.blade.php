@extends('flowerberrypi')
 
@section('title', 'Sensoren')
@section('url', 'https://www.rezeptexperte.de/show_categories') 

@section('content')
        <h1>Sensors</h1>

		<div class="grid-container">
            <div class="grid-item">
        	<h2>Kategorien</h2>
            @foreach($sensors as $sensor) 
                @php
                    if ($sensor->sensor_type == 1) 
                    {
                        $action = '/remote_sockets';
                    } 
                    else if ($sensor->sensor_type == 3) 
                    {
                        $action = '/relays';
                    } 
                    else if ($sensor->sensor_type == 4) 
                    {
                        $action = '/temperatures';
                    } 
                    else if ($sensor->sensor_type == 5) 
                    {
                        $action = '/distances';
                    } 
                    else 
                    {
                        $action = '/';
                    }
                @endphp            
              <a  href="{{ $action }}">{{ $sensor->name }}</a><br><br>
            @endforeach
        	</div>
        	

@endsection