@extends('layouts/contentNavbarLayout')

@section('title', 'Doctor Appoinment')
<meta name="csrf-token" content="{{ csrf_token() }}">
@section('page-script')
<script src="{{asset('assets/js/form-basic-inputs.js')}}"></script>
@endsection

<!-- Stock Upload Modal (ensure present and at the end for proper popup behavior) -->
<div class="modal fade" id="uploadStockModal" tabindex="-1" aria-labelledby="uploadStockModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="stockUploadForm" action="{{ route('stock.upload') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="uploadStockModalLabel">Upload Stock CSV</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="file" class="form-label">Select CSV File</label>
            <input type="file" class="form-control" id="file" name="file" accept=".csv" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Upload</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>
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
<script>
    window.allStockItems = @json($allStockItems);
</script>
@section('content')

<div class="row">



    <!-- Form controls -->
 <div class="card-header d-flex justify-content-between align-items-center" style="padding-bottom: 20px;">
    <h5 style="color:red;">Stock Management</h5>
    <div>
        <a href="{{ asset('assets/samples/stock_sample.csv') }}" class="btn btn-success me-2">
            <i class="bi bi-download"></i> Download Sample CSV
        </a>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadStockModal">
            <i class="bi bi-upload"></i> Upload Stock
        </button>
    </div>
</div>

    <div class="card">



        <div class="table-responsive text-nowrap">

            <table class="display" id="doctorTable" class="table table-bordered" style="margin-bottom: 20px;padding-top:20px;">

         <thead class="custom-header">
            <tr>
                <th>Name</th>
                <th>SKU</th>
                <th>Quantity</th>
                <th>Low Stock</th>
                <th>Price</th>
                <th>Unit</th>
                <th>Action</th>
            </tr>
        </thead>


        <tbody>
            @php
                $grouped = collect($allStockItems)->groupBy(function($item) {
                    return $item->name . '|' . $item->sku . '|' . $item->unit . '|' . $item->price;
                });
            @endphp
            @foreach($grouped as $key => $items)
                @php
                    $first = $items->first();
                    $sumQty = $items->sum('quantity');
                    $sumLowStock = $items->sum('low_stock_threshold');
                @endphp
                <tr>
                    <td>{{ $first->name }}</td>
                    <td>{{ $first->sku }}</td>
                    <td>{{ $sumQty }}</td>
                    <td>{{ $sumLowStock }}</td>
                    <td>{{ $first->price }}</td>
                    <td>{{ $first->unit }}</td>
                    <td>
                        <button class="btn btn-info btn-sm view-history" data-name="{{ $first->name }}" data-unit="{{ $first->unit }}">History</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- History Modal -->
    <div class="modal fade" id="historyModal" tabindex="-1" aria-labelledby="historyModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="historyModalLabel">Stock Batch History</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div id="historyContent">Loading...</div>
          </div>
        </div>
      </div>
    </div>
        </tbody>
    </table>


    </div>
</div>



</div>

<!-- Model Code -->




<!-- Note Popup -->







<link rel="stylesheet" href="https://cdn.datatables.net/2.0.3/css/dataTables.dataTables.css">
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.0.3/js/dataTables.js"></script>


<script>
$(document).on('click', '.view-history', function() {
    var name = $(this).data('name');
    var unit = $(this).data('unit');
    var allStock = window.allStockItems;
    var filtered = allStock.filter(function(item) {
        return item.name === name && item.unit === unit;
    });
    var html = '<table class="table table-bordered"><thead><tr><th>Batch Number</th><th>SKU</th><th>Quantity</th><th>Low Stock</th><th>Price</th><th>Date</th></tr></thead><tbody>';
    filtered.forEach(function(item) {
        html += '<tr>' +
            '<td>' + item.batch_number + '</td>' +
            '<td>' + item.sku + '</td>' +
            '<td>' + item.quantity + '</td>' +
            '<td>' + item.low_stock_threshold + '</td>' +
            '<td>' + item.price + '</td>' +
            '<td>' + item.created_at + '</td>' +

            '</tr>';
    });
    html += '</tbody></table>';
    $('#historyContent').html(html);
    $('#historyModal').modal('show');
});
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



<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>








<!-- Add this in your layout or modal template -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.all.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>







@endsection
