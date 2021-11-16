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
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Full history</h6>
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

            <!-- Pie Chart -->
            <div class="col-xl">
                <div class="card shadow mb-4">
                    <!-- Card Header -->
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Wallets</h6>
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

        <!-- Content Row -->
        <div class="row">

            <!-- Binance -->
            <div class="col-xl">
                <div class="card shadow mb-4">
                    <!-- Card Header -->
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Binance</h6>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="chart-area h-auto">
                            <canvas id="binanceChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Row -->
        <div class="row">

            <!-- Ethereum -->
            <div class="col-xl">
                <div class="card shadow mb-4">
                    <!-- Card Header -->
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Ethereum</h6>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="chart-area h-auto">
                            <canvas id="erc20Chart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Row -->
        <div class="row">

            <!-- Mexc -->
            <div class="col-xl">
                <div class="card shadow mb-4">
                    <!-- Card Header -->
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Mexc</h6>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="chart-area h-auto">
                            <canvas id="mexcChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Row -->
        <div class="row">

            <!-- Bitbay -->
            <div class="col-xl">
                <div class="card shadow mb-4">
                    <!-- Card Header -->
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Bitbay</h6>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="chart-area h-auto">
                            <canvas id="bitbayChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Row -->
        <div class="row">

            <!-- Binance Smart Chain -->
            <div class="col-xl">
                <div class="card shadow mb-4">
                    <!-- Card Header -->
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Binance Smart Chain</h6>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="chart-area h-auto">
                            <canvas id="bsc20Chart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{--    <div>--}}
    {{--        <!-- pie chart -->--}}
    {{--        <h1 class="text-2xl text-gray-700">Bitbay</h1>--}}
    {{--        <div class="flex justify-center py-2 align-middle inline-block">--}}
    {{--            <canvas id="bitbayChart"></canvas>--}}
    {{--        </div>--}}
    {{--    </div>--}}

    {{--    <div>--}}
    {{--        <!-- pie chart -->--}}
    {{--        <h1 class="text-2xl text-gray-700">Bsc20</h1>--}}
    {{--        <div class="flex justify-center py-2 align-middle inline-block">--}}
    {{--            <canvas id="bsc20Chart"></canvas>--}}
    {{--        </div>--}}
    {{--    </div>--}}

    <script>

        var lineChart = new Chart(document.getElementById('lineChart').getContext('2d'), {
            type: 'line',
            data: {
                labels: {!! $lineChart['labels'] !!},
                datasets: [{
                    label: 'Value in PLN',
                    data: {!! $lineChart['data'] !!},
                }]
            },
            options: {
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
            },
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

        var binanceChart = new Chart(document.getElementById('binanceChart').getContext('2d'), {
            type: 'pie',
            data: {
                labels: {!! $binanceChart['labels'] !!},
                datasets: [{
                    label: '# of Votes',
                    data: {!! $binanceChart['data'] !!},
                    borderWidth: 0
                }]
            },
            options: pieChartOptions,
        });


        var erc20Chart = new Chart(document.getElementById('erc20Chart').getContext('2d'), {
            type: 'pie',
            data: {
                labels: {!! $erc20Chart['labels'] !!},
                datasets: [{
                    label: '# of Votes',
                    data: {!! $erc20Chart['data'] !!},
                    borderWidth: 0
                }]
            },
            options: pieChartOptions,
        });

        var mexcChart = new Chart(document.getElementById('mexcChart').getContext('2d'), {
            type: 'pie',
            data: {
                labels: {!! $mexcChart['labels'] !!},
                datasets: [{
                    label: '# of Votes',
                    data: {!! $mexcChart['data'] !!},
                    borderWidth: 0
                }]
            },
            options: pieChartOptions,
        });

        var bsc20Chart = new Chart(document.getElementById('bsc20Chart').getContext('2d'), {
            type: 'pie',
            data: {
                labels: {!! $bsc20Chart['labels'] !!},
                datasets: [{
                    label: '# of Votes',
                    data: {!! $bsc20Chart['data'] !!},
                    borderWidth: 0
                }]
            },
            options: pieChartOptions,
        });


        var bitbayChart = new Chart(document.getElementById('bitbayChart').getContext('2d'), {
            type: 'pie',
            data: {
                labels: {!! $bitbayChart['labels'] !!},
                datasets: [{
                    label: '# of Votes',
                    data: {!! $bitbayChart['data'] !!},
                    borderWidth: 0
                }]
            },
            options: pieChartOptions,
        });
    </script>

@endsection
