<nav class="navbar header-navbar pcoded-header" style="    background-image: linear-gradient(to right bottom, #0f51b3, #0080dc, #135a97, #0065cd, #1291eb);">
    <div class="navbar-wrapper">
        <div class="navbar-logo">
            <a class="mobile-menu ml-4" id="mobile-collapse" href="#!">
                <i class="ti-menu"></i>
            </a>
            <div class="mobile-search">
                <div class="header-search">
                    <div class="main-search morphsearch-search">
                        <div class="input-group">
                            <span class="input-group-addon search-close"><i class="ti-close"></i></span>
                            <input type="text" class="form-control" placeholder="Enter Keyword">
                            <span class="input-group-addon search-btn"><i class="ti-search"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <a href="" class="ml-5">
                <img class="img-fluid" src="../files/assets/images/logo.png" alt="Theme-Logo" style="height: 42px;" />
            </a>
            <a class="mobile-options ">
                <i class="ti-more"></i>
            </a>
        </div>

        <div class="navbar-container container-fluid">
            <ul class="nav-left ml-2">
                <li>
                    <div class="sidebar_toggle"><a href="javascript:void(0)"><i class="ti-menu"></i></a></div>
                </li>
            </ul>
            <ul class="nav-right">
     
                <li class="user-profile header-notification">
                    <a href="#!">
                        <img src="../files/assets/images/avatar-4.jpg" class="img-radius" alt="User-Profile-Image">
                        <span>{{Auth()->guard('admin')->user()->name}}</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>