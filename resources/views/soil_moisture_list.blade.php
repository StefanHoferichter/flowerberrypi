@extends('flowerberrypi')
 
@section('title', 'Sensoren')
@section('url', 'https://www.rezeptexperte.de/show_categories') 

@section('content')
        <h1>Soil Moistures</h1>

		<div class="grid-container">
            <div class="grid-item">
        	<h2>Current</h2>
            @foreach($readings as $reading) 
              Sensor: {{ $reading->name }}<br>
              Sensor Id: {{ $reading->sensor_id }}<br>
              Value {{ $reading->value }} V<br>
              Classification: {{ $reading->classification }}<br>
              Zone Id: {{ $reading->zone_id }}<br><br>
            @endforeach
            
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
<!--  
{{--
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
--}}
-->            
        	</div>
        	
<canvas id="lineChart" width="600" height="400"></canvas>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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