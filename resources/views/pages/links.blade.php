@extends('layouts.master')
@section('content')

    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="page-title-box">
            <h4 class="page-title">Links</h4>
        </div>

        <div class="card mb-4">
            <div class="card-header pt-4">
                <h4 class="header-title">Fear and greed index</h4>
            </div>
            <div class="card-body pt-0">
                <a href="https://alternative.me/crypto/fear-and-greed-index/"><img src="https://alternative.me/crypto/fear-and-greed-index.png" alt="Bitcoin Fear &amp; Greed Index" style="object-fit: scale-down"></a>
            </div>
        </div>



        <div class="card mb-4">
            <div class="card-header pt-4">
                <h4 class="header-title">Bitcoin onchain indicators</h4>
            </div>
            <div class="card-body pt-0">
                <ul class="text-cyan text-lg">
                    <li><a href="https://www.lookintobitcoin.com/charts/mvrv-zscore/" target="_blank" rel="noopener noreferrer">MVRV Z-score</a> - Gdy w zielonym - kupuj, Gdy powyżej połowy (miedzy zielonym a czerwonym) warto powoli wychodzić</li>
                    <li><a href="https://www.lookintobitcoin.com/charts/reserve-risk/" target="_blank" rel="noopener noreferrer">Reserve risk</a> - Gdy w zielonym - kupuj, Gdy powyżej połowy (miedzy zielonym a czerwonym) warto powoli wychodzić</li>
                    <li><a href="https://www.lookintobitcoin.com/charts/rhodl-ratio/ " target="_blank" rel="noopener noreferrer">RHODL Ratio</a> - zależność pomiędzy STH, a LTH. Na podstawie ceny realized (czyli faktycznych transakcji). wchodzić na zielonym. wychodzić na czerwonym.</li>
                    <li><a href="https://www.lookintobitcoin.com/charts/relative-unrealized-profit--loss/" target="_blank" rel="noopener noreferrer">Relative unrealized Profit Loss </a> - podobne do MVRV z-score. procentowy stosunek niezrealizowanych zysków do niezrealizowanych strat. na podstawie zrealizowanych transakcji.</li>
                    <li><a href="https://www.lookintobitcoin.com/charts/wallets-greater-than-100-btc/" target="_blank" rel="noopener noreferrer">Wallets >100 BTC</a></li>
                    <li><a href="https://www.lookintobitcoin.com/charts/wallets-greater-than-1000-btc/" target="_blank" rel="noopener noreferrer">Wallets >1000 BTC</a></li>
                    <li><a href="https://www.lookintobitcoin.com/charts/active-address-sentiment-indicator/" target="_blank" rel="noopener noreferrer">Active Address Sentiment Indicator</a> - sentyment aktywnych adresów. najlepiej sprawdza się krótko/średnio-terminowo. Gdy wykres ponad czerwoną wstęgą = rynek jest przegrzany, jest fomo. Gdy pod - warto wchodzić.</li>
                    <li><a href="https://studio.glassnode.com/metrics?a=BTC&category=&m=addresses.NewNonZeroCount" target="_blank" rel="noopener noreferrer">Number of New Addressess</a> - Gdy liczba nowych adresów drastycznie rośnie - tzn. że tworzy się FOMO</li>
                    <li><a href="https://studio.glassnode.com/metrics?a=BTC&category=&ema=0&m=indicators.Sopr&mAvg=30&mMedian=0" target="_blank" rel="noopener noreferrer">SOPR 30d SMA</a></li>
                    <li><a href="https://studio.glassnode.com/metrics?a=BTC&category=&ema=0&m=indicators.Sopr&mAvg=90&mMedian=0" target="_blank" rel="noopener noreferrer">SOPR 90d SMA</a></li>
                    <li><a href="https://cryptoquant.com/overview?search=sopr" target="_blank" rel="noopener noreferrer">SOPR STH/LTH</a></li>
                    <li><a href="https://studio.glassnode.com/metrics?a=BTC&category=&ema=0&m=supply.ActiveMore1YPercent&mAvg=0&mMedian=0" target="_blank" rel="noopener noreferrer">Supply Last Active 1+ year </a> - pokazuje kiedy następuje dystrybucja</li>
                    <li><a href="https://cryptoquant.com/overview/btc-exchange-flows" target="_blank" rel="noopener noreferrer">Bitcoin Exchange Flows</a></li>
                    <li><a href="https://cryptoquant.com/overview/btc-exchange-flows/216?window=day" target="_blank" rel="noopener noreferrer">Bitcoin Spot Exchanges Reserve</a></li>
                    <li><a href="https://studio.glassnode.com/metrics?a=BTC&category=&ema=0&m=supply.ActiveMore1YPercent&mAvg=0&mMedian=0" target="_blank" rel="noopener noreferrer">Supply Last Active 1+ year </a> - pokazuje kiedy następuje dystrybucja</li>
                    <li><a href="https://www.lookintobitcoin.com/charts/stock-to-flow-model/" target="_blank" rel="noopener noreferrer">Stock-to-Flow model</a> - Jeżeli wykres na dole jes poniżej poziomej linii (na zielono) to wg modelu oznacza że BTC jest niedoszacowany. Kolory wykresu na górze oznaczają czas względem halvingu. Czerwony = halving</li>
                    <li><a href="https://www.lookintobitcoin.com/charts/golden-ratio-multiplier/" target="_blank" rel="noopener noreferrer">Golden Ratio Multiplier</a> - wejscie - dolna wstega, potencjalny poczatek wychodzenia - od zielonej wstegi</li>
                    <li><a href="https://www.lookintobitcoin.com/charts/puell-multiple/" target="_blank" rel="noopener noreferrer">Puell Multiple</a> - Profitiwość i zachowanie kopalni btc. Gdy na zielonym - kopalnie kapitulują (kopanie nieopłacalne) - warto wchodzić. Zauważ wypłaszczanie się linii oporu w CZASIE</li>
                    <li><a href="http://charts.woobull.com/bitcoin-nvt-price/" target="_blank" rel="noopener noreferrer">Bitcoin NVT Price</a> - może pokazywać bardzo dobre momenty zakupu. podłogę cenową dla btc - w długim terminie</li>
                    <li><a href="https://www.blockchaincenter.net/altcoin-season-index/" target="_blank" rel="noopener noreferrer">Altcoin season meter</a></li>
                    <li><a href="https://bitinfocharts.com/pl/bitcoin/address/1P5ZEDWTKTFGxQjZphgWPQUpe554WKDfHQ" target="_blank" rel="noopener noreferrer">Interesting BTC wallet</a></li>


                </ul>
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->

@endsection
