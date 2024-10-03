@extends('layouts/contentNavbarLayout')

@section('title', 'Customer Management')

@section('page-script')
<script src="{{asset('assets/js/form-basic-inputs.js')}}"></script>
@endsection
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">

<!-- Include other styles here -->
@section('content')
<h4 class="py-0 mb-4">
  <span class="text-muted fw-light" style="color:red !important;">Loans</span>
</h4>

<div class="row">
  <div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
      <div class="card" style="background-color: #FED8B1;color:#000;font-weight:bold;">
        <div class="card-body">
          <div class="d-flex align-items-start justify-content-between">
            <div class="content-left">
              <span>Loans (Total)</span>
              <div class="d-flex align-items-end mt-2">
                <h4 class="mb-0 me-2" style="color:#000;font-weight:bold;">{{ $total =  $loans->count();  }}</h4>
                <!-- <small class="text-success">(+29%)</small> -->
              </div>
              <!-- <p class="mb-0">Total Employees</p> -->
            </div>
            <div class="avatar">
              <span class="avatar-initial rounded bg-label-primary">
                <i class="bx bx-user bx-sm"></i>
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-xl-3">
      <div class="card" style="background-color: #90EE90;color:#000;font-weight:bold;">
        <div class="card-body">
          <div class="d-flex align-items-start justify-content-between">
            <div class="content-left">
              <span>Today</span>
              <div class="d-flex align-items-end mt-2">
                <h4 class="mb-0 me-2" style="color:#000;font-weight:bold;">{{ $todayLoans;  }}</h4>
                <!-- <small class="text-success">(+18%)</small> -->
              </div>
              <!-- <p class="mb-0">Up to Date </p> -->
            </div>
            <div class="avatar">
              <span class="avatar-initial rounded bg-label-success">
                <i class="bx bx-user-check bx-sm"></i>
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-xl-3">
      <div class="card" style="background-color: #FF7F7F;color:#000;font-weight:bold;">
        <div class="card-body">
          <div class="d-flex align-items-start justify-content-between">
            <div class="content-left">
              <span>Week</span>
              <div class="d-flex align-items-end mt-2">
                <h4 class="mb-0 me-2" style="color:#000;font-weight:bold;">{{ $weekLoans;  }}</h4>
                <!-- <small class="text-danger">(-14%)</small> -->
              </div>
              <!-- <p class="mb-0">Up to Date </p> -->
            </div>
            <div class="avatar">
              <span class="avatar-initial rounded bg-label-danger">
                <i class="bx bx-group bx-sm"></i>
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-xl-3">
      <div class="card" style="background-color: #CBC3E3;color:#000;font-weight:bold;">
        <div class="card-body">
          <div class="d-flex align-items-start justify-content-between">
            <div class="content-left">
              <span>Month</span>
              <div class="d-flex align-items-end mt-2">
                <h4 class="mb-0 me-2" style="color:#000;font-weight:bold;">{{ $monthLoans;  }}</h4>
                <!-- <small class="text-success">(+42%)</small> -->
              </div>
              <!-- <p class="mb-0">Up to Date </p> -->
            </div>
            <div class="avatar">
              <span class="avatar-initial rounded bg-label-warning">
                <i class="bx bx-user-voice bx-sm"></i>
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>


  <!-- Form controls -->

  <div class="card">
    <div class="table-responsive text-nowrap">

      <table class="table" id="usersTable">
        <thead>
          <tr>

            <th>Loan No</th>
            <th>Cust.ID</th>
            <th>Cust.Name</th>
            <th>Location</th>
            <th>Loan Amount</th>
            <th>Type</th>
            <th>Int.Scheme</th>
            <th>L.Date</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>

        <tbody>
        @foreach ( $loans as $loan)
        <tr>
          <td>{{ $loan->loan_number }}</td>
          <td>{{ $loan->customer->customer_id }}</td>
          <td>{{ $loan->customer->first_name }} {{ $loan->customer->last_name }}</td>
          <td>{{ $loan->location->branch_name }}</td>
          <td>{{ $loan->total_loan_amount }}</td>
          <td>{{ $loan->loanType->loan_types }}</td>
          <td>{{ $loan->interest_month." - Month" }}</td>
          <td>{{ $loan->created_at }}</td>
          @php
    switch ($loan->status) {
        case 'New':
            $statusClass = 'status-new';
            break;
        case 'Approved':
            $statusClass = 'status-approved';
            break;
        case 'Rejected':
            $statusClass = 'status-rejected';
            break;
        case 'Dispatch':
            $statusClass = 'status-dispatch';
            break;
        case 'Withdraw':
            $statusClass = 'status-withdraw';
            break;
        default:
            $statusClass = '';
            break;
    }
