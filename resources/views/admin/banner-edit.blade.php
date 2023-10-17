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
                            <h6 class="txt-dark capitalize-font"><i class="icon-list mr-10"></i>About Banner</h6>
                            <hr>
                            <div class="row">
                                <input type="hidden" id="benner_id" value="{{ $banner->id }}">
                                <!--/span-->
                               
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label mb-10">Categories</label>
                                        <select name="category_id" id="category_id" class="form-control selectpicker btn-outline-none" required data-style="btn-default btn-outline">
                                            @forelse ($categories as $category)
                                            <option value="{{ $category->id }}" {{ $category->id == $banner->category_id ? "selected":""}}>{{ $category->name }}</option>
                                            @empty
                                            <option value="">No category found</option>
                                            @endforelse

                                        </select>
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
                                        <div id="imagePreview">
                                            <img src="{{ url('/image/banner/'.$banner->image) }}" width="80">
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
                                <button type="button" class="btn btn-warning" onclick="window.location.assign('/admin/banners')">Cancel</button>
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
        var id = $("#benner_id").val();
        // console.log(form);
        $.ajax({
            url: "/admin/edit_banner/" + id,
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
                        window.location.assign('/admin/banners')
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

    $("#restaurant_id").on("change", function() {
        var id = $(this).val();
        getParent(id)
    })

    function getParent(r_id) {
        var token = $("input[name='token']").val();
        console.log(token);
        $("#category_id").html("");
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': token
            },
            url: "/admin/get_categories_with_id/" + r_id,
            method: "GET",
            success: function(data) {
                console.log(data)
                $("#category_id").append(data);
                // $("#category_id").trigger("change");
                $('#category_id').selectpicker('refresh');
            },
            error: function(data) {
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