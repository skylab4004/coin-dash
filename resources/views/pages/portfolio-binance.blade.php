@extends('layouts.master')
@section('content')

{{--    'lastSnapshotTime'              => $lastSnapshotTime,--}}
{{--    'pieChart'                      => $pieChart,--}}
{{--    'last7DaysSixHoursStackedChart' => $last7DaysSixHoursStackedChart,--}}
{{--    'last30DaysStackedChart'        => $last30DaysStackedChart,--}}


    <!-- Begin Page Content -->
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Portfolio - Binance</h1>
        </div>

        <!-- Content Row -->
        <div class="row">

            <!-- Stacked Chart -->
            <div class="col-xl">
                <div class="card shadow mb-4">
                    <!-- Card Header -->
                    <div class="card-header pt-4">
                        <h4 class="header-title">Last 7 days / 4H</h4>
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
                    <div class="card-header pt-4">
                        <h4 class="header-title">Last 30 days / 1D</h4>
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

            <!-- Pie Chart -->
            <div class="col-xl">
                <div class="card shadow mb-4">
                    <div class="card-header pt-4">
                        <h4 class="header-title">Wallets</h4>
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

        <!-- Current Portfolio -->
        <div class="card mb-4">
            <div class="card-header pt-4">
                <h4 class="header-title">Current portfolio - Binance</h4>
            </div>
            <div class="card-body pt-0">
                <table class="table table-hover table-centered mb-0">
                    <thead>
                    <tr>
                        <th>Coin</th>
{{--                        <th>%</th>--}}
                        <th>Quantity</th>
                        <th>PLN</th>
                        <th>USD</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($snapshot as $assetSnapshot)
                        <tr>
                            <td>{{$assetSnapshot['asset']}}</td>
{{--                            <td>{{$assetSnapshot['percentage']}}</td>--}}
                            <td>{{$assetSnapshot['quantity']}}</td>
                            <td>{{$assetSnapshot['value_in_pln']}}</td>
                            <td>{{$assetSnapshot['value_in_usd']}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    <script>

        var pieChartOptions = {
            cutoutPercentage: 50,
            plugins: {
                colorschemes: {
                    scheme: 'tableau.JewelBright9'
                },
                datalabels: {
                    formatter: (value, ctx) => {
                        let datasets = ctx.chart.data.datasets;
                        if (datasets.indexOf(ctx.dataset) === datasets.length - 1) {
                            let sum = datasets[0].data.reduce((a, b) => a + b, 0);
                            let percentage = Math.round((value / sum) * 100);
                            if (percentage < 2) {
                                return '';
                            }
                            return percentage + '%';
                        } else {
                            return percentage;
                        }
                    },
                    color: '#fff',
                }
            }
        };

        // pie chart
        var pieChart = new Chart(document.getElementById('pieChart').getContext('2d'), {
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

        // stacked chart options
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
