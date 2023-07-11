@extends('admin.include.sidebar')

@section('body')

<div class="row mt-25">
    <div class="col-sm-12">
        <div class="panel panel-default card-view">
            <div class="panel-wrapper collapse in">
                <div class="panel-body">
                    <form id="BannerForm" enctype="multipart/form-data">
                        <div class="form-wrap">
                            <input type="hidden" id="delivery_fee_id" value="{{ $delivery_fee->id }}">
                            {{ csrf_field() }}
                            <h6 class="txt-dark capitalize-font"><i class="icon-list mr-10"></i>About Delivery Fee</h6>
                            <hr>
                            <div class="row">
                                <!--/span-->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label mb-10">Free Delivery</label>
                                        <input type="number" class="form-control" placeholder="Free Delivery" name="free_delivery" value="{{ $delivery_fee->free_delivery }}" id="free_delivery" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label mb-10">Basic Delivery Charge</label>
                                        <input type="number" class="form-control" placeholder="Basic Delivery Charge" name="basic_delivery_charge" value="{{ $delivery_fee->basic_delivery_charge }}" id="charge_per_kilo" required>
                                    </div>
                                </div>
                                <!--/span-->
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label mb-10">Charge Per Kilo</label>
                                        <input type="number" class="form-control" placeholder="Charge Per Kilo" name="charge_per_kilo" value="{{ $delivery_fee->charge_per_kilo }}" id="charge_per_kilo" required>
                                    </div>
                                </div>
                            </div>
                            <div class="seprator-block"></div>
                            <div class="form-actions">
                                <button class="btn btn-success btn-icon left-icon mr-10" id="updatebtn"> <i class="fa fa-check"></i> <span>save</span></button>
                                <button type="button" class="btn btn-warning" onclick="window.location.assign('/admin/delivery_fees')">Cancel</button>

                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


@stop

@section('scripts')

<script>
    $("#BannerForm").on("submit", function(e) {
        e.preventDefault();
        var form = $("#BannerForm");
        $("#updatebtn").html("<i class='fa fa-spinner fa-spin' style='padding:0px;margin-right:10px' id='spinner'></i>Updating..")
        var formData = new FormData(form[0]);
        var id = $("#delivery_fee_id").val();
        // console.log(form);
        $.ajax({
            url: "/admin/edit_delivery_fee/" + id,
            method: "POST",
            data: formData,
            contentType: false, //this is requireded please see answers above
            processData: false,
            success: function(data) {
                console.log(data.message);
                if (data.message != "") {
                    popup(data.message, true);
                    $("#spinner").hide();
                    setTimeout(function() {
                        window.location.assign('/admin/delivery_fees')
                    }, 1500)
                }
            },
            error: function(data) {
                console.log(data.status)
                // if (data.status == 302) {
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
                // }

            }
        });
    });

   
</script>


@stop