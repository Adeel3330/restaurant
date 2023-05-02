@extends('admin.include.sidebar')

@section('body')

<!-- Row -->
<div class="row mt-25">
    <div class="col-sm-12">
        <div class="panel panel-default card-view">
            <div class="panel-heading">
                <div class="pull-left">
                    <h6 class="panel-title txt-dark">Admins List</h6>
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
                                        <th>Admin name</th>
                                        <th>Email</th>
                                        <th>Phone No</th>
                                        <th>Image</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>ID #</th>
                                        <th>Admin name</th>
                                        <th>Email</th>
                                        <th>Phone No</th>
                                        <th>Image</th>
                                        <th>Actions</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @forelse ($admins as $admin)
                                    <tr>
                                        <td>{{ $admin->id }}</td>
                                        <td>{{ $admin->name }}</td>
                                        <td>{{ $admin->email }}</td>
                                        <td>{{ $admin->phone_no }}</td>
                                        
                                        <td><img class="img-circle" height="80" src="{{ (isset($_SERVER['https']) && $_SERVER['https'] !== 'off') ?  $_SERVER['DOCUMENT_ROOT'] . '/image/admin/' .$admin->image : url('/image/admin/'.$admin->image) }}" width="80" /></td>
                                        <td><a href="/admin/admin-edit/{{$admin->id}}" class="text-inverse p-r-10" data-toggle="tooltip" title="" data-original-title="Edit"><i class="fa fa-check"></i></a> <a href="javascript:void(0)" onclick="deleteItem('<?php echo $admin->id ?>' ,'/admin/delete_admin','Admin')" class="text-inverse" title="" data-toggle="tooltip" data-original-title="Delete"><i class="fa fa-trash"></i></a></td>
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