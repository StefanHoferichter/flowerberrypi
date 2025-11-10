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
        
        const datasets = rawSeries.map((series, index) => ({
           	type: series.type === 'bar' ? 'scatter' : 'line',
            label: `${series.name} (${series.unit})`,
            data: series.values,
            fill: false,
            borderColor: series.color,
            borderWidth: 3,
			pointStyle: series.type === 'bar' ? 'rectRot': 'circle', 
            pointRadius: context => {
                const value = context.raw ?? context.parsed?.y;
                if (series.type === 'bar') {
                    // Bar: Radius 10, außer Wert ist 0 → 0
                    return value === 0 ? 0 : 8;
                } else {
                    // Nicht Bar: Radius 3
                    return 2;
                }
            },
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
                borderColor: '#555555',
                borderWidth: 1.5,
                label: {
                    content: t.label + ` (${t.unit})`,
                    enabled: true,
                    position: 'start',
                    backgroundColor: 'rgba(255,255,255,0.7)',
                    color: '#555555'
                },
       			yScaleID: t.unit
            };
        });
        
       const scales = {
            x: {
              type: 'category',
              offset: false,
              grid: {
                drawOnChartArea: true,
                color: '#cccccc',
              },
              ticks: {
                autoSkip: false,
                align: 'center',
              }
            }         
		};

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
                        return value.toFixed(1) + ' ' + unit;
                    }
                },
                grid: {
                    drawOnChartArea: index === 0 // Nur Hauptachse mit Gitter
                }
            };
        });


        new Chart(ctx, {
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

    </script>
