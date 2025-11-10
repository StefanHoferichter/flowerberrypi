@extends('flowerberrypi')
 
@section('title', 'Sensoren')
@section('submenu')
@include ('include_sensor_menu')  
@endsection
@section('content')

@include('include_time_horizon_menu')

        <h1>Cameras</h1>

        <h2>Current</h2>

            @foreach($cameras as $camera) 
              {{ $camera->name }}<br>
            	<form class="" action="/camera" target="_top" method="post" novalidate="">
                  @csrf
                    <input class="" type="hidden" name="id" value="{{ $camera->id }}">
                    <button name="action" value="on" type="submit">Click</button>
    	          </form>
            @endforeach
        	@if($pictures != null)
            	@foreach($pictures as $picture) 
        	           <img  width='800' src='{{ $picture->filename }}'>
            	@endforeach
            @endif            
        	
        <h2>History</h2>
		<div class="grid-container">
        	

        	@if($history != null)
            	@foreach($history as $picture) 
            <div class="grid-item">
        	           <img width='500' src='{{ $picture->filename }}'><br>
        	           Day: {{ $picture->day }} Time of day: {{ $picture->tod }}
        	</div>
            	@endforeach
            @endif            
        	</div>
        	
        	
        	<br>

@endsection