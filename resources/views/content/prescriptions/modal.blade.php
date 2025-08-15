<!-- Prescription Modal -->
<div class="modal fade" id="prescriptionModal" tabindex="-1" aria-labelledby="prescriptionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="prescriptionModalLabel">Prescription</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="prescriptionForm">
        <div class="modal-body">
          <input type="hidden" name="appointment_id" id="prescription_appointment_id">
          <div class="mb-3">
            <label for="patient_name" class="form-label">Patient Name</label>
            <input type="text" class="form-control" id="patient_name" name="patient_name" required>
          </div>
          <div id="medicine-list">
            <div class="row mb-2 medicine-row">
              <div class="col-md-4">
                <input type="text" class="form-control" name="medicines[0][medicine]" placeholder="Medicine Name" required>
              </div>
              <div class="col-md-2">
                <input type="text" class="form-control" name="medicines[0][morning]" placeholder="Morning">
              </div>
              <div class="col-md-2">
                <input type="text" class="form-control" name="medicines[0][afternoon]" placeholder="Afternoon">
              </div>
              <div class="col-md-2">
                <input type="text" class="form-control" name="medicines[0][evening]" placeholder="Evening">
              </div>
              <div class="col-md-2">
                <input type="number" class="form-control" name="medicines[0][days]" placeholder="Days">
              </div>
            </div>
          </div>
          <button type="button" class="btn btn-sm btn-secondary" id="add-medicine">Add Medicine</button>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save Prescription</button>
        </div>
      </form>
    </div>
  </div>
</div>
<script>
$(function() {
  let medIndex = 1;
  $('#add-medicine').click(function() {
    let row = `<div class="row mb-2 medicine-row">
      <div class="col-md-4"><input type="text" class="form-control" name="medicines[${medIndex}][medicine]" placeholder="Medicine Name" required></div>
      <div class="col-md-2"><input type="text" class="form-control" name="medicines[${medIndex}][morning]" placeholder="Morning"></div>
      <div class="col-md-2"><input type="text" class="form-control" name="medicines[${medIndex}][afternoon]" placeholder="Afternoon"></div>
      <div class="col-md-2"><input type="text" class="form-control" name="medicines[${medIndex}][evening]" placeholder="Evening"></div>
      <div class="col-md-2"><input type="number" class="form-control" name="medicines[${medIndex}][days]" placeholder="Days"></div>
    </div>`;
    $('#medicine-list').append(row);
    medIndex++;
  });

  $('#prescriptionForm').submit(function(e) {
    e.preventDefault();
    let formData = $(this).serialize();
    $.post('/prescriptions/store', formData, function(res) {
      if(res.status === 'success') {
        swal('Saved!', 'Prescription saved successfully.', 'success');
        $('#prescriptionModal').modal('hide');
      } else {
        swal('Error', 'Could not save prescription.', 'error');
      }
    });
  });
});
</script>
