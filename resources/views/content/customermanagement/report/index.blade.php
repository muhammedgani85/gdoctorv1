@extends('layouts/contentNavbarLayout')

@section('title', 'Customer Management')

@section('page-script')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/buttons/3.2.0/js/dataTables.buttons.js"></script>
<script src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.dataTables.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.print.min.js"></script>





<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.2.0/css/buttons.dataTables.css">
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
  <span class="text-muted fw-light" style="color:red !important;">Customer</span>
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
        <div class="col-md-2 col-sm-12 mb-2">
            <label for="status" class="font-weight-bold">Status:</label>
            <select name="status" id="status" class="form-control">
                <option value="">All</option>
                <option value="Active" {{ $status == 'Active' ? 'selected' : '' }}>Active</option>
                <option value="InActive" {{ $status == 'InActive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <div class="col-md-2 col-sm-12 mb-2">
            <label for="pincode" class="font-weight-bold">Pincode:</label>
            <select name="pincode" id="pincode" class="form-control">
                <option value="">Choose</option>
                @foreach ($pincode as $pin)
                    <option value="{{ $pin->id }}">{{ $pin->pin_code . '-' . $pin->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2 col-sm-12 mb-2">
            <label for="sandha" class="font-weight-bold">Sandha:</label>
            <select name="sandha" id="sandha" class="form-control">
                <option value="">Choose</option>
                @foreach ($sandhas as $sandha)
                    <option value="{{ $sandha->id }}">{{ $sandha->sandha_name . " - Duration : " . $sandha->duration }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2 col-sm-12 mb-2">
            <label for="city" class="font-weight-bold">City:</label>
            <select name="city" id="city" class="form-control">
                <option value="">Choose</option>
                @foreach ($city as $cty)
                    <option value="{{ $cty->id }}">{{ $cty->name . " - " . $cty->name_tamil }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2 col-sm-12 mb-2">
            <label for="city" class="font-weight-bold">Ref 1:</label>
            <select name="city" id="city" class="form-control">
                <option value="">Choose</option>
                @foreach ($ref_customer as $ref1)
                    <option value="{{ $ref1->id }}">{{ $ref1->first_name . " - " . $ref1->last_name."- ".$ref1->phone_number }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-auto mb-2" style="margin-top:30px;">
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="{{ route('customers.report.index') }}" class="btn btn-secondary">Clear</a>
        </div>
    </div>
</form>





<table class="table table-bordered" style="margin-bottom: 20px;" id="customer_report">
    <thead style="background-color: #aed6f1;">
        <tr>
            <th>Customer ID</th>
            <th>Name</th>
            <th>Phone Number</th>
            <th>PinCode</th>
            <th>City</th>
            <th>Status</th>
            <th data-export="hidden">Sandha</th>
        </tr>
    </thead>
    <tbody>
        @forelse($customers as $customer)
            <tr>
                <td>{{ $customer->customer_id }}</td>
                <td>{{ $customer->first_name." ".$customer->last_name }}</td>
                <td>{{ $customer->phone_number }}</td>
                <td>{{ $customer->customerpincode->pin_code." - ".$customer->customerpincode->name }}</td>
                <td>{{ $customer->customercity->name}}</td>
                <td>{{ $customer->status }}</td>
                <td data-export="hidden">{{ $customer->sandha_plan }}</td> <!-- Hidden in export -->
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




<script>
new DataTable('#customer_report', {
    buttons: [
        'excel'
    ],
    layout: {
        topStart: 'buttons'
    },
    exportOptions: {
          columns: function (idx, data, node) {
              // Exclude columns with 'data-export="hidden"'
              return !$(node).attr('data-export');
          }
      }
});


</script>

@endsection
