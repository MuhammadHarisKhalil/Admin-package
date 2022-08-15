<!DOCTYPE html>
<html lang="en">
<!-- Added by HTTrack -->
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<!-- /Added by HTTrack -->
<head>
    <title>Admin Panel</title>
    <!-- Meta -->
    @include('backend_web.layouts.header')
</head>
<body>
    <!-- Pre-loader start -->
    <div class="theme-loader">
        <div class="loader-track">
            <div class="loader-bar"></div>
        </div>
    </div>
    <!-- Pre-loader end -->
    <div id="pcoded" class="pcoded">
        <div class="pcoded-overlay-box"></div>
        <div class="pcoded-container navbar-wrapper">
            @include('backend_web.layouts.navbar')
            <div class="pcoded-main-container">
                <div class="pcoded-wrapper">
                    @include('backend_web.layouts.sidebar')
                    <div class="pcoded-content">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>
@include('backend_web.layouts.script')
</body>
</html>
