@extends('flowerberrypi')
 
@section('title', 'Reboot')
@section('submenu')
@include ('include_system_menu')  
@endsection

@section('content')

        <h1>Reboot</h1>


		<div class="grid-container">
            <div class="grid-item">
				The system will reboot in a few seconds.
			</div>
        </div>
@endsection