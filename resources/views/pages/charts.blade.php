@extends('layouts.master')
@section('content')

    <div>
        <p>Last update: {!! $lastSnapshotTime !!} </p>
    </div>

    <div class="flex-1">

        <!-- graphs -->
        <div>
            <h1 class="text-2xl text-gray-700">Last 7 days (6h interval)</h1>
            <canvas id="last-7days-stacked-chart"></canvas>
        </div>

        <div>
            <h1 class="text-2xl text-gray-700">Last 30 days (1d interval)</h1>
            <canvas id="last-30days-stacked-chart"></canvas>
        </div>

        <!-- pie chart -->
        <div>
            <h1 class="text-2xl text-gray-700">Portfolio pie chart</h1>
            <canvas id="pieChart"></canvas>
        </div>

    </div>
    <script>

        var stackedChartOptions = {
            elements: {
                line: {
                    tension: 0,
                    borderWidth: 0,
                },
                point: {
                    radius: 0,
                },
            },
            legend: {
                display: false,
            },
            datasets: [{
                fill: true,
            }],
            responsive: true,
            tooltips: {
                mode: 'nearest',
                intersect: false,
            },
            hover: {
                mode: 'nearest',
                intersect: false,
            },
            scales: {
                yAxes: [{
                    stacked: true,
                }],
                xAxes: [{
                    ticks: {
                        display: false,
                    }
                }],
            },
            plugins: {
                colorschemes: {
                    scheme: 'tableau.JewelBright9'
                },
                datalabels: {
                    display: false,
                },
            }
        };

        var pieChartOptions = {
            cutoutPercentage: 50,
            legend: {
                display: false,
            },
            plugins: {
                colorschemes: {
                    scheme: 'tableau.JewelBright9'
                },
                datalabels: {
                    display: false,
                }
            }
        };


        var myChart = new Chart(document.getElementById('pieChart').getContext('2d'), {
            type: 'pie',
            data: {
                labels: {!! $pieChart['labels'] !!},
                datasets: [{
                    label: '# of Votes',
                    data: {!! $pieChart['data'] !!},
                    borderWidth: 0
                }]
            },
            options: pieChartOptions,
        });


        // last 7 days stacked chart
        var last_7days_stacked_chart = new Chart('last-7days-stacked-chart', {
            type: 'line',
            data: {!!  json_encode($last7DaysSixHoursStackedChart) !!},
            options: stackedChartOptions
        });

        // last 30 days stacked chart
        var last_30days_stacked_chart = new Chart('last-30days-stacked-chart', {
            type: 'line',
            data: {!!  json_encode($last30DaysStackedChart) !!},
            options: stackedChartOptions
        });

    </script>
@endsection
