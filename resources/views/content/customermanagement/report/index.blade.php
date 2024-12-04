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
  <span class="text-muted fw-light" style="color:red !important;">Customer Report</span>
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
    <form method="GET" action="{{ route('customers.report.index') }}" class="mb-3 p-3 border rounded bg-light">
    <div class="form-row align-items-center">

        <!-- Date Range Filter: From Date -->
        <div class="col-md-2col-sm-12 mb-2">
            <label for="from_date" class="font-weight-bold">From:</label>
            <input type="date" name="from_date" id="from_date" class="form-control" value="{{ $fromDate }}">
        </div>

        <!-- Date Range Filter: To Date -->
        <div class="col-md-2 col-sm-12 mb-2">
            <label for="to_date" class="font-weight-bold">To:</label>
            <input type="date" name="to_date" id="to_date" class="form-control" value="{{ $toDate }}">
        </div>

        <!-- Branch Filter (Only for specific roles) -->
        @if(in_array($role, [1, 9, 10]))
            <div class="col-md-2 col-sm-12 mb-2">
            <label for="branch_id" class="font-weight-bold">Branch:</label>
            <select name="branch_id" id="branch_id" class="form-control">
            <option value="all">All</option>
            @foreach($branches as $branch)
            <option value="{{ $branch->id }}" {{ $branchId == $branch->id ? 'selected' : '' }}>
            {{ $branch->branch_name }}
            </option>
            @endforeach
            </select>
            </div>
        @endif

        <!-- Status Filter -->
        <div class="col-md-2 col-sm-12 mb-2">
            <label for="status" class="font-weight-bold">Status:</label>
            <select name="status" id="status" class="form-control">
                <option value="">All</option>
                <option value="Active" {{ $status == 'Active' ? 'selected' : '' }}>Active</option>
                <option value="InActive" {{ $status == 'InActive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <!-- Submit Button -->
        <div class="col-auto mb-2" style="margin-top:30px;">
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="{{ route('customers.report.index') }}" class="btn btn-secondary">Clear</a>
        </div>

    </div>
</form>




<table class="table table-bordered" style="margin-bottom: 20px;">
    <thead>
        <tr>
            <th>Customer ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Phone Number</th>
            <th>Email</th>
            <th>Branch</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @forelse($customers as $customer)
            <tr>
                <td>{{ $customer->customer_id }}</td>
                <td>{{ $customer->first_name }}</td>
                <td>{{ $customer->last_name }}</td>
                <td>{{ $customer->phone_number }}</td>
                <td>{{ $customer->email_id }}</td>
                <td>{{ $customer->branch->branch_name ?? 'N/A' }}</td>
                <td>{{ $customer->status }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="7" style="color: red; text-align: center;">No records found</td>
            </tr>
        @endforelse
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
