@extends('flowerberrypi')
 
@section('title', 'Sensoren')
@section('submenu')
@include ('include_watering_menu')  
@endsection

@section('content')

@include('time_horizon_menu')

        <h1>Triggered Waterings</h1>
        <h2>Trigger Watering</h2>

		<div class="data-container">
            <div class="grid-item">
			
			   <form class="" action="/trigger_watering" target="_top" method="post" novalidate="">
                  @csrf
        	<table border="0" cellpadding="5" cellspacing="0">
                   <tr><td>Zone:</td><td><select name="zone_id">
            @foreach($zones as $zone) 
              <option value="{{$zone->id}}">{{ $zone->name }} </option>
            @endforeach
              </select></td></tr>
              <tr><td>Watering Classification:</td><td><select name="watering_classification">
				@for ($i = 2; $i <= 3; $i++)
              		<option value="{{$i}}">{{ $i }} </option>
				@endfor                    
              </select></td></tr>
              <tr><td></td><td><button name="action" value="on" type="submit">Submit</button></td><td><button type="submit" formaction="/triggered_watering" formmethod="GET">Refresh</button></td></tr>
            </table>        
    	       </form>
			
            
        	<h2>History</h2>
        	
        	<table border="1" cellpadding="5" cellspacing="0">
                <thead>
                    <tr>
                        <th>Zone Id</th>
                        <th>Zone</th>
                        <th>Day</th>
                        <th>Hour</th>
                        <th>Watering</th>
                        <th>Executed</th>
                    </tr>
                </thead>
                <tbody>
        	
            @foreach($triggered_waterings as $dec) 
                        <tr>
              <td>{{ $dec->zone_id }} </a></td>
              <td><a href="/zone_details/{{$dec->zone_id}}">{{ $dec->zone->name }}</a></td>
              <td>{{ $dec->day }} </a></td>
				 <td> {{ $dec->hour  }} </td>
				 <td> {{ $dec->watering_classification }} </td>
				 <td> {{ $dec->executed }} </td>
                </tr>
             
            @endforeach
                </tbody>
            </table>        
        	
        	</div>
        	
        	

@endsection