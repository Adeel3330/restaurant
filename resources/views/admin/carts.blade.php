@extends('admin.include.sidebar')

@section('body')

<!-- Row -->
<div class="row mt-25">
    <div class="col-sm-12">
        <div class="panel panel-default card-view">
            <div class="panel-heading">
                <div class="pull-left">
                    <h6 class="panel-title txt-dark">Carts List</h6>
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
                                        <th>Product</th>
                                        <th>Product Image</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>ID #</th>
                                        <th>User</th>
                                        <th>Product</th>
                                        <th>Product Image</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Actions</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @forelse ($carts as $cart)
                                    <tr>
                                        <td>{{ $cart->id }}</td>
                                        <td>{{ $cart->user->name }}</td>
                                        <td>{{ $cart->product->name }}</td>

                                        <td><img class="img-circle" alt="Product" height="80" src="{{ (isset($_SERVER['https']) && $_SERVER['https'] !== 'off') ?  $_SERVER['DOCUMENT_ROOT'] . '/image/product/' .$cart->product->image : url('/image/product/'.$cart->product->image) }}" width="80" /></td>
                                        <td>{{ $cart->product->price }}</td>
                                        <td>{{ $cart->quantity }}</td>
                                        <td><a href="javascript:void(0)" onclick="deleteItem('<?php echo $cart->id ?>' ,'/admin/delete_cart','Cart')" class="text-inverse" title="" data-toggle="tooltip" data-original-title="Delete"><i class="fa fa-trash"></i></a></td>
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