@extends('flowerberrypi')
 
@section('title', 'Relays')
@section('submenu')
@include ('include_sensor_menu')  
@endsection
@section('content')
        <h1>Relays</h1>


		<div class="data-container">
            <div class="grid-item">
        	
        	  <table border="1" cellpadding="5" cellspacing="0">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Sensor</th>
                        <th>Control</th>
                        <th>Zone</th>
                    </tr>
                </thead>
                <tbody>
        </tr>
    </thead>
    <tbody>
            @foreach($relays as $r) 
             <tr>
                <td>{{ $r->id }}</td>
                <td>{{ $r->name }}</td>
            	<td>            	
            	<form class="" action="/relays" target="_top" method="post" novalidate="">
                  @csrf
                    <input class="" type="hidden" name="id" value="{{ $r->id }}">
                    <button name="action" value="on" type="submit">On</button>
                    <button name="action" value="off" type="submit">Off</button>
    	          </form>
				</td>
                <td><a href="/zone_details/{{$r->zone_id}}">{{ $r->zone->name }}</a></td>
            </tr>
        @endforeach
    </tbody>
</table>
        	
        	</div>
        	<br>
        	

@endsection