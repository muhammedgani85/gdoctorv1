@extends('layouts/contentNavbarLayout')

@section('title', 'New Sandha')

@section('content')

<h4 class="py-3 mb-4"><span class="text-muted fw-light">Sandha Details </span> </h4>
<form id="customerForm" enctype="multipart/form-data">
  @csrf
  <div class="row">
    <!-- Basic -->
    <div class="col-md-6">
      <div class="card mb-4">
        <h5 class="card-header">Information</h5>
        <div class="card-body demo-vertical-spacing demo-only-element">




          <div class="input-group">
            <span class="input-group-text">Sandha Name</span>
            <input type="text" aria-label="sandha_name name" name="sandha_name" id="sandha_name" class="form-control">

          </div>


          <div class="input-group">
            <span class="input-group-text">Duration</span>
            <input type="text" aria-label="First name" name="duration" id="duration" class="form-control">
            <input type="hidden" aria-label="First name" name="added_by" id="added_by" class="form-control" value="{{ session('user_data')->location }}">

          </div>


          <div class="input-group">
            <span class="input-group-text">Price</span>
            <input type="text" aria-label="First name" name="price" id="price" class="form-control">

          </div>

         <!--  -->





        </div>
      </div>
    </div>

    <!-- Merged -->





    <!-- Sizing -->

    <!-- Checkbox and radio addons -->

  </div>





  <!-- Button with dropdowns & addons -->



  <!-- Custom file input -->

  <div class="row" align="centre">
    <div class="col-12">
      <div class="card">

        <div class="card-body demo-vertical-spacing demo-only-element">
          <div class="input-group">


            <button type="button" id="submitForm" class="btn rounded-pill btn-success">Save</button>
            <button type="button" class="btn rounded-pill btn-danger">Reset</button>


          </div>


        </div>
      </div>
    </div>
  </div>

</form>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
  $('#submitForm').click(function(e) {

    e.preventDefault();
    let formData = new FormData($('#customerForm')[0]);

    $.ajax({
      url: "{{ route('sandhas.store') }}",
      method: 'POST',
      data: formData,
      contentType: false,
      processData: false,
      success: function(response) {
        // alert(response.success);
        swal("Done!", response.success, "success");
        //location.reload();
        window.location.href = "{{ url('/sandhas')}}";
      },
      error: function(response) {

        let errors = response.responseJSON.errors;
        $('#errorMessages').remove(); // Remove the previous error messages container
        let errorHtml = '<div id="errorMessages"><ul>';
        $.each(errors, function(key, value) {
          errorHtml += '<li>' + value + '</li>';
        });
        errorHtml += '</ul></div>';
        $('#customerForm').before(errorHtml);

      }
    });

  });
</script>

<script>
  function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
      return false;
    }
    return true;
  }
</script>
<script>
  $(document).ready(function() {
    $('.btn-delete').on("click", function() {
      var $this = $(this);
      swal({
        title: "InActive?",
        text: "Please ensure and then confirm!",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, cancel!",
        reverseButtons: true
      }).then(function(e) {
        if (e.value) {
          var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
          var userId = $this.data('id');

          $.ajax({
            type: 'DELETE',
            url: '{{ route("users.softDelete", "") }}/' + userId,
            data: {
              _token: CSRF_TOKEN
            },
            dataType: 'JSON',
            success: function(results) {
              if (results.success) {
                swal("Done!", results.message, "success");
                setTimeout(function() {
                  location.reload()
                }, 2000);
              } else {
                swal("Error!", results.message, "error");
              }
            },
            error: function(xhr) {
              console.log(xhr.responseText);
            }
          });
        }
      });
    });
  });
</script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#pincode').select2({
                placeholder: 'Search for Pincode...',
                ajax: {
                    url: '{{ route("fetch.pincode") }}',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            term: params.term // search term
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data
                        };
                    },
                    cache: true
                }
            });
        });
    </script>

<script>
  $(document).ready(function () {
    // Event listener for dropdown click
    $('#pincode').on('click', function () {
        if ($('#pincode').children('option').length === 1) { // Fetch only if no data loaded yet
            $('#loading-spinner').show(); // Show loading spinner

            // Simulate AJAX call to fetch data
            $.ajax({
                url: '{{ route("fetch.pincode") }}', // Replace with your route
                method: 'GET',
                dataType: 'json',
                success: function (data) {
                    $('#loading-spinner').hide(); // Hide loading spinner

                    // Populate dropdown with fetched data
                    $('#pincode').empty().append('<option selected>Choose...</option>');
                    $.each(data, function (key, value) {
                        $('#pincode').append('<option value="' + value.id + '">' + value.pin_code + ' - ' + value.name + '</option>');
                    });
                },
                error: function () {
                    $('#loading-spinner').hide(); // Hide loading spinner
                    alert('Failed to fetch data. Please try again later.');
                }
            });
        }
    });
});

</script>


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.all.min.js"></script>
@endsection
