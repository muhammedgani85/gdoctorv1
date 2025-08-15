@extends('layouts/contentNavbarLayout')

@section('title', 'Doctor Management')
<meta name="csrf-token" content="{{ csrf_token() }}">
@section('page-script')
<script src="{{asset('assets/js/form-basic-inputs.js')}}"></script>
@endsection
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
<style>
.text-left {
    text-align: left;
}

.text-success {
    color: green;
}

.text-warning {
    color: purple;
}

.text-danger {
    color: red;
}
</style>
<style>
 /* Make checked switch green */
.form-check-input:checked {
    background-color: #28a745; /* Bootstrap green */
    border-color: #28a745;
}

/* Optional: animate switch thumb */
.form-check-input {
    transition: background-color 0.3s, border-color 0.3s;
}

</style>

<style>
  thead.custom-header {
    background-color: #4CAF50; /* green */
    color: white;
  }
</style>

<!-- Include other styles here -->
@section('content')

<div class="row">



    <!-- Form controls -->
 <div class="card-header d-flex justify-content-between align-items-center" style="padding-bottom: 20px;">
    <h5 style="color:red;">List of Doctor(s)</h5>
   <a href="javascript:void(0);" class="btn btn-primary open-user-modal" data-url="/doctors/create">
    <i class="bx bx-plus"></i> Add Doctor
</a>
</div>
    <div class="card">



        <div class="table-responsive text-nowrap">

            <table class="display" id="doctorTable" class="table table-bordered" style="margin-bottom: 20px;padding-top:20px;">

        <thead class="table-primary custom-header" >
            <tr>
                <th>S.No</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Speciality</th>
                <th>Available Days</th>
                <th>Timing</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
          @if(isset($doctors))
            @foreach($doctors as $index => $doctor)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $doctor->doctor_name }}</td>
                <td>{{ $doctor->phone_number }}</td>
                <td>{{ $doctor->speciality }}</td>
                <td>{{ $doctor->availability_days }}</td>
                <td>
            @foreach($doctor->timeSlots as $slot)
                <div>{{ date('h:i A', strtotime($slot->timing_from)) }} - {{ date('h:i A', strtotime($slot->timing_to)) }}</div>
            @endforeach
        </td>
                <td>
              <div class="form-check form-switch">
              <input
              class="form-check-input toggle-status"
              type="checkbox"
              role="switch"
              id="statusSwitch{{ $doctor->id }}"
              data-id="{{ $doctor->id }}"
              {{ $doctor->status == 'Active' ? 'checked' : '' }}
              >

              </div>

                </td>
                <td>
                    <button class="btn btn-primary btn-sm edit-doctor" data-id="{{ $doctor->id }}">Edit</button>
                    <button class="btn btn-danger btn-sm delete-doctor" id='delete-doctor' data-id="{{ $doctor->id }}">Delete</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
        @endif
        </tbody>
        </table>
    </div>
</div>


</div>

<!-- Model Code -->

<!-- User Modal -->
<div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg"> <!-- Use modal-lg for wider modal -->
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="userModalLabel">Add Doctor</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" >
        <!-- Form will be loaded here -->

    <form id="doctorForm" method="POST">
    @csrf
   <input type="hidden" name="doctor_id" id="doctor_id">
    <input type="hidden" name="_method" id="formMethod" value="POST">


    <!-- Doctor Name -->
    <div class="mb-3">
        <label for="doctor_name" class="form-label">Doctor Name <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="doctor_name" id="doctor_name"
               value="{{ old('doctor_name', $doctor->doctor_name ?? '') }}" required>
    </div>

    <!-- Phone Number -->
    <div class="mb-3">
        <label for="phone_number" class="form-label">Phone Number <span class="text-danger">*</span></label>
        <input type="tel" class="form-control" name="phone_number" id="phone_number"
               value="{{ old('phone_number', $doctor->phone_number ?? '') }}" required>
    </div>

    <!-- Availability Days (Multi-select) -->
   <!-- Availability Days (Checkboxes) -->
