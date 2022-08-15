<nav class="pcoded-navbar" style="background: #0f365e;margin-top: 27px;border-top-right-radius: 30px;">
    <div class="sidebar_toggle"><a href="#"><i class="icon-close icons"></i></a></div>
    <div class="pcoded-inner-navbar main-menu">
        <ul class="pcoded-item pcoded-left-item mt-2">
            <li class="">
                <a href="{{ route('admin-dashboard') }}">
                    <span class="pcoded-micon"><i class="ti-home"></i><b>D</b></span>
                    <span class="pcoded-mtext">DashBoard</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
            <li class="">
                <a href="{{ route('AdminLogout') }}">
                    <span class="pcoded-micon"><i class="ti-layout-sidebar-left"></i><b>N</b></span>
                    <span class="pcoded-mtext">Logout</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
        </ul>
    </div>
</nav>
