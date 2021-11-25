@extends('layouts.master')
@section('content')

    <!-- Begin Page Content -->
    <div class="container-fluid">

    <!-- Page Heading -->
        <div class="page-title-box">
            <h4 class="page-title">Dashboard</h4>
        </div>

        <div class="alert alert-info alert-dismissible fade show" role="alert" id="alert-snapshot">
            Last update: <strong> {!! $lastSnapshotTime !!} </strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <!-- Content Row -->
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

        <!-- Balances per DEX/CEX -->
        <div class="card mb-4">
            <div class="card-header pt-4">
                <h4 class="header-title">Balances per CEX/DEX</h4>
            </div>
            <div class="card-body pt-0">
                <table class="table table-sm table-hover table-centered mb-0">
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
{{--                        <td>--}}
{{--                            <x-percent-badge value={{$tiles[\App\Http\Controllers\Constants::TILE_BINANCE_PNL_DELTA_TODAY]}}/>--}}
{{--                        </td>--}}
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

        <!-- Profit and Loss Today -->
        <div class="card mb-4">
            <div class="card-header pt-4">
                <h4 class="header-title">Profit and Loss Today</h4>
            </div>
            <div class="card-body pt-0">
                <table class="table table-sm table-centered mb-0">
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

        <!-- Current Portfolio -->
        <div class="card mb-4">
            <div class="card-header pt-4">
                <h4 class="header-title">Current portfolio</h4>
            </div>
            <div class="card-body pt-0">
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
        //     $("#success-alert").delay(4000).slideUp(200, function() {
        //         $(this).alert('close');
        //     });
        // });

        $(document).ready(function() {
            // show the alert
            setTimeout(function() {
                $(".alert").alert('close');
            }, 10000);
        });
    </script>

@endsection
