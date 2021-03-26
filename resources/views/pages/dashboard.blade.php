@extends('layouts.master')
@section('content')

    <div class="flex-1">

        <!-- stats tiles -->
        <div class="flex flex-wrap justify-between">
            <x-stats-tile title="Total Balance"
                          value={{$tiles[\App\Http\Controllers\ProvisionDashboard::TILE_TOTAL_BALANCE]}} unit="PLN"
                          percent={{null}}/>
            <x-stats-tile title="PNL Today"
                          value={{$tiles[\App\Http\Controllers\ProvisionDashboard::TILE_TOTAL_PNL_TODAY]}} unit="PLN"
                          percent={{$tiles[\App\Http\Controllers\ProvisionDashboard::TILE_TOTAL_PNL_DELTA_TODAY]}}/>
            <x-stats-tile title="Binance Balance"
                          value={{$tiles[\App\Http\Controllers\ProvisionDashboard::TILE_BINANCE_BALANCE]}} unit="PLN"
                          percent={{null}}/>
            <x-stats-tile title="Binance PNL Today"
                          value={{$tiles[\App\Http\Controllers\ProvisionDashboard::TILE_BINANCE_PNL_TODAY]}} unit="PLN"
                          percent={{$tiles[\App\Http\Controllers\ProvisionDashboard::TILE_BINANCE_PNL_DELTA_TODAY]}}/>
            <x-stats-tile title="Metamask Balance"
                          value={{$tiles[\App\Http\Controllers\ProvisionDashboard::TILE_METAMASK_BALANCE]}} unit="PLN"
                          percent={{null}}/>
            <x-stats-tile title="Metamask PNL Today"
                          value={{$tiles[\App\Http\Controllers\ProvisionDashboard::TILE_METAMASK_PNL_TODAY]}} unit="PLN"
                          percent={{$tiles[\App\Http\Controllers\ProvisionDashboard::TILE_METAMASK_PNL_DELTA_TODAY]}}/>
            <x-stats-tile title="Mexc Balance"
                          value={{$tiles[\App\Http\Controllers\ProvisionDashboard::TILE_MXC_BALANCE]}} unit="PLN"
                          percent={{null}}/>
            <x-stats-tile title="Mexc PNL Today"
                          value={{$tiles[\App\Http\Controllers\ProvisionDashboard::TILE_MXC_PNL_TODAY]}} unit="PLN"
                          percent={{$tiles[\App\Http\Controllers\ProvisionDashboard::TILE_MXC_PNL_DELTA_TODAY]}}/>
            <x-stats-tile title="Yesterday closing"
                          value={{$tiles[\App\Http\Controllers\ProvisionDashboard::TILE_YESTERDAY_TOTAL_BALANCE]}} unit="PLN"
                          percent={{null}}/>
        </div>

        <!-- graphs -->
        <div class="flex">
            {{--            <div class="w-1/4">--}}
            {{--                <div class="aspect-w-16 aspect-h-9">--}}
            {{--                    <h1 class="text-2xl text-gray-700">Last 2 hours (5 min interval)</h1>--}}
            {{--                    <div class="aspect-w-16 aspect-h-9">--}}
            {{--                        <canvas id="last-hour-stacked-chart"></canvas>--}}
            {{--                    </div>--}}
            {{--                </div>--}}
            {{--            </div>--}}

            {{--            <div class="w-1/4">--}}
            {{--                <h1 class="text-2xl text-gray-700">Last 24 hours (1h interval)</h1>--}}
            {{--                <div>--}}
            {{--                    <canvas id="last-24hours-stacked-chart"></canvas>--}}
            {{--                </div>--}}
            {{--            </div>--}}

            <div class="flex-auto w-1/3">
                    <h1 class="text-2xl text-gray-700">Last 7 days (6h interval)</h1>
                    <canvas id="last-7days-stacked-chart"></canvas>
            </div>

            <div class="flex-auto w-1/3">
                <h1 class="text-2xl text-gray-700">Last 30 days (1d interval)</h1>
                <canvas id="last-30days-stacked-chart"></canvas>
            </div>
        </div>

        <!-- current portfolio table -->
        <h1 class="text-2xl text-gray-700 justify-center">Current portfolio</h1>
        <div class="flex justify-center py-2 align-middle inline-block">
            <div class="shadow-2xl overflow-hidden border-b-4 border-gray-400 sm:rounded-lg">
                <table class="table-auto divide-y divide-gray-200">
                    <thead class="bg-gray-800">
                    <tr>
                        <!-- font-sans text-gray-400 uppercase text-sm font-medium mt-2 -->
                        <th scope="col"
                            class="px-6 py-3 text-left text-sm font-sans font-medium text-gray-300 uppercase tracking-wider">
                            Asset
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-sm font-sans font-medium text-gray-300 uppercase tracking-wider">
                            Quantity
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-sm font-sans font-medium text-gray-300 uppercase tracking-wider">
                            Value PLN
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-sm font-sans font-medium text-gray-300 uppercase tracking-wider">
                            Value USD
                        </th>
                    </tr>
                    </thead>
                    <tbody class="bg-gray-300 divide-y divide-gray-400">
                    @foreach($currentPortfolioSnapshot as $assetSnapshot)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{$assetSnapshot['asset']}}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{$assetSnapshot['quantity']}}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{$assetSnapshot['value_in_pln']}}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{$assetSnapshot['value_in_usd']}}</div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <h1 class="text-2xl text-gray-700 justify-center">Profit and loss</h1>
        <div class="flex justify-center py-2 align-middle inline-block">
            <div class="shadow-2xl overflow-hidden border-b-4 border-gray-400 sm:rounded-lg">
                <table class="table-auto divide-y divide-gray-200">
                    <thead class="bg-gray-800">
                    <tr>
                        <!-- font-sans text-gray-400 uppercase text-sm font-medium mt-2 -->
                        <th scope="col"
                            class="px-6 py-3 text-left text-sm font-sans font-medium text-gray-300 uppercase tracking-wider">
                            coin
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-sm font-sans font-medium text-gray-300 uppercase tracking-wider">
                            value_in_pln
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-sm font-sans font-medium text-gray-300 uppercase tracking-wider">
                            pnl 5 minutes
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-sm font-sans font-medium text-gray-300 uppercase tracking-wider">
                            pnl 1 hours
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-sm font-sans font-medium text-gray-300 uppercase tracking-wider">
                            pnl 3 hours
                        </th>
                    </tr>
                    </thead>
                    <tbody class="bg-gray-300 divide-y divide-gray-400">
                    @foreach($profitAndLosses as $profitAndLoss)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{$profitAndLoss->asset}}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{$profitAndLoss->value_in_pln}}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{$profitAndLoss->pnl_5_min}}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{$profitAndLoss->pnl_1h}}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{$profitAndLoss->pnl_3h}}</div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- pie chart -->
        <h1 class="text-2xl text-gray-700">Portfolio pie chart</h1>
        <div class="flex justify-center py-2 align-middle inline-block">
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
        {{--var last_hour_stacked_chart = new Chart('last-hour-stacked-chart', {--}}
        {{--    type: 'line',--}}
        {{--    data: {!!  json_encode($lastHourStackedChart) !!},--}}
        {{--    options: options--}}
        {{--});--}}

        // last day stacked chart
        {{--var last_24hours_stacked_chart = new Chart('last-24hours-stacked-chart', {--}}
        {{--    type: 'line',--}}
        {{--    data: {!!  json_encode($last24HoursStackedChart) !!},--}}
        {{--    options: options--}}
        {{--});--}}

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
