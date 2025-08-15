@extends('layouts/contentNavbarLayout')

@section('title', 'Doctor Appoinment')
<meta name="csrf-token" content="{{ csrf_token() }}">
@section('page-script')
<script src="{{asset('assets/js/form-basic-inputs.js')}}"></script>
@endsection
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<style>
  thead.custom-header {
    background-color: #4CAF50; /* green */
    color: white;
  }
</style>

<style>
  thead.custom-header {
    background-color: #4CAF50; /* green */
    color: white;
  }

  .custom-tooltip .tooltip-inner {
    white-space: normal !important;
    max-width: 300px;
    text-align: left;
}
</style>

<!-- Include other styles here -->
@section('content')

<div class="row">



    <!-- Form controls -->
 <div class="card-header d-flex justify-content-between align-items-center" style="padding-bottom: 20px;">
    <h5 style="color:red;">Appoinments</h5>
   <a href="javascript:void(0);" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#appointmentModal">
    <i class="bx bx-plus"></i> Add Appoinment
</a>
</div>

    <div class="card">



        <div class="table-responsive text-nowrap">

            <table class="display" id="doctorTable" class="table table-bordered" style="margin-bottom: 20px;padding-top:20px;">

         <thead class="custom-header">
            <tr>
                <th>Token</th>
                <th>Patient</th>

                <th>Phone</th>
                <th>Gender</th>
                <th>Age</th>
                <th>Doctor</th>
                <th>Date</th>
                <th>Time</th>
                <th title="Booking Type">B-Type</th>

                <th>Status &nbsp;&nbsp;

                  <i class="bi bi-info-circle-fill text-text-danger"
                  data-bs-toggle="tooltip"
                  data-bs-placement="right"
                  data-bs-html="true"
                  title="
                  <b>Appointment Statuses:</b><br>
                  <span style='color:#6c757d'>Pending</span> - Appointment booked<br>
                  <span style='color:#0d6efd'>Checked-in</span> - Patient has arrived<br>
                  <span style='color:#fd7e14'>In Queue</span> - Waiting to be called<br>
                  <span style='color:#198754'>In Progress</span> - Currently with doctor<br>
                  <span style='color:#14532d'>Completed</span> - Finished<br>
                  <span style='color:#dc3545'>Cancelled</span> - Cancelled<br>
                  <span style='color:#b02a37'>No Show</span> - Did not come<br>
                  <span style='color:#6f42c1'>Rescheduled</span> - Moved to other slot
                  ">
    </i>
                </th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @php
            function getStatusColor($status) {
            return match ($status) {
            'Pending'     => 'orange', // Gray
            'Checked-in'  => '#0d6efd', // Blue
            'In Queue'    => '#fd7e14', // Orange
            'In Progress' => '#198754', // Green
            'Completed'   => '#14532d', // Dark Green
            'Cancelled'   => '#dc3545', // Red
            'No Show'     => '#b02a37', // Red darker
            'Rescheduled' => '#6f42c1', // Purple
            default       => '#adb5bd', // Light Gray
            };
            }
            @endphp
            @foreach($appointments as $appointment)
                <tr>
                   <td>{{ $appointment->token_number }}</td>
                    <td>{{ $appointment->patient_name }}</td>
                    <td>{{ $appointment->phone_number }}</td>
                    <td>{{ $appointment->gender }}</td>
                    <td>{{ $appointment->age }}</td>
                    <td>{{ $appointment->doctor->doctor_name }}</td>
                    <td>{{ $appointment->appointment_date }}</td>
                    <td>{{ \Carbon\Carbon::parse($appointment->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($appointment->end_time)->format('h:i A') }}</td>
                    <td>
                    @if($appointment->btype === 'Direct Booking')
                    <i class="bi bi-person-check-fill text-success" title="Direct Booking"></i>
                    @else
                    <i class="bi bi-share-fill text-primary" title="Social Media Booking"></i>
                    @endif
                    </td>
                    <td>
  <div class="d-flex align-items-start gap-2">
    <select class="form-select status-dropdown" data-id="{{ $appointment->id }}"
            style="background-color: {{ getStatusColor($appointment->status) }}; color: white;">
      @foreach(['Pending', 'Checked-in', 'In Queue', 'In Progress', 'Completed', 'Cancelled', 'No Show', 'Rescheduled'] as $status)
        <option value="{{ $status }}" {{ $appointment->status === $status ? 'selected' : '' }}>
          {{ $status }}
        </option>
      @endforeach
    </select>


  </div>
</td>

                    <td>

                      <button class="btn btn-sm btn-success notes-button" data-id="{{ $appointment->id }}">Pay & Notes</button> &nbsp;
                        <button class="btn btn-sm btn-danger delete-appointment" data-id="{{ $appointment->id }}">Delete</button>
                        &nbsp;
                        <button class="btn btn-sm btn-danger token-number" data-toke_id="{{ $appointment->token_number }}">Call</button>
                        &nbsp;
<button class="btn btn-sm btn-primary prescription-btn"
    data-id="{{ $appointment->id }}"
    data-patient='{{ json_encode(["name" => $appointment->patient_name, "phone" => $appointment->phone_number, "gender" => $appointment->gender, "age" => $appointment->age]) }}'>
    Prescription
</button>


                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>


    </div>
</div>



</div>


<!-- Prescription Modal -->
<div class="modal fade" id="prescriptionModal" tabindex="-1" aria-labelledby="prescriptionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="prescriptionModalLabel">Prescription for <span id="prescPatientName"></span></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="prescPatientDetails" class="mb-3"></div>
        <table class="table table-bordered" id="prescMedicineTable">
          <thead>
            <tr>
              <th>Medicine</th>
              <th>Morning</th>
              <th>Afternoon</th>
              <th>Evening/Night</th>
              <th>No of Days</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><select class="form-control presc-medicine-select"><option value="">Select Medicine</option></select></td>
              <td><input type="number" class="form-control presc-morning" min="0" value="0"></td>
              <td><input type="number" class="form-control presc-afternoon" min="0" value="0"></td>
              <td><input type="number" class="form-control presc-evening" min="0" value="0"></td>
              <td><input type="number" class="form-control presc-days" min="1" value="1"></td>
              <td><button type="button" class="btn btn-danger btn-sm presc-remove-row">Remove</button></td>
            </tr>
          </tbody>
        </table>
        <button type="button" class="btn btn-success btn-sm" id="prescAddRow">Add Medicine</button>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="prescSaveBtn">Save Prescription</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Model Code -->

<!-- Appointment Booking Modal -->
<div class="modal fade" id="appointmentModal" tabindex="-1" aria-labelledby="appointmentModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form id="appointmentForm">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Book Appointment</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
              <label>Appoinment Date</label>
              <input type="date" class="form-control" name="appoinment_date" value="{{ date('Y-m-d') }}" required>

          </div>
          <div class="mb-3">
              <label>Patient Name</label>
              <input type="text" class="form-control" name="patient_name" required>
          </div>
          <div class="mb-3">
              <label>Phone Number</label>
              <input type="text" class="form-control" name="phone_number" required>
          </div>
          <div class="mb-3">
              <label>Gender</label>
              <select class="form-control" name="gender" required>
                  <option value="">Select</option>
                  <option value="Male">Male</option>
                  <option value="Female">Female</option>
                  <option value="Other">Other</option>
              </select>
          </div>
          <div class="mb-3">
              <label>Age</label>
              <input type="number" class="form-control" name="age" required>
          </div>


          <div class="mb-3">
              <label>Primary Illness</label>
              <select class="form-control select2" name="illness">
              <option value="General">General</option>
              @foreach(\App\Models\IllnessType::get() as $illness)
              <option value="{{ $illness->name }}">{{ $illness->name }}</option>
              @endforeach
              </select>
          </div>



          <div class="mb-3">
              <label>Doctor</label>
              <select class="form-control" name="doctor_id" required>
                  <option value="">Select Doctor</option>
                  @foreach(App\Models\Doctor::all() as $doctor)
                      <option value="{{ $doctor->id }}">{{ $doctor->doctor_name }}</option>
                  @endforeach
              </select>
          </div>
          <div class="mb-3">
          <label>Available Time Slots</label>
          <select class="form-control" name="slot_id" id="slotSelect" required>
          <option value="">Select Slot</option>
          <!-- Options will be loaded via AJAX -->
          </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Book</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- Model Popup End -->


<!-- Note Popup -->

<!-- Notes Modal -->
<div class="modal fade" id="notesModal" tabindex="-1" aria-labelledby="notesModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="notesForm">
      @csrf
      <input type="hidden" name="appointment_id" id="appointmentId">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Add/Edit Patient Notes</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body row g-3">
  <div class="col-md-6"><input type="text" class="form-control" name="weight" placeholder="Weight"></div>
  <div class="col-md-6"><input type="text" class="form-control" name="height" placeholder="Height"></div>
  <div class="col-md-6"><input type="text" class="form-control" name="bp" placeholder="BP"></div>
  <div class="col-md-6"><input type="text" class="form-control" name="o2" placeholder="O2 Level"></div>
  <div class="col-md-6"><input type="text" class="form-control" name="sugar_pp" placeholder="Sugar PP"></div>
  <div class="col-md-6"><input type="text" class="form-control" name="sugar_af" placeholder="Sugar AF"></div>
  <div class="col-md-6"><input type="text" class="form-control" name="consulting_fees" placeholder="Consulting Fees"></div>
  <div class="col-md-6"><input type="text" class="form-control" name="medicine" placeholder="Medicine"></div>
  <div class="col-md-6">
    <select name="scan_required" class="form-select">
      <option value="">Scan Required?</option>
      <option value="Yes">Yes</option>
      <option value="No">No</option>
    </select>
  </div>
  <div class="col-md-6"><input type="text" class="form-control" name="scan_centre" placeholder="Scan Centre Name"></div>
  <div class="col-12"><textarea class="form-control" name="notes" placeholder="Notes" rows="3"></textarea></div>
</div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Save Notes</button>
        </div>
      </div>
    </form>
  </div>
</div>


<!-- Note Popup End -->






<link rel="stylesheet" href="https://cdn.datatables.net/2.0.3/css/dataTables.dataTables.css">
<!-- Ensure jQuery is loaded first -->
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<!-- Ensure Bootstrap JS is loaded immediately after jQuery for modal support -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Now load DataTables and other scripts -->
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
   $(document).on('click', '.delete-appointment', function () {
    let id = $(this).data('id');

    swal({
      title: "Are you sure?",
      text: "This will permanently delete the appointment!",
      icon: "warning",
      buttons: true,
      dangerMode: true,
    }).then((willDelete) => {
      if (willDelete) {
        $.ajax({
          url: `/appointments/${id}`,
          type: 'DELETE',
          data: {
            _token: '{{ csrf_token() }}'
          },
          success: function (response) {
            swal("Deleted!", "Appointment has been removed.", "success")
              .then(() => {
                location.reload(); // or remove row from DOM dynamically
              });
          },
          error: function () {
            swal("Error", "Failed to delete appointment.", "error");
          }
        });
      }
    });
  });

    $('#appointmentForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: '{{ route('appointments.store') }}',
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.status === 'slot_full') {
                    Swal({
                        title: 'Time Slot Full',
                        text: response.message,
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes',
                        cancelButtonText: 'No'
                    }).then((result) => {
                        if (result.value) {
                            // allow force booking (optional logic)
                        }
                    });
                } else if (response.status === 'success') {
                    $('#appointmentModal').modal('hide');
                    swal({
                        title: 'Success',
                        text: 'Token: ' + response.token,
                        type: 'success'
                    }).then(() => {
                        location.reload();
                    });
                }
            },
            error: function(xhr) {
                swal('Error', 'Something went wrong.', 'error');
            }
        });
    });
