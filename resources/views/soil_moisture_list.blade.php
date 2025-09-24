@extends('flowerberrypi')
 
@section('title', 'Sensoren')
@section('url', 'https://www.rezeptexperte.de/show_categories') 
@section('submenu')
@include ('include_sensor_menu')  
@endsection
@section('content')

@include('time_horizon_menu')

        <h1>Soil Moistures</h1>

		<div class="data-container">
            <div class="grid-item">
        	<h2>Current</h2>
            <table border="1" cellpadding="5" cellspacing="0">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Sensor</th>
                        <th>Value</th>
                        <th>Classification</th>
                        <th>Zone</th>
                    </tr>
                </thead>
                <tbody>
            @foreach($readings as $reading) 
             <tr>
                <td>{{ $reading->sensor_id }}</td>
                <td>{{ $reading->name }}</td>
                <td>{{ $reading->value  }}</td>
                <td>{{ $reading->classification }}</td>
                <td><a href="/zone_details/{{$reading->zone_id}}">{{ $reading->zone_name }}</a></td>
            </tr>
        @endforeach
            </tbody>
        </table>
            
        	<h2>History</h2>
            
            
            <table border="1" cellpadding="5" cellspacing="0">
                <thead>
                    <tr>
                        <th>Timestamp</th>
                        @foreach ($sensorIds as $sensorId)
                            <th>Sensor {{ $sensorId }}</th>
                            <th>Sensor {{ $sensorId }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
       @foreach ($history as $timestamp => $values)
             <tr>
                <td>{{ $timestamp }}</td>
                @foreach ($sensorIds as $sensorId)
                    <td>
                        {{ $values[$sensorId]['value'] ?? '' }}
                    </td>
                    <td>
                        {{ $values[$sensorId]['classification'] ?? '' }}
                    </td>
                @endforeach
            </tr>
        @endforeach
        </tbody>
    </table>
         
        	</div>

                    	<h2>Graph</h2>
      	
@include('chart')
        	</div>
        	

@endsection