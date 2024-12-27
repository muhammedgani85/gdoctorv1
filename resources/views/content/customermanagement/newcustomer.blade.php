@extends('layouts/contentNavbarLayout')

@section('title', 'New Employee')

@section('content')

<h4 class="py-3 mb-4"><span class="text-muted fw-light">Customer Details </span> </h4>
<form id="customerForm" enctype="multipart/form-data">
  @csrf
  <div class="row">
    <!-- Basic -->
    <div class="col-md-6">
      <div class="card mb-4">
        <h5 class="card-header">Basic Information</h5>
        <div class="card-body demo-vertical-spacing demo-only-element">

          <div class="input-group">
            <span class="input-group-text">Customer ID</span>
            <input type="text" aria-label="First name" name="customer_id" id="customer_id" class="form-control" value="{{ $customerId }}" readonly>
            <input type="hidden" aria-label="First name" name="location_id" id="location_id" class="form-control" value="{{ $location }}" readonly>

          </div>

          <div class="input-group">
            <span class="input-group-text">Initial</span>
            <input type="text" aria-label="First name" name="initial" id="initial" class="form-control" maxlength="2" minlength="1">

          </div>
          <div class="input-group">
            <span class="input-group-text">First Name</span>
            <input type="text" aria-label="First name" name="first_name" id="first_name" class="form-control">

          </div>

          <div class="input-group">
            <span class="input-group-text">Last Name</span>
            <input type="text" aria-label="First name" name="last_name" id="last_name" class="form-control">

          </div>
          <div class="input-group">
            <span class="input-group-text">Father Name</span>
            <input type="text" aria-label="First name" name="father_name" id="father_name" class="form-control">

          </div>


          <div class="input-group">
            <span class="input-group-text">Spouse Name</span>
            <input type="text" aria-label="First name" name="spouse_name" id="spouse_name" class="form-control">

          </div>

          <div class="input-group">
            <label class="input-group-text" for="inputGroupSelect01">Gender</label>
            <select class="form-select" id="gender" name="gender">
              <option selected>Choose...</option>
              <option value="Male">Male</option>
              <option value="FeMale">FeMale</option>
              <option value="Others">Others</option>

            </select>
          </div>

          <div class="input-group">
            <span class="input-group-text">DOB</span>
            <input type="date" aria-label="First name" name="dob" id="dob" class="form-control">

          </div>

          <div class="input-group">
            <label class="input-group-text" for="inputGroupSelect01">Marital Status</label>
            <select class="form-select" id="marital_status" name="marital_status">
              <option selected>Choose...</option>
              <option value="UnMarried">UnMarried</option>
              <option value="Married">Married</option>
              <option value="Single">Single</option>
              <option value="Widow">Widow</option>
              <option value="None">None</option>

            </select>
          </div>

        </div>
      </div>
    </div>

    <!-- Merged -->
    <div class="col-md-6">
      <div class="card mb-4">
        <h5 class="card-header">Contact Details</h5>
        <div class="card-body demo-vertical-spacing demo-only-element">



          <div class="input-group">
            <span class="input-group-text">Phone Number</span>
            <input type="text" aria-label="First name" name="phone_number" id="phone_number" class="form-control" onkeypress="return isNumber(event)" maxlength="13" minlength="10">

          </div>

          <div class="input-group">
            <span class="input-group-text">Emr. Number</span>
            <input type="text" aria-label="First name" name="emergency_number" id="emergency_number" class="form-control" onkeypress="return isNumber(event)" maxlength="13" minlength="10">

          </div>

          <div class="input-group">
            <span class="input-group-text">Email</span>
            <input type="text" aria-label="First name" name="email_id" id="email_id" class="form-control">

          </div>
          <div class="input-group">
            <span class="input-group-text">State</span>
            <select class="form-select" id="state_id" name="state_id">
              <option selected>Choose...</option>
              @foreach($states as $state)
              <option value="{{  $state->id }}">{{ $state->name. "-".$state->name_tamil  }}</option>

              @endforeach
            </select>

          </div>
          <div class="input-group">
            <span class="input-group-text">District</span>
            <select class="form-select" id="district_id" name="district_id">
              <option selected>Choose...</option>
              @foreach($district as $dis)
              <option value="{{  $dis->id }}">{{ $dis->district_name. "-".$dis->district_name_tamil  }}</option>

              @endforeach
            </select>

          </div>

          <div class="input-group">
            <span class="input-group-text">City</span>
            <select class="form-select" id="city" name="city">
              <option selected>Choose...</option>
              @foreach($city as $cty)
              <option value="{{  $cty->id }}">{{ $cty->name. "-".$cty->name_tamil  }}</option>

              @endforeach
            </select>

          </div>

          <div class="input-group">
            <span class="input-group-text">Pincode</span>
            <select class="form-select" id="pincode" name="pincode">
    <option selected>Choose...</option>
