@extends('flowerberrypi')
 
@section('title', 'Sensoren')
@section('url', 'https://www.rezeptexperte.de/show_categories') 

@section('content')
        <h1>Forecast</h1>

		<div class="grid-container">
            <div class="grid-item">
            <table border="1" cellpadding="5" cellspacing="0">
                <thead>
                    <tr>
                        <th>Temperature</th>
                        <th>Precipitation</th>
                        <th>Cloud Cover</th>
                    </tr>
                </thead>
                <tbody>
                    @for ($i = 0; $i < count($temperatures); $i++)
                        <tr>
                            <td>{{ $temperatures[$i] }}</td>
                            <td>{{ $precipitation[$i] }}</td>
                            <td>{{ $cloudCovers[$i] }}</td>
                        </tr>
                    @endfor
                </tbody>
            </table>        
            
            
            <canvas id="lineChart" width="600" height="400"></canvas>
            <script>
    const ctx = document.getElementById('lineChart').getContext('2d');

    const labels = @json($labels);
    const temperature = @json($temperatures);
    const precipitation = @json($precipitation);
    const cloudCover = @json($cloudCovers);
    
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
                    label: 'Niederschlag (mm)',
                    data: precipitation,
                    borderColor: 'rgba(54, 162, 235, 1)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    yAxisID: 'yPrecip',
                    fill: false,
                    tension: 0.3,
                },
                {
                    label: 'Bewölkung (%)',
                    data: cloudCover,
                    borderColor: 'rgba(255, 206, 86, 1)',
                    backgroundColor: 'rgba(255, 206, 86, 0.2)',
                    yAxisID: 'yCloud',
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

                    // Verhindert Überlappung mit yTemp-Achse
                    grid: {
                        drawOnChartArea: false,
                    },
                },
                yCloud: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    offset: true,  // Verschiebt Achse etwas nach rechts
                    title: {
                        display: true,
                        text: 'Bewölkung (%)'
                    },
                    grid: {
                        drawOnChartArea: false,
                    },
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