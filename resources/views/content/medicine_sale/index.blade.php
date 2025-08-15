@extends('layouts/contentNavbarLayout')

@section('title', 'Medicine Sale')
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
    <h5 style="color:red;">Medicine Sales</h5>
   <a href="{{ route('medicine-sale.create') }}" class="btn btn-primary">
    <i class="bx bx-plus"></i> Add Sale
</a>
</div>

    <div class="card">
        <div class="table-responsive text-nowrap">
            <table class="display" id="medicineSaleTable" class="table table-bordered" style="margin-bottom: 20px;padding-top:20px;">
         <thead class="custom-header">
            <tr>
                <th>ID</th>
                <th>Patient</th>
                <th>Total Amount</th>
                <th>Discount</th>
                <th>Final Amount</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sales as $sale)
            <tr>
                <td>{{ $sale->id }}</td>
                <td>{{ $sale->patient_id }}</td>
                <td>{{ $sale->total_amount }}</td>
                <td>{{ $sale->discount }}</td>
                <td>{{ $sale->final_amount }}</td>
                <td>{{ $sale->created_at }}</td>
                <td>
                    <a href="{{ route('medicine-sale.show', $sale->id) }}" class="btn btn-info btn-sm">View</a>
                    <a href="{{ route('medicine-sale.edit', $sale->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('medicine-sale.destroy', $sale->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>
</div>
</div>

<script>
$(document).ready(function() {
    new DataTable('#medicineSaleTable', {
        "pageLength": 10,
        "lengthMenu": [5, 10, 25, 50, 75, 100],
        "language": {
            "search": "",
            "searchPlaceholder": "Search...",
            "emptyTable": "No data available",
            "info": "",
            "infoEmpty": "",
            "infoFiltered": "",
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
});
</script>
@endsection
