@extends('flowerberrypi')
 
@section('title', 'Shutdown')
@section('submenu')
@include ('include_system_menu')  
@endsection

@section('content')

        <h1>Shutdown</h1>


		<div class="grid-container">
            <div class="grid-item">
				The system will shut down in a few seconds.
			</div>
        </div>
@endsection