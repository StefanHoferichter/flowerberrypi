@extends('flowerberrypi')
 
@section('title', 'Sensoren')
@section('url', 'https://www.rezeptexperte.de/show_categories') 

@section('content')
@include('submenu')
@include('time_horizon_menu')

        <h1>Temperatures</h1>


        	<h2>Current</h2>
        	
		<div class="data-container">
            <div class="grid-item">
        	  <table border="1" cellpadding="5" cellspacing="0">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Sensor</th>
                        <th>Temperature</th>
                        <th>Humidity</th>
                        <th>Classification</th>
                        <th>Zone</th>
                    </tr>
                </thead>
                <tbody>
            @foreach($readings as $reading) 
             <tr>
                <td>{{ $reading->sensor_id }}</td>
                <td>{{ $reading->name }}</td>
                <td>{{ $reading->temperature }}</td>
                <td>{{ $reading->humidity }}</td>
                <td>{{ $reading->classification }}</td>
                <td><a href="/zone_details/{{$reading->zone_id}}">{{ $reading->zone_name }}</a></td>
            </tr>
        @endforeach
            </tbody>
        </table>
        	
        
        	<h2>History</h2>
            <table border="1" cellpadding="5" cellspacing="0">
                <thead>
                    <tr>
                        <th>Sensor Id</th>
                        <th>Value</th>
                        <th>Classification</th>
                        <th>timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($history as $hist) 
                        <tr>
                            <td>{{ $hist->sensor_id }}</td>
                            <td>{{ $hist->value }}</td>
                            <td>{{ $hist->classification }}</td>
                            <td>{{ $hist->created_at }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>        
        	
                    	<h2>Graph</h2>
        	
            <canvas id="lineChart" width="600" height="400"></canvas>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@1.4.0/dist/chartjs-plugin-annotation.min.js"></script>
<script>
    const ctx = document.getElementById('lineChart').getContext('2d');

    const labels = @json($labels);
    const temperature = @json($temperatures);
    
    const thresholds = @json($thresholds);
    
    const annotations = {};
    thresholds.forEach((t, i) => {
        annotations['line' + i] = {
            type: 'line',
            yMin: t.y,
            yMax: t.y,
            borderColor: '#555555',
            borderWidth: 1.5,
            label: {
                content: t.label + ` (${t.unit})`,
                enabled: true,
                position: 'start',
                backgroundColor: 'rgba(255,255,255,0.7)',
                color: '#555555'
            },
   			yScaleID: 'y'
        };
    });
    
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
                    yAxisID: 'y',
                    fill: false,
                    tension: 0.3,
                }
            ]
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
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Temperatur (°C)'
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