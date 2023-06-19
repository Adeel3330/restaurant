@extends('admin.include.sidebar')

@section('body')

<!-- Row -->
<div class="row mt-25">
    <div class="col-sm-12">
        <div class="panel panel-default card-view">
            <div class="panel-heading">
                <div class="pull-left">
                    <h6 class="panel-title txt-dark">Driver List</h6>
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
                                        <th>Driver name</th>
                                        <th>Email</th>
                                        <th>Phone No</th>
                                        <th>Image</th>
                                        <th>Restaurant</th>
                                        <th>Accept/Reject</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>ID #</th>
                                        <th>Driver name</th>
                                        <th>Email</th>
                                        <th>Phone No</th>
                                        <th>Image</th>
                                        <th>Restaurant</th>
                                        <th>Accept/Reject</th>
                                        <th>Actions</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @forelse ($drivers as $driver)
                                    <tr>
                                        <input type="hidden" id="_token" value="{{ csrf_token() }}">
                                        <td>{{ $driver->id }}</td>
                                        <td>{{ $driver->name }}</td>
                                        <td>{{ $driver->email }}</td>
                                        <td>{{ $driver->phone_no }}</td>

                                        <td><img class="img-circle" height="80" src="{{ (isset($_SERVER['https']) && $_SERVER['https'] !== 'off') ?  $_SERVER['DOCUMENT_ROOT'] . '/image/driver/' .$driver->image : url('/image/driver/'.$driver->image) }}" width="80" /></td>
                                        <td>{{ $driver->restaurant->name }}</td>
                                        @if($driver->status == 'Pending')
                                        <td><a href="javascript:;" onclick="UpdateStatus('<?php echo $driver->id ?>' ,'/admin/update_driver_status','driver','Active')" class="text-inverse p-r-10 btn btn-success btn-icon-anim btn-circle btn-lg" data-toggle="tooltip" title="" data-original-title="Accepted"><i class="fa fa-check"></i></a> <a href="javascript:void(0)" onclick="UpdateStatus('<?php echo $driver->id ?>' ,'/admin/update_driver_status','driver','Rejected')" class="text-inverse btn btn-danger btn-icon-anim btn-circle btn-lg" title="" data-toggle="tooltip" data-original-title="Rejected"><i class="fa fa-close"></i></a></td>

                                        @else
                                        <td><span class="badge {{ $driver->status == 'Active' || $driver->status == 'Accepted' ? 'badge badge-primary':'badge badge-danger' }}">{{ $driver->status == 'Active' || $driver->status == 'Accepted' ? 'Accepted':$driver->status }}</span></td>
                                        @endif
                                        <td><a href="/admin/driver-edit/{{$driver->id}}" class="text-inverse p-r-10" data-toggle="tooltip" title="" data-original-title="Edit"><i class="fa fa-check"></i></a> <a href="javascript:void(0)" onclick="deleteItem('<?php echo $driver->id ?>' ,'/admin/delete_driver','Driver')" class="text-inverse" title="" data-toggle="tooltip" data-original-title="Delete"><i class="fa fa-trash"></i></a></td>
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