<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from hencework.com/theme/kenny/blank.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 19 Apr 2023 09:50:17 GMT -->

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>Restaurant Admin Panel</title>
    <meta name="description" content="Restaurant Admin Panel" />
    <meta name="keywords" content="cms,admin,restaurant" />
    <meta name="author" content="admin" />

    <!-- Favicon -->
    <link rel="shortcut icon" href="favicon.ico">
    <link rel="icon" href="favicon.ico" type="image/x-icon">

    <!-- Data table CSS -->
    <link href="{{ url('/vendors/bower_components/datatables/media/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Custom CSS -->
    <link href="{{ url('/dist/css/style.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ url('/dist/css/glyphicons.less') }}" rel="stylesheet" type="text/css">
    <link href="{{ url('/vendors/bower_components/sweetalert/dist/sweetalert.css') }}">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <!-- bootstrap-select CSS -->
    <link href="{{ url('/vendors/bower_components/bootstrap-select/dist/css/bootstrap-select.min.css') }}" rel="stylesheet" type="text/css" />

</head>

<body>
    <!--Preloader-->
    <div class="preloader-it">
        <div class="la-anim-1"></div>
    </div>
    <!--/Preloader-->
    <div class="wrapper">

        <!-- Top Menu Items -->
        <nav class="navbar navbar-inverse navbar-fixed-top">
            <a id="toggle_nav_btn" class="toggle-left-nav-btn inline-block mr-20 pull-left" href="javascript:void(0);"><i class="fa fa-bars"></i></a>
            <a href="/admin/home"><img class="brand-img pull-left" src="{{ url('/dist/img/logo.png') }}" alt="brand" /></a>
            <ul class="nav navbar-right top-nav pull-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle pr-0" data-toggle="dropdown"><img src="{{ url('dist/img/user1.png') }}" alt="user_auth" class="user-auth-img img-circle" /><span class="user-online-status"></span></a>
                    <ul class="dropdown-menu user-auth-dropdown" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut">
                        <li>
                            <a href="/admin/logout"><i class="fa fa-fw fa-power-off"></i> Log Out</a>
                        </li>
                    </ul>
                </li>
            </ul>
            <div class="collapse navbar-search-overlap" id="site_navbar_search">
                <form role="search">
                    <div class="form-group mb-0">
                        <div class="input-search">
                            <div class="input-group">
                                <input type="text" id="overlay_search" name="overlay-search" class="form-control pl-30" placeholder="Search">
                                <span class="input-group-addon pr-30">
                                    <a href="javascript:void(0)" class="close-input-overlay" data-target="#site_navbar_search" data-toggle="collapse" aria-label="Close" aria-expanded="true"><i class="fa fa-times"></i></a>
                                </span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </nav>
        <!-- /Top Menu Items -->

        <!-- Left Sidebar Menu -->
        <div class="fixed-sidebar-left">
            <ul class="nav navbar-nav side-nav nicescroll-bar">
                <li>
                    <a href="/admin/home"><i class="fa fa-dashboard mr-10"></i>Dashboard <span class="pull-right"></span></a>
                </li>
                <li>
                    <a href="javascript:void(0);" data-toggle="collapse" data-target="#rest_dr"><i class="icon-basket-loaded mr-10"></i>Restaurants<span class="pull-right"><i class="fa fa-fw fa-angle-down"></i></span></a>
                    <ul id="rest_dr" class="collapse collapse-level-1">
                        <li>
                            <a href="/admin/restaurants">Restaurants</a>
                        </li>
                        <li>
                            <a href="/admin/restaurant-create">Restaurant Create</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="javascript:void(0);" data-toggle="collapse" data-target="#banner_dr"><i class="icon-briefcase mr-10"></i>Banners<span class="pull-right"><i class="fa fa-fw fa-angle-down"></i></span></a>
                    <ul id="banner_dr" class="collapse collapse-level-1">
                        <li>
                            <a href="/admin/banners">Banners</a>
                        </li>
                        <li>
                            <a href="/admin/banner-create">Banner Create</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="javascript:void(0);" data-toggle="collapse" data-target="#cate_dr"><i class="ti-layout-grid2 mr-10"></i>Categories<span class="pull-right"><i class="fa fa-fw fa-angle-down"></i></span></a>
                    <ul id="cate_dr" class="collapse collapse-level-1">
                        <li>
                            <a href="/admin/categories">Categories</a>
                        </li>
                        <li>
                            <a href="/admin/category-create">Category Create</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="javascript:void(0);" data-toggle="collapse" data-target="#sub_cate_dr"><i class="ti-layout-grid2 mr-10"></i>Sub Categories<span class="pull-right"><i class="fa fa-fw fa-angle-down"></i></span></a>
                    <ul id="sub_cate_dr" class="collapse collapse-level-1">
                        <li>
                            <a href="/admin/sub-categories">Sub Categories</a>
                        </li>
                        <li>
                            <a href="/admin/sub-category-create">Sub Category Create</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="javascript:void(0);" data-toggle="collapse" data-target="#products_dr"><i class="fa fa-product-hunt mr-10"></i>Products<span class="pull-right"><i class="fa fa-fw fa-angle-down"></i></span></a>
                    <ul id="products_dr" class="collapse collapse-level-1">
                        <li>
                            <a href="/admin/products">Products</a>
                        </li>
                        <li>
                            <a href="/admin/product-create">Product Create</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="/admin/carts"><i class="icon-basket-loaded mr-10"></i>Carts <span class="pull-right"><span class="label label-info mr-10"></span></span></a>
                </li>
                <li>
                    <a href="/admin/orders"><i class="fa fa-first-order mr-10"></i>Orders <span class="pull-right"><span class="label label-info mr-10"></span></span></a>
                </li>
                <li>
                    <a href="javascript:void(0);" data-toggle="collapse" data-target="#admin_dr"><i class="fa fa-users mr-10"></i>Admins<span class="pull-right"><i class="fa fa-fw fa-angle-down"></i></span></a>
                    <ul id="admin_dr" class="collapse collapse-level-1">
                        <li>
                            <a href="/admin/admins">Admin</a>
                        </li>
                        <li>
                            <a href="/admin/admin-create">Admin Create</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
        <!-- /Left Sidebar Menu -->

        <!-- Right Sidebar Menu -->

        <!-- /Right Sidebar Menu -->

        <!-- Main Content -->
        <div class="page-wrapper">
            <div class="container-fluid">
                @yield('body')
                <!-- Title -->
                <!-- <div class="row heading-bg  bg-blue">
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                        <h5 class="txt-light">blank page</h5>
                    </div>
                     Breadcrumb
                    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                        <ol class="breadcrumb">
                            <li><a href="index.html">Dashboard</a></li>
                            <li><a href="#"><span>speciality pages</span></a></li>
                            <li class="active"><span>blank page</span></li>
                        </ol>
                    </div>
                   
                </div> -->
                <!-- /Title -->

                <!-- Footer -->

                <!-- /Footer -->

            </div>
            <!-- /Main Content -->

        </div>
        <!-- /#wrapper -->

        <!-- JavaScript -->

        <!-- jQuery -->
        <script src="{{ url('/vendors/bower_components/jquery/dist/jquery.min.js') }}"></script>

        <!-- Bootstrap Core JavaScript -->
        <script src="{{ url('/vendors/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>

        <!-- Data table JavaScript -->
        <script src="{{ url('/vendors/bower_components/datatables/media/js/jquery.dataTables.min.js') }}"></script>
        <script src="{{ url('/dist/js/dataTables-data.js') }}"></script>
        <!-- Slimscroll JavaScript -->
        <script src="{{ url('/dist/js/jquery.slimscroll.js') }}"></script>

        <!-- Fancy Dropdown JS -->
        <script src="{{ url('/dist/js/dropdown-bootstrap-extended.js') }}"></script>
        <!-- Init JavaScript -->
        <script src="{{ url('/dist/js/init.js') }}"></script>




        <!-- Piety JavaScript -->
        <script src="{{ url('/vendors/bower_components/peity/jquery.peity.min.js') }}"></script>
        <script src="{{ url('/dist/js/peity-data.js') }}"></script>

        <script src="{{ url('/dist/js/productorders-data.js') }}"></script>



        <!-- Sparkline JavaScript -->
        <script src="{{ url('/vendors/jquery.sparkline/dist/jquery.sparkline.min.js') }}"></script>

        <script src="{{ url('/vendors/bower_components/jquery.easy-pie-chart/dist/jquery.easypiechart.min.js') }}"></script>
        <script src="{{ url('/dist/js/skills-counter-data.js') }}"></script>

        <!-- Morris Charts JavaScript -->
        <script src="{{ url('/vendors/bower_components/raphael/raphael.min.js') }}"></script>
        <script src="{{ url('/vendors/bower_components/morris.js/morris.min.js') }}"></script>
        <script src="{{ url('/dist/js/morris-data.js') }}"></script>

        <script src="{{ url('/vendors/bower_components/jquery-toast-plugin/dist/jquery.toast.min.js') }}"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
        <script src="{{ url('/dist/js/ecommerce-data.js') }}"></script>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <!-- Data table JavaScript -->
        <script src="{{ url('/vendors/bower_components/datatables/media/js/jquery.dataTables.min.js') }}"></script>
        <script src="{{ url('/vendors/bower_components/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
        <script src="{{ url('/vendors/bower_components/datatables.net-buttons/js/buttons.flash.min.js') }}"></script>
        <script src="{{ url('/vendors/bower_components/jszip/dist/jszip.min.js') }}"></script>
        <script src="{{ url('/vendors/bower_components/pdfmake/build/pdfmake.min.js') }}"></script>
        <script src="{{ url('/vendors/bower_components/pdfmake/build/vfs_fonts.js') }}"></script>

        <script src="{{ url('/vendors/bower_components/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
        <script src="{{ url('/vendors/bower_components/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
        <script src="{{ url('/dist/js/export-table-data.js') }}"></script>

        <script src="{{ url('/dist/js/ajaxfunctions.js') }}"></script>

        <!-- Bootstrap Select JavaScript -->
        <script src="{{ url('/vendors/bower_components/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>

        <script>
            var SweetAlert = function() {};


            /* Bootstrap Select Init*/
            $('.selectpicker').selectpicker();

            //examples 
            SweetAlert.prototype.init = function() {};


            function deleteItem(id, url, message) {
                console.log(id, url, message);

                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: "#fcb03b",
                    confirmButtonText: "Yes, delete it!",
                    cancelButtonText: 'No, cancel!',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url + '/' + id,
                            method: "GET",
                            error: function(data) {
                                popup(data.responseJSON.message)
                            },
                            success: function(data) {
                                console.log(data);
                                if (data != "") {
                                    swalWithBootstrapButtons.fire(
                                        'Deleted!',
                                        'Your ' + message + ' has been deleted.',
                                        'success'
                                    )
                                    setTimeout(function() {
                                        location.reload(true);
                                    }, 2000)
                                }
                            }

                        })

                    } else if (
                        /* Read more about handling dismissals below */
                        result.dismiss === Swal.DismissReason.cancel
                    ) {
                        swalWithBootstrapButtons.fire(
                            'Cancelled',
                            'Your ' + message + ' is safe :)',
                            'error'
                        )
                    }
                })
                return false;

            }

            // };
        </script>

        @yield('scripts')
</body>


</html>