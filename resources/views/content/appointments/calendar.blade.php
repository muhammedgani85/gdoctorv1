@extends('layouts/contentNavbarLayout')

@section('title', 'Doctor Appointment')

<!-- CSRF Token for AJAX -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Optional custom form JS -->
@section('page-script')
<script src="{{ asset('assets/js/form-basic-inputs.js') }}"></script>
@endsection

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

<!-- FullCalendar CSS -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/timegrid/main.min.css" rel="stylesheet" />

@section('content')


<style>
  /* Beautiful card for calendar */
  .calendar-card {
    background: linear-gradient(135deg, #f8fafc 0%, #e3f0ff 100%);
    border-radius: 18px;
    box-shadow: 0 4px 24px rgba(76, 175, 80, 0.08);
    padding: 2.5rem 2rem 2rem 2rem;
    margin-bottom: 2rem;
    border: none;
  }
  #calendar {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.04);
    padding: 1.5rem;
    min-height: 600px;
    width: 100%;
    overflow-x: auto;
  }
  .fc-toolbar-title {
    font-size: 2rem;
    font-weight: 700;
    color: #1976d2;
    letter-spacing: 0.5px;
  }
  .fc-button {
    border-radius: 6px !important;
    background: linear-gradient(90deg, #4A90E2 0%, #6FCF97 100%) !important;
    color: #fff !important;
    border: none !important;
    font-weight: 600;
    box-shadow: 0 2px 8px rgba(76, 175, 80, 0.07);
    transition: background 0.2s;
  }
  .fc-button-active, .fc-button:active, .fc-button:hover {
    background: linear-gradient(90deg, #1976d2 0%, #43a047 100%) !important;
    color: #fff !important;
  }
  .fc-event {
    background: linear-gradient(90deg, #43a047 0%, #4A90E2 100%) !important;
    color: #fff !important;
    border: none !important;
    border-radius: 8px !important;
    font-size: 10px !important;
    font-weight: 500;
    padding: 2px 8px !important;
    box-shadow: 0 2px 8px rgba(67, 160, 71, 0.10);
    opacity: 0.97;
    white-space: normal !important;
    word-break: break-word !important;
    overflow-wrap: break-word !important;
    line-height: 1.3;
    min-width: 0;
    max-width: 100%;
    display: block;
  }
  .fc-daygrid-event-dot {
    border-color: #43a047 !important;
  }
  .fc-day-today {
    background: #e3f0ff !important;
  }
  @media (max-width: 992px) {
    .calendar-card { padding: 0.5rem; }
    #calendar { padding: 0.2rem; min-height: 350px; }
    .fc-toolbar-title { font-size: 1.1rem; }
    .fc-event { font-size: 0.95rem; padding: 2px 4px !important; }
    .fc-daygrid-event-harness { font-size: 12px !important; min-width: 0; word-break: break-word; }
  }
  @media (max-width: 576px) {
    .calendar-card { padding: 0.2rem; }
    #calendar { padding: 0.1rem; min-height: 250px; }
    .fc-toolbar-title { font-size: 1rem; }
    .fc-event { font-size: 0.9rem; padding: 1px 2px !important; }
    .fc-daygrid-event-harness { font-size: 12px !important; min-width: 0; word-break: break-word; white-space: normal; }
  }
</style>

<div class="row">
  <div class="col-12">
    <div class="calendar-card">
      <h3 class="mb-4" style="font-weight:700; color:#1976d2; letter-spacing:0.5px;">Appointments Calendar</h3>
      <div id="calendar"></div>
    </div>
  </div>
</div>

<!-- FullCalendar JS -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/timegrid/index.global.min.js"></script>

<!-- Bootstrap JS (includes Popper for tooltips) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');

    const calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'timeGridWeek',
      height: 'auto',
      slotMinTime: '06:00:00',
      slotMaxTime: '23:00:00',
      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'timeGridDay,timeGridWeek,dayGridMonth'
      },
      events: '/appointments/calendar/data', // This should return a JSON array
      eventDidMount: function (info) {
        // Enable Bootstrap 5 tooltip
        new bootstrap.Tooltip(info.el, {
          title: info.event.title.replace(/\n/g, "<br>"),
          html: true,
          placement: 'top',
          trigger: 'hover'
        });
      }
    });

    calendar.render();
  });
</script>

@endsection
