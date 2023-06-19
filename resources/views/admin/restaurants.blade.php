@extends('admin.include.sidebar')

@section('body')

<!-- Row -->
<div class="row mt-25">
    <div class="col-sm-12">
        <div class="panel panel-default card-view">
            <div class="panel-heading">
                <div class="pull-left">
                    <h6 class="panel-title txt-dark">Restaurants List</h6>
                </div>
                <div class="pull-right">
                    <a class="btn btn-primary" href="/admin/week_days">Add Restaurant Timings</a>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="panel-wrapper collapse in">
                <div class="panel-body">
                    <div class="table-wrap">
                        <div class="table-responsive">
                            <input type="hidden" id="_token" value="{{ csrf_token() }}">
                            <table id="example" class="table table-hover display  pb-30">
                                <thead>
                                    <tr>
                                        <th>ID #</th>
                                        <th>Name</th>
                                        <th>Image</th>
                                        <th>Accept/Reject</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>ID #</th>
                                        <th>Name</th>
                                        <th>Image</th>
                                        <th>Accept/Reject</th>
                                        <th>Actions</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @forelse ($restaurants as $restaurant)
                                    <tr>
                                        <td>{{ $restaurant->id }}</td>
                                        <td>{{ $restaurant->name }}</td>
                                        <td><img class="img-circle" height="80" src="{{ (isset($_SERVER['https']) && $_SERVER['https'] !== 'off') ?  $_SERVER['DOCUMENT_ROOT'] . '/image/restaurants/' .$restaurant->image : url('/image/restaurants/'.$restaurant->image) }}" width="80" /></td>
                                        @if($restaurant->status == 'Pending')
                                        <td><a href="javascript:;" onclick="UpdateStatus('<?php echo $restaurant->id ?>' ,'/admin/update_restaurant_status','restaurant','Active')" class="text-inverse p-r-10 btn btn-success btn-icon-anim btn-circle btn-lg" data-toggle="tooltip" title="" data-original-title="Accepted"><i class="fa fa-check"></i></a> <a href="javascript:void(0)" onclick="UpdateStatus('<?php echo $restaurant->id ?>' ,'/admin/update_restaurant_status','restaurant','Rejected')" class="text-inverse btn btn-danger btn-icon-anim btn-circle btn-lg" title="" data-toggle="tooltip" data-original-title="Rejected"><i class="fa fa-close"></i></a></td>
                                        @else
                                        <td><span class="{{ $restaurant->status == 'Rejected' ? 'label label-danger font-weight-100':'label label-primary font-weight-100' }}">{{ $restaurant->status }}</span></td>

                                        @endif
                                        <td><a href="/admin/restaurant-edit/{{$restaurant->id}}" class="text-inverse p-r-10" data-toggle="tooltip" title="" data-original-title="Edit"><i class="fa fa-check"></i></a> <a href="javascript:void(0)" onclick="deleteItem('<?php echo $restaurant->id ?>' ,'/admin/delete_restaurant','Restaurant')" class="text-inverse" title="" data-toggle="tooltip" data-original-title="Delete"><i class="fa fa-trash"></i></a></td>
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