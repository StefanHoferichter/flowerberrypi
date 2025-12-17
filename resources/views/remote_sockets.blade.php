@extends('flowerberrypi')
 
@section('title', 'Remote Sockets')
@section('submenu')
@include ('include_setup_menu')  
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
                        <th>Code On</th>
                        <th>Code Off</th>
                        <th>Zone</th>
                        <th>Save</th>
                    </tr>
                </thead>
                <tbody>
            @foreach($remoteSockets as $rs) 
             <tr>
             	<form method="post" action="/setup_433mhz_sockets">        @csrf
                <td>{{ $rs->id }}</td>
                <input type="hidden" name="id" value="{{ $rs->id }}">
                <td><input type="text" name="name" value="{{ $rs->name }}"></td>
                <td><input type="number" step="1" name="code_on" value="{{ $rs->code_on}}"></td>
                <td><input type="number" step="1" name="code_off" value="{{ $rs->code_off}}"></td>
                <td><select name="zone_id">
                @foreach($zones as $zone) 
                  <option value="{{$zone->id}}" @if ($zone->id === $rs->zone_id) selected="true" @endif>{{ $zone->name }}</option>
                @endforeach
                  </select></td>
                <td><button name="action" value="on" type="submit">Save</button></td>
             	</form>
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
                        <th>URL On</th>
                        <th>URL Off</th>
                        <th>Zone</th>
                        <th>Save</th>
                    </tr>
                </thead>
                <tbody>
            @foreach($wifiSockets as $ws) 
             <tr>
             	<form method="post" action="/setup_wifi_sockets">        @csrf
                <td>{{ $ws->id }}</td>
                <input type="hidden" name="id" value="{{ $ws->id }}">
                <td><input type="text" name="name" value="{{ $ws->name }}"></td>
                <td><input type="text" name="url_on" value="{{ $ws->url_on}}"></td>
                <td><input type="text" name="url_off" value="{{ $ws->url_off}}"></td>
                <td><select name="zone_id">
                @foreach($zones as $zone) 
                  <option value="{{$zone->id}}" @if ($zone->id === $ws->zone_id) selected="true" @endif>{{ $zone->name }}</option>
                @endforeach
                  </select></td>
                <td><button name="action" value="on" type="submit">Save</button></td>
             	</form>
            </tr>
        @endforeach
                    </tbody>
                </table>
        	</div>
        	</div>

@endsection