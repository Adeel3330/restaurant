@extends('admin.include.sidebar')

@section('body')

<!-- Row -->
<div class="row mt-25">
    <div class="col-sm-12">
        <div class="panel panel-default card-view">
            <div class="panel-heading">
                <div class="pull-left">
                    <h6 class="panel-title txt-dark">Delivery Fee List</h6>
                </div>
                @if ($create_btn == 'show')
                <div class="pull-right">
                    <a class="btn btn-primary" href="/admin/delivery-fee-create">Delivery Fee Create</a>
                </div>
                @endif
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
                                        <th>Free Delivery</th>
                                        <th>Basic Charge</th>
                                        <th>Charge Per Kilo</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>ID #</th>
                                        <th>Free Delivery</th>
                                        <th>Basic Charge</th>
                                        <th>Charge Per Kilo</th>
                                        <th>Actions</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @forelse ($delivery_fees as $delivery_fee)
                                    <tr>
                                        <td>{{ $delivery_fee->id }}</td>
                                        <td>{{ $delivery_fee->free_delivery }}</td>
                                        <td>{{ $delivery_fee->basic_delivery_charge }}</td>
                                        <td>{{ $delivery_fee->charge_per_kilo }}</td>
                                        <td><a href="/admin/delivery-fee-edit/{{$delivery_fee->id}}" class="text-inverse p-r-10" data-toggle="tooltip" title="" data-original-title="Edit"><i class="fa fa-check"></i></a> <a href="javascript:void(0)" onclick="deleteItem('<?php echo $delivery_fee->id ?>' ,'/admin/delete_delivery_fee','Banner')" class="text-inverse" title="" data-toggle="tooltip" data-original-title="Delete"><i class="fa fa-trash"></i></a></td>
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