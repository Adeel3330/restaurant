// $(document).ready(function () {
//     toastr.options = {
//         'closeButton': true,
//         'debug': false,
//         'newestOnTop': false,
//         'progressBar': false,
//         'positionClass': 'toast-top-right',
//         'preventDuplicates': false,
//         'showDuration': '1000',
//         'hideDuration': '1000',
//         'timeOut': '3000',
//         'extendedTimeOut': '1000',
//         'showEasing': 'swing',
//         'hideEasing': 'linear',
//         'showMethod': 'fadeIn',
//         'hideMethod': 'fadeOut',
//     }

   
// });

toastr.options = {
    'progressBar': true,
}

var SweetAlert = function () { };

//examples 
SweetAlert.prototype.init = function () {


function deleteItem(id,url,message){
    console.log(id,url,message);
    swal({
        title: "Are you sure?",
        text: "You will not be able to recover this imaginary file!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#fcb03b",
        confirmButtonText: "Yes, delete it!",
        closeOnConfirm: false
    }, function () {
        swal("Deleted!", "Your imaginary file has been deleted.", "success");
    });
    return false;
}
  
};

function popup(message, success) {
    if (success) {
        toastr.success(message,'success')
    }
    else {
        toastr.error(message,'error')
    }
}

$("#uploadFile").on("change", function () {
    var files = !!this.files ? this.files : [];
    if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support
    if (/^image/.test(files[0].type)) { // only image file
        var reader = new FileReader(); // instance of the FileReader
        reader.readAsDataURL(files[0]); // read the local file

        reader.onloadend = function () {
            $("#imagePreview").html("");
             // set image data as background of div
            $("#imagePreview").css("background-image", "url(" + this.result + ")");
            $("#imagePreview").css("height","180px");
        }
    }



});
