@extends('layouts.master')
@section('content')

    <div class="flex-1">

        <!-- stats tiles -->
        <div class="flex flex-wrap justify-between">
            <x-stats-tile title="Total Balance" value={{$lastSnapshotValueInPln}} unit="PLN" percent={{null}}/>
            <x-stats-tile title="PNL Today" value={{$todaysTotalPNLinPln}} unit="PLN"
                          percent={{$todaysTotalDeltaPercentsFromPln}}/>
            {{--                <x-stats-tile title="PNL last 30 min." value="TODO" unit="PLN" percent="TODO"/>--}}
            <x-stats-tile title="Binance Balance" value={{$lastSnapshotBinanceValueInPln}} unit="PLN"
                          percent={{null}}/>
            <x-stats-tile title="Binance PNL Today" value={{$todaysBinancePNLinPln}} unit="PLN"
                          percent={{$todaysBinanceDeltaPercentsFromPln}}/>
            {{--                <x-stats-tile title="Binance PNL last 30 min." value="TODO" unit="PLN" percent="TODO"/>--}}
            <x-stats-tile title="Metamask Balance" value={{$lastSnapshotMetamaskValueInPln}} unit="PLN"
                          percent={{null}}/>
            <x-stats-tile title="Metamask PNL Today" value={{$todaysMetamaskPNLinPln}} unit="PLN"
                          percent={{$todaysMetamaskDeltaPercentsFromPln}}/>
            {{--                <x-stats-tile title="Metamask PNL last 30 min." value="TODO" unit="PLN" percent="TODO"/>--}}
            <x-stats-tile title="Mexc Balance" value={{$lastSnapshotMxcValueInPln}} unit="PLN"
                          percent={{null}}/>
            <x-stats-tile title="Mexc PNL Today" value={{$todaysMxcPNLinPln}} unit="PLN"
                          percent={{$todaysMxcDeltaPercentsFromPln}}/>
            {{--                <x-stats-tile title="Metamask PNL last 30 min." value="TODO" unit="PLN" percent="TODO"/>--}}
            <x-stats-tile title="Yesterday closing" value={{$yesterdaysValueInPln}} unit="PLN" percent={{null}}/>
        </div>

        <!-- graphs -->
        <div class="flex flex-wrap">
            <div class="w-1/2">
                <h1 class="text-2xl text-gray-700">Last 2 hours (5 min interval)</h1>
                <div class="aspect-w-16 aspect-h-9"`>
                    <canvas id="last-hour-stacked-chart"></canvas>
                </div>
            </div>

            <div class="w-1/2">
                <h1 class="text-2xl text-gray-700">Last 24 hours (1h interval)</h1>
                <div>
                    <canvas id="last-24hours-stacked-chart"></canvas>
                </div>
            </div>

            <div class="w-1/2">
                <h1 class="text-2xl text-gray-700">Last 7 days (6h interval)</h1>
                <div>
                    <canvas id="last-7days-stacked-chart"></canvas>
                </div>
            </div>

            <div class="w-1/2">
                <h1 class="text-2xl text-gray-700">Last 30 days (1d interval)</h1>
                <div>
                    <canvas id="last-30days-stacked-chart"></canvas>
                </div>
            </div>
        </div>

        <!-- current portfolio table -->
        <h1 class="text-2xl text-gray-700">Current portfolio</h1>
        <table>
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

        <!-- pie chart -->
        <div>
            <h1 class="text-2xl text-gray-700">Portfolio pie chart</h1>
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

        // last hour stacked chart (5 minutes interval)
        var last_hour_stacked_chart = new Chart('last-hour-stacked-chart', {
            type: 'line',
            data: {!!  json_encode($lastHourStackedChart) !!},
            options: options
        });

        // last day stacked chart
        var last_24hours_stacked_chart = new Chart('last-24hours-stacked-chart', {
            type: 'line',
            data: {!!  json_encode($last24HoursStackedChart) !!},
            options: options
        });

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


    </script>
@stop
