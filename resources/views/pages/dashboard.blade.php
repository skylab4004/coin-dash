@extends('layouts.master')
@section('content')

    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="page-title-box">
            <h4 class="page-title">Dashboard</h4>
        </div>

        <!-- Last update alert -->
        <div class="alert alert-primary alert-dismissible fade show" role="alert" id="alert-snapshot">
            Last update: <strong> {!! $lastSnapshotTime !!} </strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

    {{--    <!-- Progress bar - time until next reload -->--}}
    {{--        <span id="time">5:00</span>--}}
    {{--        <div class="progress mx-auto mb-2" style="max-width: 300px;">--}}
    {{--            <div class="progress-bar-striped progress-bar-animated bg-info" role="progressbar" id="progressBar" style="width: 100%" aria-valuenow="time"></div>--}}
    {{--        </div>--}}

    <!-- Stats tiles row -->
        <div class="row">

            <x-stats-tile title="Total Balance"
                          value={{$tiles[\App\Http\Controllers\Constants::TILE_TOTAL_BALANCE]}} unit="PLN"
                          percent={{null}}/>
            <x-stats-tile title="PNL Today"
                          value={{$tiles[\App\Http\Controllers\Constants::TILE_TOTAL_PNL_TODAY]}} unit="PLN"
                          percent={{$tiles[\App\Http\Controllers\Constants::TILE_TOTAL_PNL_DELTA_TODAY]}}/>
            <x-stats-tile title="ROI"
                          value={{$tiles[\App\Http\Controllers\Constants::KEY_ROI_IN_PLN]}} unit="PLN"
                          percent={{$tiles[\App\Http\Controllers\Constants::KEY_ROI_IN_PERCENTS]}}/>
            <x-stats-tile title="Yesterday closing"
                          value={{$tiles[\App\Http\Controllers\Constants::TILE_YESTERDAY_TOTAL_BALANCE]}} unit="PLN"
                          percent={{null}}/>
        </div>

        <!-- Content Row -->
        <div class="row">

            <!-- Top 5 Gainers today -->
            <div class="col-sm">
                <div class="card mb-4">
                    <div class="card-body table-responsive align-middle">
                        <table class="table table-sm table-borderless table-hover table-centered mb-0 align-middle">
                            <thead>
                            <tr>
                                <th>Gainer</th>
                                <th>Midnight</th>
                                <th>3h</th>
                                <th>1h</th>
                                <th>5m</th>
                                <th>PLN</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($topGainers as $gainer)
                                <tr>
                                    <td class="align-middle"><span class="text-success font-weight-bold font-20">{{$gainer->asset}}</span>
                                    </td>
                                    <td class="align-middle">
                                        <x-percent-badge value="{{$gainer->pnl_midnight}}" unit=""/>
                                    </td>
                                    <td class="align-middle">
                                        <x-percent-badge value="{{$gainer->pnl_3h}}" unit=""/>
                                    </td>
                                    <td class="align-middle">
                                        <x-percent-badge value="{{$gainer->pnl_1h}}" unit=""/>
                                    </td>
                                    <td class="align-middle">
                                        <x-percent-badge value="{{$gainer->pnl_5_min}}" unit=""/>
                                    </td>
                                    <td class="align-middle">
                                        <span class="text-nowrap">{{$gainer->value_in_pln}}</span>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Top 5 Losers today -->
            <div class="col-sm">
                <div class="card mb-4">
                    <div class="card-body table-responsive">
                        <table class="table table-sm table-borderless table-hover table-centered mb-0">
                            <thead>
                            <tr>
                                <th>Loser</th>
                                <th>Midnight</th>
                                <th>3h</th>
                                <th>1h</th>
                                <th>5m</th>
                                <th>PLN</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($topLosers as $loser)
                                <tr>
                                    <td class="align-middle"><span class="text-danger font-weight-bold font-20">{{$loser->asset}}</span></td>
                                    <td class="align-middle">
                                        <x-percent-badge value="{{$loser->pnl_midnight}}" unit=""/>
                                    </td>
                                    <td class="align-middle">
                                        <x-percent-badge value="{{$loser->pnl_3h}}" unit=""/>
                                    </td>
                                    <td class="align-middle">
                                        <x-percent-badge value="{{$loser->pnl_1h}}" unit=""/>
                                    </td>
                                    <td class="align-middle">
                                        <x-percent-badge value="{{$loser->pnl_5_min}}" unit=""/>
                                    </td>
                                    <td class="align-middle">
                                        <span class="text-nowrap">{{$loser->value_in_pln}}</span>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Row -->
        <div class="row">

            <!-- Line chart for portfolio value in PLN (full history, daily) -->
            <div class="col-xl">
                <div class="card shadow mb-4">
                    <!-- Card Header -->
                    <div class="card-header pt-4 pb-0">
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

            <!-- Line chart for portfolio value in PLN (last 7 days, each 30 minutes) -->
            <div class="col-xl">
                <div class="card shadow mb-4">
                    <!-- Card Header -->
                    <div class="card-header pt-4 pb-0">
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


        <!-- Balance change per DEX/CEX wallet -->
        <div class="card mb-4">
            <div class="card-header pt-4">
                <h4 class="header-title">Balances per CEX/DEX</h4>
            </div>
            <div class="card-body table-responsive pt-0">
                <table class="table table-sm table-hover table-centered mb-0">
                    <thead>
                    <tr>
                        <th>Source</th>
                        <th>PNL today</th>
                        <th>Δ today</th>
                        <th>Balance</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>Binance</td>
                        <td>
                            <x-percent-badge
                                    value={{$tiles[\App\Http\Controllers\Constants::TILE_BINANCE_PNL_TODAY]}} unit=""/>
                        </td>
                        <td>
                            <x-percent-badge
                                    value={{$tiles[\App\Http\Controllers\Constants::TILE_BINANCE_PNL_DELTA_TODAY]}} unit="%"/>
                        </td>
                        <td>{{$tiles[\App\Http\Controllers\Constants::TILE_BINANCE_BALANCE]}}</td>
                    </tr>
                    <tr>
                        <td>Metamask</td>
                        <td>
                            <x-percent-badge
                                    value={{$tiles[\App\Http\Controllers\Constants::TILE_METAMASK_PNL_TODAY]}} unit=""/>
                        </td>
                        <td>
                            <x-percent-badge
                                    value={{$tiles[\App\Http\Controllers\Constants::TILE_METAMASK_PNL_DELTA_TODAY]}} unit="%"/>
                        </td>
                        <td>{{$tiles[\App\Http\Controllers\Constants::TILE_METAMASK_BALANCE]}}</td>
                    </tr>
                    <tr>
                        <td>BSC</td>
                        <td>
                            <x-percent-badge
                                    value={{$tiles[\App\Http\Controllers\Constants::TILE_BEP20_PNL_TODAY]}} unit=""/>
                        </td>
                        <td>
                            <x-percent-badge
                                    value={{$tiles[\App\Http\Controllers\Constants::TILE_BEP20_PNL_DELTA_TODAY]}} unit="%"/>
                        </td>
                        <td>{{$tiles[\App\Http\Controllers\Constants::TILE_BEP20_BALANCE]}}</td>
                    </tr>

                    <tr>
                        <td>Mexc</td>
                        <td>
                            <x-percent-badge
                                    value={{$tiles[\App\Http\Controllers\Constants::TILE_MXC_PNL_TODAY]}} unit=""/>
                        </td>
                        <td>
                            <x-percent-badge
                                    value={{$tiles[\App\Http\Controllers\Constants::TILE_MXC_PNL_DELTA_TODAY]}} unit="%"/>
                        </td>
                        <td>{{$tiles[\App\Http\Controllers\Constants::TILE_MXC_BALANCE]}}</td>
                    </tr>

                    <tr>
                        <td>Bitbay</td>
                        <td>
                            <x-percent-badge
                                    value={{$tiles[\App\Http\Controllers\Constants::TILE_BITBAY_PNL_TODAY]}} unit=""/>
                        </td>
                        <td>
                            <x-percent-badge
                                    value={{$tiles[\App\Http\Controllers\Constants::TILE_BITBAY_PNL_DELTA_TODAY]}} unit="%"/>
                        </td>
                        <td>{{$tiles[\App\Http\Controllers\Constants::TILE_BITBAY_BALANCE]}}</td>
                    </tr>

                    <tr>
                        <td>Polygon</td>
                        <td>
                            <x-percent-badge
                                    value={{$tiles[\App\Http\Controllers\Constants::TILE_POLYGON_PNL_TODAY]}} unit=""/>
                        </td>
                        <td>
                            <x-percent-badge
                                    value={{$tiles[\App\Http\Controllers\Constants::TILE_POLYGON_PNL_DELTA_TODAY]}} unit="%"/>
                        </td>
                        <td>{{$tiles[\App\Http\Controllers\Constants::TILE_POLYGON_BALANCE]}}</td>
                    </tr>

                    <tr>
                        <td>Ascendex</td>
                        <td>
                            <x-percent-badge
                                    value={{$tiles[\App\Http\Controllers\Constants::TILE_ASCENDEX_PNL_TODAY]}} unit=""/>
                        </td>
                        <td>
                            <x-percent-badge
                                    value={{$tiles[\App\Http\Controllers\Constants::TILE_ASCENDEX_PNL_DELTA_TODAY]}} unit="%"/>
                        </td>
                        <td>{{$tiles[\App\Http\Controllers\Constants::TILE_ASCENDEX_BALANCE]}} </td>
                    </tr>

                    <tr>
                        <td>Coinbase</td>
                        <td>
                            <x-percent-badge
                                    value={{$tiles[\App\Http\Controllers\Constants::TILE_COINBASE_PNL_TODAY]}} unit=""/>
                        </td>
                        <td>
                            <x-percent-badge
                                    value={{$tiles[\App\Http\Controllers\Constants::TILE_COINBASE_PNL_DELTA_TODAY]}} unit="%"/>
                        </td>
                        <td>{{$tiles[\App\Http\Controllers\Constants::TILE_COINBASE_BALANCE]}} </td>
                    </tr>

                    <tr>
                        <td>KuCoin</td>
                        <td>
                            <x-percent-badge
                                    value={{$tiles[\App\Http\Controllers\Constants::TILE_KUCOIN_PNL_TODAY]}} unit=""/>
                        </td>
                        <td>
                            <x-percent-badge
                                    value={{$tiles[\App\Http\Controllers\Constants::TILE_KUCOIN_PNL_DELTA_TODAY]}} unit="%"/>
                        </td>
                        <td>{{$tiles[\App\Http\Controllers\Constants::TILE_KUCOIN_BALANCE]}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Profit and Loss Today -->
        <div class="card mb-4">
            <div class="card-header pt-4">
                <h4 class="header-title">Profit and Loss Today</h4>
            </div>
            <div class="card-body table-responsive pt-0">
                <table class="table table-sm table-centered mb-0">
                    <thead>
                    <tr>
                        <th>Coin</th>
                        <th>Midnight</th>
                        <th>3h</th>
                        <th>1h</th>
                        <th>5m</th>
                        <th>PLN</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($profitAndLosses as $profitAndLoss)
                        <tr>
                            <td>{{$profitAndLoss->asset}}</td>
                            <td>
                                <x-percent-badge value="{{$profitAndLoss->pnl_midnight}}" unit=""/>
                            </td>
                            <td>
                                <x-percent-badge value="{{$profitAndLoss->pnl_3h}}" unit=""/>
                            </td>
                            <td>
                                <x-percent-badge value="{{$profitAndLoss->pnl_1h}}" unit=""/>
                            </td>
                            <td>
                                <x-percent-badge value="{{$profitAndLoss->pnl_5_min}}" unit=""/>
                            </td>
                            <td>
                                <span class="text-nowrap">{{$profitAndLoss->value_in_pln}}</span>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Current Portfolio snapshot -->
        <div class="card mb-4">
            <div class="card-header pt-4">
                <h4 class="header-title">Current portfolio snapshot</h4>
            </div>
            <div class="card-body table-responsive pt-0">
                <table class="table table-hover table-centered mb-0">
                    <thead>
                    <tr>
                        <th>Coin</th>
                        <th>%</th>
                        <th>Quantity</th>
                        <th>PLN</th>
                        <th>USD</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($currentPortfolioSnapshot as $assetSnapshot)
                        <tr>
                            <td>{{$assetSnapshot['asset']}}</td>
                            <td>{{$assetSnapshot['percentage']}}</td>
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
    <!-- /.container-fluid -->

    <!-- Tables Page level custom scripts -->
    {{--    <script src="{{ asset('js/demo/datatables-demo.js') }}"></script>--}}

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <script>

        // $(document).ready(function() {
        //     // show the alert
        //     setTimeout(function() {
        //         $(".alert").alert('close');
        //     }, 15000);
        // });

        // function startTimer(duration, display, bar) {
        //     var timer = duration, minutes, seconds;
        //     setInterval(function () {
        //         minutes = parseInt(timer / 60, 10);
        //         seconds = parseInt(timer % 60, 10);
        //
        //         var totalSeconds = 5 * 60, remainingSeconds = minutes * 60 + seconds
        //
        //         bar.style.width = (remainingSeconds * 100 / totalSeconds) + "%";
        //
        //         minutes = minutes < 10 ? "0" + minutes : minutes;
        //         seconds = seconds < 10 ? "0" + seconds : seconds;
        //
        //         display.textContent = minutes + ":" + seconds;
        //
        //         if (--timer < 0) {
        //             timer = duration;
        //         }
        //     }, 1000);
        // }
        //
        // window.onload = function () {
        //     var minutes = 60 * 5,
        //         display = document.querySelector('#time');
        //     bar = document.querySelector('#progressBar');
        //     startTimer(minutes, display, bar);
        // };

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
                    // scheme: 'tableau.JewelBright9',
                    scheme: ['#727cf5', '#6b5eae', '#ff679b', '#fa5c7c', '#fd7e14', '#ffbc00', '#0acf97', '#02a8b5', '#39afd1', '#2c8ef8']

                },
                datalabels: {
                    display: false,
                },
            },
            scales: {
                xAxes: [{
                    ticks: {
                        display: false,
                    },
                }],
                yAxes: [{
                    ticks: {
                        display: false,
                    },
                }],
            }
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
    </script>

@endsection
