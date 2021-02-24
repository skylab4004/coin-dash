<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>Coin Dash</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-colorschemes"></script>
</head>
<body>
{{--    'currentValueInPln'            => $currentValueInPln,--}}
{{--    'currentValueInUsd'            => $currentValueInUsd,--}}
{{--    'currentBinanceValueInPln'     => $currentBinanceValueInPln,--}}
{{--    'currentBinanceValueInUsd'     => $currentBinanceValueInUsd,--}}
{{--    'currentMetamaskValueInPln'    => $currentMetamaskValueInPln,--}}
{{--    'currentMetamaskValueInUsd'    => $currentMetamaskValueInUsd,--}}
{{--    'yesterdaysValueInPln'         => $yesterdaysValueInPln,--}}
{{--    'yesterdaysValueInUsd'         => $yesterdaysValueInUsd,--}}
{{--    'yesterdaysBinanceValueInPln'  => $yesterdaysBinanceValueInPln,--}}
{{--    'yesterdaysBinanceValueInUsd'  => $yesterdaysBinanceValueInUsd,--}}
{{--    'yesterdaysMetamaskValueInPln' => $yesterdaysMetamaskValueInPln,--}}
{{--    'yesterdaysMetamaskValueInUsd' => $yesterdaysMetamaskValueInUsd,--}}
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
        <td>{{ $currentValueInPln }}</td>
        <td>{{ $currentValueInPln-$yesterdaysValueInPln}}</td>
        <td>{{ (($currentValueInPln-$yesterdaysValueInPln)/$currentValueInPln)*100}}</td>
    </tr>

    <tr>
        <td>Binance</td>
        <td>{{ $currentBinanceValueInPln }}</td>
        <td>{{ $currentBinanceValueInPln-$yesterdaysBinanceValueInPln}}</td>
        <td>{{ (($currentBinanceValueInPln-$yesterdaysBinanceValueInPln)/$currentBinanceValueInPln)*100}}</td>
    </tr>

    <tr>
        <td>Metamask</td>
        <td>{{ $currentMetamaskValueInPln }}</td>
        <td>{{ $currentMetamaskValueInPln-$yesterdaysMetamaskValueInPln}}</td>
        <td>{{ (($currentMetamaskValueInPln-$yesterdaysMetamaskValueInPln)/$currentMetamaskValueInPln)*100}}</td>
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
        <td>{{ $currentValueInUsd }}</td>
        <td>{{ $currentValueInUsd-$yesterdaysValueInUsd}}</td>
        <td>{{ (($currentValueInUsd-$yesterdaysValueInUsd)/$currentValueInUsd)*100}}</td>
    </tr>

    <tr>
        <td>Binance</td>
        <td>{{ $currentBinanceValueInUsd }}</td>
        <td>{{ $currentBinanceValueInUsd-$yesterdaysBinanceValueInUsd}}</td>
        <td>{{ (($currentBinanceValueInUsd-$yesterdaysBinanceValueInUsd)/$currentBinanceValueInUsd)*100}}</td>
    </tr>

    <tr>
        <td>Metamask</td>
        <td>{{ $currentMetamaskValueInUsd }}</td>
        <td>{{ $currentMetamaskValueInUsd-$yesterdaysMetamaskValueInUsd}}</td>
        <td>{{ (($currentMetamaskValueInUsd-$yesterdaysMetamaskValueInUsd)/$currentMetamaskValueInUsd)*100}}</td>
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

<canvas id="pieChart"></canvas>

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

<script>
    var ctx = document.getElementById('pieChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'pie',
        data: {
{{--            {{ dd($pieChart['labels']) }}--}}
            labels: {!! $pieChart['labels'] !!},  // ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
            datasets: [{
                label: '# of Votes',
                data: {!! $pieChart['data'] !!}, // [12, 19, 3, 5, 2, 3],
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
</script>


</body>
</html>
