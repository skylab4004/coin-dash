@extends('layouts.master')
@section('content')

    <div>
        <h1 class="text-2xl text-gray-700">Portfolio value</h1>
        <div class="flex justify-center py-2 align-middle inline-block">
            <canvas id="lineChart"></canvas>
        </div>
    </div>

    <div>
        <!-- pie chart -->
        <h1 class="text-2xl text-gray-700">Portfolio by wallets</h1>
        <div class="flex justify-center py-2 align-middle inline-block">
            <canvas id="pieChart"></canvas>
        </div>
    </div>

    <div>
        <!-- pie chart -->
        <h1 class="text-2xl text-gray-700">Binance</h1>
        <div class="flex justify-center py-2 align-middle inline-block">
            <canvas id="binanceChart"></canvas>
        </div>
    </div>

    <div>
        <!-- pie chart -->
        <h1 class="text-2xl text-gray-700">Erc20</h1>
        <div class="flex justify-center py-2 align-middle inline-block">
            <canvas id="erc20Chart"></canvas>
        </div>
    </div>

    <div>
        <!-- pie chart -->
        <h1 class="text-2xl text-gray-700">Mexc</h1>
        <div class="flex justify-center py-2 align-middle inline-block">
            <canvas id="mexcChart"></canvas>
        </div>
    </div>

    <div>
        <!-- pie chart -->
        <h1 class="text-2xl text-gray-700">Bilaxy</h1>
        <div class="flex justify-center py-2 align-middle inline-block">
            <canvas id="bilaxyChart"></canvas>
        </div>
    </div>

    <div>
        <!-- pie chart -->
        <h1 class="text-2xl text-gray-700">Bsc20</h1>
        <div class="flex justify-center py-2 align-middle inline-block">
            <canvas id="bsc20Chart"></canvas>
        </div>
    </div>

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
                },
            },
        });

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
            options: {
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
                                let percentage = Math.round((value / sum) * 100) + '%';
                                return percentage;
                            } else {
                                return percentage;
                            }
                        },
                        color: '#fff',
                    }
                }
            }
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
            options: {
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
                                let percentage = Math.round((value / sum) * 100) + '%';
                                return percentage;
                            } else {
                                return percentage;
                            }
                        },
                        color: '#fff',
                    }
                }
            }
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
            options: {
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
                                let percentage = Math.round((value / sum) * 100) + '%';
                                return percentage;
                            } else {
                                return percentage;
                            }
                        },
                        color: '#fff',
                    }
                }
            }
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
            options: {
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
                                let percentage = Math.round((value / sum) * 100) + '%';
                                return percentage;
                            } else {
                                return percentage;
                            }
                        },
                        color: '#fff',
                    }
                }
            }
        });

        var bilaxyChart = new Chart(document.getElementById('bilaxyChart').getContext('2d'), {
            type: 'pie',
            data: {
                labels: {!! $bilaxyChart['labels'] !!},
                datasets: [{
                    label: '# of Votes',
                    data: {!! $bilaxyChart['data'] !!},
                    borderWidth: 0
                }]
            },
            options: {
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
                                let percentage = Math.round((value / sum) * 100) + '%';
                                return percentage;
                            } else {
                                return percentage;
                            }
                        },
                        color: '#fff',
                    }
                }
            }
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
            options: {
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
                                let percentage = Math.round((value / sum) * 100) + '%';
                                return percentage;
                            } else {
                                return percentage;
                            }
                        },
                        color: '#fff',
                    }
                }
            }
        });

    </script>

@endsection
