@extends('flowerberrypi')
 
@section('title', 'Sensoren')
@section('url', 'https://www.rezeptexperte.de/show_categories') 

@section('content')
@include('submenu')
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
            
<canvas id="lineChart" width="600" height="400"></canvas>
            <script>
    const ctx = document.getElementById('lineChart').getContext('2d');

    const labels = @json($labels);
    const temperature = @json($temperatures);
    const precipitation = @json($precipitation);
    const cloud_cover = @json($cloud_cover);
    
const chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Temperatur (°C)',
                    data: temperature,
                    borderColor: 'rgba(255, 99, 132, 1)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    yAxisID: 'yTemp',
                    fill: false,
                    tension: 0.3,
                },
                {
                    label: 'Precipitaion (mm)',
                    data: precipitation,
                    borderColor: 'rgba(54, 162, 235, 1)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    yAxisID: 'yPrecip',
                    fill: false,
                    tension: 0.3,
                },
                {
                    label: 'Cloud Cover (%)',
                    data: cloud_cover,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        yAxisID: 'yPrecip',
                    fill: false,
                    tension: 0.3,
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                yTemp: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Temperatur (°C)'
                    },
                },
                yPrecip: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Niederschlag (mm)'
                    },
                    grid: {
                        drawOnChartArea: false // verhindert überlappende Linien
                	}
            	},
                x: {
                    title: {
                        display: true,
                        text: 'Zeit'
                    }
                }
            }
        }
    });
</script>
        	</div>
            

@endsection