@endphp

<td class="{{ $statusClass }}">
    {{ $loan->status }}
</td>


          <td>

          <a href="javascript:void(0);" data-id="{{ $loan->loan_number }}"  title="view"><i class='bx bx-show'></i></i></a>
          <a href="http://127.0.0.1:8000/customers/2/edit" title="Interest List"><i class='bx bx-list-ol'></i></a>
          <a href="http://127.0.0.1:8000/customers/2/edit" title="Pay Interest"><i class='bx bx-rupee' style="color:red;" ></i></a>

          <a href="javascript:void(0);" data-id="{{ $loan->loan_number }}" class="btn btn-primary pay-interest" title="Pay Interest">
    <i class='bx bx-rupee' style="color:red;"></i>
</a>


          </td>

        </tr>

        @endforeach

        </tbody>

    </table>
  </div>
</div>


</div>
<!-- Bootstrap CSS -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<!-- Popper.js for Bootstrap 4 -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.6.0/dist/umd/popper.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- Pay Interest Modal -->
<!-- Modal -->
<div class="modal fade" id="interestPaymentModal" tabindex="-1" role="dialog" aria-labelledby="interestPaymentModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="interestPaymentModalLabel">Pay Loan Interest</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="interestPaymentForm">
          <div class="form-group">
            <label for="loanNumber">Loan Number</label>
            <input type="text" class="form-control" id="loanNumber" name="loan_number" readonly>
          </div>

          <div class="form-group">
            <label>Choose Month to Pay Interest</label>
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>Month</th>
                  <th>Interest Due</th>
                  <th>Pay</th>
                </tr>
              </thead>
              <tbody>
                <!-- Dynamically generate rows for each month -->
                <tr>
                  <td>January</td>
                  <td>500</td>
                  <td>
                    <input type="radio" name="payment_month" value="1" required>
                  </td>
                </tr>
                <tr>
                  <td>February</td>
                  <td>500</td>
                  <td>
                    <input type="radio" name="payment_month" value="2">
                  </td>
                </tr>
                <!-- Add more months as needed -->
              </tbody>
            </table>
          </div>

          <div class="form-group">
            <label for="paymentAmount">Payment Amount</label>
            <input type="number" class="form-control" id="paymentAmount" name="payment_amount" required>
          </div>

          <div class="form-group">
            <label for="paymentType">Payment Method</label>
            <select class="form-control" id="paymentType" name="payment_method" required>
              <option value="cash">Cash</option>
              <option value="gpay">GPay</option>
              <option value="bank_transfer">Bank Transfer</option>
            </select>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save Payment</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<link rel="stylesheet" href="https://cdn.datatables.net/2.0.3/css/dataTables.dataTables.css">
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.0.3/js/dataTables.js"></script>


<script>
  new DataTable('#usersTable', {
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
  $(document).ready(function() {
    $('.btn-delete').on("click", function() {
      var $this = $(this);
      swal({
        title: "InActive?",
        text: "Please ensure and then confirm!",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, cancel!",
        reverseButtons: true
      }).then(function(e) {
        if (e.value) {
          var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
          var userId = $this.data('id');

          $.ajax({
            type: 'DELETE',
            url: '{{ route("customers.softDelete", "") }}/' + userId,
            data: {
              _token: CSRF_TOKEN
            },
            dataType: 'JSON',
            success: function(results) {
              if (results.success) {
                swal("Done!", results.message, "success");
                setTimeout(function() {
                  location.reload()
                }, 2000);
              } else {
                swal("Error!", results.message, "error");
              }
            },
            error: function(xhr) {
              console.log(xhr.responseText);
            }
          });
        }
      });
    });

  });


  $(document).ready(function() {
    $('.pay-interest').on('click', function() {
        var loanNumber = $(this).data('id'); // Get loan number from the clicked element

        // Populate loan number in the modal
        $('#loanNumber').val(loanNumber);

        // Open the modal
        $('#interestPaymentModal').modal('show');
    });

    $('#interestPaymentForm').on('submit', function(e) {
        e.preventDefault();

        // Add logic here to handle form submission, such as sending an AJAX request
        // to save the payment details to the backend.

        alert('Form submitted!');
        $('#interestPaymentModal').modal('hide');
    });
});
</script>


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.all.min.js"></script>

@endsection
