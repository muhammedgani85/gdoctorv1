@extends('layouts.app')
@section('content')
<div class="container">
  <h3>Prescription Details</h3>
  <div class="card mb-3">
    <div class="card-body">
      <h5>Patient: {{ $prescription->patient_name }}</h5>
      <p>Appointment ID: {{ $prescription->appointment_id }}</p>
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>Medicine</th>
            <th>Morning</th>
            <th>Afternoon</th>
            <th>Evening</th>
            <th>Days</th>
          </tr>
        </thead>
        <tbody>
          @foreach($prescription->items as $item)
          <tr>
            <td>{{ $item->medicine }}</td>
            <td>{{ $item->morning }}</td>
            <td>{{ $item->afternoon }}</td>
            <td>{{ $item->evening }}</td>
            <td>{{ $item->days }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
