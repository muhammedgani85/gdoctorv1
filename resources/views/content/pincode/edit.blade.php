@extends('layouts/contentNavbarLayout')

@section('title', 'New Sandha')

@section('content')

<h4 class="py-3 mb-4"><span class="text-muted fw-light">Sandha Details </span> </h4>
<form id="editSandhaForm" method="POST" action="{{ route('sandhas.update', $sandha->id) }}">
  @csrf
  @method('PUT') <!-- Add method override for PUT -->

  <div class="row">
    <div class="col-md-6">
      <div class="card mb-4">
        <h5 class="card-header">Sandha Information</h5>
        <div class="card-body demo-vertical-spacing demo-only-element">

          <!-- Sandha Name -->
          <div class="input-group">
            <span class="input-group-text">Sandha Name</span>
            <input type="text" name="sandha_name" class="form-control" value="{{ $sandha->sandha_name }}" required>
          </div>

          <!-- Duration -->
          <div class="input-group">
            <span class="input-group-text">Duration</span>
            <input type="number" name="duration" class="form-control" value="{{ $sandha->duration }}" required>
          </div>

          <!-- Price -->
          <div class="input-group">
            <span class="input-group-text">Price</span>
            <input type="number" step="0.01" name="price" class="form-control" value="{{ $sandha->price }}" required>
          </div>

          <!-- Status -->
          <div class="input-group">
            <span class="input-group-text">Status</span>
            <select name="status" class="form-control" required>
              <option value="active" {{ $sandha->status == 'active' ? 'selected' : '' }}>Active</option>
              <option value="inactive" {{ $sandha->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
          </div>

          <!-- Description -->
          <div class="input-group">
            <span class="input-group-text">Description</span>
            <textarea name="description" class="form-control">{{ $sandha->description }}</textarea>
          </div>

        </div>
      </div>
    </div>
  </div>

  <!-- Save Button -->
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <button type="submit" class="btn rounded-pill btn-success">Save Changes</button>
          <a href="{{ route('sandhas.index') }}" class="btn rounded-pill btn-secondary">Cancel</a>
        </div>
      </div>
    </div>
  </div>
</form>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



<script>
 $('#editSandhaForm').on('submit', function (e) {
    e.preventDefault();

    let form = $(this); // Reference to the form
    Swal.fire({
        title: "Save Changes?",
        text: "Please ensure and then confirm!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, Update it!",
        cancelButtonText: "No, cancel!",
        reverseButtons: true,
    }).then((result) => {
        if (result.isConfirmed) {
            // Proceed with form submission via AJAX
            $.ajax({
                url: form.attr('action'), // Ensure correct route in 'action' attribute
                method: 'POST', // Change to 'PUT' if your route expects a PUT request
                data: form.serialize(),
                success: function (response) {
                    Swal.fire({
                        title: "Success!",
                        text: "Sandha updated successfully.",
                        icon: "success",
                    }).then(() => {
                        window.location.href = "{{ route('sandhas.index') }}"; // Redirect to index
                    });
                },
                error: function (xhr) {
                    let errors = xhr.responseJSON.errors;
                    let errorHtml = '<ul>';
                    $.each(errors, function (key, value) {
                        errorHtml += '<li>' + value + '</li>';
                    });
                    errorHtml += '</ul>';
                    Swal.fire("Error!", errorHtml, "error");
                }
            });
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            Swal.fire("Cancelled", "Your changes were not saved!", "info");
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



@endsection