</script>
<script>
$(document).ready(function() {
    $('select[name="doctor_id"]').on('change', function() {
        let doctorId = $(this).val();

        if (doctorId) {
            $.ajax({
                url: '/doctor/' + doctorId + '/slots',
                type: 'GET',
                success: function(data) {
                    let slotSelect = $('#slotSelect');
                    slotSelect.empty();
                    slotSelect.append('<option value="">Select Slot</option>');
                    $.each(data, function(index, slot) {
                        let start = formatTime(slot.timing_from);
                        let end = formatTime(slot.timing_to);
                        slotSelect.append('<option value="' + slot.id + '">' + start + ' - ' + end + '</option>');
                    });
                }
            });
        } else {
            $('#slotSelect').empty().append('<option value="">Select Slot</option>');
        }
    });





    function formatTime(timeStr) {
        const [h, m] = timeStr.split(':');
        const hour = parseInt(h);
        const ampm = hour >= 12 ? 'PM' : 'AM';
        const hour12 = hour % 12 || 12;
        return `${hour12}:${m} ${ampm}`;
    }
});
</script>


<!-- Removed legacy sweetalert.min.js to avoid conflicts. Only SweetAlert2 (Swal) is used. -->

<script>
  $('.status-dropdown').on('change', function () {
    const appointmentId = $(this).data('id');
    const status = $(this).val();
    const fullToken = $(this).closest('tr').find('td:first').text();
    const tokenNumber = fullToken.trim().split('-').pop(); // extracts '001'

    $.ajax({
      url: '/appointments/update-status',
      type: 'POST',
      data: {
        _token: '{{ csrf_token() }}',
        id: appointmentId,
        status: status
      },
      success: function (res) {
        if (status === "In Progress") {
        speakToken(tokenNumber);
      }
        swal("Success", status + " Status updated successfully", "success");
        location.reload();
      },
      error: function () {
        swal("Error", "Failed to update status", "error");
      }
    });
  });



  $(document).on('click', '.token-number', function () {
     const fullToken = $(this).closest('tr').find('td:first').text();
     const tokenNumber = fullToken.trim().split('-').pop(); // extracts '001'
     speakToken(tokenNumber);
     });

