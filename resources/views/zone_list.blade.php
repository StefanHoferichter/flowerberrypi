@extends('flowerberrypi')
 
@section('title', 'Sensoren')
@section('url', 'https://www.rezeptexperte.de/show_categories') 

@section('content')
@include('submenu')

        <h1>Zones</h1>


		<div class="data-container">
            <div class="grid-item">
        	<table border="1" cellpadding="5" cellspacing="0">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Name</th>
                        <th>enabled</th>
                        <th>Sensors</th>
                        <th>Remote Sockets</th>
                    </tr>
                </thead>
                <tbody>
        	
            @foreach($zones as $zone) 
                        <tr>
              <td>{{ $zone->id }}</td>
              <td><a href="/zone_details/{{$zone->id}}">{{ $zone->name }} </a></td>
                @php
                    if ($zone->enabled == 1) 
                    {
                        $enabled = 'enabled';
                    } 
                    else 
                    {
                        $enabled = 'disabled';
                    }                     
                	$sensor_list="";
                	foreach($sensors as $sensor) 
                	{
                		if ($sensor->zone_id == $zone->id)
                		{
                			$action = \App\Helpers\GlobalStuff::get_url_from_sensor_type($sensor->sensor_type);
                			$sensor_list= $sensor_list . '<a href="' . $action . '">'. $sensor->name . '</a>, ';
                		}
                	}   
                	$rs_list="";
                	foreach($remoteSockets as $rs) 
                	{
                		if ($rs->zone_id == $zone->id)
                			$rs_list= $rs_list . $rs->name . ',';
                		
                	}   
                @endphp
                            
				 <td> {{ $enabled }} </td>
				 <td> {!! $sensor_list !!} </td>
				 <td> <a href="/remote_sockets">{{ $rs_list }} </a></td>
                </tr>
             
            @endforeach
                </tbody>
            </table>        
        	</div>
        	</div>
        	

@endsection