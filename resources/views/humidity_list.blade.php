@extends('flowerberrypi')
 
@section('title', 'Sensoren')
@section('url', 'https://www.rezeptexperte.de/show_categories') 

@section('content')
        <h1>Humidities</h1>

		<div class="grid-container">
            <div class="grid-item">
        	<h2>Current</h2>
            @foreach($readings as $reading) 
              Sensor: {{ $reading->name }}<br>
              Value {{ $reading->value }} V<br>
              Classification: {{ $reading->classification }}<br><br>
            @endforeach
            
        	<h2>History</h2>
            
            
            <table border="1" cellpadding="5" cellspacing="0">
                <thead>
                    <tr>
                        <th>Sensor Id</th>
                        <th>Value</th>
                        <th>Classification</th>
                        <th>timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($history as $hist) 
                        <tr>
                            <td>{{ $hist->sensor_id }}</td>
                            <td>{{ $hist->value }}</td>
                            <td>{{ $hist->classification }}</td>
                            <td>{{ $hist->created_at }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>        
            
        	</div>
        	

@endsection