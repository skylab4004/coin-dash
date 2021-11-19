@extends('layouts.master')
@section('content')

    <!-- Begin Page Content -->
    <div class="container-fluid">


{{--        <div class="d-sm-flex align-items-center justify-content-between mb-4">--}}
{{--            <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>--}}
{{--        </div>--}}

        <!-- Page Heading -->
        <div class="page-title-box">
            <h4 class="page-title">Dashboard</h4>
        </div>


        <!-- Content Row -->
        <div class="row">

            <!-- Total balance Card -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total balance
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{$tiles[\App\Http\Controllers\Constants::TILE_TOTAL_BALANCE]}}
                                    PLN
                                </div>
                            </div>
                            <!-- Icon -->
                            {{--                            <div class="col-auto">--}}
                            {{--                                <i class="fas fa-calendar fa-2x text-gray-300"></i>--}}
                            {{--                            </div>--}}
                        </div>
                        {{--                        <div class="row no-gutters align-items-center">--}}
                        {{--                            <span class="badge badge-secondary">{{$tiles[\App\Http\Controllers\Constants::TILE_BINANCE_PNL_DELTA_TODAY]}}%</span>--}}
                        {{--                        </div>--}}
                    </div>
                </div>
            </div>

            <!-- Profit And Loss Today Card -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Profit And Loss Today
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{$tiles[\App\Http\Controllers\Constants::TILE_TOTAL_PNL_TODAY]}}
                                    PLN
                                </div>
                            </div>
                            {{--                            <div class="col-auto">--}}
                            {{--                                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>--}}
                            {{--                            </div>--}}
                        </div>
                        <div class="row no-gutters align-items-center">
                            <span class="badge badge-secondary">{{$tiles[\App\Http\Controllers\Constants::TILE_BINANCE_PNL_DELTA_TODAY]}}%</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Return on Investment -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Return on Investment
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{$tiles[\App\Http\Controllers\Constants::KEY_ROI_IN_PLN]}}
                                    PLN
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                            </div>
                        </div>
                        <div class="row no-gutters align-items-center">
                            <span class="badge badge-secondary">{{$tiles[\App\Http\Controllers\Constants::KEY_ROI_IN_PERCENTS]}}%</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Yesterday's closing Card -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Yesterday's closing
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{$tiles[\App\Http\Controllers\Constants::TILE_YESTERDAY_TOTAL_BALANCE]}}
                                    PLN
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-comments fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Balances per DEX/CEX -->
        <div class="card mb-4">
            <div class="card-header py-3">
                <h4 class="header-title mb-3">Balances per CEX/DEX</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>Source</th>
                            <th>Balance</th>
                            <th>PNL today</th>
                            <th>Δ today</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>Binance</td>
                            <td>{{$tiles[\App\Http\Controllers\Constants::TILE_BINANCE_BALANCE]}}</td>
                            <td>{{$tiles[\App\Http\Controllers\Constants::TILE_BINANCE_PNL_TODAY]}}</td>
                            <td>{{$tiles[\App\Http\Controllers\Constants::TILE_BINANCE_PNL_DELTA_TODAY]}}%</td>
                        </tr>
                        <tr>
                            <td>Metamask</td>
                            <td>{{$tiles[\App\Http\Controllers\Constants::TILE_METAMASK_BALANCE]}}</td>
                            <td>{{$tiles[\App\Http\Controllers\Constants::TILE_METAMASK_PNL_TODAY]}}</td>
                            <td>{{$tiles[\App\Http\Controllers\Constants::TILE_METAMASK_PNL_DELTA_TODAY]}}%</td>
                        </tr>
                        <tr>
                            <td>BSC</td>
                            <td>{{$tiles[\App\Http\Controllers\Constants::TILE_BEP20_BALANCE]}}</td>
                            <td>{{$tiles[\App\Http\Controllers\Constants::TILE_BEP20_PNL_TODAY]}}</td>
                            <td>{{$tiles[\App\Http\Controllers\Constants::TILE_BEP20_PNL_DELTA_TODAY]}}%</td>
                        </tr>

                        <tr>
                            <td>Mexc</td>
                            <td>{{$tiles[\App\Http\Controllers\Constants::TILE_MXC_BALANCE]}}</td>
                            <td>{{$tiles[\App\Http\Controllers\Constants::TILE_MXC_PNL_TODAY]}}</td>
                            <td>{{$tiles[\App\Http\Controllers\Constants::TILE_MXC_PNL_DELTA_TODAY]}}%</td>
                        </tr>

                        <tr>
                            <td>Bitbay</td>
                            <td>{{$tiles[\App\Http\Controllers\Constants::TILE_BITBAY_BALANCE]}}</td>
                            <td>{{$tiles[\App\Http\Controllers\Constants::TILE_BITBAY_PNL_TODAY]}}</td>
                            <td>{{$tiles[\App\Http\Controllers\Constants::TILE_BITBAY_PNL_DELTA_TODAY]}}%</td>
                        </tr>

                        <tr>
                            <td>Polygon</td>
                            <td>{{$tiles[\App\Http\Controllers\Constants::TILE_POLYGON_BALANCE]}}</td>
                            <td>{{$tiles[\App\Http\Controllers\Constants::TILE_POLYGON_PNL_TODAY]}}</td>
                            <td>{{$tiles[\App\Http\Controllers\Constants::TILE_POLYGON_PNL_DELTA_TODAY]}}%</td>
                        </tr>

                        <tr>
                            <td>Ascendex</td>
                            <td>{{$tiles[\App\Http\Controllers\Constants::TILE_ASCENDEX_BALANCE]}} </td>
                            <td>{{$tiles[\App\Http\Controllers\Constants::TILE_ASCENDEX_PNL_TODAY]}}</td>
                            <td>{{$tiles[\App\Http\Controllers\Constants::TILE_ASCENDEX_PNL_DELTA_TODAY]}}%</td>
                        </tr>

                        <tr>
                            <td>Coinbase</td>
                            <td>{{$tiles[\App\Http\Controllers\Constants::TILE_COINBASE_BALANCE]}} </td>
                            <td>{{$tiles[\App\Http\Controllers\Constants::TILE_COINBASE_PNL_TODAY]}}</td>
                            <td>{{$tiles[\App\Http\Controllers\Constants::TILE_COINBASE_PNL_DELTA_TODAY]}}%</td>
                        </tr>

                        <tr>
                            <td>KuCoin</td>
                            <td>{{$tiles[\App\Http\Controllers\Constants::TILE_KUCOIN_BALANCE]}}</td>
                            <td>{{$tiles[\App\Http\Controllers\Constants::TILE_KUCOIN_PNL_TODAY]}}</td>
                            <td>{{$tiles[\App\Http\Controllers\Constants::TILE_KUCOIN_PNL_DELTA_TODAY]}}%</td>
                        </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Profit and Loss Today -->
        <div class="card mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Profit and Loss Today</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>Coin</th>
                            <th>PLN</th>
                            <th>Midnight</th>
                            <th>3h</th>
                            <th>1h</th>
                            <th>5m</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($profitAndLosses as $profitAndLoss)
                        <tr>
                            <td>{{$profitAndLoss->asset}}</td>
                            <td>{{$profitAndLoss->value_in_pln}}</td>
                            <td>{{$profitAndLoss->pnl_midnight}}</td>
                            <td>{{$profitAndLoss->pnl_3h}}</td>
                            <td>{{$profitAndLoss->pnl_1h}}</td>
                            <td>{{$profitAndLoss->pnl_5_min}}</td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Current Portfolio -->
        <div class="card mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Current portfolio</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
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

    </div>
    <!-- /.container-fluid -->


    <!-- Tables Page level custom scripts -->
    <script src="{{ asset('js/demo/datatables-demo.js') }}"></script>

