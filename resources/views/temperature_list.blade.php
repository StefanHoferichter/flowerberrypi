@extends('flowerberrypi')
 
@section('title', 'Sensoren')
@section('url', 'https://www.rezeptexperte.de/show_categories') 
@section('submenu')
@include ('include_submenu')  
@endsection
@section('content')

@include('time_horizon_menu')

        <h1>Temperatures</h1>

        	<h2>Current</h2>
        	
		<div class="data-container">
            <div class="grid-item">
        	  <table border="1" cellpadding="5" cellspacing="0">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Sensor</th>
                        <th>Temperature</th>
                        <th>Humidity</th>
                        <th>Classification</th>
                        <th>Zone</th>
                    </tr>
                </thead>
                <tbody>
            @foreach($readings as $reading) 
             <tr>
                <td>{{ $reading->sensor_id }}</td>
                <td>{{ $reading->name }}</td>
                <td>{{ $reading->temperature }}</td>
                <td>{{ $reading->humidity }}</td>
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
                        <th>Sensor Id</th>
                        <th>Temperature</th>
                        <th>Humidity</th>
                        <th>Classification</th>
                        <th>timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($temp_history as $hist) 
                        <tr>
                            <td>{{ $hist->sensor_id }}</td>
                            <td>{{ $hist->value }}</td>
                            <td>{{ $hum_history[$loop->index]->value }}</td>
                            <td>{{ $hist->classification }}</td>
                            <td>{{ $hist->created_at }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>        
        	
                    	<h2>Graph</h2>
        	
@include('chart')
        	</div>
        	

@endsection