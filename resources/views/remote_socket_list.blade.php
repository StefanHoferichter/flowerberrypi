@extends('flowerberrypi')
 
@section('title', 'Sensoren')
@section('submenu')
@include ('include_sensor_menu')  
@endsection
@section('content')
        <h1>Remote Sockets</h1>

		<div class="data-container">
            <div class="grid-item">
        	<h2>433MHz Sockets</h2>
        	
        	  <table border="1" cellpadding="5" cellspacing="0">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Sensor</th>
                        <th>Control</th>
                        <th>Zone</th>
                    </tr>
                </thead>
                <tbody>
            <tbody>
            @foreach($remoteSockets as $remoteSocket) 
             <tr>
                <td>{{ $remoteSocket->id }}</td>
                <td>{{ $remoteSocket->name }}</td>
            	<td>            	
            	<form class="" action="/433mhz_sockets" target="_top" method="post" novalidate="">
                  @csrf
                    <input class="" type="hidden" name="id" value="{{ $remoteSocket->id }}">
                    <button name="action" value="on" type="submit">On</button>
                    <button name="action" value="off" type="submit">Off</button>
    	          </form>
				</td>
                <td><a href="/zone_details/{{$remoteSocket->zone->id}}">{{ $remoteSocket->zone->name }}</a></td>
                
            </tr>
        @endforeach
                </tbody>
            </table>
        	</div>
    	</div>
    	<br>

		<div class="data-container">
            <div class="grid-item">
        	<h2>WiFi Sockets</h2>
        	
        	  <table border="1" cellpadding="5" cellspacing="0">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Sensor</th>
                        <th>Control</th>
                        <th>Zone</th>
                    </tr>
                </thead>
    			<tbody>
                    @foreach($wifiSockets as $wifiSocket) 
                     <tr>
                        <td>{{ $wifiSocket->id }}</td>
                        <td>{{ $wifiSocket->name }}</td>
                    	<td>            	
                    	<form class="" action="/wifi_sockets" target="_top" method="post" novalidate="">
                          @csrf
                            <input class="" type="hidden" name="id" value="{{ $wifiSocket->id }}">
                            <button name="action" value="on" type="submit">On</button>
                            <button name="action" value="off" type="submit">Off</button>
            	          </form>
        				</td>
                        <td><a href="/zone_details/{{$wifiSocket->zone->id}}">{{ $wifiSocket->zone->name }}</a></td>
                        
                    </tr>
                @endforeach
            </tbody>
        </table>
        	</div>
        	</div>
        	<br>

@endsection