@endsection


{{--    <div>--}}
{{--    </div>--}}
{{--    <p>Last update: {!! $lastSnapshotTime !!} </p>--}}
{{--    <div class="flex-1">--}}
{{--        <!-- stats tiles -->--}}
{{--        <div class="flex flex-wrap justify-between">--}}
{{--            <x-stats-tile title="Total Balance"--}}
{{--                          value={{$tiles[\App\Http\Controllers\Constants::TILE_TOTAL_BALANCE]}} unit="PLN"--}}
{{--                          percent={{null}}/>--}}
{{--            <x-stats-tile title="PNL Today"--}}
{{--                          value={{$tiles[\App\Http\Controllers\Constants::TILE_TOTAL_PNL_TODAY]}} unit="PLN"--}}
{{--                          percent={{$tiles[\App\Http\Controllers\Constants::TILE_TOTAL_PNL_DELTA_TODAY]}}/>--}}
{{--            <x-stats-tile title="ROI"--}}
{{--                          value={{$tiles[\App\Http\Controllers\Constants::KEY_ROI_IN_PLN]}} unit="PLN"--}}
{{--                          percent={{$tiles[\App\Http\Controllers\Constants::KEY_ROI_IN_PERCENTS]}}/>--}}
{{--            <x-stats-tile title="Yesterday closing"--}}
{{--                          value={{$tiles[\App\Http\Controllers\Constants::TILE_YESTERDAY_TOTAL_BALANCE]}} unit="PLN"--}}
{{--                          percent={{null}}/>--}}
{{--        </div>--}}
{{--        <div class="flex flex-wrap justify-between">--}}
{{--            <x-stats-tile title="Binance Balance"--}}
{{--                          value={{$tiles[\App\Http\Controllers\Constants::TILE_BINANCE_BALANCE]}} unit="PLN"--}}
{{--                          percent={{null}}/>--}}
{{--            <x-stats-tile title="Binance PNL Today"--}}
{{--                          value={{$tiles[\App\Http\Controllers\Constants::TILE_BINANCE_PNL_TODAY]}} unit="PLN"--}}
{{--                          percent={{$tiles[\App\Http\Controllers\Constants::TILE_BINANCE_PNL_DELTA_TODAY]}}/>--}}
{{--            <x-stats-tile title="Metamask Balance"--}}
{{--                          value={{$tiles[\App\Http\Controllers\Constants::TILE_METAMASK_BALANCE]}} unit="PLN"--}}
{{--                          percent={{null}}/>--}}
{{--            <x-stats-tile title="Metamask PNL Today"--}}
{{--                          value={{$tiles[\App\Http\Controllers\Constants::TILE_METAMASK_PNL_TODAY]}} unit="PLN"--}}
{{--                          percent={{$tiles[\App\Http\Controllers\Constants::TILE_METAMASK_PNL_DELTA_TODAY]}}/>--}}
{{--            <x-stats-tile title="BSC Balance"--}}
{{--                          value={{$tiles[\App\Http\Controllers\Constants::TILE_BEP20_BALANCE]}} unit="PLN"--}}
{{--                          percent={{null}}/>--}}
{{--            <x-stats-tile title="BSC PNL Today"--}}
{{--                          value={{$tiles[\App\Http\Controllers\Constants::TILE_BEP20_PNL_TODAY]}} unit="PLN"--}}
{{--                          percent={{$tiles[\App\Http\Controllers\Constants::TILE_BEP20_PNL_DELTA_TODAY]}}/>--}}
{{--            <x-stats-tile title="Mexc Balance"--}}
{{--                          value={{$tiles[\App\Http\Controllers\Constants::TILE_MXC_BALANCE]}} unit="PLN"--}}
{{--                          percent={{null}}/>--}}
{{--            <x-stats-tile title="Mexc PNL Today"--}}
{{--                          value={{$tiles[\App\Http\Controllers\Constants::TILE_MXC_PNL_TODAY]}} unit="PLN"--}}
{{--                          percent={{$tiles[\App\Http\Controllers\Constants::TILE_MXC_PNL_DELTA_TODAY]}}/>--}}
{{--            <x-stats-tile title="Bitbay Balance"--}}
{{--                          value={{$tiles[\App\Http\Controllers\Constants::TILE_BITBAY_BALANCE]}} unit="PLN"--}}
{{--                          percent={{null}}/>--}}
{{--            <x-stats-tile title="Bitbay PNL Today"--}}
{{--                          value={{$tiles[\App\Http\Controllers\Constants::TILE_BITBAY_PNL_TODAY]}} unit="PLN"--}}
{{--                          percent={{$tiles[\App\Http\Controllers\Constants::TILE_BITBAY_PNL_DELTA_TODAY]}}/>--}}
{{--            <x-stats-tile title="Polygon Balance"--}}
{{--                          value={{$tiles[\App\Http\Controllers\Constants::TILE_POLYGON_BALANCE]}} unit="PLN"--}}
{{--                          percent={{null}}/>--}}
{{--            <x-stats-tile title="Polygon PNL Today"--}}
{{--                          value={{$tiles[\App\Http\Controllers\Constants::TILE_POLYGON_PNL_TODAY]}} unit="PLN"--}}
{{--                          percent={{$tiles[\App\Http\Controllers\Constants::TILE_POLYGON_PNL_DELTA_TODAY]}}/>--}}
{{--            <x-stats-tile title="Ascendex Balance"--}}
{{--                          value={{$tiles[\App\Http\Controllers\Constants::TILE_ASCENDEX_BALANCE]}} unit="PLN"--}}
{{--                          percent={{null}}/>--}}
{{--            <x-stats-tile title="Ascendex PNL Today"--}}
{{--                          value={{$tiles[\App\Http\Controllers\Constants::TILE_ASCENDEX_PNL_TODAY]}} unit="PLN"--}}
{{--                          percent={{$tiles[\App\Http\Controllers\Constants::TILE_ASCENDEX_PNL_DELTA_TODAY]}}/>--}}
{{--            <x-stats-tile title="Coinbase Balance"--}}
{{--                          value={{$tiles[\App\Http\Controllers\Constants::TILE_COINBASE_BALANCE]}} unit="PLN"--}}
{{--                          percent={{null}}/>--}}
{{--            <x-stats-tile title="Coinbase PNL Today"--}}
{{--                          value={{$tiles[\App\Http\Controllers\Constants::TILE_COINBASE_PNL_TODAY]}} unit="PLN"--}}
{{--                          percent={{$tiles[\App\Http\Controllers\Constants::TILE_COINBASE_PNL_DELTA_TODAY]}}/>--}}
{{--            <x-stats-tile title="Kucoin Balance"--}}
{{--                          value={{$tiles[\App\Http\Controllers\Constants::TILE_KUCOIN_BALANCE]}} unit="PLN"--}}
{{--                          percent={{null}}/>--}}
{{--            <x-stats-tile title="Kucoin PNL Today"--}}
{{--                          value={{$tiles[\App\Http\Controllers\Constants::TILE_KUCOIN_PNL_TODAY]}} unit="PLN"--}}
{{--                          percent={{$tiles[\App\Http\Controllers\Constants::TILE_KUCOIN_PNL_DELTA_TODAY]}}/>--}}
{{--        </div>--}}

