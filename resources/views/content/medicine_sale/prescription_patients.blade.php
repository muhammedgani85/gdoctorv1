@extends('layouts/contentNavbarLayout')
@section('title', 'Today Patients with Prescription')
@section('content')
<div class="row">
    <div class="card-header d-flex justify-content-between align-items-center" style="padding-bottom: 20px;">
        <h5 style="color:red;">Today's Patients & Prescriptions</h5>
    </div>
    <div class="card">
        <table class="table table-bordered" id="todayPatientsTable">
            <thead>
                <tr>
                    <th>Token</th>
                    <th>Patient</th>
                    <th>Phone</th>
                    <th>Gender</th>
                    <th>Age</th>
                    <th>Doctor</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Prescription</th>
                </tr>
            </thead>
            <tbody>
                @foreach($appointments as $a)
                <tr>
                    <td>{{ $a->token_number }}</td>
                    <td>{{ $a->patient_name }}</td>
                    <td>{{ $a->phone_number }}</td>
                    <td>{{ $a->gender }}</td>
                    <td>{{ $a->age }}</td>
                    <td>{{ $a->doctor->name ?? '' }}</td>
                    <td>{{ $a->appointment_date }}</td>
                    <td>{{ $a->status }}</td>
                    <td>
                        @if($a->prescription)
                        <button class="btn btn-sm btn-primary view-prescription" data-id="{{ $a->id }}">View/Edit</button>
                        @else
                        <button class="btn btn-sm btn-success add-prescription" data-id="{{ $a->id }}">Add</button>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<!-- Modal for prescription details will be loaded here -->
<div id="prescriptionModalContainer"></div>
<script>
$(function() {
    // View/Edit prescription
    $(document).on('click', '.view-prescription, .add-prescription', function() {
        var appointmentId = $(this).data('id');
        $.get('/prescriptions/' + appointmentId, function(res) {
            // Render modal with prescription details and cost calculation
            // You can reuse your existing modal logic here
        });
    });
});
</script>
