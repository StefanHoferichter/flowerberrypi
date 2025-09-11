@extends('flowerberrypi')
 
@section('title', 'Sensoren')
@section('url', 'https://www.rezeptexperte.de/show_categories') 
@section('submenu')
@include ('include_submenu')  
@endsection
@section('content')
        <h1>Remote Sockets</h1>

		<div class="data-container">
            <div class="grid-item">
        	<h2>Current</h2>
        	
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
        </tr>
    </thead>
    <tbody>
            @foreach($remoteSockets as $remoteSocket) 
             <tr>
                <td>{{ $remoteSocket->id }}</td>
                <td>{{ $remoteSocket->name }}</td>
            	<td>            	
            	<form class="" action="/remote_sockets" target="_top" method="post" novalidate="">
                  @csrf
                    <input class="" type="hidden" name="id" value="{{ $remoteSocket->id }}">
                    <button name="action" value="on" type="submit">On</button>
                    <button name="action" value="off" type="submit">Off</button>
    	          </form>
				</td>
                <td>{{ $remoteSocket->zone_id  }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

        	</div>
        	<br>

@endsection