@extends('flowerberrypi')
 
@section('title', 'Zones')
@section('submenu')
@include ('include_setup_menu')  
@endsection
@section('content')

        <h1>Zones</h1>


		<div class="data-container">
            <div class="grid-item">
        	<table border="1" cellpadding="5" cellspacing="0">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>enabled</th>
                        <th>Name</th>
                        <th>Has Watering</th>
                        <th>Rain Sensitive</th>
                        <th>Outdoor</th>
                        <th>Save</th>
                    </tr>
                </thead>
                <tbody>
        	
            @foreach($zones as $zone) 
                  <tr>
                 	<form method="post" action="/setup_zones">        @csrf
                    <td>{{ $zone->id }}</td>
	                <input type="hidden" name="id" value="{{ $zone->id }}">
                	<td><input type="checkbox" name="enabled" value="1" @if ($zone->enabled> 0) checked @endif></td>
                	<td><input type="text" name="name" value="{{ $zone->name }}" size="50"></td>
                	<td><input type="checkbox" name="has_watering" value="1" @if ($zone->has_watering > 0) checked @endif></td>
                	<td><input type="checkbox" name="rain_sensitive" value="1" @if ($zone->rain_sensitive > 0) checked @endif></td>
                	<td><input type="checkbox" name="outdoor" value="1" @if ($zone->outdoor > 0) checked @endif></td>
                    <td><button name="action" value="on" type="submit">Save</button></td>
                 	</form>
                  </tr>
             
            @endforeach
                </tbody>
            </table>        
        	</div>
        	</div>
        	

@endsection