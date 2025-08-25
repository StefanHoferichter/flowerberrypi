@extends('flowerberrypi')
 
@section('title', 'Sensoren')
@section('url', 'https://www.rezeptexperte.de/show_categories') 

@section('content')
        <h1>Forecast</h1>

@include('submenu')

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
  
            <table border="1" cellpadding="5" cellspacing="0">
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
            

@endsection