</select>


          </div>

          <!-- Add a loading spinner -->
<div id="loading-spinner" style="display: none;color:red;">
    Loading data, please wait...
</div>


          <div class="input-group input-group-merge">
            <span class="input-group-text">Permant ddress</span>
            <textarea class="form-control" aria-label="With textarea" name="permanent_address" id="permanent_address"></textarea>
          </div>

          <div class="input-group input-group-merge">
            <span class="input-group-text">Communication Address</span>
            <textarea class="form-control" aria-label="With textarea" name="communication_address" id="communication_address"></textarea>
          </div>
          <div class="input-group input-group-merge">
            <span class="input-group-text">Ward</span>
            <textarea class="form-control" aria-label="With textarea" name="ward" id="ward"></textarea>
          </div>
        </div>
      </div>
    </div>

    <!-- Sizing -->
    <div class="col-md-6">
      <div class="card mb-4">
        <h5 class="card-header">Identification</h5>
        <div class="card-body demo-vertical-spacing demo-only-element">

          <div class="input-group">
            <span class="input-group-text">Aadhar Number</span>
            <input type="text" aria-label="First name" name="aadhar_number" id="aadhar_number" class="form-control" onkeypress="return isNumber(event)" onkeypress="return isNumber(event)" maxlength="12" minlength="12">

          </div>

          <div class="input-group">
            <span class="input-group-text">Driving Lic Number</span>
            <input type="text" aria-label="First name" name="driving_license_number" id="driving_license_number" class="form-control">

          </div>

          <div class="input-group">
            <span class="input-group-text">PAN</span>
            <input type="text" aria-label="First name" name="pan" id="pan" class="form-control">

          </div>
        </div>
      </div>
    </div>
    <!-- Checkbox and radio addons -->
    <div class="col-md-6">
      <div class="card mb-4">
        <h5 class="card-header">Ocupation</h5>
        <div class="card-body demo-vertical-spacing demo-only-element">

          <div class="input-group">
            <label class="input-group-text" for="inputGroupSelect01">Occupation</label>
            <select class="form-select" id="occupation_id" name="occupation_id">
              <option selected>Choose...</option>
              @foreach($occupations as $occupation)
              <option value="{{  $occupation->id }}">{{ $occupation->occupation }}</option>

              @endforeach
            </select>
          </div>

          <div class="input-group">
            <label class="input-group-text" for="inputGroupSelect01">Type</label>
            <select class="form-select" id="occupation_type" name="occupation_type">
              <option selected>Choose...</option>
              <option value="Salaried">Salaried</option>
              <option value="Business">Business</option>

            </select>
          </div>

          <div class="input-group">
            <span class="input-group-text">Job Title</span>
            <input type="text" aria-label="First name" name="job_type_details" id="job_type_details" class="form-control">

          </div>

        </div>
      </div>

    </div>
  </div>


  <div class="row">
    <!-- Multiple inputs & addon -->
    <div class="col-md-6">
      <div class="card mb-4">
        <h5 class="card-header">References</h5>

        <div class="card-body demo-vertical-spacing demo-only-element">

          <div class="input-group">
          <label class="input-group-text" for="inputGroupSelect01">Ref 1</label>

          <select class="form-select" id="r_name" name="r_name">
              <option selected>Choose...</option>
              @foreach ($ref_customers as $refc )
              <option value="{{ $refc->id }} ">{{ $refc->first_name." - ".$refc->last_name }} </option>

              @endforeach

            </select>





          </div>
          <div class="input-group">
            <span class="input-group-text">Phone No:</span>
            <input type="text" aria-label="First name" name="r_phone" id="r_phone" class="form-control" readonly>

          </div>
          <div class="input-group input-group-merge">
            <span class="input-group-text">Address</span>
            <textarea class="form-control" aria-label="With textarea" name="r_address" id="r_address" readonly></textarea>
          </div>


          <div class="input-group">
            <span class="input-group-text">R.Name2:</span>
            <input type="text" aria-label="First name" name="r_name" id="r_name1" class="form-control">

          </div>
          <div class="input-group">
            <span class="input-group-text">Phone No:</span>
            <input type="text" aria-label="First name" name="r_phone" id="r_phone1" class="form-control">

          </div>
          <div class="input-group input-group-merge">
            <span class="input-group-text">Address</span>
            <textarea class="form-control" aria-label="With textarea" name="r2_address" id="r2_address"></textarea>
          </div>

          <div class="input-group input-group-merge">
            <span class="input-group-text">Others</span>
            <textarea class="form-control" aria-label="With textarea" name="r_others" id="r_others"></textarea>
          </div>

        </div>

      </div>
    </div>


    <!-- Speech To Text -->
    <div class="col-md-6">
      <div class="card mb-4">
        <h5 class="card-header">Documents</h5>
        <div class="card-body demo-vertical-spacing demo-only-element">

          <div class="input-group">
            <label class="input-group-text" for="document">Photo</label>
            <input type="file" class="form-control" id="customer_photo" name="customer_photo">
          </div>

          <div class="input-group">
            <label class="input-group-text" for="document">Aadhaar Card</label>
            <input type="file" class="form-control" id="customer_aadharr" name="customer_aadharr">
          </div>

          <div class="input-group">
            <label class="input-group-text" for="document">Others</label>
            <input type="file" class="form-control" id="customer_other" name="customer_other">
          </div>
        </div>
      </div>

      <div class="card mb-4" style="display:none;">
        <h5 class="card-header">Bank Details</h5>
        <div class="card-body demo-vertical-spacing demo-only-element">

          <div class="input-group">
            <span class="input-group-text">Account Holder Name</span>
            <input type="text" aria-label="First name" name="account_holder_name" id="account_holder_name" class="form-control">

          </div>

          <div class="input-group">
            <span class="input-group-text">Bank Name</span>
            <input type="text" aria-label="First name" name="bank_name" id="bank_name" class="form-control">

          </div>

          <div class="input-group">
            <span class="input-group-text">Account Number</span>
            <input type="text" aria-label="First name" name="account_number" id="account_number" class="form-control">

          </div>
          <div class="input-group">
            <span class="input-group-text">IFSC</span>
            <input type="text" aria-label="First name" name="ifsc" id="ifsc" class="form-control">

          </div>



          <div class="input-group">
            <span class="input-group-text">Gpay or PhonePe</span>
            <input type="text" aria-label="First name" name="gpay_no" id="gpay_no" class="form-control">

          </div>
        </div>
      </div>

      <div class="card mb-4">
        <h5 class="card-header">Sandha Details</h5>
        <div class="card-body demo-vertical-spacing demo-only-element">

          <div class="input-group">
            <span class="input-group-text">Plan</span>
            <select class="form-select" id="sandha_plan" name="sandha_plan">
            <option selected>Choose Plan</option>
            @foreach ($sandha_details as $sandha)
            <option value="{{ $sandha->id }}">{{ $sandha->sandha_name. " - Duration : ".$sandha->duration." - Price : RS-".$sandha->price }}</option>
            @endforeach

            </select>

          </div>


        </div>
      </div>
    </div>


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
      url: "{{ route('customers.store') }}",
      method: 'POST',
      data: formData,
      contentType: false,
      processData: false,
      success: function(response) {
        // alert(response.success);
        swal("Done!", response.success, "success");
        //location.reload();
        window.location.href = "{{ url('/customers')}}";
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

<script>
        $(document).ready(function () {
            $('#r_name').change(function () {
                var customerId = $(this).val();
                if (customerId) {
                    $.ajax({
                        url: '/get-customer-details', // Laravel route
                        type: 'GET',
                        data: { id: customerId },
                        cache: false, // Prevent browser caching
                        success: function (response) {
                            $('#r_phone').val(response.r_phone);
                            $('#r_address').val(response.r_address);
                        },
                        error: function () {
                            alert('Failed to fetch customer details.');
                        }
                    });
                } else {
                    $('#phone_number').val('');
                    $('#address').val('');
                }
            });
        });
    </script>


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.all.min.js"></script>
@endsection
