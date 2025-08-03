@extends('flowerberrypi')
 
@section('title', 'Sensoren')
@section('url', 'https://www.rezeptexperte.de/show_categories') 

@section('content')
        <h1>Relays</h1>

		<div class="grid-container">
            <div class="grid-item">
        	
        	
            @foreach($relays as $r) 
              {{ $r->name }}<br>
            	<form class="" action="/relays" target="_top" method="post" novalidate="">
                  @csrf
                    <input class="" type="hidden" name="id" value="{{ $r->id }}">
                    <button name="action" value="on" type="submit">On</button>
                    <button name="action" value="off" type="submit">Off</button>
    	          </form>
            @endforeach
        	</div>
        	<br>
        	

@endsection