<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Appointment Booking</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      background: #f8f9fa;
      font-family: 'Segoe UI', sans-serif;
    }
    .booking-form {
      max-width: 500px;
      margin: 50px auto;
      background: white;
      border-radius: 10px;
      box-shadow: 0 0 15px rgba(0,0,0,0.1);
      padding: 30px;
    }
    .booking-form h4 {
      margin-bottom: 25px;
      text-align: center;
      color: #198754;
    }
    .form-control, .form-select {
      border-radius: 8px;
    }
    .btn-success {
      width: 100%;
      border-radius: 8px;
    }
  </style>
</head>
<body>

<div class="booking-form">
  <h4>Book an Appointment</h4>
 <form id="appointmentForm">
  @csrf
  <div class="modal-body">
    <!-- Appointment Date -->
    <div class="mb-3">
      <label>Appointment Date</label>
      <input type="date" class="form-control" name="appoinment_date" value="{{ date('Y-m-d') }}" required>
    </div>

    <!-- Patient Name -->
    <div class="mb-3">
      <label>Patient Name</label>
      <input type="text" class="form-control" name="patient_name" required>
    </div>

    <!-- Phone Number -->
    <div class="mb-3">
      <label>Phone Number</label>
      <input type="text" class="form-control" name="phone_number" required>
    </div>

    <!-- Gender -->
    <div class="mb-3">
      <label>Gender</label>
      <select class="form-control" name="gender" required>
        <option value="">Select</option>
        <option value="Male">Male</option>
        <option value="Female">Female</option>
        <option value="Other">Other</option>
      </select>
    </div>

    <!-- Age -->
    <div class="mb-3">
      <label>Age</label>
      <input type="number" class="form-control" name="age" required>
    </div>

    <!-- Doctor -->
    <div class="mb-3">
      <label>Doctor</label>
      <select class="form-control" name="doctor_id" id="doctorSelect" required>
        <option value="">Select Doctor</option>
        <!-- Fetched via AJAX -->
      </select>
    </div>

    <!-- Time Slot -->
    <div class="mb-3">
      <label>Available Time Slots</label>
      <select class="form-control" name="slot_id" id="slotSelect" required>
        <option value="">Select Slot</option>
        <!-- Fetched via AJAX -->
      </select>
    </div>
  </div>

  <input type='hidden' name='btype' value='socialMedia' />

  <div class="modal-footer">
    <button type="submit" class="btn btn-success">Book</button>

  </div>
</form>
</div>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
$(document).ready(function() {

    // Load Doctors
    $.get("http://127.0.0.1:8000/api/doctors", function (data) {
        $('#doctorSelect').html('<option value="">Select Doctor</option>');
        $.each(data, function (i, doctor) {
            $('#doctorSelect').append(
                '<option value="' + doctor.id + '">' + doctor.doctor_name + '</option>'
            );
        });
    }).fail(function () {
        alert("Failed to load doctors.");
    });
    // Load Slots on Doctor Select
    $('#doctorSelect').change(function() {
        const doctorId = $(this).val();
        $('#slotSelect').html('<option value="">Loading...</option>');
        if (doctorId) {
            $.get("http://127.0.0.1:8000/api/doctor/" + doctorId + "/slots", function(data) {
                let options = '<option value="">Select Slot</option>';
                $.each(data, function(i, slot) {
                    options += '<option value="' + slot.id + '">' + slot.timing_from + ' - ' + slot.timing_to + '</option>';
                });
                $('#slotSelect').html(options);
            });
        } else {
            $('#slotSelect').html('<option value="">Select Slot</option>');
        }
    });

    // Submit Form
    $('#appointmentForm').submit(function (e) {
    e.preventDefault();
    const formData = $(this).serialize();

    $.ajax({
        url: 'http://127.0.0.1:8000/api/appointments',
        method: 'POST',
        data: formData,
        success: function (response) {
            console.log(response);

            if (response.status === 'success') {
                alert(
                    'Appointment booked successfully!\n' +
                    'Token: ' + response.token + '\n' +
                    'Time: ' + response.time
                );
                $('#appointmentForm')[0].reset();
                $('#slotSelect').html('<option value="">Select Slot</option>');
            } else if (response.status === 'slot_full') {
                alert(response.message);
            } else {
                alert('Unexpected response. Please try again.');
            }
        },
        error: function (xhr) {
            // Show server-side validation or fallback error
            if (xhr.responseJSON && xhr.responseJSON.message) {
                alert('Error: ' + xhr.responseJSON.message);
            } else {
                alert('Failed to book appointment!');
            }
        }
    });
});

});
</script>





</body>
</html>
