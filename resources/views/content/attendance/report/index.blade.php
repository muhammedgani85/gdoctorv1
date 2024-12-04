@extends('layouts/contentNavbarLayout')

@section('title', 'Leave Management')

@section('page-script')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

<style>
  .holiday {
    color: red;
  }

  .sunday {
    color: blue;
  }
</style>

<style>
.form-control {
    height: 40px; /* Set the height for input fields */
}
.btn {
    height: 40px; /* Make buttons the same height as inputs */
    margin-left: 5px; /* Optional: Add some space between buttons */
}
</style>

@section('content')
<h4 class="py-0 mb-4">
  <span class="text-muted fw-light" style="color:red !important;">Attendance Report</span>
</h4>

<div class="row">
  @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  @if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
  @endif

  <div class="card">

    <div class="table-responsive text-nowrap" style="margin-top:30px;">
    <form action="{{ route('attendance.report.index') }}" method="GET" class="mb-3">
    <div class="d-flex align-items-center flex-wrap">
        <div class="form-group mr-2 mb-2">
            <label for="month" class="mr-2">Month:</label>
            <input type="month" name="month" id="month" class="form-control" value="{{ request('month') }}" required>
        </div>

        <div class="form-group mr-2 mb-2">
            <label for="employee_id" class="mr-2">Employee:</label>
            <select name="employee_id" id="employee_id" class="form-control">
                <option value="">All</option>
                @foreach($employees as $employee)
                    <option value="{{ $employee->id }}" {{ request('employee_id') == $employee->id ? 'selected' : '' }}>
                        {{ $employee->first_name }} {{ $employee->last_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group mr-2 mb-2">
            <label for="location_id" class="mr-2">Branch:</label>
            <select name="location_id" id="location_id" class="form-control">
                <option value="">All</option>
                @foreach($locations as $location)
                    <option value="{{ $location->id }}" {{ request('location_id') == $location->id ? 'selected' : '' }}>
                        {{ $location->branch_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Buttons in the same row -->
        <div class="form-group d-flex mb-2" style="margin-top:30px;">
            <button type="submit" class="btn btn-primary mr-2">Filter</button>
            <a href="{{ route('attendance.report.index') }}" class="btn btn-secondary">Clear</a>
        </div>
    </div>
</form>



      <table id="attendanceTable" class="display nowrap" style="width:100%">
        <thead>
            <tr>
                <th>Employee ID</th>
                <th>Employee Name</th>
                <th>Date</th>
                <th>Present</th>
                <th>Location</th>
            </tr>
        </thead>
        <tbody>
            @if($attendanceRecords->isNotEmpty())
                @foreach($attendanceRecords as $record)
                    <tr>
                        <td>{{ $record->user->emp_id ?? '--' }}</td>
                        <td>{{ $record->user->first_name ?? '--' }} {{ $record->user->last_name ?? '--' }}</td>
                        <td>{{ $record->date ?? '--' }}</td>
                        <td>{{ $record->present ? 'Yes' : 'No' }}</td>
                        <td>{{ $record->user->branch->branch_name ?? '--' }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="5" style="text-align: center; color: red;">
                        No record found
                    </td>
                </tr>
            @endif
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Include required libraries -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>


<script>
new DataTable('#attendanceTable', {
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
    "layout": {
        "topStart": {
            "buttons": ['copy', 'csv', 'excel', 'pdf', 'print']
        }
    }
  });
</script>

@endsection