<!-- Availability Days (with "All" option) -->
<div class="mb-3">
    <label class="form-label d-block">Availability Days <span class="text-danger">*</span></label>
    @php
        $days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
        $selectedDays = isset($doctor) ? explode(',', $doctor->availability_days) : [];
    @endphp

    <div class="row">
        <!-- "All" Checkbox -->
        <div class="col-md-3">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="select_all_days">
                <label class="form-check-label fw-bold" for="select_all_days">All</label>
            </div>
        </div>

        <!-- Individual Days -->
        @foreach($days as $day)
            <div class="col-md-3">
                <div class="form-check">
                    <input class="form-check-input availability-day" type="checkbox"
                           name="availability_days[]"
                           id="day_{{ $day }}" value="{{ $day }}"
                           {{ in_array($day, $selectedDays) ? 'checked' : '' }}>
                    <label class="form-check-label" for="day_{{ $day }}">{{ $day }}</label>
                </div>
            </div>
        @endforeach
    </div>
</div>



    <!-- Timing (From - To) -->
    <div class="row mb-3">

       <div id="timeSlotsContainer">
    <div class="time-slot row mb-3">
        <div class="col-md-6">
            <label for="timing_from" class="form-label">Timing From <span class="text-danger">*</span></label>
            <select name="timing_from[]" class="form-control" required>
                @foreach(range(6, 22) as $hour)
                    <option value="{{ $hour }}:00">
                        {{ date('h:i A', strtotime("$hour:00")) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6">
            <label for="timing_to" class="form-label">Timing To <span class="text-danger">*</span></label>
            <select name="timing_to[]" class="form-control" required>
                @foreach(range(6, 22) as $hour)
                    <option value="{{ $hour }}:00">
                        {{ date('h:i A', strtotime("$hour:00")) }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<button type="button" class="btn btn-sm btn-primary mt-2" id="addSlot">Add Time Slot</button>

    <!-- Speciality -->
    <div class="mb-3">
        <label for="speciality" class="form-label">Speciality <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="speciality" id="speciality"
               value="{{ old('speciality', $doctor->speciality ?? '') }}" required>
    </div>

    <div class="mb-3">
        <label for="speciality" class="form-label">Consulting Fees <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="fees" id="fees"
               value="{{ old('fees', $doctor->fees ?? '') }}">
    </div>

    <!-- Submit -->
    <div class="text-end">
        <button type="submit" class="btn btn-success">SAVE</button>
    </div>
</form>

      </div>
    </div>
  </div>
</div>


<!-- Model Popup End -->





<link rel="stylesheet" href="https://cdn.datatables.net/2.0.3/css/dataTables.dataTables.css">
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.0.3/js/dataTables.js"></script>


<script>
new DataTable('#doctorTable', {
    "pageLength": 10, // Set default page length
    "lengthMenu": [5, 10, 25, 50, 75, 100], // Set options for page length
    "language": {
        "search": "", // Remove the search label
        "searchPlaceholder": "Search...", // Optionally, you can add a placeholder
        "emptyTable": "No data available",
        "info": "", // Remove the "Showing X to Y of Z entries"
        "infoEmpty": "", // Remove the "Showing 0 to 0 of 0 entries"
        "infoFiltered": "", // Remove the "filtered from X total entries"

        "paginate": {
            "first": "First",
            "last": "Last",
            "next": "Next",
            "previous": "Previous"
        },
        "zeroRecords": "No matching records found"
    },
    "pagingType": "full_numbers",
});
</script>
<script>
$(document).on('click', '.open-user-modal', function () {
    const url = $(this).data('url'); // e.g., /doctors/create
    if (!url) {
        console.error('URL not found on .open-user-modal button');
        return;
    }

    // Reset and prepare the form
    $('#doctorForm')[0].reset();
    $('#doctorForm').attr('action', `/doctors/store`);
    $('#doctorForm input[name="_method"]').remove(); // Remove PUT if previously added
    $('#doctor_id').val('');
    $('#doctor_name').val('');
    $('#phone_number').val('');
    $('#speciality').val('');

    $('.availability-day').prop('checked', false);
    $('#select_all_days').prop('checked', false);

    // Clear time slots and add one default
    $('#timeSlotsContainer').html(`
        <div class="time-slot row mb-3">
            <div class="col-md-6">
                <label class="form-label">Timing From</label>
                <select name="timing_from[]" class="form-control" required>
                    ${generateTimeOptions()}
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Timing To</label>
                <select name="timing_to[]" class="form-control" required>
                    ${generateTimeOptions()}
                </select>
            </div>
        </div>
    `);

    // Load modal
    $('#userModalLabel').text('Loading...');
    $('#userModalBody').html('<div class="text-center p-3">Loading...</div>');
    $('#userModal').modal('show');

    // Load form via AJAX from the create URL
    $.get(url, function (data) {
        $('#userModalLabel').text('Add Doctor');
        $('#userModalBody').html(data);
    }).fail(function (xhr) {
        $('#userModalBody').html('<div class="text-danger p-3">Failed to load form.</div>');
    });
});

</script>


<script>
    $(document).ready(function () {
        const allDays = @json($days);

        // If all are already checked, check the "All" box
        function checkAllCheckboxStatus() {
            const allChecked = allDays.every(day =>
                $('#day_' + day).is(':checked')
            );
            $('#select_all_days').prop('checked', allChecked);
        }

        // On load
        checkAllCheckboxStatus();

        // Click on "All" checkbox
        $('#select_all_days').on('change', function () {
            $('.availability-day').prop('checked', this.checked);
        });

        // When any individual checkbox changes
        $('.availability-day').on('change', function () {
            checkAllCheckboxStatus();
        });
    });
</script>

<script>


    function openAddModal() {
        $('#doctorForm')[0].reset();
        $('#doctor_id').val('');
        $('#doctorModal').modal('show');
    }

  let url = $('#doctorForm').attr('action');
 $('#doctorForm').on('submit', function (e) {
    e.preventDefault();

    let formData = new FormData(this);
    let url = $('#doctorForm').attr('action');
    let method = $('#doctorForm input[name="_method"]').val() || 'POST';

    $.ajax({
        url: url,
        type: method,
        data: formData,
        contentType: false,
        processData: false,
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').val()
        },
        success: function (response) {
            if (response.success) {
                // Close the modal
                $('#userModal').modal('hide');

                // Show SweetAlert message
                swal({
                    title: "Success!",
                    text: response.message || "Doctor saved successfully!",
                    icon: "success"
                }).then(() => {
                    // Reload after alert is closed
                    location.reload();
                });
            } else {
                swal({
                    title: "Error!",
                    text: response.message || "Something went wrong.",
                    icon: "error"
                });
            }
        },
        error: function (xhr) {
            let errorMsg = "Something went wrong!";
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                let errors = xhr.responseJSON.errors;
                errorMsg = Object.values(errors).map(err => err[0]).join('\n');
            }

            swal({
                title: "Validation Error",
                text: errorMsg,
                icon: "error"
            });
        }
    });
});

// Status change function

$(document).on('change', '.toggle-status', function () {
    let doctorId = $(this).data('id');
    let isChecked = $(this).is(':checked');
    let newStatus = isChecked ? 'Active' : 'Inactive';

    // Update label text
    $(this).siblings('label').text(newStatus);

    $.ajax({
        url: '/doctors/toggle-status/' + doctorId,
        type: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            status: newStatus
        },
        success: function (response) {
            swal({
                title: "Updated!",
                text: response.message || "Doctor status updated.",
                icon: "success"
            });
        },
        error: function () {
            swal("Error", "An unexpected error occurred.", "error");
        }
    });
});


