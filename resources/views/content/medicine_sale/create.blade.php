@extends('layouts/contentNavbarLayout')
@section('title', 'Medicine Sale')
@section('content')
<div class="row">
    <div class="card-header d-flex justify-content-between align-items-center" style="padding-bottom: 20px;">
        <h5 style="color:red;">Medicine Sale</h5>
        <a href="{{ route('medicine-sale.index') }}" class="btn btn-secondary">Sale List</a>
    </div>
    <div class="card">
        <form id="medicineSaleForm" method="POST" action="{{ route('medicine-sale.store') }}">
            @csrf
            <div class="mb-3">
                <label for="patient" class="form-label">Patient</label>
<select class="form-control" id="patient" name="patient_id" required>
    <option value="">Select Patient</option>
    {{-- Will be dynamically populated --}}
</select>
<button type="button" class="btn btn-info btn-sm mt-2" id="searchPatientBtn">Search Patient</button>

<!-- Patient Search Modal -->
<div class="modal fade" id="patientSearchModal" tabindex="-1" aria-labelledby="patientSearchModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="patientSearchModalLabel">Search Patient</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="text" id="patientSearchInput" class="form-control mb-2" placeholder="Search by name, phone, etc...">
        <table class="table table-bordered" id="patientSearchTable">
          <thead><tr><th>ID</th><th>Name</th><th>Phone</th><th>Action</th></tr></thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>
</div>
            </div>
            <div class="mb-3">
                <label class="form-label">Medicines</label>
                <table class="table table-bordered" id="medicineTable">
                    <thead>
                        <tr>
                            <th>Medicine</th>
                            <th>Batch</th>
                            <th>Unit</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
<td>
    <select name="items[0][medicine_name]" class="form-control medicine-select" required>
        <option value="">Select Medicine</option>
        {{-- Will be dynamically populated --}}
    </select>
</td>
<td><input type="text" name="items[0][batch_number]" class="form-control batch-number" required readonly></td>
<td><input type="text" name="items[0][unit]" class="form-control unit" required readonly></td>
<td><input type="number" name="items[0][price]" class="form-control price" required readonly></td>
<td><input type="number" name="items[0][quantity]" class="form-control quantity" required min="1"></td>
<td><input type="number" name="items[0][subtotal]" class="form-control subtotal" readonly></td>
<td><button type="button" class="btn btn-danger btn-sm remove-row">Remove</button></td>
                        </tr>
                    </tbody>
                </table>
                <button type="button" class="btn btn-success btn-sm" id="addMedicineRow">Add Medicine</button>
            </div>
            <div class="mb-3">
                <label for="total_amount" class="form-label">Total Amount</label>
                <input type="number" class="form-control" id="total_amount" name="total_amount" readonly>
            </div>
            <div class="mb-3">
                <label for="discount" class="form-label">Discount</label>
                <input type="number" class="form-control" id="discount" name="discount" value="0">
            </div>
            <div class="mb-3">
                <label for="final_amount" class="form-label">Final Amount</label>
                <input type="number" class="form-control" id="final_amount" name="final_amount" readonly>
            </div>
            <div class="mb-3">
                <label for="notes" class="form-label">Notes</label>
                <textarea class="form-control" id="notes" name="notes"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Save & Print</button>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
// Real data from backend
window.patients = @json($patients ?? []);
window.medicines = @json($stockItems ?? []);

// Populate patient select
$(function() {
    $('#patient').empty().append('<option value="">Select Patient</option>');
    window.patients.forEach(function(p) {
        $('#patient').append(`<option value="${p.id}">${p.patient_name} (${p.phone_number})</option>`);
    });
    // Populate medicine select in first row
    let medSel = $('.medicine-select');
    medSel.empty().append('<option value="">Select Medicine</option>');
    // Group by name+unit+price+batch for unique dropdown
    let uniqueMeds = [];
    let seen = new Set();
    window.medicines.forEach(function(m) {
        let key = m.name + '|' + m.unit + '|' + m.price + '|' + m.batch_number;
        if (!seen.has(key)) {
            uniqueMeds.push(m);
            seen.add(key);
        }
    });
    uniqueMeds.forEach(function(m) {
        medSel.append(`<option value="${m.name}|${m.unit}|${m.price}|${m.batch_number}">${m.name} (${m.unit}) - ₹${m.price} [Batch: ${m.batch_number}]</option>`);
    });
});

