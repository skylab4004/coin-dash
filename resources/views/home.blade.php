<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>Coin Dash</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-colorschemes"></script>
</head>
<body>

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
        <td>{{ $lastSnapshotValueInPln-$yesterdaysValueInPln}}</td>
        <td>{{ (($lastSnapshotValueInPln-$yesterdaysValueInPln)/$lastSnapshotValueInPln)*100}}</td>
    </tr>

    <tr>
        <td>Binance</td>
        <td>{{ $lastSnapshotBinanceValueInPln }}</td>
        <td>{{ $lastSnapshotBinanceValueInPln-$yesterdaysBinanceValueInPln}}</td>
        <td>{{ (($lastSnapshotBinanceValueInPln-$yesterdaysBinanceValueInPln)/$lastSnapshotBinanceValueInPln)*100}}</td>
    </tr>

    <tr>
        <td>Metamask</td>
        <td>{{ $lastSnapshotMetamaskValueInPln }}</td>
        <td>{{ $lastSnapshotMetamaskValueInPln-$yesterdaysMetamaskValueInPln}}</td>
        <td>{{ (($lastSnapshotMetamaskValueInPln-$yesterdaysMetamaskValueInPln)/$lastSnapshotMetamaskValueInPln)*100}}</td>
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
        <td>{{ $lastSnapshotValueInUsd-$yesterdaysValueInUsd}}</td>
        <td>{{ (($lastSnapshotValueInUsd-$yesterdaysValueInUsd)/$lastSnapshotValueInUsd)*100}}</td>
    </tr>

    <tr>
        <td>Binance</td>
        <td>{{ $lastSnapshotBinanceValueInUsd }}</td>
        <td>{{ $lastSnapshotBinanceValueInUsd-$yesterdaysBinanceValueInUsd}}</td>
        <td>{{ (($lastSnapshotBinanceValueInUsd-$yesterdaysBinanceValueInUsd)/$lastSnapshotBinanceValueInUsd)*100}}</td>
    </tr>

    <tr>
        <td>Metamask</td>
        <td>{{ $lastSnapshotMetamaskValueInUsd }}</td>
        <td>{{ $lastSnapshotMetamaskValueInUsd-$yesterdaysMetamaskValueInUsd}}</td>
        <td>{{ (($lastSnapshotMetamaskValueInUsd-$yesterdaysMetamaskValueInUsd)/$lastSnapshotMetamaskValueInUsd)*100}}</td>
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
</body>
</html>
