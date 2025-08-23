@extends('flowerberrypi')
 
@section('title', 'Sensoren')
@section('url', 'https://www.rezeptexperte.de/show_categories') 

@section('content')
        <h1>Manual Waterings</h1>

		<div class="grid-container">
            <div class="grid-item">
			
            
        	<h2>History</h2>
        	
        	<table border="1" cellpadding="5" cellspacing="0">
                <thead>
                    <tr>
                        <th>Day</th>
                        <th>Time of Day</th>
                        <th>Type</th>
                        <th>Forecast</th>
                        <th>Humidity</th>
                        <th>Watering</th>
                    </tr>
                </thead>
                <tbody>
        	
            @foreach($manual_waterings as $dec) 
                        <tr>
              <td>{{ $dec->day }} </a></td>
				 <td> {{ $dec->tod  }} </td>
				 <td> {{ $dec->type }} </td>
				 <td> {{ $dec->forecast_classification }} </td>
				 <td> {{ $dec->humidity_classification }} </td>
				 <td> {{ $dec->watering_classification }} </td>
                </tr>
             
            @endforeach
                </tbody>
            </table>        
        	
        	</div>
        	
        	

@endsection