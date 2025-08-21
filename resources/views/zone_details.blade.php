@extends('flowerberrypi')
 
@section('title', 'Sensoren')
@section('url', 'https://www.rezeptexperte.de/show_categories') 

@section('content')
        <h1>Zone</h1>

		<div class="grid-container">
            <div class="grid-item">
			
            Zone Id: {{ $zone->id }}<br>
            Zone Name: {{ $zone->name }}<br>
            
            
        	<h2>History</h2>
        	
        	<table border="1" cellpadding="5" cellspacing="0">
                <thead>
                    <tr>
                        <th>Day</th>
                        <th>Time of Day</th>
                        <th>Forecast</th>
                        <th>Humidity</th>
                        <th>Watering</th>
                    </tr>
                </thead>
                <tbody>
        	
            @foreach($decisions as $dec) 
                        <tr>
              <td>{{ $dec->day }} </a></td>
				 <td> {{ $dec->tod  }} </td>
				 <td> {{ $dec->forecast_classification }} </td>
				 <td> {{ $dec->humidity_classification }} </td>
				 <td> {{ $dec->watering_classification }} </td>
                </tr>
             
            @endforeach
                </tbody>
            </table>        
        	
        	</div>
<style>
    #lineChart {
        width: 100%;
        max-width: 100%;
        height: auto;
        aspect-ratio: 4 / 3;
        display: block;
    }

    @media (max-width: 600px) {
        #lineChart {
            aspect-ratio: 1 / 1;
        }
    }
</style>        	
<div style="width: 100%; height: 600px;">
    <canvas id="lineChart" style="width: 100%; height: 100%;"></canvas>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@1.4.0/dist/chartjs-plugin-annotation.min.js"></script>



    <script>
    
            const ChartAnnotations = window['chartjs-plugin-annotation'];
        Chart.register(ChartAnnotations);
    
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
        
        const thresholds = @json($thresholds);

        const annotations = {};
        thresholds.forEach((t, i) => {
            annotations['line' + i] = {
                type: 'line',
                yMin: t.y,
                yMax: t.y,
                borderColor: 'red',
                borderWidth: 1.5,
                label: {
                    content: t.label + ` (${t.unit})`,
                    enabled: true,
                    position: 'start',
                    backgroundColor: 'rgba(255,255,255,0.7)',
                    color: 'red'
                },
       			yScaleID: t.unit
            };
        });
        
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
                    },
                    annotation: {
                        annotations: annotations
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