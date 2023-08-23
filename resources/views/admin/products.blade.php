@extends('admin.include.sidebar')

@section('body')

<!-- Row -->
<div class="row mt-25">
    <div class="col-sm-12">
        <div class="panel panel-default card-view">
            <div class="panel-heading">
                <div class="pull-left">
                    <h6 class="panel-title txt-dark">Products List</h6>
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
                                        <th>Name</th>
                                        <th>Category</th>
                                      
                                        <th>Image</th>
                                        <th>Price</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>ID #</th>
                                        <th>Name</th>
                                        <th>Category</th>
                                        
                                        <th>Image</th>
                                        <th>Price</th>
                                        <th>Actions</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @forelse ($products as $product)
                                    <tr>
                                        <td>{{ $product->id }}</td>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->category->name }}</td>
                                   
                                        <td><img class="img-circle" alt="Product" height="80" src="{{ (isset($_SERVER['https']) && $_SERVER['https'] !== 'off') ?  $_SERVER['DOCUMENT_ROOT'] . '/image/product/' .$product->image : url('/image/product/'.$product->image) }}" width="80" /></td>
                                        <td>{{ $product->price }}</td>
                                        <td><a href="/admin/product-edit/{{$product->id}}" class="text-inverse p-r-10" data-toggle="tooltip" title="" data-original-title="Edit"><i class="fa fa-check"></i></a> <a href="javascript:void(0)" onclick="deleteItem('<?php echo $product->id ?>' ,'/admin/delete_product','Product')" class="text-inverse" title="" data-toggle="tooltip" data-original-title="Delete"><i class="fa fa-trash"></i></a></td>
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