// Voice
function speakToken(token) {
  const message = new SpeechSynthesisUtterance(`Token number ${token}, please proceed to the doctor's room.`);
  message.lang = 'ta-IN'; // optional: change to 'ta-IN' or 'hi-IN' or en-US for Tamil/Hindi/English voices
  message.volume = 1; // 0 to 1
  message.rate = 1;   // 0.1 to 10
  message.pitch = 1;  // 0 to 2

  window.speechSynthesis.speak(message);
}

</script>



<script>
  document.addEventListener('DOMContentLoaded', function () {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl)
    })
  });
</script>

<script>
document.querySelectorAll('.notes-button').forEach(button => {
  button.addEventListener('click', async function () {
    const appointmentId = this.getAttribute('data-id');
    document.getElementById('appointmentId').value = appointmentId;

    // Reset form
    const form = document.getElementById('notesForm');
    form.reset();

    // Fetch existing note
    const response = await fetch(`/appointments/${appointmentId}/note`);
    if (response.ok) {
      const data = await response.json();
      for (const key in data) {
        if (form.elements[key]) {
          form.elements[key].value = data[key];
        }
      }
    }

    new bootstrap.Modal(document.getElementById('notesModal')).show();
  });
});

// Save Note
document.getElementById('notesForm').addEventListener('submit', async function (e) {
  e.preventDefault();
  const form = e.target;
  const appointmentId = form.appointment_id.value;

  const response = await fetch(`/appointments/${appointmentId}/note`, {
    method: 'POST',
    headers: {
      'X-CSRF-TOKEN': form._token.value,
      'Content-Type': 'application/json',
    },
    body: JSON.stringify(Object.fromEntries(new FormData(form))),
  });

  if (response.ok) {
    alert('Notes saved!');
    bootstrap.Modal.getInstance(document.getElementById('notesModal')).hide();
  } else {
    alert('Error saving notes');
  }
});
</script>