</script>

<script>



 // Delete Feature




// Delete Features



$(document).on('click', '.delete-doctor', function () {
    const id = $(this).data('id');

    swal({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        buttons: {
            cancel: "No",
            confirm: "Yes"
        },
        dangerMode: true,
    }).then((willDelete) => {
        if (willDelete) {
            $.ajax({
                url: `/doctors/delete/${id}`,
                method: 'DELETE',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function (res) {
                    swal("Deleted!", res.message, "success").then(() => {
                        location.reload();
                    });
                },
                error: function () {
                    swal("Error!", "Something went wrong.", "error");
                }
            });
        }
    });
});

// Edit featuree

$(document).on('click', '.edit-doctor', function () {
    const id = $(this).data('id');

    $.get(`/doctors/edit/${id}`, function (doctor) {
        // Reset form
        $('#doctorForm')[0].reset();
        $('#doctor_id').val(doctor.id);

        // Set form action and method for update
        $('#doctorForm').attr('action', `/doctors/${doctor.id}`);
        $('#formMethod').val('PUT');

        // Fill other fields...
        $('input[name="doctor_name"]').val(doctor.doctor_name);
        $('input[name="phone_number"]').val(doctor.phone_number);
        $('input[name="speciality"]').val(doctor.speciality);
        $('input[name="fees"]').val(doctor.fees);

        // Set availability days
        $('input[name="availability_days[]"]').prop('checked', false);
        if (doctor.availability_days) {
            const days = doctor.availability_days.split(',');
            days.forEach(day => {
                $(`input[name="availability_days[]"][value="${day}"]`).prop('checked', true);
            });
        }

        // Set time slots
        $('#timeSlotsContainer').html('');
        if (doctor.time_slots && doctor.time_slots.length > 0) {
            doctor.time_slots.forEach(slot => {
                $('#timeSlotsContainer').append(`
                    <div class="time-slot row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Timing From</label>
                            <select name="timing_from[]" class="form-control" required>
                                ${generateTimeOptions(slot.timing_from)}
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Timing To</label>
                            <select name="timing_to[]" class="form-control" required>
                                ${generateTimeOptions(slot.timing_to)}
                            </select>
                        </div>
                    </div>
                `);
            });
        }

        $('#userModal').modal('show');
    });
});


