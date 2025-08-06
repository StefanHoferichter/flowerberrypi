@extends('flowerberrypi')
 
@section('title', 'Sensoren')
@section('url', 'https://www.rezeptexperte.de/show_categories') 

@section('content')
        <h1>Cameras</h1>

		<div class="grid-container">
            <div class="grid-item">
        	<h2>Kategorien</h2>
        	
        	
            @foreach($cameras as $camera) 
              {{ $camera->name }}<br>
            	<form class="" action="/camera" target="_top" method="post" novalidate="">
                  @csrf
                    <input class="" type="hidden" name="id" value="{{ $camera->id }}">
                    <button name="action" value="on" type="submit">Click</button>
    	          </form>
            @endforeach
        	</div>
        	@if($pictures != null)
            	@foreach($pictures as $picture) 
        	           <img src='{{ $picture->filename }}'>
            	@endforeach
            @endif            
        	
        	
        	<br>

@endsection