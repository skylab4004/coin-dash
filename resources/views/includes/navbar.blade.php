<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">SB Admin <sup>2</sup></div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="index.html">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Interface
    </div>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
           aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-fw fa-cog"></i>
            <span>Components</span>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Custom Components:</h6>
                <a class="collapse-item" href="buttons.html">Buttons</a>
                <a class="collapse-item" href="cards.html">Cards</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Utilities Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
           aria-expanded="true" aria-controls="collapseUtilities">
            <i class="fas fa-fw fa-wrench"></i>
            <span>Utilities</span>
        </a>
        <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
             data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Custom Utilities:</h6>
                <a class="collapse-item" href="utilities-color.html">Colors</a>
                <a class="collapse-item" href="utilities-border.html">Borders</a>
                <a class="collapse-item" href="utilities-animation.html">Animations</a>
                <a class="collapse-item" href="utilities-other.html">Other</a>
            </div>
        </div>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Addons
    </div>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages"
           aria-expanded="true" aria-controls="collapsePages">
            <i class="fas fa-fw fa-folder"></i>
            <span>Pages</span>
        </a>
        <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Login Screens:</h6>
                <a class="collapse-item" href="login.html">Login</a>
                <a class="collapse-item" href="register.html">Register</a>
                <a class="collapse-item" href="forgot-password.html">Forgot Password</a>
                <div class="collapse-divider"></div>
                <h6 class="collapse-header">Other Pages:</h6>
                <a class="collapse-item" href="404.html">404 Page</a>
                <a class="collapse-item" href="blank.html">Blank Page</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Charts -->
    <li class="nav-item">
        <a class="nav-link" href="charts.html">
            <i class="fas fa-fw fa-chart-area"></i>
            <span>Charts</span></a>
    </li>

    <!-- Nav Item - Tables -->
    <li class="nav-item">
        <a class="nav-link" href="tables.html">
            <i class="fas fa-fw fa-table"></i>
            <span>Tables</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

    <!-- Sidebar Message -->
    <div class="sidebar-card d-none d-lg-flex">
        <img class="sidebar-card-illustration mb-2" src="img/undraw_rocket.svg" alt="...">
        <p class="text-center mb-2"><strong>SB Admin Pro</strong> is packed with premium features, components, and more!</p>
        <a class="btn btn-success btn-sm" href="https://startbootstrap.com/theme/sb-admin-pro">Upgrade to Pro!</a>
    </div>

</ul>
<!-- End of Sidebar -->

