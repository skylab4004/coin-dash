@extends('layouts.master')
@section('content')

    <div>
        <p>Last update: {!! $lastSnapshotTime !!} </p>
    </div>

    <div class="flex-1">

        <!-- graphs -->
        <div class="flex flex-auto">
            <h1 class="text-2xl text-gray-700">Last 7 days (6h interval)</h1>
            <canvas id="last-7days-stacked-chart"></canvas>
        </div>

        <div class="flex flex-auto">
            <h1 class="text-2xl text-gray-700">Last 30 days (1d interval)</h1>
            <canvas id="last-30days-stacked-chart"></canvas>
        </div>

        <!-- pie chart -->
        <h1 class="text-2xl text-gray-700">Portfolio pie chart</h1>
        <div class="flex justify-center py-2 align-middle inline-block">
            <canvas id="pieChart"></canvas>
        </div>

    </div>
    <script>

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
            options: {
                cutoutPercentage: 50,
                plugins: {
                    colorschemes: {
                        scheme: 'tableau.JewelBright9'
                    }
                }
            }
        });

        var options = {
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
            maintainAspectRatio: false,
            // spanGaps: false,
            scales: {
                yAxes: [{
                    stacked: true,
                    ticks: {
                        mirror: true,
                        z: 1,
                    },

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
                }
            }
        };

        // last 7 days stacked chart
        var last_7days_stacked_chart = new Chart('last-7days-stacked-chart', {
            type: 'line',
            data: {!!  json_encode($last7DaysSixHoursStackedChart) !!},
            options: options
        });

        // last 30 days stacked chart
        var last_30days_stacked_chart = new Chart('last-30days-stacked-chart', {
            type: 'line',
            data: {!!  json_encode($last30DaysStackedChart) !!},
            options: options
        });

        function getTimeRemaining(endtime) {
            const total = Date.parse(endtime) - Date.parse(new Date());
            const seconds = Math.floor((total / 1000) % 60);
            const minutes = Math.floor((total / 1000 / 60) % 60);
            const hours = Math.floor((total / (1000 * 60 * 60)) % 24);
            const days = Math.floor(total / (1000 * 60 * 60 * 24));

            return {
                total,
                days,
                hours,
                minutes,
                seconds
            };
        }

    </script>
@stop