{{--        <h1 class="text-2xl text-gray-700 justify-center">Profit and loss</h1>--}}
{{--        <div class="flex justify-center py-2 align-middle inline-block overflow-auto">--}}
{{--            <div class="shadow-md overflow-hidden border-b-4 border-gray-400 sm:rounded-lg">--}}
{{--                <table class="table-auto divide-y divide-gray-200">--}}
{{--                    <thead class="bg-gray-800">--}}
{{--                    <tr>--}}
{{--                        <!-- font-sans text-gray-400 uppercase text-sm font-medium mt-2 -->--}}
{{--                        <th scope="col"--}}
{{--                            class="px-2 py-1.5 text-left text-sm font-sans font-medium text-gray-300 uppercase">--}}
{{--                            coin--}}
{{--                        </th>--}}
{{--                        <th scope="col"--}}
{{--                            class="px-2 py-1.5 text-left text-sm font-sans font-medium text-gray-300 uppercase">--}}
{{--                            PLN--}}
{{--                        </th>--}}
{{--                        <th scope="col"--}}
{{--                            class="px-2 py-1.5 text-left text-sm font-sans font-medium text-gray-300 uppercase">--}}
{{--                            &Delta; Midnight--}}
{{--                        </th>--}}
{{--                        <th scope="col"--}}
{{--                            class="px-2 py-1.5 text-left text-sm font-sans font-medium text-gray-300 uppercase">--}}
{{--                            &Delta; 3h--}}
{{--                        </th>--}}
{{--                        <th scope="col"--}}
{{--                            class="px-2 py-1.5 text-left text-sm font-sans font-medium text-gray-300 uppercase">--}}
{{--                            &Delta; 1h--}}
{{--                        </th>--}}
{{--                        <th scope="col"--}}
{{--                            class="px-2 py-1.5 text-left text-sm font-sans font-medium text-gray-300 uppercase">--}}
{{--                            &Delta; 5m--}}
{{--                        </th>--}}
{{--                    </tr>--}}
{{--                    </thead>--}}
{{--                    <tbody class="bg-gray-300 divide-y divide-gray-400">--}}
{{--                    @foreach($profitAndLosses as $profitAndLoss)--}}
{{--                        <tr>--}}
{{--                            <td class="px-2 py-2.5 whitespace-nowrap">--}}
{{--                                <div class="text-sm font-medium text-gray-900">{{$profitAndLoss->asset}}</div>--}}
{{--                            </td>--}}
{{--                            <td class="px-2 py-2.5 whitespace-nowrap text-right">--}}
{{--                                <div class="text-sm text-gray-900">{{$profitAndLoss->value_in_pln}}</div>--}}
{{--                            </td>--}}
{{--                            <td class="px-2 py-2.5 whitespace-nowrap text-right">--}}
{{--                                <div class="text-sm text-gray-900">{{$profitAndLoss->pnl_midnight}}</div>--}}
{{--                            </td>--}}
{{--                            <td class="px-2 py-2.5 whitespace-nowrap text-right">--}}
{{--                                <div class="text-sm text-gray-900 text-right">{{$profitAndLoss->pnl_3h}}</div>--}}
{{--                            </td>--}}
{{--                            <td class="px-2 py-2.5 whitespace-nowrap">--}}
{{--                                <div class="text-sm text-gray-900 text-right">{{$profitAndLoss->pnl_1h}}</div>--}}
{{--                            </td>--}}
{{--                            <td class="px-2 py-2.5 whitespace-nowrap">--}}
{{--                                <div class="text-sm text-gray-900 text-right">{{$profitAndLoss->pnl_5_min}}</div>--}}
{{--                            </td>--}}
{{--                        </tr>--}}
{{--                    @endforeach--}}
{{--                    </tbody>--}}
{{--                </table>--}}
{{--            </div>--}}
{{--        </div>--}}

