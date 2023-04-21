@extends('admin.include.sidebar')

@section('body')


<!-- Title -->
<div class="row heading-bg  bg-yellow">
    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
        <h5 class="txt-light">e-commerce</h5>
    </div>
    <!-- Breadcrumb -->
    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
        <ol class="breadcrumb">
            <li><a href="/admin/home">Dashboard</a></li>
            <li><a href="/admin/home"><span>e-commerce</span></a></li>
            <li class="active"><span>e-commerce</span></li>
        </ol>
    </div>
    <!-- /Breadcrumb -->
</div>
<!-- /Title -->
<!-- Row -->
<div class="row">


    <div class="col-md-3 col-sm-5 col-xs-12">
        <div class="panel panel-default card-view pa-0">
            <div class="panel-wrapper collapse in">
                <div class="panel-body pa-0">
                    <div class="sm-data-box bg-red">
                        <div class="row ma-0">
                            <div class="col-xs-5 text-center pa-0 icon-wrap-left">
                                <i class="icon-briefcase txt-light"></i>
                            </div>
                            <div class="col-xs-7 text-center data-wrap-right">
                                <h6 class="txt-light">Total restaurants</h6>
                                <span class="txt-light counter counter-anim">{{ $data['restaurants'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
    <div class="col-md-3 col-sm-5">
        <div class="panel panel-default card-view pa-0">
            <div class="panel-wrapper collapse in">
                <div class="panel-body pa-0">
                    <div class="sm-data-box bg-yellow">
                        <div class="row ma-0">
                            <div class="col-xs-5 text-center pa-0 icon-wrap-left">
                                <i class="icon-rocket txt-light"></i>
                            </div>
                            <div class="col-xs-7 text-center data-wrap-right">
                                <h6 class="txt-light">Total Products</h6>
                                <span class="txt-light counter">{{ $data['products'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="panel panel-default card-view">
            <div class="panel-heading">
                <div class="pull-left">
                    <h6 class="panel-title txt-dark">total users</h6>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="panel-wrapper collapse in">
                <div class="panel-body">
                    <div class="sm-graph-box">
                        <div class="row">
                            <div class="col-xs-6">
                                <div id="sparkline_2"><canvas width="90" height="45" style="display: inline-block; width: 90px; height: 45px; vertical-align: top;"></canvas></div>
                            </div>
                            <div class="col-xs-6">
                                <div class="counter-wrap text-right">
                                    <span class="counter-cap"><i class="fa  fa-level-up txt-success"></i></span><span class="counter">{{ $data['users'] }}</span><span></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="col-lg-3 col-md-4 col-sm-12">
        <div class="panel panel-default card-view">
            <div class="panel-heading">
                <div class="pull-left">
                    <h6 class="panel-title txt-dark">Total Orders</h6>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="panel-wrapper collapse in">
                <div class="panel-body">
                    <div class="sm-graph-box">
                        <div class="row">
                            <div class="col-xs-6">
                                <div id="sparkline_1"></div>
                            </div>
                            <div class="col-xs-6">
                                <div class="counter-wrap text-right">
                                    <span class="counter-cap"><i class="fa  fa-level-up txt-success"></i></span><span class="counter">{{ $data['orders_count'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

<!-- <div class="row">
    <div class="col-sm-12">
        <div class="panel panel-default card-view">
            <div class="panel-heading">
                <div class="pull-left">
                    <h6 class="panel-title txt-dark">Product orders</h6>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="panel-wrapper collapse in">
                <div class="panel-body">
                    <div class="table-wrap">
                        <div class="table-responsive">
                            <table class="table display responsive product-overview mb-30" id="myTable">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Order ID</th>
                                        <th>Photo</th>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Jens Brincker</td>
                                        <td>#85457898</td>
                                        <td>
                                            <img src="{{ url('dist/img/chair.jpg') }}" alt="iMac" width="80">
                                        </td>
                                        <td>Square Chair</td>
                                        <td>20</td>
                                        <td>10-10-2016</td>
                                        <td>
                                            <span class="label label-success font-weight-100">Paid</span>
                                        </td>
                                        <td><a href="javascript:void(0)" class="text-inverse p-r-10" data-toggle="tooltip" title="Edit"><i class="fa fa-check"></i></a> <a href="javascript:void(0)" class="text-inverse" title="Delete" data-toggle="tooltip"><i class="fa fa-trash"></i></a></td>
                                    </tr>
                                    <tr>
                                        <td>Mark Hay</td>
                                        <td>#85457897</td>
                                        <td>
                                            <img src="{{ url('dist/img/chair2.jpg') }}" alt="iPhone" width="80">
                                        </td>
                                        <td>Semi Circle Chair</td>
                                        <td>15</td>
                                        <td>09-10-2016</td>
                                        <td>
                                            <span class="label label-warning font-weight-100">Pending</span>
                                        </td>
                                        <td><a href="javascript:void(0)" class="text-inverse p-r-10" data-toggle="tooltip" title="Edit"><i class="fa fa-check"></i></a> <a href="javascript:void(0)" class="text-inverse" title="Delete" data-toggle="tooltip"><i class="fa fa-trash"></i></a></td>
                                    </tr>
                                    <tr>
                                        <td>Anthony Davie</td>
                                        <td>#85457896</td>
                                        <td>
                                            <img src="{{ url('dist/img/chair3.jpg') }}" alt="apple_watch" width="80">
                                        </td>
                                        <td>Rounded Chair</td>
                                        <td>10</td>
                                        <td>08-10-2016</td>
                                        <td>
                                            <span class="label label-success font-weight-100">Paid</span>
                                        </td>
                                        <td><a href="javascript:void(0)" class="text-inverse p-r-10" data-toggle="tooltip" title="Edit"><i class="fa fa-check"></i></a> <a href="javascript:void(0)" class="text-inverse" title="Delete" data-toggle="tooltip"><i class="fa fa-trash"></i></a></td>
                                    </tr>
                                    <tr>
                                        <td>David Perry</td>
                                        <td>#85457895</td>
                                        <td>
                                            <img src="{{ url('dist/img/chair4.jpg') }}" alt="mac_mouse" width="80">
                                        </td>
                                        <td>Wooden chair</td>
                                        <td>15</td>
                                        <td>02-10-2016</td>
                                        <td>
                                            <span class="label label-default font-weight-100">Failed</span>
                                        </td>
                                        <td><a href="javascript:void(0)" class="text-inverse p-r-10" data-toggle="tooltip" title="Edit"><i class="fa fa-check"></i></a> <a href="javascript:void(0)" class="text-inverse" title="Delete" data-toggle="tooltip"><i class="fa fa-trash"></i></a></td>
                                    </tr>
                                    <tr>
                                        <td>Anthony Davie</td>
                                        <td>#85457894</td>
                                        <td>
                                            <img src="{{ url('dist/img/chair.jpg') }}" alt="iMac" width="80">
                                        </td>
                                        <td>Rounded Chair</td>
                                        <td>20</td>
                                        <td>08-10-2016</td>
                                        <td>
                                            <span class="label label-success font-weight-100">Paid</span>
                                        </td>
                                        <td><a href="javascript:void(0)" class="text-inverse p-r-10" data-toggle="tooltip" title="Edit"><i class="fa fa-check"></i></a> <a href="javascript:void(0)" class="text-inverse" title="Delete" data-toggle="tooltip"><i class="fa fa-trash"></i></a></td>
                                    </tr>
                                    <tr>
                                        <td>Alan Gilchrist</td>
                                        <td>#85457893</td>
                                        <td>
                                            <img src="{{ url('dist/img/chair2.jpg') }}" alt="iPhone" width="80">
                                        </td>
                                        <td>Wooden Chair</td>
                                        <td>24</td>
                                        <td>06-10-2016</td>
                                        <td>
                                            <span class="label label-warning font-weight-100">Pending</span>
                                        </td>
                                        <td><a href="javascript:void(0)" class="text-inverse p-r-10" data-toggle="tooltip" title="Edit"><i class="fa fa-check"></i></a> <a href="javascript:void(0)" class="text-inverse" title="Delete" data-toggle="tooltip"><i class="fa fa-trash"></i></a></td>
                                    </tr>
                                    <tr>
                                        <td>Sue Woodger</td>
                                        <td>#85457892</td>
                                        <td>
                                            <img src="{{ url('dist/img/chair3.jpg') }}" alt="apple_watch" width="80">
                                        </td>
                                        <td>Square Chair</td>
                                        <td>30</td>
                                        <td>05-10-2016</td>
                                        <td>
                                            <span class="label label-success font-weight-100">Paid</span>
                                        </td>
                                        <td><a href="javascript:void(0)" class="text-inverse p-r-10" data-toggle="tooltip" title="Edit"><i class="fa fa-check"></i></a> <a href="javascript:void(0)" class="text-inverse" title="Delete" data-toggle="tooltip"><i class="fa fa-trash"></i></a></td>
                                    </tr>
                                    <tr>
                                        <td>Barry Croucher</td>
                                        <td>#85457891</td>
                                        <td>
                                            <img src="{{ url('dist/img/chair4.jpg') }}" alt="mac_mouse" width="80">
                                        </td>
                                        <td>Semi Circle Chair</td>
                                        <td>28</td>
                                        <td>01-10-2016</td>
                                        <td>
                                            <span class="label label-default font-weight-100">Failed</span>
                                        </td>
                                        <td><a href="javascript:void(0)" class="text-inverse p-r-10" data-toggle="tooltip" title="Edit"><i class="fa fa-check"></i></a> <a href="javascript:void(0)" class="text-inverse" title="Delete" data-toggle="tooltip"><i class="fa fa-trash"></i></a></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> -->

<div class="row mt-25">
    <div class="col-sm-12">
        <div class="panel panel-default card-view">
            <div class="panel-heading">
                <div class="pull-left">
                    <h6 class="panel-title txt-dark">Orders List</h6>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="panel-wrapper collapse in">
                <div class="panel-body">
                    <div class="table-wrap">
                        <div class="table-responsive">
                            <table id="example" class="table table-hover display  pb-30">
                                <thead>
                                    <tr>
                                        <th>ID #</th>
                                        <th>User</th>
                                        <!-- <th>Product</th> -->
                                        <th>Total Items</th>
                                        <th>Transaction Id</th>
                                        <th>Status</th>
                        
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>ID #</th>
                                        <th>User</th>
                                       
                                        <th>Total Items</th>
                                        <th>Transaction Id</th>
                                        <th>Status</th>
                                      
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @forelse ($orders as $order)

                                    <tr>
                                        <td>{{ $order->id }}</td>
                                        <td>{{ $order->user->name }}</td>

                                        <td>{{ count($order->orders_items) }}</td>
                                        <td>{{ $order->transaction_id }}</td>
                                        <td><span class='{{ $order->status == "pending" ? "label label-primary font-weight-100":"label label-success font-weight-100" }}'>{{ $order->status }}</span></td>
                                        
                                    </tr>

                                    @empty
                                    <tr>
                                        <td colspan="8">No Record Found</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@stop