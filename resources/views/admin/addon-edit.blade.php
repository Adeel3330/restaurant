@extends('admin.include.sidebar')

@section('body')

<div class="row mt-25">
    <div class="col-sm-12">
        <div class="panel panel-default card-view">
            <div class="panel-wrapper collapse in">
                <div class="panel-body">
                    <form id="CategoryForm" enctype="multipart/form-data">
                        <div class="form-wrap">
                            <input type="hidden" id="addon_id" value="{{ $addon->id }}">
                            {{ csrf_field() }}
                            <h6 class="txt-dark capitalize-font"><i class="icon-list mr-10"></i>About Addon</h6>
                            <hr>
                            <div class="row">
                                <!--/span-->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label mb-10">Addon Name</label>
                                        <input class="form-control" value="{{ $addon->name }}" name="name" required type="text" placeholder="Enter Addon name">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label mb-10">Categories</label>
                                        <select name="category_id" class="form-control selectpicker btn-outline-none" data-style="btn-default btn-outline">
                                            @forelse ($categories as $category)
                                            <option value="{{ $category->id }}" {{ $addon->category_id == $category->id ? "selected":"" }}>{{ $category->name }}</option>
                                            @empty
                                            <option value="">No Category found</option>
                                            @endforelse

                                        </select>
                                    </div>
                                </div>
                                <!--/span-->
                            </div>
                            <div class="row mt-10">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label mb-10">Sub Categories</label>
                                        <select name="sub_category_id" class="form-control selectpicker btn-outline-none" data-style="btn-default btn-outline">
                                            @forelse ($sub_categories as $sub_category)
                                            <option value="{{ $sub_category->id }}" {{ $addon->sub_category_id == $sub_category->id ? "selected":"" }}>{{ $sub_category->name }}</option>
                                            @empty
                                            <option value="">No Sub Category found</option>
                                            @endforelse

                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label mb-10">Restaurants</label>
                                        <select name="restaurant_id" class="form-control selectpicker btn-outline-none" data-style="btn-default btn-outline">
                                            @forelse ($restaurants as $restaurant)
                                            <option value="{{ $restaurant->id }}" {{ $addon->restaurant_id == $restaurant->id ? "selected":"" }}>{{ $restaurant->name }}</option>
                                            @empty
                                            <option value="">No Restaurant found</option>
                                            @endforelse

                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label mb-10">Description</label>
                                        <textarea name="description" required cols="80" rows="4" class="form-control" placeholder="Enter Addon description">{{ $addon->description }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label mb-10">Price</label>
                                        <input type="number" name="price" required class="form-control" value="{{ $addon->price }}" placeholder="Enter Addon description">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label mb-10">Flavours</label>
                                        <select name="flavour_ids[]" class="form-control selectpicker btn-outline-none" data-style="btn-default btn-outline" multiple>
                                            @forelse ($flavours as $flavour)
                                            <option value="{{ $flavour->id }}" @foreach($addon->flavour_ids as $flavour_id)
                                                {{$flavour_id->flavour_id == $flavour->id ? "selected":"" }}
                                                @endforeach
                                                >{{ $flavour->name }}
                                            </option>
                                            @empty
                                            <option value="">No flavour found</option>
                                            @endforelse

                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="seprator-block"></div>
                            <h6 class="txt-dark capitalize-font"><i class="icon-picture mr-10"></i>upload image</h6>
                            <hr>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="img-upload-wrap">
                                        <div id="imagePreview">
                                            <img src="{{ url('/image/addon/'.$addon->image) }}" width="80">
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
                                <button type="button" class="btn btn-warning" onclick="window.location.assign('/admin/addons')">Cancel</button>

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
        var id = $("#addon_id").val();
        // console.log(form);
        $.ajax({
            url: "/admin/edit_addon/" + id,
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
                        window.location.assign('/admin/addons')
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