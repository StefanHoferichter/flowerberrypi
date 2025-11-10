@extends('flowerberrypi')
 
@section('title', 'Sensoren')
@section('submenu')
@include ('include_dummy_menu')  
@endsection

@section('content')

@include('time_horizon_menu')

        <h1>Forecast</h1>

		<div class="data-container">
            <div class="grid-item">
        	<h2>Current Daily Forecast</h2>
            <table border="1" cellpadding="5" cellspacing="0">
                    <tr>
                        <th>Day</th>
                        <th>Min Temperature (°C)</th>
                        <th>Max Temperature (°C)</th>
                        <th>Rain Sum (mm)</th>
                        <th>Sunshine Duration (s)</th>
                        <th>Classification</th>
                    </tr>
                        <tr>
                            <td>{{ $forecast->day}}</td>
                            <td>{{ $forecast->min_temp }}</td>
                            <td>{{ $forecast->max_temp }}</td>
                            <td>{{ $forecast->rain_sum}}</td>
                            <td>{{ $forecast->sunshine_duration }}</td>
                            <td>{{ $forecast->classification }}</td>
                        </tr>
            </table>        
        	</div>
  
        	<h2>Current Hourly Forecast</h2>
            <div class="grid-item">
            <table border="1" cellpadding="5" cellspacing="0">
                    <tr>
                        <th>Date</th>
                        <th>Hour</th>
                        <th>Temperature</th>
                        <th>Precipitation</th>
                        <th>Cloud Cover</th>
                    </tr>
                    @foreach($hourly_forecast as $hwf) 
                        <tr>
                            <td>{{ $hwf->day }}</td>
                            <td>{{ $hwf->hour }}</td>
                            <td>{{ $hwf->temperature }}</td>
                            <td>{{ $hwf->precipitation }}</td>
                            <td>{{ $hwf->cloud_cover }}</td>
                        </tr>
                    @endforeach
            </table>        
        	</div>
        	
            <div class="grid-item">
        	<h2>History Daily Forecasts</h2>

            <table border="1" cellpadding="5" cellspacing="0">
                <thead>
                    <tr>
                        <th>Day</th>
                        <th>Min Temperature (°C)</th>
                        <th>Max Temperature (°C)</th>
                        <th>Rain Sum (mm)</th>
                        <th>Sunshine Duration (s)</th>
                        <th>Classification</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($history as $hist) 
                        <tr>
                            <td>{{ $hist->day }}</td>
                            <td>{{ $hist->min_temp }}</td>
                            <td>{{ $hist->max_temp }}</td>
                            <td>{{ $hist->rain_sum }}</td>
                            <td>{{ $hist->sunshine_duration }}</td>
                            <td>{{ $hist->classification }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>        
        	</div>
            
                    	<h2>Graph</h2>

@include('include_chart')
        	</div>
            

@endsection