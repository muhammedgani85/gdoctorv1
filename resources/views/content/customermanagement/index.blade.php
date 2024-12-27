@extends('layouts/contentNavbarLayout')

@section('title', 'Customer Management')

@section('page-script')
<script src="{{asset('assets/js/form-basic-inputs.js')}}"></script>
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
  .text-left {
    text-align: left;
  }

  .text-success {
    color: green;
  }

  .text-warning {
    color: purple;
  }

  .text-danger {
    color: red;
  }


</style>
<!-- Include other styles here -->
@section('content')
<h4 class="py-0 mb-4">
  <span class="text-muted fw-light" style="color:red !important;">Customer(s)</span>
</h4>

<div class="row">
  <div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
      <div class="card" style="background-color: #FED8B1;color:#000;font-weight:bold;">
        <div class="card-body">
          <div class="d-flex align-items-start justify-content-between">
            <div class="content-left">
              <span>Customers (Total)</span>
              <div class="d-flex align-items-end mt-2">
                <h4 class="mb-0 me-2" style="color:#000;font-weight:bold;">{{ $total =  $customers->count();  }}</h4>
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
                <h4 class="mb-0 me-2" style="color:#000;font-weight:bold;">{{ $todayCustomers;  }}</h4>
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
                <h4 class="mb-0 me-2" style="color:#000;font-weight:bold;">{{ $weekCustomers;  }}</h4>
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
                <h4 class="mb-0 me-2" style="color:#000;font-weight:bold;">{{ $monthCustomers;  }}</h4>
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

      <table class="table table-bordered" style="margin-bottom: 20px;" id="usersTable">
        <thead style="background-color: #aed6f1;">
          <tr>
            <th
            style='width:10% !important;'>S.No</th>
            <th>Photo</th>
            <th>Cust.ID</th>
            <th>Name</th>
            <th>Phone Number</th>
            <th>City</th>
            <!-- <th>Aadhar Number</th> -->
           <!--  <th>Location</th> -->
            <th>C.Date</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>

          @foreach ($customers as $user)
          <tr>
            <td  style='width:10% !important;'>{{ $loop->iteration }}</td>

            <td>
            <img
    src="{{ $user->customer_photo ? asset('storage/' . $user->customer_photo) : asset('assets/images/sj_logo.png') }}"
    alt="Image"
    style="width:50px; height:50px; border-radius:50%;"
>
          </td>
            <td class="text-left">{{ $user->customer_id  }}</td>
            <td class="text-left">{{ $user->initial }} {{ $user->first_name }} {{ $user->last_name }} </td>

            <td class="text-left">{{ $user->phone_number }}</td>
            <td class="text-left">{{ $user->city }}</td>

            <td class="text-left">{{ date('d-m-Y',strtotime($user->created_at)) }}</td>
            <td class="text-left {{ $user->status === 'Active' ? 'text-success' : ($user->status === 'Inactive' ? 'text-warning' : 'text-danger') }}">
              {{ $user->status }}
            </td>

            <td class="text-left">
              <div class="dropdown">
                <a href="javascript:void(0);" data-id="{{ $user->id }}" class="btn-delete" title="Inactive"><i class="bx bx-trash me-1" style=' color:red;'></i></a>
                <a href="{{ route('customers.edit', $user->id) }}" title="edit"><i class="bx bx-pencil me-1"></i></a>
                <a href="javascript:void(0);" title="details"><i class='bx bxs-detail'></i></a>

              </div>
    </div>
    </td>
    </tr>
    @endforeach
    </tbody>
    </table>
  </div>
</div>


</div>
<script>
new DataTable('#usersTable', {

    lengthMenu: [10, 25, 50, 100],
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
</script>


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.all.min.js"></script>

@endsection
