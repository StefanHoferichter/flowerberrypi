@extends('flowerberrypi')
 
@section('title', 'Sensoren')
@section('url', 'https://www.rezeptexperte.de/show_categories') 
@section('submenu')
@include ('include_dummy_menu')  
@endsection

@section('content')

@include('time_horizon_menu')

        <h1>Zone Details {{ $zone->name }}</h1>

		<div class="data-container">

        	<table border="1" cellpadding="5" cellspacing="0">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Zone</th>
                        <th>Has Watering</th>
                        <th>Rain Sensitive</th>
                        <th>Outdoor</th>
                    </tr>
                </thead>
                <tbody>
                        <tr>
              <td> {{ $zone->id }}</td>
				 <td>{{ $zone->name }}</td>
				 <td>{{ $zone->has_watering }}</td>
				 <td>{{ $zone->rain_sensitive }}</td>
				 <td>{{ $zone->outdoor }}</td>
                </tr>
                </tbody>
            </table>        
            
        	<h2>Sensors</h2>
            <table border="1" cellpadding="5" cellspacing="0">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Name</th>
                        <th>enabled</th>
                    </tr>
                </thead>
                <tbody>
        	
            @foreach($sensors as $sensor) 
                @php
                    if ($sensor->enabled == 1) 
                    {
                        $enabled = 'enabled';
                    } 
                    else 
                    {
                        $enabled = 'disabled';
                    } 
                    $action = \App\Helpers\GlobalStuff::get_url_from_sensor_type($sensor->sensor_type);
                @endphp            
               <tr>
           			<td class="{{$enabled}}"> {{ $sensor->id }} </td>
              <td class="{{$enabled}}"><a  class="{{$enabled}}" href="{{ $action }}">{{ $sensor->name }} </a></td>
				 <td class="{{$enabled}}"> {{ $enabled }} </td>
                </tr>
             
            @endforeach
                </tbody>
            </table>        
            
			
        	<h2>Watering Decisions</h2>
        	<table border="1" cellpadding="5" cellspacing="0">
                <thead>
                    <tr>
                        <th>Day</th>
                        <th>Time of Day</th>
                        <th>Forecast</th>
                        <th>Soil Moisture</th>
                        <th>Tank</th>
                        <th>Watering</th>
                        <th>Executed</th>
                    </tr>
                </thead>
                <tbody>
            @foreach($decisions as $dec) 
                        <tr>
              <td>{{ $dec->day }} </a></td>
				 <td> {{ $dec->tod  }} </td>
				 <td> {{ $dec->forecast_classification }} </td>
				 <td> {{ $dec->soil_moisture_classification }} </td>
				 <td> {{ $dec->tank_classification }} </td>
				 <td> {{ $dec->watering_classification }} </td>
				 <td> {{ $dec->executed }} </td>
                </tr>
            @endforeach
                </tbody>
            </table>        

        	<h2>Manual Watering</h2>
        	<table border="1" cellpadding="5" cellspacing="0">
                <thead>
                    <tr>
                        <th>Day</th>
                        <th>Hour</th>
                        <th>Watering</th>
                    </tr>
                </thead>
                <tbody>
            @foreach($manual_decisions as $dec) 
                        <tr>
              <td>{{ $dec->day }} </a></td>
				 <td> {{ $dec->hour  }} </td>
				 <td> {{ $dec->watering_classification }} </td>
                </tr>
            @endforeach
                </tbody>
            </table>        

        	<h2>What-If Decisions</h2>
        	<table border="1" cellpadding="5" cellspacing="0">
                <thead>
                    <tr>
                        <th>Day</th>
                        <th>Forecast</th>
                        <th>Soil Moisture</th>
                        <th>Tank</th>
                        <th>Watering</th>
                    </tr>
                </thead>
                <tbody>
                        <tr>
              <td>{{ $whatif_decision->day }} </a></td>
				 <td> {{ $whatif_decision->forecast_classification }} </td>
				 <td> {{ $whatif_decision->soil_moisture_classification }} </td>
				 <td> {{ $whatif_decision->tank_classification }} </td>
				 <td> {{ $whatif_decision->watering_classification }} </td>
                </tr>
                </tbody>
            </table>        
        	
        	</div>

                    	<h2>Graph</h2>
        	
@include('chart')

        	

@endsection