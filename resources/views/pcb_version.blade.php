@extends('flowerberrypi')
 
@section('title', 'Sensoren')
@section('submenu')
@include ('include_diagnosis_menu')  
@endsection
@section('content')

        <h1>PCB Version</h1>


		<div class="data-container">
            <div class="grid-item">
        	
        	<h2>Current</h2>
                {{$output}}
                
            </div>
            </div>

@endsection