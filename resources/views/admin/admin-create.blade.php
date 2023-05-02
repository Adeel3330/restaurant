@extends('admin.include.sidebar')

@section('body')

<div class="row mt-25">
    <div class="col-sm-12">
        <div class="panel panel-default card-view">
            <div class="panel-wrapper collapse in">
                <div class="panel-body">
                    <form id="BannerForm" enctype="multipart/form-data">
                        <div class="form-wrap">

                            {{ csrf_field() }}
                            <h6 class="txt-dark capitalize-font"><i class="icon-list mr-10"></i>About Admin</h6>
                            <hr>
                            <div class="row">
                                <!--/span-->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label mb-10">Name</label>
                                        <input type="text" name="name" class="form-control" placeholder="Enter name" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label mb-10">Email</label>
                                        <input type="email" name="email" class="form-control" placeholder="Enter email" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label mb-10"> Password</label>
                                        <input type="password" name="password" class="form-control" placeholder="Enter password" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label mb-10"> Phone No</label>
                                        <input type="tel" name="phone_no" class="form-control" placeholder="Enter phone no" required>
                                    </div>
                                </div>
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
                                <button type="button" class="btn btn-warning" onclick="window.location.assign('/admin/admins')">Cancel</button>

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
        // console.log(form);
        $.ajax({
            url: "/admin/admin_create",
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
                        window.location.assign('/admin/admins')
                    }, 1500)
                }
            },
            error: function(data) {
                console.log(data.status)
                if (data.status != 200) {
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

            }
        });
    });
</script>


@stop