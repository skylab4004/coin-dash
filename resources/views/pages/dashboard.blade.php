@extends('layouts.master')
@section('content')

    <div class="flex-1">

        {{--stats--}}
        <div>
            <div class="flex py-4 flex-wrap justify-evenly">
                <x-stats-tile title="Total Balance" value={{$lastSnapshotValueInPln}} unit="PLN" percent={{null}}/>
                <x-stats-tile title="PNL Today" value={{$todaysTotalPNLinPln}} unit="PLN" percent={{$todaysTotalDeltaPercentsFromPln}}/>
{{--                <x-stats-tile title="PNL last 30 min." value="TODO" unit="PLN" percent="TODO"/>--}}

                <x-stats-tile title="Binance Balance" value={{$lastSnapshotBinanceValueInPln}} unit="PLN" percent={{null}}/>
                <x-stats-tile title="Binance PNL Today" value={{$todaysBinancePNLinPln}} unit="PLN" percent={{$todaysBinanceDeltaPercentsFromPln}}/>
{{--                <x-stats-tile title="Binance PNL last 30 min." value="TODO" unit="PLN" percent="TODO"/>--}}

                <x-stats-tile title="Metamask Balance" value={{$lastSnapshotMetamaskValueInPln}} unit="PLN" percent={{null}}/>
                <x-stats-tile title="Metamask PNL Today" value={{$todaysMetamaskPNLinPln}} unit="PLN" percent={{$todaysMetamaskDeltaPercentsFromPln}}/>
                {{--                <x-stats-tile title="Metamask PNL last 30 min." value="TODO" unit="PLN" percent="TODO"/>--}}

                <x-stats-tile title="Yesterday closing" value={{$yesterdaysValueInPln}} unit="PLN" percent={{null}}/>

            </div>
        </div>

    </div>

    <h1>Last 24 hours</h1>
    <div style="height: 700px">
        <canvas id="last-day-stacked-chart"></canvas>
    </div>

    <h1>Daily chart</h1>
    <div style="height: 700px">
        <canvas id="daily-stacked-chart"></canvas>
    </div>

    <h1>Hourly chart</h1>
    <div style="height: 700px">
        <canvas id="hourly-stacked-chart"></canvas>
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

    <script>

        var myChart = new Chart(document.getElementById('pieChart').getContext('2d'), {
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

        var options = {
            elements: {
                line : {
                    tension: 0,
                    borderWidth: 0,
                },
                point: {
                    radius: 0,
                },
            },
            tooltips : {
                intersect: true,
            },
            maintainAspectRatio: false,
            spanGaps: false,
            scales: {
                yAxes: [{
                    stacked: true
                }]
            },
            plugins: {
                filler: {
                    propagate: true,
                },
                'samples-filler-analyser': {
                    target: 'chart-analyser'
                },
                colorschemes: {
                    scheme: 'tableau.JewelBright9'
                }
            }
        };

        // daily stacked chart
        var daily_stacked_chart = new Chart('daily-stacked-chart', {
            type: 'line',
            data: {!!  json_encode($dailyStackedChart) !!},
            options: options
        });

        // hourly stacked chart
        var hourly_stacked_chart = new Chart('hourly-stacked-chart', {
            type: 'line',
            data: {!!  json_encode($hourlyStackedChart) !!},
            options: options
        });

        // last day stacked chart
        var last_day_stacked_chart = new Chart('last-day-stacked-chart', {
            type: 'line',
            data: {!!  json_encode($lastDayStackedChart) !!},
            options: options
        });
    </script>


    Last snapshot: {{ $lastSnapshotTime }} <br/>
    This page took {{ intval(((microtime(true) - LARAVEL_START))*1000) }} ms to render
@stop
