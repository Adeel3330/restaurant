@extends('admin.include.sidebar')

@section('body')

<div class="row mt-25">
    <div class="col-sm-12">
        <div class="panel panel-default card-view">
            <div class="panel-wrapper collapse in">
                <div class="panel-body">
                    <form id="RestaurantForm" enctype="multipart/form-data">
                        <div class="form-wrap">

                            {{ csrf_field() }}
                            <h6 class="txt-dark capitalize-font"><i class="icon-list mr-10"></i>About Restaurant</h6>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label mb-10">Restaurant Name</label>
                                        <input type="text" id="firstName" name="name" class="form-control" placeholder="Please enter restaurant name" required>
                                    </div>
                                </div>
                                <!--/span-->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label mb-10">Logntitude</label>
                                        <input type="text" id="lastName" name="longitude" class="form-control" placeholder="Please enter longitude" required>
                                    </div>
                                </div>
                                <!--/span-->
                            </div>
                            <!-- Row -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label mb-10">Latitude</label>
                                        <input type="text" id="latitude" class="form-control" name="latitude" required placeholder="Please enter latitude">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label mb-10">Address</label>
                                        <input type="text" id="address" class="form-control" name="address" required placeholder="Please enter address">
                                    </div>
                                </div>
                                <!--/span-->

                                <!--/span-->
                            </div>

                            <!-- Row -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label mb-10">Phone No</label>
                                        <input type="text" id="phone_no" class="form-control" name="phone_no" required placeholder="Please enter Phone no">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label mb-10">Weeks Timings</label>
                                        <select name="week_ids[]" class="form-control selectpicker btn-outline-none" data-style="btn-default btn-outline" multiple>
                                            @forelse ($weeks as $week)
                                            <option value="{{ $week->id }}">{{ $week->name }}</option>
                                            @empty
                                            <option value="">No week found</option>
                                            @endforelse

                                        </select>
                                    </div>
                                </div>
                                <!--/span-->

                                <!--/span-->
                            </div>


                            <div class="seprator-block"></div>
                            <h6 class="txt-dark capitalize-font"><i class="icon-picture mr-10"></i>upload image</h6>
                            <hr>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="img-upload-wrap">
                                        <div id="imagePreview"></div>
                                        <!-- <img class="img-responsive" src="dist/img/chair.jpg" alt="upload_img"> -->
                                    </div>
                                    <div class="fileupload btn btn-info btn-anim"><i class="fa fa-upload"></i><span class="btn-text">Upload new image</span>
                                        <input type="file" class="upload" name="image" id="uploadFile" required accept="image/*">
                                    </div>
                                </div>
                            </div>
                            <div class="seprator-block"></div>
                            <div class="form-actions">
                                <button class="btn btn-success btn-icon left-icon mr-10" id="updatebtn"> <i class="fa fa-check"></i> <span>save</span></button>
                                <button type="button" class="btn btn-warning" onclick="window.location.assign('/admin/restaurants')">Cancel</button>

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
    $("#RestaurantForm").on("submit", function(e) {
        e.preventDefault();
        var form = $("#RestaurantForm");
        $("#updatebtn").html("<i class='fa fa-spinner fa-spin' style='padding:0px;margin-right:10px' id='spinner'></i>Updating..")
        var formData = new FormData(form[0]);
        // console.log(form);
        $.ajax({
            url: "/admin/restaurant_create",
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
                        window.location.assign('/admin/restaurants')
                    }, 1500)
                }
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
        });
    });
</script>


@stop