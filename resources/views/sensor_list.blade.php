@extends('flowerberrypi')
@section('title', 'Sensors')
@section('submenu')
@include ('include_sensor_menu')  
@endsection
@section('content')
        <h1>Sensors</h1>



		<div class="data-container">
            <div class="grid-item">
        	<h2>List</h2>
        	
        	<table border="1" cellpadding="5" cellspacing="0">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>enabled</th>
                        <th>Zone</th>
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
                    if ($sensor->zone->enabled == 1) 
                    {
                        $zone_enabled = 'enabled';
                    } 
                    else 
                    {
                        $zone_enabled = 'disabled';
                    } 
                @endphp            
               <tr>
           			<td class="{{$enabled}}"> {{ $sensor->id }} </td>
               
                @php
                        $action = \App\Helpers\GlobalStuff::get_url_from_sensor_type($sensor->sensor_type);
                @endphp            
              <td class="{{$enabled}}"><a class="{{$enabled}}" href="{{ $action }}">{{ $sensor->name }}</a></td>
				 <td  class="{{$enabled}}"><a class="{{$enabled}}" href="{{ $action }}">{{ $sensor->type->name }}</a></td>
				 <td  class="{{$enabled}}"> {{ $enabled }} </td>
               <td><a class="{{$zone_enabled}}" href="/zone_details/{{$sensor->zone->id}}">{{ $sensor->zone->name }}</a></td>
                @php
                @endphp            
				 <td  class="{{$zone_enabled}}"> {{ $zone_enabled }} </td>
                </tr>
             
            @endforeach
                </tbody>
            </table>        
        	</div>
        	

@endsection