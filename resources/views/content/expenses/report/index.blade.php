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
    <form action="{{ route('expenses.report.index') }}" method="GET">
        <div class="form-row align-items-center">
            <div class="col-auto">
                <label for="from_date">From:</label>
                <input type="date" name="from_date" class="form-control" value="{{ $fromDate }}">
            </div>
            <div class="col-auto">
                <label for="to_date">To:</label>
                <input type="date" name="to_date" class="form-control" value="{{ $toDate }}">
            </div>
            <div class="col-auto">
                <label for="expense_type_id">Expense Type:</label>
                <select name="expense_type_id" class="form-control">
                    <option value="">All</option>
                    @foreach($expenseTypes as $type)
                        <option value="{{ $type->id }}" {{ $expenseTypeId == $type->id ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <!-- Location Filter for Specific Roles -->
        @if(in_array($role, ['8','9','10']))
            <div class="col-auto mb-2">
                <label for="location">Location:</label>
                <select name="location" id="location" class="form-control">
                    <option value="">All</option>
                    @foreach($locations as $location)
                        <option value="{{ $location->id }}" {{ request('location') == $location->id ? 'selected' : '' }}>
                            {{ $location->branch_name }}
                        </option>
                    @endforeach
                </select>
            </div>
        @endif
            <div class="form-group d-flex mb-2" style="margin-top:35px;">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('expenses.report.index') }}" class="btn btn-secondary">Clear</a>
            </div>
        </div>
    </form>



    <h6 class="mt-4" align="center" style="color:red;">Expenses Report for: {{ $fromDate }} to {{ $toDate }}</h6>

    <table class="display nowrap" id="expensesReportTable">
        <thead>
            <tr>
              <th>Date</th>
                <th>Expense Type</th>
                <th>Description</th>
                <th>Branch</th>
                <th>Total Amount</th>
            </tr>
        </thead>
        <tbody>
            @forelse($expenses as $expense)
                <tr>
                    <td>{{ date('d-m-Y',strtotime($expense->date)) ?? 'N/A' }}</td>
                    <td>{{ $expense->expenseType->name ?? 'N/A' }}</td>
                    <td>{{ $expense->description ?? 'N/A' }}</td>
                    <td>{{ $expense->branch->branch_name ?? 'N/A' }}</td>
                    <td>{{ number_format($expense->amount, 2) }}</td>

                </tr>
            @empty
                <tr>
                    <td colspan="3" style="text-align: center; color: red;">
                        No record found
                    </td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr >
                <th></th>
                <th></th>
                <th></th>
                <th style="color: green;">Grand Total</th>
                <th style="color: green;font-weight:bold;">{{ number_format($grandTotal, 2) }}</th>
            </tr>
        </tfoot>
    </table>
    </div>
  </div>
</div>

<!-- Include required libraries -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>


<script>
new DataTable('#expensesReportTable', {
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
