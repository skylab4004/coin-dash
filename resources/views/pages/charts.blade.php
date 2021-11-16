@extends('layouts.master')
@section('content')

    <!-- Begin Page Content -->
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Portfolio</h1>
        </div>
        <div>
            <p>Last update: {!! $lastSnapshotTime !!} </p>
        </div>


        <!-- Content Row -->
        <div class="row">

            <!-- Line Chart -->
            <div class="col-xl">
                <div class="card shadow mb-4">
                    <!-- Card Header -->
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Last 7 days (6h interval)</h6>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="chart-area h-auto">
                            <canvas id="last-7days-stacked-chart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Row -->
        <div class="row">
            <!-- Line Chart -->
            <div class="col-xl">
                <div class="card shadow mb-4">
                    <!-- Card Header -->
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Last 30 days (1d interval)</h6>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="chart-area h-auto">
                            <canvas id="last-30days-stacked-chart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Row -->
        <div class="row">
            <!-- Line Chart -->
            <div class="col-xl">
                <div class="card shadow mb-4">
                    <!-- Card Header -->
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Portfolio pie chart</h6>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="chart-area h-auto">
                            <canvas id="pieChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
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
