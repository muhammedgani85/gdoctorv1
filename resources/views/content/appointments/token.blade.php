<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Doctor Token Table</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap CSS CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      padding: 20px;
      background-color: #f2f2f2;
    }
    .table-container {
      background: white;
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
  </style>
</head>
<body>

<div class="container table-container">
  <h2 class="mb-4 text-center">Doctor Token Display</h2>

  <!-- Filter -->
  <div class="row mb-3">
    <div class="col-md-6">
      <label for="doctorFilter" class="form-label">Filter by Doctor:</label>
      <select class="form-select" id="doctorFilter" onchange="filterTable()">
      <option value="all">-- Show All --</option>
      @foreach($doctorList as $doctor)
      <option value="{{ $doctor['id'] }}">{{ $doctor['name'] }}</option>
      @endforeach
      </select>
    </div>
  </div>

  <!-- Table -->
  <div class="table-responsive">
     <table class="table table-bordered table-hover" id="doctorTable">

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


                <th>Status</th>

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
  <div class="d-flex align-items-start gap-2">
    <select class="form-select status-dropdown" data-id="{{ $appointment->id }}"
            style="background-color: {{ getStatusColor($appointment->status) }}; color: white;">
      @foreach(['Pending', 'Checked-in', 'In Queue', 'In Progress', 'Completed', 'Cancelled', 'No Show', 'Rescheduled'] as $status)
        <option value="{{ $status }}" {{ $appointment->status === $status ? 'selected' : '' }}>
          {{ $status }}
        </option>
      @endforeach
    </select>

    <i class="bi bi-info-circle-fill text-primary"
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
  </div>
</td>



                </tr>
            @endforeach
        </tbody>
    </table>
  </div>
</div>

<!-- JS for Filtering -->
<script>
  function filterTable() {
    alert('Testing');
    let selectedDoctor = document.getElementById("doctorFilter").value.toLowerCase();
    let rows = document.querySelectorAll("#doctorTable tbody tr");

    rows.forEach(row => {
      let doctorName = row.cells[5].textContent.toLowerCase();
      alert(doctorName);
      if (selectedDoctor === "all" || doctorName === selectedDoctor) {
        row.style.display = "";
      } else {
        row.style.display = "none";
      }
    });
  }
</script>

<script>
  setTimeout(() => {
    location.reload();
  }, 1 * 60 * 1000); // 2 minutes
</script>




</body>
</html>