@php
  $distinctMedicines = \App\Models\StockItem::query()->distinct()->pluck('name')->filter()->values();
@endphp
<script>
// Distinct medicine names from stock_items table
window.distinctMedicineNames = @json($distinctMedicines);
function getDistinctMedicineNames() {
  return window.distinctMedicineNames || [];
}
// Wrap all jQuery code to ensure $ refers to jQuery
(function($) {
  $(function() {
    $(document).on('click', '.prescription-btn', function() {
      let patient = $(this).data('patient');
      let appointmentId = $(this).data('id');
      $('#prescPatientName').text(patient.name);
      $('#prescPatientDetails').html(`
        <b>Phone:</b> ${patient.phone} &nbsp; <b>Gender:</b> ${patient.gender} &nbsp; <b>Age:</b> ${patient.age}
      `);
      // Set appointment id as data attribute on modal
      $('#prescriptionModal').data('appointment-id', appointmentId);

      // Fetch existing prescription for this appointment
      $.get('/prescriptions/' + appointmentId, function(res) {
        let tbody = $('#prescMedicineTable tbody');
        tbody.empty();
        if (res.status === 'success' && res.items && res.items.length > 0) {
          res.items.forEach(function(item) {
            let row = `<tr>
              <td><select class="form-control presc-medicine-select"><option value="">Select Medicine</option></select></td>
              <td><input type="number" class="form-control presc-morning" min="0" value="${item.morning || 0}"></td>
              <td><input type="number" class="form-control presc-afternoon" min="0" value="${item.afternoon || 0}"></td>
              <td><input type="number" class="form-control presc-evening" min="0" value="${item.evening || 0}"></td>
              <td><input type="number" class="form-control presc-days" min="1" value="${item.days || 1}"></td>
              <td><button type="button" class="btn btn-danger btn-sm presc-remove-row">Remove</button></td>
            </tr>`;
            tbody.append(row);
            // Set medicine dropdown value after options are populated
          });
        } else {
          // No existing prescription, add one empty row
          let row = `<tr>
            <td><select class="form-control presc-medicine-select"><option value="">Select Medicine</option></select></td>
            <td><input type="number" class="form-control presc-morning" min="0" value="0"></td>
            <td><input type="number" class="form-control presc-afternoon" min="0" value="0"></td>
            <td><input type="number" class="form-control presc-evening" min="0" value="0"></td>
            <td><input type="number" class="form-control presc-days" min="1" value="1"></td>
            <td><button type="button" class="btn btn-danger btn-sm presc-remove-row">Remove</button></td>
          </tr>`;
          tbody.append(row);
        }
        // Populate medicine dropdowns for all rows
        let medNames = getDistinctMedicineNames();
        tbody.find('.presc-medicine-select').each(function(i) {
          let sel = $(this);
          sel.empty().append('<option value="">Select Medicine</option>');
          medNames.forEach(n => sel.append(`<option value="${n}">${n}</option>`));
          if (res.status === 'success' && res.items && res.items[i]) {
            sel.val(res.items[i].medicine);
          }
        });
        // Bootstrap 5: show modal using JS API
        var modalEl = document.getElementById('prescriptionModal');
        if (modalEl) {
          var modal = bootstrap.Modal.getOrCreateInstance(modalEl);
          modal.show();
        }
      });
    });
    $('#prescAddRow').on('click', function() {
      let medNames = getDistinctMedicineNames();
      let row = `<tr>
        <td><select class="form-control presc-medicine-select"><option value="">Select Medicine</option>${medNames.map(n => `<option value='${n}'>${n}</option>`)}</select></td>
        <td><input type="number" class="form-control presc-morning" min="0" value="0"></td>
        <td><input type="number" class="form-control presc-afternoon" min="0" value="0"></td>
        <td><input type="number" class="form-control presc-evening" min="0" value="0"></td>
        <td><input type="number" class="form-control presc-days" min="1" value="1"></td>
        <td><button type="button" class="btn btn-danger btn-sm presc-remove-row">Remove</button></td>
      </tr>`;
      $('#prescMedicineTable tbody').append(row);
    });
    $(document).on('click', '.presc-remove-row', function() {
      $(this).closest('tr').remove();
    });
    // AJAX for #prescSaveBtn to store prescription
    let prescSaving = false;
    $('#prescSaveBtn').on('click', function() {
      if (prescSaving) return; // Prevent duplicate saves
      // Collect prescription data
      let appointmentId = $('.prescription-btn:focus').data('id') || $('.prescription-btn.active').data('id') || null;
      if (!appointmentId) {
        appointmentId = $('#prescriptionModal').data('appointment-id') || null;
      }
      let patient = $('#prescPatientName').text();
      let patientDetails = $('#prescPatientDetails').html();
      let medicines = [];
      $('#prescMedicineTable tbody tr').each(function() {
        let med = $(this).find('.presc-medicine-select').val();
        if (!med) return;
        medicines.push({
          medicine: med,
          morning: $(this).find('.presc-morning').val(),
          afternoon: $(this).find('.presc-afternoon').val(),
          evening: $(this).find('.presc-evening').val(),
          days: $(this).find('.presc-days').val()
        });
      });
      if (medicines.length === 0) {
        Swal.fire({icon:'warning',title:'Add Medicine',text:'Please add at least one medicine.'});
        return;
      }
      swal({
        title: "Save Prescription?",
        text: "Do you want to save this prescription?",
        icon: "warning",
        buttons: {
          yes: {
            text: "Yes",
            value: "yes",
            className: "btn btn-success"
          },
          print: {
            text: "Yes & Print",
            value: "print",
            className: "btn btn-primary"
          },
          cancel: {
            text: "Cancel",
            value: null,
            className: "btn btn-secondary"
          }
        },
        dangerMode: true
      }).then((value) => {
        if (value === "yes" || value === "print") {
          prescSaving = true;
          $('#prescSaveBtn').prop('disabled', true);
          $.ajax({
            url: '/prescriptions/store',
            method: 'POST',
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
              appointment_id: appointmentId,
              patient_name: patient,
              medicines: medicines
            },
            success: function(res) {
              var modalEl = document.getElementById('prescriptionModal');
              if (modalEl) {
                var modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                modal.hide();
              }
              prescSaving = false;
              $('#prescSaveBtn').prop('disabled', false);
              if (value === "print") {
                // Open new tab for print preview with hospital info and welcome card
                let printWindow = window.open('', '_blank');
                let medRows = medicines.map(m => `<tr><td>${m.medicine}</td><td>${m.morning}</td><td>${m.afternoon}</td><td>${m.evening}</td><td>${m.days}</td></tr>`).join('');
                let html = `
                  <html><head>
                    <title>Prescription Print</title>
                    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
                    <style>
                      body{padding:20px;}
                      .print-btn{margin-bottom:20px;}
                      .hospital-header{border-bottom:2px solid #198754;margin-bottom:20px;padding-bottom:10px;}
                      .welcome-card{margin-top:30px;background:#f8f9fa;border:1px solid #198754;border-radius:8px;padding:16px;text-align:center;}
                    </style>
                  </head><body>
                    <div class="hospital-header text-center">
                      <h2 style="color:#198754;margin-bottom:0;">Janasakthi Hospital</h2>
                      <div>123 Main Road, Your City, State 600001</div>
                      <div>Phone: +91-98765 43210</div>
                    </div>
                    <button class="btn btn-primary print-btn" onclick="window.print()">Print</button>
                    <h3 class="text-center">Prescription</h3>
                    <div><b>Patient:</b> ${patient}</div>
                    <div>${patientDetails}</div>
                    <table class="table table-bordered mt-3">
                      <thead><tr><th>Medicine</th><th>Morning</th><th>Afternoon</th><th>Evening/Night</th><th>No of Days</th></tr></thead>
                      <tbody>${medRows}</tbody>
                    </table>
                    <div class="welcome-card">
                      <h4>Welcome to Janasakthi Hospital</h4>
                      <p>We wish you a speedy recovery!<br>For any queries, call us at <b>+91-98765 43210</b></p>
                    </div>
                  </body></html>
                `;
                printWindow.document.write(html);
                printWindow.document.close();
              } else {
                swal("Prescription saved!", {icon: "success"});
              }
            },
            error: function(xhr) {
              swal("Error saving prescription.", {icon: "error"});
              prescSaving = false;
              $('#prescSaveBtn').prop('disabled', false);
            }
          });
        }
        // else Cancel: do nothing
      });
    });
  });
})(jQuery);
</script>

<!-- Add this in your layout or modal template -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<!-- Remove duplicate jQuery include to avoid conflicts -->
<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->




<!-- Use SweetAlert v1.x for swal({...}) compatibility -->
<link rel="stylesheet" href="https://unpkg.com/sweetalert/dist/sweetalert.css">
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>








@endsection
