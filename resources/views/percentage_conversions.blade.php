@extends('flowerberrypi')
 
@section('title', 'Percentage Conversions')
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
                        <th>Save</th>
                    </tr>
                </thead>
                <tbody>
        </tr>
    </thead>
    <tbody>
            @foreach($pcs as $pc) 
             <tr>
             	<form method="post" action="/setup_percentage_conversions">        @csrf
                <td>{{ $pc->id }}</td>
                <input type="hidden" name="id" value="{{ $pc->id }}">
                <td>{{ $pc->sensor->name }}</td>
                <td><input type="number" step="0.1" name="lower_limit" value="{{ $pc->lower_limit }}"></td>
                <td><input type="number" step="0.1" name="upper_limit" value="{{ $pc->upper_limit }}"></td>
                <td><input type="checkbox" name="invert" value="1" @if ($pc->invert > 0) checked @endif></td>
                <td><button name="action" value="on" type="submit">Save</button></td>
             	</form>
            </tr>
        @endforeach
    </tbody>
</table>

        	</div>
        	<br>

@endsection