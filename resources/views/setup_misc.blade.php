@extends('flowerberrypi')
 
@section('title', 'Sensoren')
@section('submenu')
@include ('include_setup_menu')  
@endsection
@section('content')
                @php
                    $timezones = [
                        // Europe
                        'Europe/London',
                        'Europe/Berlin',
                        'Europe/Paris',
                        'Europe/Rome',
                        'Europe/Madrid',
                        'Europe/Amsterdam',
                        'Europe/Brussels',
                        'Europe/Vienna',
                        'Europe/Zurich',
                        'Europe/Stockholm',
                        'Europe/Copenhagen',
                        'Europe/Oslo',
                        'Europe/Helsinki',
                        'Europe/Athens',
                        'Europe/Kiev',
                        'Europe/Moscow',
            
                        // North America
                        'America/New_York',
                        'America/Chicago',
                        'America/Denver',
                        'America/Los_Angeles',
                        'America/Phoenix',
                        'America/Anchorage',
                        'America/Honolulu',
            
                        // South America
                        'America/Sao_Paulo',
                        'America/Buenos_Aires',
                        'America/Santiago',
                        'America/Lima',
            
                        // Asia
                        'Asia/Dubai',
                        'Asia/Kolkata',
                        'Asia/Karachi',
                        'Asia/Bangkok',
                        'Asia/Singapore',
                        'Asia/Tokyo',
                        'Asia/Seoul',
                        'Asia/Shanghai',
                        'Asia/Hong_Kong',
                        'Asia/Taipei',
            
                        // Oceania
                        'Australia/Perth',
                        'Australia/Adelaide',
                        'Australia/Sydney',
                        'Pacific/Auckland',
            
                        // Africa
                        'Africa/Cairo',
                        'Africa/Johannesburg',
                        'Africa/Nairobi',
                    ];
                @endphp

        <h1>Misc</h1>

        <h2>Password</h2>

     	@if(session('status'))
            <div class="data-container">
                {{ session('status') }}
            </div>
        @endif


		<div class="data-container">
            <div class="grid-item">
        	<table border="1" cellpadding="5" cellspacing="0">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Enter Password</th>
                        <th>Confirm Password</th>
                        <th>Message</th>
                        <th>Save</th>
                    </tr>
                </thead>
                <tbody>
        	
            @foreach($users as $user) 
                  <tr>
                 	<form method="post" action="/setup_password">        @csrf
                    <td>{{ $user->id }}</td>
	                <input type="hidden" name="id" value="{{ $user->id }}">
                	<td>{{ $user->name }}</td>
                	<td><input type="password" name="new_password"  value="" size="20"></td>
                	<td><input type="password" name="new_password_confirmation"  value="" size="20"></td>
                	<td>
                    	@error('new_password')
                            <p class="highlighted">{{ $message }}</p>
                        @enderror
                    </td>
                    <td><button name="action" value="on" type="submit">Save</button></td>
                 	</form>
                  </tr>
            @endforeach
                </tbody>
            </table>        
        	</div>
        </div>
        
        <h2>Location</h2>
        	
		<div class="data-container">
            <div class="grid-item">
        	<table border="1" cellpadding="5" cellspacing="0">
                <thead>
                    <tr>
                        <th>Latitude</th>
                        <th>Logitude</th>
                        <th>Timezone</th>
                        <th>Message</th>
                        <th>Save</th>
                    </tr>
                </thead>
                <tbody>
        	
            @foreach($locations as $location) 
                  <tr>
                 	<form method="post" action="/setup_location">        @csrf
	                <input type="hidden" name="id" value="{{ $location->id }}">
                	<td><input type="number" name="latitude" step="0.0001" min="-90" max="90" required  value="{{ $location->latitude }}" size="20"></td>
                	<td><input type="number" name="longitude" step="0.0001" min="-180" max="180" required  value="{{ $location->longitude }}" size="20"></td>
                	<td>
                    	<select name="timezone" id="timezone" class="form-select">
                            @foreach ($timezones as $tz)
                                <option value="{{ $tz }}" @selected(old('timezone', $location->timezone ?? '') === $tz)>
                                    {{ $tz }}
                                </option>
                            @endforeach
                        </select>
                	</td>
                	<td>
                	@if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <td><button name="action" value="on" type="submit">Save</button></td>
                 	</form>
                  </tr>
            @endforeach
                </tbody>
            </table>        
        	<a  href="https://open-meteo.com/en/docs" target="_blank">Check here for coordinates of your city (Search button)</a>
        	</div>
        </div>
        	
        	
@endsection