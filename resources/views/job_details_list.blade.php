@extends('flowerberrypi')
 
@section('title', 'Sensoren')
@section('url', 'https://www.rezeptexperte.de/show_categories') 

@section('content')

        <h1>Job Details</h1>


        	<h2>Sensor Values</h2>
        	
		<div class="data-container">
            <div class="grid-item">
            <table border="1" cellpadding="5" cellspacing="0">
                <thead>
                    <tr>
                        <th>Day</th>
                        <th>Hour</th>
                        <th>Sensor Id</th>
                        <th>Sensor</th>
                        <th>Type</th>
                        <th>Value</th>
                        <th>Classification</th>
                        <th>Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sensor_values as $hist) 
                        <tr>
                            <td>{{ $hist->day }}</td>
                            <td>{{ $hist->hour }}</td>
                            <td>{{ $hist->sensor_id }}</td>
                            <td>{{ $hist->sensor->name }}</td>
                            <td>{{ $hist->sensor_value_type->name }}</td>
                            <td>{{ $hist->value }}</td>
                            <td>{{ $hist->classification }}</td>
                            <td>{{ $hist->created_at }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>        
        	
        	</div>
        
        	<h2>Pictures</h2>

		<div class="grid-container">
        	@if($pictures != null)
            	@foreach($pictures as $picture) 
            <div class="grid-item">
        	           <img width='500' src='/{{$picture->filename}}'><br>
        	           Day: {{ $picture->day }} Time of day: {{ $picture->tod }}
        	</div>
            	@endforeach
            @endif            
        	</div>
        	
        	
            <h2>Watering Decisions</h2>
        	<table border="1" cellpadding="5" cellspacing="0">
                <thead>
                    <tr>
                        <th>Day</th>
                        <th>Time of Day</th>
                        <th>Zone</th>
                        <th>Type</th>
                        <th>Forecast</th>
                        <th>Soil Moisture</th>
                        <th>Distance</th>
                        <th>Watering</th>
                    </tr>
                </thead>
                <tbody>
            @foreach($watering_decisions as $dec) 
                        <tr>
              <td>{{ $dec->day }} </a></td>
				 <td> {{ $dec->tod  }} </td>
				 <td> {{ $dec->zone_id  }} </td>
				 <td> {{ $dec->type }} </td>
				 <td> {{ $dec->forecast_classification }} </td>
				 <td> {{ $dec->humidity_classification }} </td>
				 <td> {{ $dec->distance_classification }} </td>
				 <td> {{ $dec->watering_classification }} </td>
                </tr>
            @endforeach
                </tbody>
            </table>        
        	

@endsection