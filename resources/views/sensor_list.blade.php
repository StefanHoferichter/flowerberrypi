@extends('flowerberrypi')
 
@section('title', 'Sensoren')
@section('url', 'https://www.rezeptexperte.de/show_categories') 

@section('content')
@include('submenu')
        <h1>Sensors</h1>



		<div class="data-container">
            <div class="grid-item">
        	<h2>List</h2>
        	
        	<table border="1" cellpadding="5" cellspacing="0">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Name</th>
                        <th>enabled</th>
                        <th>Zone</th>
                        <th>enabled</th>
                    </tr>
                </thead>
                <tbody>
        	
            @foreach($sensors as $sensor) 
               <tr>
           			<td> {{ $sensor->id }} </td>
               
                @php
                        $action = \App\Helpers\GlobalStuff::get_url_from_sensor_type($sensor->sensor_type);
                @endphp            
              <td><a  href="{{ $action }}">{{ $sensor->name }} </a></td>
                @php
                    if ($sensor->enabled == 1) 
                    {
                        $enabled = 'enabled';
                    } 
                    else 
                    {
                        $enabled = 'disabled';
                    } 
                @endphp            
				 <td> {{ $enabled }} </td>
               <td><a  href="/zone_details/{{$sensor->zone->id}}">{{ $sensor->zone->name }}</a></td>
                @php
                    if ($sensor->zone->enabled == 1) 
                    {
                        $enabled = 'enabled';
                    } 
                    else 
                    {
                        $enabled = 'disabled';
                    } 
                @endphp            
				 <td> {{ $enabled }} </td>
                </tr>
             
            @endforeach
                </tbody>
            </table>        
        	</div>
        	

@endsection