<!-- Sidebar -->
<ul class="navbar-nav sidebar sidebar-light accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
        <div class="sidebar-brand-icon">
            <i class="fab fa-bitcoin"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Dash</div>
    </a>

    <!-- Divider -->
{{--    <hr class="sidebar-divider my-0">--}}

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Portfolio
    </div>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('portfolio-overview') }}">
            <i class="fas fa-chart-pie"></i>
            <span>Overview</span></a>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
           aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-wallet"></i>
            <span>Wallets</span>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ route('portfolio-binance') }}">Binance</a>
                <a class="collapse-item" href="{{ route('portfolio-ethereum') }}">Ethereum</a>
                <a class="collapse-item" href="{{ route('portfolio-mexc') }}">Mexc</a>
                <a class="collapse-item" href="{{ route('portfolio-bsc') }}">BSC</a>
                <a class="collapse-item" href="{{ route('portfolio-bitbay') }}">BitBay</a>
                <a class="collapse-item" href="{{ route('portfolio-polygon') }}">Polygon</a>
                <a class="collapse-item" href="{{ route('portfolio-ascendex') }}">AscendEx</a>
                <a class="collapse-item" href="{{ route('portfolio-coinbase') }}">CoinBase</a>
                <a class="collapse-item" href="{{ route('portfolio-kucoin') }}">KuCoin</a>
            </div>
        </div>
    </li>


    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Charts
    </div>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('charts') }}">
            <i class="fas fa-fw fa-chart-area"></i>
            <span>Charts</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Alerts
    </div>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('price-alerts.index') }}">
            <i class="fas fa-bell"></i>
            <span>Price Alerts</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Settings
    </div>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('portfolio-coins.index') }}">
            <i class="fas fa-coins"></i>
{{--            <i class="fas fa-fw fa-tachometer-alt"></i>--}}
            <span>Portfolio Coins</span></a>
    </li>


    <!-- Heading -->
    <div class="sidebar-heading">
        Links & Stuff
    </div>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('links') }}">
            <i class="fas fa-coins"></i>
            <span>Links</span></a>
    </li>

{{--    <!-- Nav Item - Pages Collapse Menu -->--}}
{{--    <li class="nav-item">--}}
{{--        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"--}}
{{--           aria-expanded="true" aria-controls="collapseTwo">--}}
{{--            <i class="fas fa-fw fa-cog"></i>--}}
{{--            <span>Components</span>--}}
{{--        </a>--}}
{{--        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">--}}
{{--            <div class="bg-white py-2 collapse-inner rounded">--}}
{{--                <h6 class="collapse-header">Custom Components:</h6>--}}
{{--                <a class="collapse-item" href="buttons.html">Buttons</a>--}}
{{--                <a class="collapse-item" href="cards.html">Cards</a>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </li>--}}

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->