// Helper: generate options with selected value
function generateTimeOptions(selectedTime) {
    let options = '';
    for (let hour = 6; hour <= 22; hour++) {
        const timeVal = `${hour.toString().padStart(2, '0')}:00`;
        const timeLabel = new Date(`1970-01-01T${timeVal}:00`).toLocaleTimeString([], {
            hour: '2-digit',
            minute: '2-digit'
        });

        // Fix comparison with DB value (e.g., '07:00:00' vs '07:00')
        const isSelected = selectedTime?.slice(0, 5) === timeVal;

        options += `<option value="${timeVal}" ${isSelected ? 'selected' : ''}>${timeLabel}</option>`;
    }
    return options;
}



$(document).on('click', '#addSlot', function () {
    const slotHtml = `
        <div class="time-slot row mb-3">
            <div class="col-md-5">
                <label class="form-label">Timing From</label>
                <select name="timing_from[]" class="form-control" required>
                    ${generateTimeOptions()}
                </select>
            </div>
            <div class="col-md-5">
                <label class="form-label">Timing To</label>
                <select name="timing_to[]" class="form-control" required>
                    ${generateTimeOptions()}
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="button" class="btn btn-danger remove-slot">Remove</button>
            </div>
        </div>
    `;
    $('#timeSlotsContainer').append(slotHtml);
});
$(document).on('click', '.remove-slot', function () {
    $(this).closest('.time-slot').remove();
});


</script>


<!-- Add this in your layout or modal template -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.all.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

@endsection
