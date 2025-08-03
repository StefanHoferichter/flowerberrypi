@extends('flowerberrypi')
 
@section('title', 'Sensoren')
@section('url', 'https://www.rezeptexperte.de/show_categories') 

@section('content')
        <h1>Sensors</h1>

		<div class="grid-container">
            <div class="grid-item">
        	<h2>Kategorien</h2>
            @foreach($sensors as $sensor) 
              <a  href="/show_recipes_from_category/{{ $sensor->id }}">{{ $sensor->name }}</a><br><br>
            @endforeach
        	</div>
        	

@endsection