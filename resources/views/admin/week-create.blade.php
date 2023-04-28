@extends('admin.include.sidebar')

@section('body')

<div class="row mt-25">
    <div class="col-sm-12">
        <div class="panel panel-default card-view">
            <div class="panel-wrapper collapse in">
                <div class="panel-body">
                    <form id="CategoryForm" enctype="multipart/form-data">
                        <div class="form-wrap">

                            {{ csrf_field() }}
                            <h6 class="txt-dark capitalize-font"><i class="icon-list mr-10"></i>About Weeks</h6>
                            <hr>
                            <div class="row">
                                <!--/span-->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label mb-10">Week Name</label>
                                        <input class="form-control" name="name" required type="text" placeholder="Enter Week name">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label mb-10">Categories</label>
                                        <input type="time" class="form-control" name="opening_time" required>
                                    </div>
                                </div>
                                <!--/span-->
                            </div>
                            <div class="row mt-10">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label mb-10">Closing Time</label>
                                        <input type="time" class="form-control" name="closing_time" required>
                                    </div>
                                </div>

                            </div>


                            <div class="seprator-block"></div>
                            <div class="form-actions">
                                <button class="btn btn-success btn-icon left-icon mr-10" id="updatebtn"> <i class="fa fa-check"></i> <span>save</span></button>
                                <button type="button" class="btn btn-warning" onclick="window.location.assign('/admin/week_days')">Cancel</button>

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
    $("#CategoryForm").on("submit", function(e) {
        e.preventDefault();
        var form = $("#CategoryForm");
        $("#updatebtn").html("<i class='fa fa-spinner fa-spin' style='padding:0px;margin-right:10px' id='spinner'></i>Updating..")
        var formData = new FormData(form[0]);
        // console.log(form);
        $.ajax({
            url: "/admin/week_day_create",
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
                        window.location.assign('/admin/week_days')
                    }, 1500)
                }
            },
            error: function(data) {
                console.log(data.status)
                if (data.status == 302) {
                    $("#spinner").hide();
                    console.log(data.responseJSON.message);
                    popup(data.responseJSON.message);
                }

            }
        });
    });
</script>


@stop