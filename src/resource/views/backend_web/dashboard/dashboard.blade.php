@extends('backend_web.layouts.index')
@section('content')
<div class="pcoded-inner-content">
    <!-- Main-body start -->
    <div class="main-body">
        <div class="page-wrapper" style="margin:20px">
            <!-- Page-body start -->
            <div class="page-body">
                <div class="row">
                    <!-- order-card start -->
                    <div class="col-md-6 col-xl-3">
                        <div class="card bg-c-blue order-card">
                            <div class="card-block">
                                <h6 class="m-b-20">Orders Received</h6>
                                <h2 class="text-right"><i class="ti-shopping-cart f-left"></i><span>486</span></h2>
                                <p class="m-b-0">Completed Orders<span class="f-right">351</span></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-3">
                        <div class="card bg-c-green order-card">
                            <div class="card-block">
                                <h6 class="m-b-20">Total Sales</h6>
                                <h2 class="text-right"><i class="ti-tag f-left"></i><span>1641</span></h2>
                                <p class="m-b-0">This Month<span class="f-right">213</span></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-3">
                        <div class="card bg-c-yellow order-card">
                            <div class="card-block">
                                <h6 class="m-b-20">Revenue</h6>
                                <h2 class="text-right"><i class="ti-reload f-left"></i><span>$42,562</span></h2>
                                <p class="m-b-0">This Month<span class="f-right">$5,032</span></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-3">
                        <div class="card bg-c-pink order-card">
                            <div class="card-block">
                                <h6 class="m-b-20">Total Profit</h6>
                                <h2 class="text-right"><i class="ti-wallet f-left"></i><span>$9,562</span></h2>
                                <p class="m-b-0">This Month<span class="f-right">$542</span></p>
                            </div>
                        </div>
                    </div>
                    <!-- order-card end -->

                </div>
            </div>
            <!-- Page-body end -->
        </div>
        <div id="styleSelector"> </div>
    </div>
</div>
@endsection
