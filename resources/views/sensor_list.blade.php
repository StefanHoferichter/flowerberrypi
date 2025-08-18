@extends('flowerberrypi')
 
@section('title', 'Sensoren')
@section('url', 'https://www.rezeptexperte.de/show_categories') 

@section('content')
        <h1>Sensors</h1>

		<div class="grid-container">
            <div class="grid-item">
        	<h2>Kategorien</h2>
        	
        	<table border="1" cellpadding="5" cellspacing="0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>enbaled</th>
                        <th>Zone</th>
                        <th>enabled</th>
                    </tr>
                </thead>
                <tbody>
        	
            @foreach($sensors as $sensor) 
                        <tr>
                @php
                    if ($sensor->sensor_type == 1) 
                    {
                        $action = '/remote_sockets';
                    } 
                    else if ($sensor->sensor_type == 3) 
                    {
                        $action = '/relays';
                    } 
                    else if ($sensor->sensor_type == 4) 
                    {
                        $action = '/temperatures';
                    } 
                    else if ($sensor->sensor_type == 5) 
                    {
                        $action = '/distances';
                    } 
                    else if ($sensor->sensor_type == 6) 
                    {
                        $action = '/humidities';
                    } 
                    else if ($sensor->sensor_type == 7) 
                    {
                        $action = '/camera';
                    } 
                    else 
                    {
                        $action = '/';
                    }
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
               <td> {{ $sensor->zone->name }} </td>
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