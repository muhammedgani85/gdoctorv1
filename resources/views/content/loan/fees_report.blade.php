@extends('layouts/contentNavbarLayout')

@section('title', 'Fees Reports')

@section('page-script')
<script src="{{asset('assets/js/form-basic-inputs.js')}}"></script>
@endsection
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- Include other styles here -->


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
@section('content')
<h4 class="py-0 mb-4">
  <span class="text-muted fw-light" style="color:red !important;">Fees</span>
</h4>

<div class="row">



  <!-- Form controls -->

  <div class="card">
    <div class="table-responsive text-nowrap">

    <form method="GET" action="{{ route('fees.report') }}" class="mb-3 p-3 border rounded bg-light">
    <div class="form-row align-items-center">
        <div class="col-md-2 col-sm-12 mb-2">
            <label for="from_date" class="font-weight-bold">From:</label>
            <input type="date" name="from_date" id="from_date" value="{{ $from }}" class="form-control">
        </div>

        <div class="col-md-2 col-sm-12 mb-2">
            <label for="to_date" class="font-weight-bold">To:</label>
            <input type="date" name="to_date" id="to_date" value="{{ $to }}" class="form-control">
        </div>

        <div class="col-auto mb-2" style="margin-top:30px;">
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="{{ route('fees.report') }}" class="btn btn-secondary">Clear</a>
        </div>
    </div>
</form>
<!-- Loader -->
<div id="loader" class="text-center mt-3" style="display: none;">
    <div class="spinner-border text-primary" role="status">
        <span class="sr-only">Loading...</span>
    </div>
    <p class="mt-2">Processing your request, please wait...</p>
</div>

       <table class="table table-bordered" style="margin-bottom: 20px;" id="loanReport">
    <thead>
        <tr>
            <th>Token Number</th>
            <th>Patient Name</th>
            <th>Consulting Fees</th>
            <th>Medicine</th>
            <th>Total</th>
            <th>Scan Refered</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($fees as $note)
            <tr>
                <td>{{ $note->appointment->token_number ?? '-' }}</td>
                <td>{{ $note->appointment->patient_name ?? '-' }}</td>
                <td>{{ $note->consulting_fees }}</td>
                <td>{{ $note->medicine }}</td>
                <td>{{ $note->consulting_fees + $note->medicine }}</td>
                <td>{{ $note->scan_required }}</td>
                <td>{{ $note->appointment->appointment_date ?? $note->created_at->format('d-m-Y') }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
    <tr style="font-weight: bold; background-color: lightgreen;">
        <td colspan="2" class="text-right">Total</td>
        <td>{{ number_format($totalConsulting, 2) }}</td>
        <td>{{ number_format($totalMedicine, 2) }}</td>
        <td>{{ number_format($totalFees, 2) }}</td>
        <td colspan="2"></td>
    </tr>
</tfoot>
</table>
  </div>
</div>


</div>





<script>
new DataTable('#loanReport', {
    buttons: [
        'excel'
    ],
    layout: {
        topStart: 'buttons'
    }
});


</script>

@endsection
