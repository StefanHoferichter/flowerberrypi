@extends('flowerberrypi')
 
@section('title', 'Diagnosis')
@section('url', 'https://www.rezeptexperte.de/show_categories') 
@section('submenu')
@include ('include_diagnosis_menu')  
@endsection

@section('content')

        <h1>Diagnosis</h1>


		<div class="grid-container">
            <div class="grid-item">
				Here you can diagnose your hardware.
			</div>
        </div>
@endsection