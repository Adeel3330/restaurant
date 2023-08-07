@extends('admin.include.sidebar')

@section('body')

<!-- Row -->
<div class="row mt-25">
    <div class="col-sm-12">
        <div class="panel panel-default card-view">
            <div class="panel-heading">
                <div class="pull-left">
                    <h6 class="panel-title txt-dark">Addon Sub Categories List</h6>
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
                                        <th>Restaurant</th>
                                        <th>Image</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>ID #</th>
                                        <th>Name</th>
                                        <th>Category</th>
                                        <th>Restaurant</th>
                                        <th>Image</th>
                                        <th>Actions</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @forelse ($addon_sub_categories as $addon_sub_category)
                                    <tr>
                                        <td>{{ $addon_sub_category->id }}</td>
                                        <td>{{ $addon_sub_category->name }}</td>
                                        <td>{{ $addon_sub_category->addon_category->name }}</td>
                                        <td>{{ $addon_sub_category->restaurant->name }}</td>
                                        <td><img class="img-circle" height="80" src="{{ (isset($_SERVER['https']) && $_SERVER['https'] !== 'off') ?  $_SERVER['DOCUMENT_ROOT'] . '/image/addon_sub_category/' .$addon_sub_category->image : url('/image/addon_sub_category/'.$addon_sub_category->image) }}" width="80" /></td>
                                        <td><a href="/admin/addon-sub-category-edit/{{$addon_sub_category->id}}" class="text-inverse p-r-10" data-toggle="tooltip" title="" data-original-title="Edit"><i class="fa fa-check"></i></a> <a href="javascript:void(0)" onclick="deleteItem('<?php echo $addon_sub_category->id ?>' ,'/admin/delete_addon_sub_category','Category')" class="text-inverse" title="" data-toggle="tooltip" data-original-title="Delete"><i class="fa fa-trash"></i></a></td>
                                    </tr>

                                    @empty
                                    <tr>
                                        <td colspan="6">No Record Found</td>
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