{{--        <!-- current portfolio table -->--}}
{{--        <h1 class="text-2xl text-gray-700 justify-center">Current portfolio</h1>--}}
{{--        <div class="flex justify-center py-2 align-middle inline-block overflow-auto">--}}
{{--            <div class="shadow-md overflow-hidden border-b-4 border-gray-400 sm:rounded-lg">--}}
{{--                <table class="table-auto divide-y divide-gray-200">--}}
{{--                    <thead class="bg-gray-800">--}}
{{--                    <tr>--}}
{{--                        <!-- font-sans text-gray-400 uppercase text-sm font-medium mt-2 -->--}}
{{--                        <th scope="col"--}}
{{--                            class="px-2 py-1.5 text-left text-sm font-sans font-medium text-gray-300 uppercase">--}}
{{--                            Coin--}}
{{--                        </th>--}}
{{--                        <th scope="col"--}}
{{--                            class="px-2 py-1.5 text-left text-sm font-sans font-medium text-gray-300 uppercase">--}}
{{--                            %--}}
{{--                        </th>--}}
{{--                        <th scope="col"--}}
{{--                            class="px-2 py-1.5 text-left text-sm font-sans font-medium text-gray-300 uppercase">--}}
{{--                            Quantity--}}
{{--                        </th>--}}
{{--                        <th scope="col"--}}
{{--                            class="px-2 py-1.5 text-left text-sm font-sans font-medium text-gray-300 uppercase">--}}
{{--                            PLN--}}
{{--                        </th>--}}
{{--                        <th scope="col"--}}
{{--                            class="px-2 py-1.5 text-left text-sm font-sans font-medium text-gray-300 uppercase">--}}
{{--                            USD--}}
{{--                        </th>--}}
{{--                    </tr>--}}
{{--                    </thead>--}}
{{--                    <tbody class="bg-gray-300 divide-y divide-gray-400">--}}
{{--                    @foreach($currentPortfolioSnapshot as $assetSnapshot)--}}
{{--                        <tr>--}}
{{--                            <td class="px-2 py-2.5 whitespace-nowrap">--}}
{{--                                <div class="text-sm font-medium text-gray-900">{{$assetSnapshot['asset']}}</div>--}}
{{--                            </td>--}}
{{--                            <td class="px-2 py-2.5 whitespace-nowrap">--}}
{{--                                <div class="text-sm font-medium text-gray-900">{{$assetSnapshot['percentage']}}</div>--}}
{{--                            </td>--}}
{{--                            <td class="px-2 py-2.5 whitespace-nowrap text-right">--}}
{{--                                <div class="text-sm text-gray-900">{{$assetSnapshot['quantity']}}</div>--}}
{{--                            </td>--}}
{{--                            <td class="px-2 py-2.5 whitespace-nowrap text-right">--}}
{{--                                <div class="text-sm text-gray-900">{{$assetSnapshot['value_in_pln']}}</div>--}}
{{--                            </td>--}}
{{--                            <td class="px-2 py-2.5 whitespace-nowrap text-right">--}}
{{--                                <div class="text-sm text-gray-900">{{$assetSnapshot['value_in_usd']}}</div>--}}
{{--                            </td>--}}
{{--                        </tr>--}}
{{--                    @endforeach--}}
{{--                    </tbody>--}}
{{--                </table>--}}
{{--            </div>--}}
{{--        </div>--}}

{{--    </div>--}}
