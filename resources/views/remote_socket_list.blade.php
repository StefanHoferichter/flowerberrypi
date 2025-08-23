@extends('flowerberrypi')
 
@section('title', 'Sensoren')
@section('url', 'https://www.rezeptexperte.de/show_categories') 

@section('content')
        <h1>Remote Sockets</h1>

		<div class="grid-container">
            <div class="grid-item">
        	<h2>Kategorien</h2>
        	
        	
            @foreach($remoteSockets as $remoteSocket) 
              Id {{ $remoteSocket->id }}<br>
              {{ $remoteSocket->name }}<br>
            	<form class="" action="/remote_sockets" target="_top" method="post" novalidate="">
                  @csrf
                    <input class="" type="hidden" name="id" value="{{ $remoteSocket->id }}">
                    <button name="action" value="on" type="submit">On</button>
                    <button name="action" value="off" type="submit">Off</button>
    	          </form>
              Zone {{ $remoteSocket->zone_id }}<br><br>
            @endforeach
        	</div>
        	<br>

@endsection