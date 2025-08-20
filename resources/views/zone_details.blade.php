@extends('flowerberrypi')
 
@section('title', 'Sensoren')
@section('url', 'https://www.rezeptexperte.de/show_categories') 

@section('content')
        <h1>Zones</h1>

		<div class="grid-container">
            <div class="grid-item">
			
            Zone Id: {{ $zone->id }}<br>
            Zone Name: {{ $zone->name }}<br>
            
            
        	<h2>History</h2>
        	</div>
        	
<canvas id="lineChart" width="600" height="400"></canvas>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const ctx = document.getElementById('lineChart').getContext('2d');

    const labels = @json($labels);
    const datasetsData = @json($datasets);
	const temperatureSeries = @json($temperatures); // neue Zeitreihe
	const forecastMax = @json($forecast_max); // neue Zeitreihe
	const forecastMin = @json($forecast_min); // neue Zeitreihe

    // Farben (für automatische Vergabe)
    const colors = [
        'rgba(54, 162, 235, 1)',
        'rgba(75, 192, 192, 1)',
        'rgba(255, 99, 132, 1)',
        'rgba(255, 206, 86, 1)',
        'rgba(153, 102, 255, 1)',
        'rgba(255, 159, 64, 1)'
    ];

    const datasets = datasetsData.map((set, index) => ({
        label: set.label,
        data: set.data,
        borderColor: colors[index % colors.length],
        backgroundColor: colors[index % colors.length].replace('1)', '0.2)'),
        fill: false,
        tension: 0.3
    }));

// Temperatur-Datensatz hinzufügen
    datasets.push({
        label: 'Temperatures',
        data: temperatureSeries,
        borderColor: 'rgba(255, 0, 0, 1)',
        backgroundColor: 'rgba(255, 255, 0, 0.2)',
        fill: false,
        tension: 0.3,
        yAxisID: 'y1' 
    });
    datasets.push({
        label: 'Forecast Max',
        data: forecastMax,
        borderColor: 'rgba(0, 255, 0, 1)',
        backgroundColor: 'rgba(100, 100, 0, 0.2)',
        fill: false,
        tension: 0.3,
        yAxisID: 'y1' 
    });
    datasets.push({
        label: 'Forecast Min',
        data: forecastMin,
        borderColor: 'rgba(10, 1, 255, 1)',
        backgroundColor: 'rgba(0, 100, 255, 0.2)',
        fill: false,
        tension: 0.3,
        yAxisID: 'y1' 
    });
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: datasets
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    title: {
                        display: true,
                        text: 'Wert'
                    }
                },
                y1: { // 👉 Zweite Y-Achse für Temperatur
                    title: {
                        display: true,
                        text: 'Temperatur (°C)'
                    },
                    position: 'right',
                    grid: {
                        drawOnChartArea: false // verhindert Überlagerung der Gitterlinien
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