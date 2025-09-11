@extends('flowerberrypi')
 
@section('title', 'Sensoren')
@section('url', 'https://www.rezeptexperte.de/show_categories') 
@section('submenu')
@include ('include_setup_menu')  
@endsection
@section('content')
        <h1>Percentage Conversions</h1>

		<div class="data-container">
            <div class="grid-item">
        	<h2>Current</h2>
        	
        	  <table border="1" cellpadding="5" cellspacing="0">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Sensor ID</th>
                        <th>Lower Limit</th>
                        <th>Upper Limit</th>
                        <th>Invert</th>
                    </tr>
                </thead>
                <tbody>
        </tr>
    </thead>
    <tbody>
            @foreach($pcs as $pc) 
             <tr>
                <td>{{ $pc->id }}</td>
                <td>{{ $pc->sensor_id }}</td>
                <td>{{ $pc->lower_limit }}</td>
                <td>{{ $pc->upper_limit }}</td>
                <td>{{ $pc->invert }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

        	</div>
        	<br>

@endsection