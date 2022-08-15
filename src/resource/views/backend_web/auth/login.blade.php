<!DOCTYPE html>
<html lang="en">

<head>
    <title>OsGood Admin Login </title>

    @include('backend_web.layouts.header')
</head>

<body class="fix-menu">


    <section class="login p-fixed d-flex text-center" style="background: linear-gradient(90deg, rgb(138 243 141) 0%, rgb(0 88 15) 35%, rgb(122 217 36) 100%);">
        <!-- Container-fluid starts -->
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <!-- Authentication card start -->
                    <div class="login-card card-block auth-body mr-auto ml-auto">
                        <form method="POST" action="{{ route('AdminLogin') }}" class="md-float-material"
                            autocomplete="off">
                            @csrf
                            <div class="text-center">
                                <img src="/files/assets/images/logo.png" style="height: 100px;" alt="logo.png">
                            </div>
                            <div class="auth-box">
                                <div class="row m-b-20">
                                    <div class="col-md-12">
                                        @include('backend_web.layouts.errors')
                                        <h3 class="text-left txt-primary">Sign In</h3>
                                    </div>

                                </div>
                                <hr />
                                <div class="input-group">
                                    <input type="email" class="form-control" name="email"
                                        placeholder="Your Email Address">
                                </div>
                                <div class="input-group">
                                    <input type="password" name="password" class="form-control"
                                        placeholder="Password">
                                    <span class="md-line"></span>
                                </div>
                                <div class="row m-t-25 text-left">
                                    <div class="col-12">
                                        <div class="checkbox-fade fade-in-primary d-">
                                            <label>
                                                <input type="checkbox" value="">
                                                <span class="cr"><i
                                                        class="cr-icon icofont icofont-ui-check txt-primary"></i></span>
                                                <span class="text-inverse">Remember me</span>
                                            </label>
                                        </div>
                                        <!-- <div class="forgot-phone text-right f-right">
                                            <a href="auth-reset-password.html" class="text-right f-w-600 text-inverse"> Forgot Password?</a>
                                        </div> -->
                                    </div>
                                </div>
                                <div class="row m-t-30">
                                    <div class="col-md-12">
                                        <button type="submit"
                                            class="btn btn-dark btn-md btn-block waves-effect text-center m-b-20">Sign
                                            in</button>
                                    </div>

                                </div>
                                <hr>
                            
                            </div>
                        </form>
                        <!-- end of form -->
                    </div>
                    <!-- Authentication card end -->
                </div>
                <!-- end of col-sm-12 -->
            </div>
            <!-- end of row -->
        </div>
        <!-- end of container-fluid -->
    </section>
    <!-- Warning Section Starts -->

    @include('backend_web.layouts.script')
</body>


</html>
