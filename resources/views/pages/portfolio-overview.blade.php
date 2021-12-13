@extends('layouts.master')
@section('content')

    <!-- Begin Page Content -->
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Portfolio</h1>
        </div>

        <!-- Content Row -->
        <div class="row">
            <!-- Line Chart -->
            <div class="col-xl">
                <div class="card shadow mb-4">
                    <!-- Card Header -->
                    <div class="card-header pt-4">
                        <h4 class="header-title">Portfolio value in PLN</h4>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="chart-area h-auto">
                            <canvas id="lineChart"></canvas>
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
                    <div class="card-header pt-4">
                        <h4 class="header-title">Portfolio value in BTC</h4>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="chart-area h-auto">
                            <canvas id="btcTotals"></canvas>
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
                    <div class="card-header pt-4">
                        <h4 class="header-title">Last 7D in PLN</h4>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="chart-area h-auto">
                            <canvas id="last24hInPlnChart"></canvas>
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
                    <div class="card-header pt-4">
                        <h4 class="header-title">Last 7D in BTC</h4>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="chart-area h-auto">
                            <canvas id="last24hInBtcChart"></canvas>
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
                    <!-- Card Header -->
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
                <h4 class="header-title">Current portfolio</h4>
            </div>
            <div class="card-body table-responsive pt-0">
                <table class="table table-hover table-centered mb-0">
                    <thead>
                    <tr>
                        <th>Coin</th>
                        <th>Source</th>
                        <th>Quantity</th>
                        <th>PLN</th>
                        <th>USD</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($snapshot as $assetSnapshot)
                        <tr>
                            <td>{{$assetSnapshot['asset']}}</td>
                            <td>{{$assetSnapshot['source']}}</td>
                            <td>{{$assetSnapshot['quantity']}}</td>
                            <td>{{$assetSnapshot['value_in_pln']}}</td>
                            <td>{{$assetSnapshot['value_in_usd']}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <script>

        var lineChartOptions = {
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
            plugins: {
                colorschemes: {
                    scheme: 'tableau.JewelBright9'
                },
                datalabels: {
                    display: false,
                },
            },
        };

        var lineChart = new Chart(document.getElementById('lineChart').getContext('2d'), {
            type: 'line',
            data: {
                labels: {!! $lineChart['labels'] !!},
                datasets: [{
                    label: 'Value in PLN',
                    data: {!! $lineChart['data'] !!},
                }]
            },
            options: lineChartOptions,
        });

        var btcTotals = new Chart(document.getElementById('btcTotals').getContext('2d'), {
            type: 'line',
            data: {
                labels: {!! $totalsBtc['labels'] !!},
                datasets: [{
                    label: 'Value in BTC',
                    data: {!! $totalsBtc['data'] !!},
                }]
            },
            options: lineChartOptions,
        });

        var last24hInPlnChart = new Chart(document.getElementById('last24hInPlnChart').getContext('2d'), {
            type: 'line',
            data: {
                labels: {!! $last24hInPlnChart['labels'] !!},
                datasets: [{
                    label: 'Value in PLN',
                    data: {!! $last24hInPlnChart['data'] !!},
                }]
            },
            options: lineChartOptions,
        });

        var last24hInBtcChart = new Chart(document.getElementById('last24hInBtcChart').getContext('2d'), {
            type: 'line',
            data: {
                labels: {!! $last24hInBtcChart['labels'] !!},
                datasets: [{
                    label: 'Value in PLN',
                    data: {!! $last24hInBtcChart['data'] !!},
                }]
            },
            options: lineChartOptions,
        });


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

    </script>

@endsection
