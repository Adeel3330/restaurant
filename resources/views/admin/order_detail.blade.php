@extends('admin.include.sidebar')

@section('body')

<!-- Row -->
<div class="row mt-25">
    <div class="col-sm-12">
        <div class="panel panel-default card-view">
            <div class="panel-heading">
                <div class="pull-left">
                    <h6 class="panel-title txt-dark">Orders View</h6>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="panel-wrapper collapse in">
                <div class="panel-body">
                    <div class="table-wrap">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-3">User</label>
                                    <div class="col-md-9">
                                        <p class="form-control-static" style="padding:0px">{{ $order->user->name }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-3">Status:</label>
                                    <div class="col-md-9">
                                        <p class="form-control-static" style="padding:0px">
                                            <td><span class='{{ $order->status == "pending" ? "label label-primary font-weight-100":"label label-success font-weight-100" }}'>{{ $order->status }}</span></td>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-3">Transaction Id:</label>
                                    <div class="col-md-9">
                                        <p class="form-control-static" style="padding:0px"> {{ $order->transaction_id }} </p>
                                    </div>
                                </div>
                            </div>
                            <!--/span-->

                            <!--/span-->
                        </div>
                        <!-- /Row -->

                        <div class="seprator-block"></div>

                        <h6 class="txt-dark capitalize-font"><i class="icon-notebook mr-10"></i>Order Items</h6>
                        <hr>

                        <div class="table-responsive">
                            <table id="example" class="table table-hover display  pb-30">
                                <thead>
                                    <tr>
                                        <th>ID #</th>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>Price (Per Unit)</th>
                                        <th>Total Price</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>ID #</th>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>Price (Per Unit)</th>
                                        <th>Total Price</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @forelse ($order->orders_items as $item)

                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td>{{ $item->product->name }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>{{ $item->payment }}</td>
                                        <td>{{ $item->quantity * $item->payment }}</td>
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
<!-- /Row -->

@stop