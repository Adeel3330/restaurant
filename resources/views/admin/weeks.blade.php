@extends('admin.include.sidebar')

@section('body')

<!-- Row -->
<div class="row mt-25">
    <div class="col-sm-12">
        <div class="panel panel-default card-view">
            <div class="panel-heading">
                <div class="pull-left">
                    <h6 class="panel-title txt-dark">Restaurants Timings List</h6>
                </div>
                <div class="pull-right">
                    <a href="/admin/week-create" class="btn btn-primary">Create Weeks</a>
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
                                        <th>Opening Time</th>
                                        <th>Closing Time</th>
                                        <th>Restaurant</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>ID #</th>
                                        <th>Name</th>
                                        <th>Opening Time</th>
                                        <th>Closing Time</th>
                                        <th>Restaurant</th>
                                        <th>Actions</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @forelse ($weeks as $week)
                                    <tr>
                                        <td>{{ $week->id }}</td>
                                        <td>{{ $week->name }}</td>
                                        <td>{{ $week->opening_time }}</td>
                                        <td>{{ $week->closing_time }}</td>
                                        <td>{{ $week->restaurants != '' ? $week->restaurants->name:'' }}</td>

                                        <td><a href="/admin/week-edit/{{$week->id}}" class="text-inverse p-r-10" data-toggle="tooltip" title="" data-original-title="Edit"><i class="fa fa-check"></i></a> <a href="javascript:void(0)" onclick="deleteItem('<?php echo $week->id ?>' ,'/admin/delete_week_day','Week')" class="text-inverse" title="" data-toggle="tooltip" data-original-title="Delete"><i class="fa fa-trash"></i></a></td>
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