{{--<!-- This example requires Tailwind CSS v2.0+ -->--}}
{{--<nav class="bg-gray-900" x-data="{ menu_open: false }">--}}
{{--    <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">--}}
{{--        <div class="relative flex items-center justify-between h-16">--}}
{{--            <div class="absolute inset-y-0 left-0 flex items-center sm:hidden">--}}
{{--                <!-- Mobile menu button-->--}}
{{--                <button @click="menu_open = !menu_open" type="button"--}}
{{--                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"--}}
{{--                        aria-controls="mobile-menu" aria-expanded="false">--}}
{{--                    <span class="sr-only">Open main menu</span>--}}
{{--                    <!-- Icon when menu is closed. -->--}}
{{--                    <!----}}
{{--                      Heroicon name: outline/menu--}}

{{--                      Menu open: "hidden", Menu closed: "block"--}}
{{--                    -->--}}
{{--                    <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"--}}
{{--                         stroke="currentColor" aria-hidden="true">--}}
{{--                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"--}}
{{--                              d="M4 6h16M4 12h16M4 18h16"/>--}}
{{--                    </svg>--}}
{{--                    <!-- Icon when menu is open. -->--}}
{{--                    <!----}}
{{--                      Heroicon name: outline/x--}}

{{--                      Menu open: "block", Menu closed: "hidden"--}}
{{--                    -->--}}
{{--                    <svg class="hidden h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"--}}
{{--                         stroke="currentColor" aria-hidden="true">--}}
{{--                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>--}}
{{--                    </svg>--}}
{{--                </button>--}}
{{--            </div>--}}
{{--            <div class="flex-1 flex items-center justify-center sm:items-stretch sm:justify-start">--}}
{{--                <div class="flex-shrink-0 flex items-center">--}}
{{--                    <img class="block lg:hidden h-8 w-auto"--}}
{{--                         src="https://tailwindui.com/img/logos/workflow-mark-indigo-500.svg" alt="Workflow">--}}
{{--                    <img class="hidden lg:block h-8 w-auto"--}}
{{--                         src="https://tailwindui.com/img/logos/workflow-logo-indigo-500-mark-white-text.svg"--}}
{{--                         alt="Workflow">--}}
{{--                </div>--}}
{{--                <div class="hidden sm:block sm:ml-6">--}}
{{--                    <div class="flex space-x-4">--}}
{{--                        <!-- Current: "bg-gray-900 text-white" -->--}}
{{--                        <!-- active:  "bg-gray-900 text-white px-3 py-2 rounded-md text-sm font-medium" -->--}}

{{--                        <!-- Default:  "text-gray-300 hover:bg-gray-700 hover:text-white" -->--}}
{{--                        <!-- inactive: "text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium"-->--}}
{{--                        <a href="{{ route('dashboard') }}"--}}
{{--                           class="{{ (strpos(Route::currentRouteName(), 'dashboard') === 0) ? 'bg-gray-900 text-white px-3 py-2 rounded-md text-sm font-medium' : 'text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium' }}">Dashboard</a>--}}
{{--                        <a href="{{ route('portfolio-charts') }}"--}}
{{--                           class="{{ (strpos(Route::currentRouteName(), 'portfolio-charts') === 0) ? 'bg-gray-900 text-white px-3 py-2 rounded-md text-sm font-medium' : 'text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium' }}">Portfolio</a>--}}
{{--                        <a href="{{ route('charts') }}"--}}
{{--                           class="{{ (strpos(Route::currentRouteName(), 'charts') === 0) ? 'bg-gray-900 text-white px-3 py-2 rounded-md text-sm font-medium' : 'text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium' }}">Charts</a>--}}
{{--                        <a href="{{ route('price-alerts.index') }}"--}}
{{--                           class="{{ (strpos(Route::currentRouteName(), 'price-alerts') === 0) ? 'bg-gray-900 text-white px-3 py-2 rounded-md text-sm font-medium' : 'text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium' }}">Price--}}
{{--                            alerts</a>--}}
{{--                        <a href="{{ route('portfolio-coins.index') }}"--}}
{{--                           class="{{ (strpos(Route::currentRouteName(), 'portfolio-coins') === 0) ? 'bg-gray-900 text-white px-3 py-2 rounded-md text-sm font-medium' : 'text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium' }}">Portfolio--}}
{{--                            coins</a>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            @auth--}}
{{--                <div class="hidden sm:block flex justify-end">--}}
{{--                    <form class="flex" method="POST" action="{{ route('logout') }}">--}}
{{--                        @csrf--}}
{{--                        <a href="{{ route('logout') }}"--}}
{{--                           class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium"--}}
{{--                           onclick="event.preventDefault(); this.closest('form').submit();">Log Out</a>--}}
{{--                    </form>--}}
{{--                </div>--}}
{{--            @endauth--}}
{{--            @guest--}}
{{--                <div class="hidden sm:block flex justify-end">--}}
{{--                    <form class="flex" method="POST" action="{{ route('logout') }}">--}}
{{--                        @csrf--}}
{{--                        <a href="{{ route('dashboard') }}"--}}
{{--                           class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium"--}}
{{--                           onclick="event.preventDefault(); this.closest('form').submit();">Log In</a>--}}
{{--                    </form>--}}
{{--                </div>--}}
{{--            @endguest--}}

{{--        </div>--}}
{{--    </div>--}}

{{--    <!-- Mobile menu, show/hide based on menu state. -->--}}
{{--    <div class="sm:hidden" id="mobile-menu" x-show="menu_open">--}}
{{--        <div class="px-2 pt-2 pb-3 space-y-1">--}}
{{--            <!-- Current: "bg-gray-900 text-white", Default: "text-gray-300 hover:bg-gray-700 hover:text-white" -->--}}
{{--            <a href="{{ route('dashboard') }}"--}}
{{--               class="{{ (strpos(Route::currentRouteName(), 'dashboard') === 0) ? 'bg-gray-900 text-white block px-3 py-2 rounded-md text-base font-medium' : 'text-gray-300 hover:bg-gray-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium' }}">Dashboard</a>--}}
{{--            <a href="{{ route('portfolio-charts') }}"--}}
{{--               class="{{ (strpos(Route::currentRouteName(), 'portfolio-charts') === 0) ? 'bg-gray-900 text-white block px-3 py-2 rounded-md text-base font-medium' : 'text-gray-300 hover:bg-gray-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium' }}">Portfolio</a>--}}
{{--            <a href="{{ route('charts') }}"--}}
{{--               class="{{ (strpos(Route::currentRouteName(), 'charts') === 0) ? 'bg-gray-900 text-white block px-3 py-2 rounded-md text-base font-medium' : 'text-gray-300 hover:bg-gray-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium' }}">Charts</a>--}}
{{--            <a href="{{ route('price-alerts.index') }}"--}}
{{--               class="{{ (strpos(Route::currentRouteName(), 'price-alerts') === 0) ? 'bg-gray-900 text-white block px-3 py-2 rounded-md text-base font-medium' : 'text-gray-300 hover:bg-gray-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium' }}">Price--}}
{{--                Alerts</a>--}}
{{--            <a href="{{ route('portfolio-coins.index') }}"--}}
{{--               class="{{ (strpos(Route::currentRouteName(), 'portfolio-coins') === 0) ? 'bg-gray-900 text-white block px-3 py-2 rounded-md text-base font-medium' : 'text-gray-300 hover:bg-gray-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium' }}">Portfolio--}}
{{--                coins</a>--}}
{{--            <form class="flex" method="POST" action="{{ route('logout') }}">--}}
{{--                @csrf--}}
{{--                <a href="{{ route('logout') }}"--}}
{{--                   class="text-gray-300 hover:bg-gray-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium">Log--}}
{{--                    out</a>--}}
{{--            </form>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</nav>--}}
