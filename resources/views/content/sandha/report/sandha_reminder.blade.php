@extends('layouts/contentNavbarLayout')

@section('title', 'Sandha Reminders')

@section('page-script')
<script src="{{asset('assets/js/form-basic-inputs.js')}}"></script>
@endsection
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
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

<div class="row">



    <!-- Form controls -->

    <div class="card">
        <h5 class="card-header" style="color:red;">Sandha Reminders</h5>

        <form method="GET" action="{{ url()->current() }}" class="mb-3">
            <div class="d-flex align-items-end gap-2 flex-wrap">
                <div>
                    <label for="district_id" class="form-label mb-1">District Incharge:</label>
                    <select name="r_name" id="r_name" class="form-control form-control-sm" style="width: 180px;">
                        <option value="">-- All Districts --</option>
                        @foreach ($ref_customers as $district)
                        <option value="{{ $district->id }}" {{ request('r_name') == $district->id ? 'selected' : '' }}>
                            {{ $district->first_name." ".$district->last_name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="district_id" class="form-label mb-1">District :</label>
                    <select name="district_id" id="district_id" class="form-control form-control-sm"
                        style="width: 180px;">
                        <option value="">-- All Districts --</option>
                        @foreach ($districts as $district)
                        <option value="{{ $district->id }}"
                            {{ request('district_id') == $district->id ? 'selected' : '' }}>
                            {{ $district->district_name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="d-block mb-1 invisible">Search</label>
                    <button type="submit" class="btn btn-sm btn-primary">Search</button>
                    <button type="button" class="btn btn-sm btn-danger" onclick="cancelAction()">Cancel</button>
                </div>
            </div>
        </form>

        <div class="table-responsive text-nowrap">

            <table class="display" id="usersTable">
                <thead>


                    <tr>
                        <th>CustomeID </th>
                        <th>Name</th>
                        <th>Sandha Plan</th>
                        <th>District</th>

                        <th>Join Date</th>
                        <th>Total issues</th>
                        <th>Sent issues</th>
                        <th>Remaining issues</th>
                    </tr>

                </thead>
                <tbody>
                    @foreach ($customers as $customer)
                    @if($customer->first_name !='')
                    <tr>
                        <td>{{ $customer->customer_id }}</td>
                        <td>{{ $customer->first_name. " ".$customer->last_name }}</td>
                        <td>{{ $customer->sandhaType->sandha_name ?? 'N/A' }}</td>
                        <td>{{ $customer->district->district_name ?? 'N/A' }}</td>

                        <td>{{ $customer->join_date }}</td>
                        <td>{{ $customer->total_copies }}</td>
                        <td>
                            <span style="color: green;font-weight:bold;">{{ $customer->sent_copies }} </span>
                        </td>
                        <td><span style="color: red;font-weight:bold;">{{ $customer->remaining_copies }}</span></td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>


</div>


<script>
new DataTable('#usersTable', {
    buttons: [
        'excel'
    ],
    layout: {
        topStart: 'buttons'
    },
    exportOptions: {
        columns: function(idx, data, node) {
            // Exclude columns with 'data-export="hidden"'
            return !$(node).attr('data-export');
        }
    }
});
</script>
<script>
function cancelAction() {
    // Optional: Refresh current page (not always necessary if redirecting)
    // location.reload();

    // Redirect to another page
    window.location.href = "{{ url('/sandhas_reminder')}}";
}
</script>



<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.all.min.js"></script>

@endsection
