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
                            <input type="hidden" id="restaurant_id" value="{{$restaurant->id}}">
                            <h6 class="txt-dark capitalize-font"><i class="icon-list mr-10"></i>About Restaurant</h6>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label mb-10">Restaurant Name</label>
                                        <input type="text" id="firstName" name="name" value="{{$restaurant->name}}" class="form-control" placeholder="Please enter restaurant name" required>
                                    </div>
                                </div>
                                <!--/span-->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label mb-10">Logntitude</label>
                                        <input type="text" id="lastName" name="longitude" value="{{ $restaurant->longitude }}" class="form-control" placeholder="Please enter longitude" required>
                                    </div>
                                </div>
                                <!--/span-->
                            </div>
                            <!-- Row -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label mb-10">Latitude</label>
                                        <input type="text" id="lastName" class="form-control" name="latitude" value="{{ $restaurant->latitude }}" required placeholder="Please enter latitude">
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
                                        <div id="imagePreview">
                                            <img src="{{ url('/image/restaurants/'.$restaurant->image) }}" width="80">
                                        </div>
                                        <!-- <img class="img-responsive" src="dist/img/chair.jpg" alt="upload_img"> -->
                                    </div>
                                    <div class="fileupload btn btn-info btn-anim"><i class="fa fa-upload"></i><span class="btn-text">Upload new image</span>
                                        <input type="file" class="upload" name="image" id="uploadFile" accept="image/*">
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
        var id = $("#restaurant_id").val();
        // console.log(form);
        $.ajax({
            url: "/admin/edit_restaurant/" + id,
            method: "POST",
            data: formData,
            contentType: false, //this is requireded please see answers above
            processData: false,
            success: function(data) {
                console.log(data.message);
                if (data.message != "") {
                    $("#updatebtn #spinner").css("display", "none");
                    popup(data.message, true);
                    setTimeout(function() {
                        window.location.assign('/admin/restaurants')
                    }, 1500)
                }
            },
            error: function(data) {
                console.log(data.status)
                if (data.status == 302) {
                    $("#updatebtn #spinner").css("display", "none");
                    console.log(data.responseJSON.message);
                    popup(data.responseJSON.message);
                }

            }
        });
    });
</script>


@stop