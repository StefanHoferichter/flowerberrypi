@extends('flowerberrypi')
@section('title', 'Sensoren')
@section('submenu')
@include ('include_setup_menu')  
@endsection
@section('content')
        <h1>Sensors</h1>



		<div class="data-container">
            <div class="grid-item">
        	<h2>List</h2>
        	
        	<table border="1" cellpadding="5" cellspacing="0">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>enabled</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>GPIO In</th>
                        <th>GPIO Out</th>
                        <th>GPIO Extra</th>
                        <th>Zone</th>
                        <th>Save</th>
                    </tr>
                </thead>
                <tbody>
        	
            @foreach($sensors as $sensor) 
               <tr>
             		<form method="post" action="/setup_sensors">        @csrf
           			<td> {{ $sensor->id }} </td>
                	<input type="hidden" name="id" value="{{ $sensor->id }}">
                	<td><input type="checkbox" name="enabled" value="1" @if ($sensor->enabled> 0) checked @endif></td>
                	<td><input type="text" name="name" value="{{ $sensor->name }}" size="50"></td>
                    <td><select name="sensor_type">
                    @foreach($sensor_types as $st) 
                      <option value="{{$st->id}}" @if ($st->id === $sensor->sensor_type) selected="true" @endif>{{ $st->name }}</option>
                    @endforeach
                      </select></td>
                	<td><input type="number" step="1" name="gpio_in" value="{{ $sensor->gpio_in }}"></td>
                	<td><input type="number" step="1" name="gpio_out" value="{{ $sensor->gpio_out }}"></td>
                	<td><input type="number" step="1" name="gpio_extra" value="{{ $sensor->gpio_extra }}"></td>
                    <td><select name="zone_id">
                    @foreach($zones as $zone) 
                      <option value="{{$zone->id}}" @if ($zone->id === $sensor->zone_id) selected="true" @endif>{{ $zone->name }}</option>
                    @endforeach
                   </select></td>
                   <td><button name="action" value="on" type="submit">Save</button></td>
             	</form>
                </tr>
            @endforeach
                </tbody>
            </table>        
        	</div>
        	

@endsection