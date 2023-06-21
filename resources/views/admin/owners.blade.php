@extends('admin.include.sidebar')

@section('body')

<!-- Row -->
<div class="row mt-25">
    <div class="col-sm-12">
        <div class="panel panel-default card-view">
            <div class="panel-heading">
                <div class="pull-left">
                    <h6 class="panel-title txt-dark">owner List</h6>
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

                                        <th>Email</th>

                                        <th>Restaurant</th>
                                        <th>Accept/Reject</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>ID #</th>

                                        <th>Email</th>

                                        <th>Restaurant</th>
                                        <th>Accept/Reject</th>
                                        <th>Actions</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @forelse ($owners as $owner)
                                    <tr>
                                        <input type="hidden" id="_token" value="{{ csrf_token() }}">
                                        <td>{{ $owner->id }}</td>
                                        <td>{{ $owner->email }}</td>
                                         <td>{{ $owner->restaurant->name }}</td>
                                        @if($owner->status == 'Pending')
                                        <td><a href="javascript:;" onclick="UpdateStatus('<?php echo $owner->id ?>' ,'/admin/update_owner_status','Owner','Active')" class="text-inverse p-r-10 btn btn-success btn-icon-anim btn-circle btn-lg" data-toggle="tooltip" title="" data-original-title="Accepted"><i class="fa fa-check"></i></a> <a href="javascript:void(0)" onclick="UpdateStatus('<?php echo $owner->id ?>' ,'/admin/update_owner_status','owner','Rejected')" class="text-inverse btn btn-danger btn-icon-anim btn-circle btn-lg" title="" data-toggle="tooltip" data-original-title="Rejected"><i class="fa fa-close"></i></a></td>

                                        @else
                                        <td><span class="badge {{ $owner->status == 'Active' || $owner->status == 'Accepted' ? 'badge badge-primary':'badge badge-danger' }}">{{ $owner->status == 'Active' || $owner->status == 'Accepted' ? 'Accepted':$owner->status }}</span></td>
                                        @endif
                                        <td><a href="/admin/owner-edit/{{$owner->id}}" class="text-inverse p-r-10" data-toggle="tooltip" title="" data-original-title="Edit"><i class="fa fa-check"></i></a> <a href="javascript:void(0)" onclick="deleteItem('<?php echo $owner->id ?>' ,'/admin/delete_owner','owner')" class="text-inverse" title="" data-toggle="tooltip" data-original-title="Delete"><i class="fa fa-trash"></i></a></td>
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