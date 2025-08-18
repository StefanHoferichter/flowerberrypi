@extends('flowerberrypi')
 
@section('title', 'Sensoren')
@section('url', 'https://www.rezeptexperte.de/show_categories') 

@section('content')
        <h1>Cycles</h1>

		<div class="grid-container">
            <div class="grid-item">
        	<h2>Kategorien</h2>
        	
        	<table border="1" cellpadding="5" cellspacing="0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>enabled</th>
                        <th>Sensors</th>
                    </tr>
                </thead>
                <tbody>
        	
            @foreach($zones as $zone) 
                        <tr>
              <td><a href="zone_details/{{$zone->id}}">{{ $zone->name }} </a></td>
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
                			$sensor_list= $sensor_list . $sensor->name . ',';
                		
                	}   
                @endphp
                            
				 <td> {{ $enabled }} </td>
				 <td> {{ $sensor_list }} </td>
                </tr>
             
            @endforeach
                </tbody>
            </table>        
        	</div>
        	

@endsection