// Patient search modal logic
$('#searchPatientBtn').on('click', function() {
    $('#patientSearchModal').modal('show');
    populatePatientTable('');
});
$('#patientSearchInput').on('input', function() {
    populatePatientTable($(this).val());
});
function populatePatientTable(query) {
    let rows = '';
    window.patients.filter(p => (p.patient_name && p.patient_name.toLowerCase().includes(query.toLowerCase())) || (p.phone_number && p.phone_number.includes(query))).forEach(function(p) {
        rows += `<tr><td>${p.id}</td><td>${p.patient_name}</td><td>${p.phone_number}</td><td><button type='button' class='btn btn-success btn-sm select-patient' data-id='${p.id}' data-name='${p.patient_name}'>Select</button></td></tr>`;
    });
    $('#patientSearchTable tbody').html(rows);
}
$(document).on('click', '.select-patient', function() {
    let id = $(this).data('id');
    $('#patient').val(id).trigger('change');
    $('#patientSearchModal').modal('hide');
});

// Add medicine row
let rowIdx = 1;
$('#addMedicineRow').on('click', function() {
    let row = `<tr>
        <td><select name="items[${rowIdx}][medicine_name]" class="form-control medicine-select" required><option value="">Select Medicine</option></select></td>
        <td><input type="text" name="items[${rowIdx}][batch_number]" class="form-control batch-number" required readonly></td>
        <td><input type="text" name="items[${rowIdx}][unit]" class="form-control unit" required readonly></td>
        <td><input type="number" name="items[${rowIdx}][price]" class="form-control price" required readonly></td>
        <td><input type="number" name="items[${rowIdx}][quantity]" class="form-control quantity" required min="1"></td>
        <td><input type="number" name="items[${rowIdx}][subtotal]" class="form-control subtotal" readonly></td>
        <td><button type="button" class="btn btn-danger btn-sm remove-row">Remove</button></td>
    </tr>`;
    $('#medicineTable tbody').append(row);
    let newSel = $('#medicineTable tbody tr:last .medicine-select');
    // Use uniqueMeds for new rows
    let uniqueMeds = [];
    let seen = new Set();
    window.medicines.forEach(function(m) {
        let key = m.name + '|' + m.unit + '|' + m.price + '|' + m.batch_number;
        if (!seen.has(key)) {
            uniqueMeds.push(m);
            seen.add(key);
        }
    });
    uniqueMeds.forEach(function(m) {
        newSel.append(`<option value="${m.name}|${m.unit}|${m.price}|${m.batch_number}">${m.name} (${m.unit}) - ₹${m.price} [Batch: ${m.batch_number}]</option>`);
    });
    rowIdx++;
});
// Remove row
$(document).on('click', '.remove-row', function() {
    $(this).closest('tr').remove();
    calculateTotals();
});
// On medicine select, fill batch/unit/price
$(document).on('change', '.medicine-select', function() {
    let val = $(this).val();
    let [name, unit, price, batch_number] = val.split('|');
    let med = window.medicines.find(m => m.name === name && m.unit === unit && m.price == price && m.batch_number === batch_number);
    let row = $(this).closest('tr');
    if (med) {
        row.find('.batch-number').val(med.batch_number);
        row.find('.unit').val(med.unit);
        row.find('.price').val(med.price);
    } else {
        row.find('.batch-number, .unit, .price').val('');
    }
    row.find('.quantity').val('');
    row.find('.subtotal').val('');
    calculateTotals();
});
// On quantity input, calculate subtotal
$(document).on('input', '.quantity', function() {
    let row = $(this).closest('tr');
    let price = parseFloat(row.find('.price').val()) || 0;
    let qty = parseInt($(this).val()) || 0;
    let subtotal = price * qty;
    row.find('.subtotal').val(subtotal);
    calculateTotals();
});
// On discount input, recalculate final amount
$('#discount').on('input', function() {
    calculateTotals();
});
function calculateTotals() {
    let total = 0;
    $('.subtotal').each(function() {
        total += parseFloat($(this).val()) || 0;
    });
    $('#total_amount').val(total);
    let discount = parseFloat($('#discount').val()) || 0;
    let finalAmount = total - discount;
    $('#final_amount').val(finalAmount > 0 ? finalAmount : 0);
}
</script>
@endsection
