@extends('flowerberrypi')
 
@section('title', 'Sensoren')
@section('url', 'https://www.rezeptexperte.de/show_categories') 

@section('content')
@include('submenu')

        <h1>Manual Waterings</h1>


		<div class="data-container">
            <div class="grid-item">
			
			   <form class="" action="/manual_watering" target="_top" method="post" novalidate="">
                  @csrf
        	<table border="0" cellpadding="5" cellspacing="0">
                   <tr><td>Zone:</td><td><select name="zone_id">
            @foreach($zones as $zone) 
              <option value="{{$zone->id}}">{{ $zone->name }} </option>
            @endforeach
              </select></td></tr>
              <tr><td>Watering Classification:</td><td><select name="watering_classification">
				@for ($i = 1; $i <= 3; $i++)
              		<option value="{{$i}}">{{ $i }} </option>
				@endfor                    
              </select></td></tr>
              <tr><td>Date:</td><td><input type="date" name="day" id="day" value="{{ \Carbon\Carbon::now()->toDateString() }}">
              
              <tr><td>Hour:</td><td><select name="hour">
				@for ($i = 1; $i <= 24; $i++)
              		<option value="{{$i}}">{{ $i }} </option>
				@endfor                    
              </select></td></tr>
              <tr><td></td><td><button name="action" value="on" type="submit">Submit</button></td></tr>
            </table>        
    	       </form>
			
            
        	<h2>History</h2>
        	
        	<table border="1" cellpadding="5" cellspacing="0">
                <thead>
                    <tr>
                        <th>Zone</th>
                        <th>Day</th>
                        <th>Hour</th>
                        <th>Watering</th>
                    </tr>
                </thead>
                <tbody>
        	
            @foreach($manual_waterings as $dec) 
                        <tr>
              <td>{{ $dec->zone_id }} </a></td>
              <td>{{ $dec->day }} </a></td>
				 <td> {{ $dec->hour  }} </td>
				 <td> {{ $dec->watering_classification }} </td>
                </tr>
             
            @endforeach
                </tbody>
            </table>        
        	
        	</div>
        	
        	

@endsection