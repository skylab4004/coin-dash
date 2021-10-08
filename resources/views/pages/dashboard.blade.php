@extends('layouts.master')
@section('content')

    <div>
        <p>Last update: {!! $lastSnapshotTime !!} </p>
    </div>
    <div class="flex-1">
        <!-- stats tiles -->
        <div class="flex flex-wrap justify-between">
            <x-stats-tile title="Total Balance"
                          value={{$tiles[\App\Http\Controllers\Constants::TILE_TOTAL_BALANCE]}} unit="PLN"
                          percent={{null}}/>
            <x-stats-tile title="PNL Today"
                          value={{$tiles[\App\Http\Controllers\Constants::TILE_TOTAL_PNL_TODAY]}} unit="PLN"
                          percent={{$tiles[\App\Http\Controllers\Constants::TILE_TOTAL_PNL_DELTA_TODAY]}}/>
            <x-stats-tile title="Binance Balance"
                          value={{$tiles[\App\Http\Controllers\Constants::TILE_BINANCE_BALANCE]}} unit="PLN"
                          percent={{null}}/>
            <x-stats-tile title="Binance PNL Today"
                          value={{$tiles[\App\Http\Controllers\Constants::TILE_BINANCE_PNL_TODAY]}} unit="PLN"
                          percent={{$tiles[\App\Http\Controllers\Constants::TILE_BINANCE_PNL_DELTA_TODAY]}}/>
            <x-stats-tile title="Metamask Balance"
                          value={{$tiles[\App\Http\Controllers\Constants::TILE_METAMASK_BALANCE]}} unit="PLN"
                          percent={{null}}/>
            <x-stats-tile title="Metamask PNL Today"
                          value={{$tiles[\App\Http\Controllers\Constants::TILE_METAMASK_PNL_TODAY]}} unit="PLN"
                          percent={{$tiles[\App\Http\Controllers\Constants::TILE_METAMASK_PNL_DELTA_TODAY]}}/>
            <x-stats-tile title="BSC Balance"
                          value={{$tiles[\App\Http\Controllers\Constants::TILE_BEP20_BALANCE]}} unit="PLN"
                          percent={{null}}/>
            <x-stats-tile title="BSC PNL Today"
                          value={{$tiles[\App\Http\Controllers\Constants::TILE_BEP20_PNL_TODAY]}} unit="PLN"
                          percent={{$tiles[\App\Http\Controllers\Constants::TILE_BEP20_PNL_DELTA_TODAY]}}/>
            <x-stats-tile title="Mexc Balance"
                          value={{$tiles[\App\Http\Controllers\Constants::TILE_MXC_BALANCE]}} unit="PLN"
                          percent={{null}}/>
            <x-stats-tile title="Mexc PNL Today"
                          value={{$tiles[\App\Http\Controllers\Constants::TILE_MXC_PNL_TODAY]}} unit="PLN"
                          percent={{$tiles[\App\Http\Controllers\Constants::TILE_MXC_PNL_DELTA_TODAY]}}/>
            <x-stats-tile title="Bitbay Balance"
                          value={{$tiles[\App\Http\Controllers\Constants::TILE_BITBAY_BALANCE]}} unit="PLN"
                          percent={{null}}/>
            <x-stats-tile title="Bitbay PNL Today"
                          value={{$tiles[\App\Http\Controllers\Constants::TILE_BITBAY_PNL_TODAY]}} unit="PLN"
                          percent={{$tiles[\App\Http\Controllers\Constants::TILE_BITBAY_PNL_DELTA_TODAY]}}/>

            <x-stats-tile title="Polygon Balance"
                          value={{$tiles[\App\Http\Controllers\Constants::TILE_POLYGON_BALANCE]}} unit="PLN"
                          percent={{null}}/>
            <x-stats-tile title="Polygon PNL Today"
                          value={{$tiles[\App\Http\Controllers\Constants::TILE_POLYGON_PNL_TODAY]}} unit="PLN"
                          percent={{$tiles[\App\Http\Controllers\Constants::TILE_POLYGON_PNL_DELTA_TODAY]}}/>

            <x-stats-tile title="Yesterday closing"
                          value={{$tiles[\App\Http\Controllers\Constants::TILE_YESTERDAY_TOTAL_BALANCE]}} unit="PLN"
                          percent={{null}}/>
        </div>

        <h1 class="text-2xl text-gray-700 justify-center">Profit and loss</h1>
        <div class="flex justify-center py-2 align-middle inline-block overflow-auto">
            <div class="shadow-md overflow-hidden border-b-4 border-gray-400 sm:rounded-lg">
                <table class="table-auto divide-y divide-gray-200">
                    <thead class="bg-gray-800">
                    <tr>
                        <!-- font-sans text-gray-400 uppercase text-sm font-medium mt-2 -->
                        <th scope="col"
                            class="px-2 py-1.5 text-left text-sm font-sans font-medium text-gray-300 uppercase">
                            coin
                        </th>
                        <th scope="col"
                            class="px-2 py-1.5 text-left text-sm font-sans font-medium text-gray-300 uppercase">
                            PLN
                        </th>
                        <th scope="col"
                            class="px-2 py-1.5 text-left text-sm font-sans font-medium text-gray-300 uppercase">
                            &Delta; Midnight
                        </th>
                        <th scope="col"
                            class="px-2 py-1.5 text-left text-sm font-sans font-medium text-gray-300 uppercase">
                            &Delta; 3h
                        </th>
                        <th scope="col"
                            class="px-2 py-1.5 text-left text-sm font-sans font-medium text-gray-300 uppercase">
                            &Delta; 1h
                        </th>
                        <th scope="col"
                            class="px-2 py-1.5 text-left text-sm font-sans font-medium text-gray-300 uppercase">
                            &Delta; 5m
                        </th>
                    </tr>
                    </thead>
                    <tbody class="bg-gray-300 divide-y divide-gray-400">
                    @foreach($profitAndLosses as $profitAndLoss)
                        <tr>
                            <td class="px-2 py-2.5 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{$profitAndLoss->asset}}</div>
                            </td>
                            <td class="px-2 py-2.5 whitespace-nowrap text-right">
                                <div class="text-sm text-gray-900">{{$profitAndLoss->value_in_pln}}</div>
                            </td>
                            <td class="px-2 py-2.5 whitespace-nowrap text-right">
                                <div class="text-sm text-gray-900">{{$profitAndLoss->pnl_midnight}}</div>
                            </td>
                            <td class="px-2 py-2.5 whitespace-nowrap text-right">
                                <div class="text-sm text-gray-900 text-right">{{$profitAndLoss->pnl_3h}}</div>
                            </td>
                            <td class="px-2 py-2.5 whitespace-nowrap">
                                <div class="text-sm text-gray-900 text-right">{{$profitAndLoss->pnl_1h}}</div>
                            </td>
                            <td class="px-2 py-2.5 whitespace-nowrap">
                                <div class="text-sm text-gray-900 text-right">{{$profitAndLoss->pnl_5_min}}</div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- current portfolio table -->
        <h1 class="text-2xl text-gray-700 justify-center">Current portfolio</h1>
        <div class="flex justify-center py-2 align-middle inline-block overflow-auto">
            <div class="shadow-md overflow-hidden border-b-4 border-gray-400 sm:rounded-lg">
                <table class="table-auto divide-y divide-gray-200">
                    <thead class="bg-gray-800">
                    <tr>
                        <!-- font-sans text-gray-400 uppercase text-sm font-medium mt-2 -->
                        <th scope="col"
                            class="px-2 py-1.5 text-left text-sm font-sans font-medium text-gray-300 uppercase">
                            Coin
                        </th>
                        <th scope="col"
                            class="px-2 py-1.5 text-left text-sm font-sans font-medium text-gray-300 uppercase">
                            %
                        </th>
                        <th scope="col"
                            class="px-2 py-1.5 text-left text-sm font-sans font-medium text-gray-300 uppercase">
                            Quantity
                        </th>
                        <th scope="col"
                            class="px-2 py-1.5 text-left text-sm font-sans font-medium text-gray-300 uppercase">
                            PLN
                        </th>
                        <th scope="col"
                            class="px-2 py-1.5 text-left text-sm font-sans font-medium text-gray-300 uppercase">
                            USD
                        </th>
                    </tr>
                    </thead>
                    <tbody class="bg-gray-300 divide-y divide-gray-400">
                    @foreach($currentPortfolioSnapshot as $assetSnapshot)
                        <tr>
                            <td class="px-2 py-2.5 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{$assetSnapshot['asset']}}</div>
                            </td>
                            <td class="px-2 py-2.5 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{$assetSnapshot['percentage']}}</div>
                            </td>
                            <td class="px-2 py-2.5 whitespace-nowrap text-right">
                                <div class="text-sm text-gray-900">{{$assetSnapshot['quantity']}}</div>
                            </td>
                            <td class="px-2 py-2.5 whitespace-nowrap text-right">
                                <div class="text-sm text-gray-900">{{$assetSnapshot['value_in_pln']}}</div>
                            </td>
                            <td class="px-2 py-2.5 whitespace-nowrap text-right">
                                <div class="text-sm text-gray-900">{{$assetSnapshot['value_in_usd']}}</div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

@endsection
