@extends('admin.include.sidebar')

@section('body')

<!-- Row -->
<input id="_token" type="hidden" name="_token" value="{{ csrf_token() }}" />
<div class="row mt-25">
    <div class="col-sm-12">
        <div class="panel panel-default card-view">
            <div class="panel-heading">
                <div class="pull-left">
                    <h6 class="panel-title txt-dark">Orders List</h6>
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
                                        <!-- <th>Product</th> -->
                                        <th>Total Items</th>
                                        <th>Transaction Id</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>ID #</th>
                                        <th>User</th>
                                        <!-- <th>Product</th> -->
                                        <th>Total Items</th>
                                        <th>Transaction Id</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </tfoot>
                                <tbody>

                                    @forelse ($orders as $order)

                                    <tr>
                                        <td>{{ $order->id }}</td>
                                        <td>{{ $order->user->name }}</td>

                                        <td>{{ count($order->orders_items) }}</td>
                                        <td>{{ $order->transaction_id }}</td>
                                        <td><span class='{{ $order->status != "Delivered" ? "label label-primary font-weight-100":"label label-success font-weight-100" }}'>{{ $order->status }}</span></td>
                                        <td><a href="/admin/order-detail/{{ $order->id }}" class="text-inverse" title="" data-toggle="tooltip" data-original-title="View"><i class="fa fa-eye"></i></a>
                                            @if($order->status == 'Accepting order')

                                            <a href="javascript:void(0)" onclick="UpdateStatus('<?php echo $order->id ?>','/admin/order_update','Order','Preparing your meal')" class="text-inverse" title="" data-toggle="tooltip" data-original-title="Change status to Preparing your meal"><i class="fa fa-check"></i></a>

                                            @elseif ($order->status == 'Preparing your meal')
                                            <a href="javascript:void(0)" onclick="UpdateStatus('<?php echo $order->id ?>','/admin/order_update','Order','Ready for collection')" class="text-inverse" title="" data-toggle="tooltip" data-original-title="Change status to Ready for collection"><i class="fa fa-check"></i></a>

                                            @endif
                                        </td>
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

@section('scripts')
<script>
    function ordercomplete(id, status) {
        $.ajax({
            url: "/admin/order_update/" + id,
            method: "POST",
            header: {
                'X-CSRF-TOKEN': '<?php echo csrf_token() ?>',
            },
            success: function(data) {
                console.log(data)
                popup(data.message, true)
                setTimeout(function() {
                    window.location.assign('/admin/orders')
                }, 500)
            },
            error: function(data) {
                console.log(data.status)
                $("#spinner").hide();
                $("#updatebtn").text("");
                $("#updatebtn").append("<i class='fa fa-check'></i>Save")
                var array = $.map(data.responseJSON, function(value, index) {
                    return [value];
                });
                array.forEach(element => {
                    // element.forEach(data => {
                    console.log(element)
                    popup(element);
                    // });
                });
            }
        })
    }
</script>
@stop