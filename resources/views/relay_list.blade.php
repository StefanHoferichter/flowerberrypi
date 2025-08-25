@extends('flowerberrypi')
 
@section('title', 'Sensoren')
@section('url', 'https://www.rezeptexperte.de/show_categories') 

@section('content')
        <h1>Relays</h1>

@include('submenu')

		<div class="grid-container">
            <div class="grid-item">
        	
        	
            @foreach($relays as $r) 
              Id: {{ $r->id }}<br>
              {{ $r->name }}<br>
            	<form class="" action="/relays" target="_top" method="post" novalidate="">
                  @csrf
                    <input class="" type="hidden" name="id" value="{{ $r->id }}">
                    <button name="action" value="on" type="submit">On</button>
                    <button name="action" value="off" type="submit">Off</button>
    	          </form>
              Zone: {{ $r->zone_id }}<br><br>
            @endforeach
        	</div>
        	<br>
        	

@endsection