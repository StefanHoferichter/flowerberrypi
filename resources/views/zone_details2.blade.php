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
    	const rawSeries = @json($timeseries);
        const units = [...new Set(rawSeries.map(s => s.unit))];
        
        const datasets = rawSeries.map(series => ({
            label: `${series.name} (${series.unit})`,
            data: series.values,
            fill: false,
            borderColor: randomColor(),
            tension: 0.1,
            yAxisID: series.unit,
        }));
        
        const scales = {};

        // Wechsle Seiten der Achsen (links/rechts), wenn mehrere Einheiten
        units.forEach((unit, index) => {
            scales[unit] = {
                type: 'linear',
                position: index % 2 === 0 ? 'left' : 'right',
                title: {
                    display: true,
                    text: unit
                },
                ticks: {
                    callback: function(value) {
                        return value + ' ' + unit;
                    }
                },
                grid: {
                    drawOnChartArea: index === 0 // Nur Hauptachse mit Gitter
                }
            };
        });

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: datasets
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Zeitreihen mit Einheiten & Achsen'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const unit = context.dataset.yAxisID;
                                return `${context.dataset.label}: ${context.formattedValue} ${unit}`;
                            }
                        }
                    }
                },
                scales: scales
            }
        });

        function randomColor() {
            const r = Math.floor(Math.random() * 200);
            const g = Math.floor(Math.random() * 200);
            const b = Math.floor(Math.random() * 200);
            return `rgb(${r}, ${g}, ${b})`;
        }
    </script>

        	

@endsection