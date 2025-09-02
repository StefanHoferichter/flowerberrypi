@extends('flowerberrypi')
 
@section('title', 'Sensoren')
@section('url', 'https://www.rezeptexperte.de/show_categories') 

@section('content')
@include('submenu')
@include('time_horizon_menu')

        <h1>Soil Moistures</h1>


		<div class="data-container">
            <div class="grid-item">
        	<h2>Current</h2>
            <table border="1" cellpadding="5" cellspacing="0">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Sensor</th>
                        <th>Value</th>
                        <th>Classification</th>
                        <th>Zone</th>
                    </tr>
                </thead>
                <tbody>
        </tr>
    </thead>
    <tbody>
            @foreach($readings as $reading) 
             <tr>
                <td>{{ $reading->sensor_id }}</td>
                <td>{{ $reading->name }}</td>
                <td>{{ $reading->value  }}</td>
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
                        <th>Timestamp</th>
                        @foreach ($sensorIds as $sensorId)
                            <th>Sensor {{ $sensorId }}</th>
                            <th>Sensor {{ $sensorId }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
        </tr>
    </thead>
    <tbody>
       @foreach ($history as $timestamp => $values)
             <tr>
                <td>{{ $timestamp }}</td>
                @foreach ($sensorIds as $sensorId)
                    <td>
                        {{ $values[$sensorId]['value'] ?? '' }}
                    </td>
                    <td>
                        {{ $values[$sensorId]['classification'] ?? '' }}
                    </td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
         
        	</div>

                    	<h2>Graph</h2>
      	
<canvas id="lineChart" width="600" height="400"></canvas>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@1.4.0/dist/chartjs-plugin-annotation.min.js"></script>
<script>
    const ctx = document.getElementById('lineChart').getContext('2d');

    const labels = @json($labels);
    const datasetsData = @json($datasets);

    // Farben (für automatische Vergabe)
    const colors = [
        'rgba(255, 99, 132, 1)',
        'rgba(54, 162, 235, 1)',
        'rgba(75, 192, 192, 1)',
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
            scales: {
                y: {
                    title: {
                        display: true,
                        text: 'Wert'
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