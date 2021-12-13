<!-- Topbar -->
<nav class="navbar navbar-expand navbar-light topbar static-top">

<!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    <!-- Custom Switch -->
    <div class="form-check form-switch">
        <input type="checkbox" class="form-check-input" id="reloadCB" onclick="toggleAutoRefresh(this);">
        <label class="form-check-label" for="reloadCB">Auto refresh</label>
    </div>

    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">

        <div class="topbar-divider d-none d-sm-block"></div>

        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
{{--                {{ Auth::user()->name }}--}}
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">Username or Login button here</span>
                <img class="img-profile rounded-circle"
                     src="img/undraw_profile.svg">
            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                 aria-labelledby="userDropdown">
                <a class="dropdown-item" href="#">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    Profile
                </a>
                <a class="dropdown-item" href="#">
                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                    Settings
                </a>
                <a class="dropdown-item" href="#">
                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                    Activity Log
                </a>
                <div class="dropdown-divider"></div>
            {{--                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">--}}
            {{--                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>--}}
            {{--                    Logout--}}
            {{--                </a>--}}

            <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <a class="dropdown-item" onclick="event.preventDefault(); this.closest('form').submit();">
                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                        Logout
                    </a>
                </form>


            </div>
        </li>

    </ul>

</nav>
<!-- End of Topbar -->

<script>
    var reloading;

    function checkReloading() {
        if (window.location.hash=="#autoreload") {
            reloading=setTimeout("window.location.reload();", 300000);
            document.getElementById("reloadCB").checked=true;
        }
    }

    function toggleAutoRefresh(cb) {
        if (cb.checked) {
            window.location.replace("#autoreload");
            reloading=setTimeout("window.location.reload();", 300000);
        } else {
            window.location.replace("#");
            clearTimeout(reloading);
        }
    }

    window.onload=checkReloading;
</script>
