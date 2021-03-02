@extends('layouts.master')
@section('content')

    <div class="flex-1">

        {{--stats--}}
        <div>
            <div class="flex py-4 flex-wrap justify-evenly">

                {{--stats card--}}
                <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 lg:w-1/6 bg-gray-800 rounded-2xl px-3 py-1 m-2 shadow-md border-green-500 border-b-4">
                    <div class="font-sans text-gray-400 uppercase text-sm font-medium mt-2">Total Balance</div>
                    <div class="flex items-baseline justify-center py-1">
                        <span class="text-green-500 font-normal text-3xl">{{ $lastSnapshotValueInPln }}</span>
                        <span class="text-sm ml-1 font-bold text-gray-600">PLN</span>
                    </div>
                </div>

                {{--stats card--}}
                <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 lg:w-1/6 bg-gray-800 rounded-2xl px-3 py-1 m-2 shadow-md border-green-500 border-b-4">
                    <div class="font-sans text-gray-400 uppercase text-sm font-medium mt-2">Total PNL today</div>
                    <div class="flex items-baseline justify-center py-1">
                        <span class="text-green-500 font-normal text-3xl"> {{ $todaysTotalPNLinPln }}</span>
                        <span class="text-sm ml-1 font-bold text-gray-600">PLN</span>
                    </div>
                    <div class="flex place-self-end">
                        <span class="bg-green-200 text-green-700 px-1 py-0.5 mb-2 ml-auto text-xs font-medium rounded-full">{{$todaysTotalDeltaPercentsFromPln}}%</span>
                    </div>
                </div>

                {{--stats card--}}
                <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 lg:w-1/6 bg-gray-800 rounded-2xl px-3 py-1 m-2 shadow-md border-red-500 border-b-4">
                    <div class="font-sans text-gray-400 uppercase text-sm font-medium mt-2">Total PNL 30 min.</div>
                    <div class="flex items-baseline justify-center py-1">
                        <span class="text-red-500 font-normal text-3xl">-TODO</span>
                        <span class="text-sm ml-1 font-bold text-gray-600">PLN</span>
                    </div>
                    <div class="flex place-self-end">
                        <span class="bg-red-200 text-red-800 px-1 py-0.5 ml-auto mb-2 text-xs font-medium rounded-full ">-TODO</span>
                    </div>
                </div>

                {{--stats card--}}
                <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 lg:w-1/6 bg-gray-800 rounded-2xl px-3 py-1 m-2 shadow-md border-green-500 border-b-4">
                    <div class="font-sans text-gray-400 uppercase text-sm font-medium mt-2">Binance Balance</div>
                    <div class="flex items-baseline justify-center py-1">
                        <span class="text-green-500 font-normal text-3xl">{{ $lastSnapshotBinanceValueInPln }}</span>
                        <span class="text-sm ml-1 font-bold text-gray-600">PLN</span>
                    </div>
                </div>

                {{--stats card--}}
                <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 lg:w-1/6 bg-gray-800 rounded-2xl px-3 py-1 m-2 shadow-md border-green-500 border-b-4">
                    <div class="font-sans text-gray-400 uppercase text-sm font-medium mt-2">Binance PNL today</div>
                    <div class="flex items-baseline justify-center py-1">
                        <span class="text-green-500 font-normal text-3xl"> {{ $todaysBinancePNLinPln }}</span>
                        <span class="text-sm ml-1 font-bold text-gray-600">PLN</span>
                    </div>
                    <div class="flex place-self-end">
                        <span class="bg-green-200 text-green-700 px-1 py-0.5 mb-2 ml-auto text-xs font-medium rounded-full">{{$todaysBinanceDeltaPercentsFromPln}}%</span>
                    </div>
                </div>

                {{--stats card--}}
                <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 lg:w-1/6 bg-gray-800 rounded-2xl px-3 py-1 m-2 shadow-md border-red-500 border-b-4">
                    <div class="font-sans text-gray-400 uppercase text-sm font-medium mt-2">Binance PNL 30 min.</div>
                    <div class="flex items-baseline justify-center py-1">
                        <span class="text-red-500 font-normal text-3xl">-TODO</span>
                        <span class="text-sm ml-1 font-bold text-gray-600">PLN</span>
                    </div>
                    <div class="flex place-self-end">
                        <span class="bg-red-200 text-red-800 px-1 py-0.5 ml-auto mb-2 text-xs font-medium rounded-full ">-TODO</span>
                    </div>
                </div>


                {{--stats card--}}
                <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 lg:w-1/6 bg-gray-800 rounded-2xl px-3 py-1 m-2 shadow-md border-green-500 border-b-4">
                    <div class="font-sans text-gray-400 uppercase text-sm font-medium mt-2">Metamask Balance</div>
                    <div class="flex items-baseline justify-center py-1">
                        <span class="text-green-500 font-normal text-3xl">{{ $lastSnapshotMetamaskValueInPln }}</span>
                        <span class="text-sm ml-1 font-bold text-gray-600">PLN</span>
                    </div>
                </div>

                {{--stats card--}}
                <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 lg:w-1/6 bg-gray-800 rounded-2xl px-3 py-1 m-2 shadow-md border-green-500 border-b-4">
                    <div class="font-sans text-gray-400 uppercase text-sm font-medium mt-2">Metamask PNL today</div>
                    <div class="flex items-baseline justify-center py-1">
                        <span class="text-green-500 font-normal text-3xl">{{ $todaysMetamaskPNLinPln  }}</span>
                        <span class="text-sm ml-1 font-bold text-gray-600">PLN</span>
                    </div>
                    <div class="flex place-self-end">
                        <span class="bg-green-200 text-green-700 px-1 py-0.5 mb-2 ml-auto text-xs font-medium rounded-full">{{$todaysMetamaskDeltaPercentsFromPln}}%</span>
                    </div>
                </div>

                {{--stats card--}}
                <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 lg:w-1/6 bg-gray-800 rounded-2xl px-3 py-1 m-2 shadow-md border-red-500 border-b-4">
                    <div class="font-sans text-gray-400 uppercase text-sm font-medium mt-2">Metamask PNL 30 min.</div>
                    <div class="flex items-baseline justify-center py-1">
                        <span class="text-red-500 font-normal text-3xl">-TODO</span>
                        <span class="text-sm ml-1 font-bold text-gray-600">PLN</span>
                    </div>
                    <div class="flex place-self-end">
                        <span class="bg-red-200 text-red-800 px-1 py-0.5 ml-auto mb-2 text-xs font-medium rounded-full ">-TODO</span>
                    </div>
                </div>

            </div>
        </div>

    </div>


    <h1>Today's PNL</h1>

    <h2>in PLN</h2>
    <table border="1">
        <thead>
        <tr>
            <td>Source</td>
            <td>TOTAL in PLN</td>
            <td>Today's PNL in PLN</td>
            <td>Today's % delta for PLNs</td>
        </tr>
        </thead>
        <tr>
            <td>ALL</td>
            <td>{{ $lastSnapshotValueInPln }}</td>
            <td>{{ $todaysTotalPNLinPln}}</td>
            <td>{{ $todaysTotalDeltaPercentsFromPln}}</td>
        </tr>

        <tr>
            <td>Binance</td>
            <td>{{ $lastSnapshotBinanceValueInPln }}</td>
            <td>{{ $todaysBinancePNLinPln }}</td>
            <td>{{ $todaysBinanceDeltaPercentsFromPln }}</td>
        </tr>

        <tr>
            <td>Metamask</td>
            <td>{{ $lastSnapshotMetamaskValueInPln }}</td>
            <td>{{ $todaysMetamaskPNLinPln }}</td>
            <td>{{ $todaysMetamaskDeltaPercentsFromPln }}</td>
        </tr>
    </table>

    <h2>in USD</h2>
    <table border="1">
        <thead>
        <tr>
            <td>Source</td>
            <td>TOTAL in USD</td>
            <td>Today's PNL in USD</td>
            <td>Today's % delta for USDs</td>
        </tr>
        </thead>
        <tr>
            <td>ALL</td>
            <td>{{ $lastSnapshotValueInUsd }}</td>
            <td>{{ $todaysTotalPNLinUsd}}</td>
            <td>{{ $todaysTotalDeltaPercentsFromUsd}}</td>
        </tr>

        <tr>
            <td>Binance</td>
            <td>{{ $lastSnapshotBinanceValueInUsd }}</td>
            <td>{{ $todaysBinancePNLinUsd }}</td>
            <td>{{ $todaysBinanceDeltaPercentsFromUsd }}</td>
        </tr>

        <tr>
            <td>Metamask</td>
            <td>{{ $lastSnapshotMetamaskValueInUsd }}</td>
            <td>{{ $todaysMetamaskPNLinUsd }}</td>
            <td>{{ $todaysMetamaskDeltaPercentsFromUsd }}</td>
        </tr>
    </table>


    <h1>Yesterday's portfolio value</h1>
    <table>
        <tr>
            <td>PLN</td>
            <td>USD</td>
        </tr>
        <tr>
            <td> {{ $yesterdaysValueInPln }}</td>
            <td> {{ $yesterdaysValueInUsd }}</td>
        </tr>
    </table>

    <h1>Stacked chart</h1>
    <div style="height: 700px">
        <canvas id="chart-0"></canvas>
    </div>

    <h1>Current portfolio</h1>
    <table border="1">
        <tr>
            <td>Asset</td>
            <td>Quantity</td>
            <td>Value PLN</td>
            <td>Value USD</td>
        </tr>
        @foreach($currentPortfolioSnapshot as $assetSnapshot)
            <tr>
                <td>{{$assetSnapshot['asset']}}</td>
                <td>{{$assetSnapshot['quantity']}}</td>
                <td>{{$assetSnapshot['value_in_pln']}}</td>
                <td>{{$assetSnapshot['value_in_usd']}}</td>
            </tr>
        @endforeach
    </table>

    <div>
        <canvas id="pieChart"></canvas>
    </div>

    <h1>Portfolio history</h1>
    <table>
        <tr>
            <td>Snapshot time</td>
            <td>Value PLN</td>
            <td>Value USD</td>
        </tr>
        <tr>
            <td>todo</td>
            <td>todo</td>
            <td>todo</td>
        </tr>
    </table>

    <div>
        <canvas id="totalsChart"></canvas>
    </div>

    <script>
        var ctx = document.getElementById('pieChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: {!! $pieChart['labels'] !!},
                datasets: [{
                    label: '# of Votes',
                    data: {!! $pieChart['data'] !!},
                    borderWidth: 1
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

        var ctx2 = document.getElementById('totalsChart').getContext('2d');
        var myChart2 = new Chart(ctx2, {
            type: 'line',
            data: {
                labels: {!! $totalsChart['labels'] !!},
                datasets: [{
                    label: '# of Votes',
                    data: {!! $totalsChart['data'] !!},
                    borderWidth: 1
                }]
            },
            options: {
                plugins: {
                    colorschemes: {
                        scheme: 'tableau.JewelBright9'
                    }
                }
            }
        });

        // stacked chart
        var data = {!!  json_encode($stackedChart) !!};

        var options = {
            maintainAspectRatio: false,
            spanGaps: false,
            scales: {
                yAxes: [{
                    stacked: true
                }]
            },
            plugins: {
                filler: {
                    propagate: true
                },
                'samples-filler-analyser': {
                    target: 'chart-analyser'
                },
                colorschemes: {
                    scheme: 'tableau.JewelBright9'
                }
            }
        };

        var chart = new Chart('chart-0', {
            type: 'line',
            data: data,
            options: options
        });
    </script>


    Last snapshot: {{ $lastSnapshotTime }} <br/>
    This page took {{ intval(((microtime(true) - LARAVEL_START))*1000) }} ms to render
@stop
