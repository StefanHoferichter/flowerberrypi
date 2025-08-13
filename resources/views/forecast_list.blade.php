@extends('flowerberrypi')
 
@section('title', 'Sensoren')
@section('url', 'https://www.rezeptexperte.de/show_categories') 

@section('content')
        <h1>Forecast</h1>

		<div class="grid-container">
            <div class="grid-item">
        	<h2>Current</h2>
              Date: {{ $forecast->day}}<br>
              Min temperature: {{ $forecast->min_temp }} °C<br>
              Max temperature: {{ $forecast->max_temp }} °C<br>
              Rain Sum: {{ $forecast->rain_sum}} mm<br>
              Sunshine: {{ $forecast->sunshine_duration }} s<br>
              Classification: {{ $forecast->classification }}<br><br>
        	</div>
        	
        	<h2>History</h2>

            <table border="1" cellpadding="5" cellspacing="0">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Min Temp</th>
                        <th>Max Temp</th>
                        <th>Rain Sum</th>
                        <th>Sunshine</th>
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

@endsection