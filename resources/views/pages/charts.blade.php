@extends('layouts.master')
@section('content')

    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="page-title-box">
            <h4 class="page-title">Portfolio</h4>
        </div>

        <!-- Last update alert -->
        <div class="alert alert-primary alert-dismissible fade show" role="alert" id="alert-snapshot">
            Last update: <strong> {!! $lastSnapshotTime !!} </strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>


        <!-- Content Row -->
        <div class="row">

            <!-- Stacked Chart -->
            <div class="col-xl">
                <div class="card shadow mb-4">
                    <!-- Card Header -->
                    <div class="card-header pt-4 pb-0">
                        <h4 class="header-title">Last 7 days (6h interval)</h4>
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
            <!-- Stacked Chart -->
            <div class="col-xl">
                <div class="card shadow mb-4">

                    <!-- Card Header -->
                    <div class="card-header pt-4 pb-0">
                        <h4 class="header-title">Last 30 days (1d interval)</h4>
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
                    <div class="card-header pt-4 pb-0">
                        <h4 class="header-title">Portfolio pie chart</h4>
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
                    // scheme: 'tableau.JewelBright9'
                    scheme: ['#2c8ef8', '#727cf5', '#6b5eae', '#ff679b', '#fa5c7c', '#fd7e14', '#ffbc00', '#0acf97', '#02a8b5', '#39afd1']
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
                    // scheme: 'tableau.JewelBright9'
                    scheme: ['#2c8ef8', '#727cf5', '#6b5eae', '#ff679b', '#fa5c7c', '#fd7e14', '#ffbc00', '#0acf97', '#02a8b5', '#39afd1']
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
