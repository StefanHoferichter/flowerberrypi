@extends('flowerberrypi')
 
@section('title', 'Sensoren')
@section('submenu')
@include ('include_watering_menu')  
@endsection

@section('content')

@include('include_time_horizon_menu')

        	
        	        <h1>Automated Waterings</h1>
        	
		<div class="data-container">
            <div class="grid-item">

        	<h2>History</h2>
        	
        	<table border="1" cellpadding="5" cellspacing="0">
                <thead>
                    <tr>
                        <th>Zone Id</th>
                        <th>Zone</th>
                        <th>Day</th>
                        <th>Time of Day</th>
                        <th>Watering</th>
                        <th>Executed</th>
                    </tr>
                </thead>
                <tbody>
        	
            @foreach($waterings as $dec) 
                        <tr>
              <td>{{ $dec->zone_id }} </a></td>
              <td><a href="/zone_details/{{$dec->zone_id}}">{{ $dec->zone->name }}</a></td>
              <td>{{ $dec->day }} </a></td>
				 <td> {{ $dec->tod  }} </td>
				 <td> {{ $dec->watering_classification }} </td>
				 <td> {{ $dec->executed }} </td>
                </tr>
             
            @endforeach
                </tbody>
            </table>        
        	
        	</div>
        	

@endsection