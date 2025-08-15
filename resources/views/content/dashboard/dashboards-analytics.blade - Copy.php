@extends('layouts/contentNavbarLayout')

@section('title', 'Dashboard - Analytics')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/apex-charts/apex-charts.css')}}">
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/apex-charts/apexcharts.js')}}"></script>
@endsection

@section('page-script')
<script src="{{asset('assets/js/dashboards-analytics.js')}}"></script>
@endsection


<style>
.scroll-list {
    max-height: 250px;
    /* or however tall 7 items are */
    overflow-y: auto;
}
</style>
<!-- Add this to your Blade layout or inside a <style> tag -->
<style>
:root {
  --primary: #4A90E2;
  --secondary: #F5A623;
  --success: #6FCF97;
  --danger: #F76C6C;
  --info: #7ED6DF;
  --warning: #F9C74F;
  --muted: #6C757D;
  --dark: #2C3E50;

  --bg-card-1: #F9FAFB;
  --bg-card-2: #F0F8FF;
  --bg-card-3: #EEFBE7;
  --bg-card-4: #FFF5F7;
}

.card {
  background-color: var(--bg-card-1);
  border-radius: 12px;
  box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05);
}

.card h5,
.card h6,
.card-title,
.card-body {
  color: var(--dark);
}

.card .form-select {
  border-color: #dcdcdc;
  color: var(--dark);
}
</style>


@section('content')
<div class="row">
    <div class="col-lg-8 mb-4 order-0">
        <div class="card">
            <div class="d-flex align-items-end row">
                <div class="col-sm-7">
                    <div class="card-body">
                        <h5 class="card-title text-primary">Welcome GDoctor
                            <!-- {{ session('user_data')->first_name }} -->! 🎉
                        </h5>
                        <p class="mb-4"> {{ isset($movtive_quote)?$movtive_quote->quote:"" }} - <span
                                style="color:green;font-weight:bold;">{{ isset($movtive_quote)?$movtive_quote->author:"UnKnown" }}</span>
                        </p>

                        <!--  <a href="javascript:;" class="btn btn-sm btn-outline-primary">View Badges</a> -->
                    </div>
                </div>
                <div class="col-sm-5 text-center text-sm-left">
                    <div class="card-body pb-0 px-0 px-md-4">
                        <img src="{{asset('assets/img/illustrations/man-with-laptop-light.png')}}" height="140"
                            alt="View Badge User" data-app-dark-img="illustrations/man-with-laptop-dark.png"
                            data-app-light-img="illustrations/man-with-laptop-light.png">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-4 order-1">
        <div class="row">
            <div class="col-lg-6 col-md-12 col-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <img src="{{asset('assets/img/icons/unicons/chart-success.png')}}" alt="chart success"
                                    class="rounded">
                            </div>
                            <!-- <div class="dropdown">
                                <button class="btn p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt3">
                                    <a class="dropdown-item" href="javascript:void(0);">View More</a>
                                    <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                                </div>
                            </div> -->
                        </div>
                        <span class="fw-semibold d-block mb-1">Appoinment</span>
                        <h3 class="card-title mb-2" style="color:green;">{{ count($total_appoinment) }}</h3>
                        <!-- <small class="text-success fw-semibold"><i class='bx bx-up-arrow-alt'></i> +72.80%</small> -->
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12 col-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <img src="{{asset('assets/img/icons/unicons/wallet-info.png')}}" alt="Credit Card"
                                    class="rounded">
                            </div>
                            <!-- <div class="dropdown">
                                <button class="btn p-0" type="button" id="cardOpt6" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt6">
                                    <a class="dropdown-item" href="javascript:void(0);">View More</a>
                                    <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                                </div>
                            </div> -->
                        </div>
                        <span>Tomorrow</span>
                        <h3 class="card-title text-nowrap mb-1" style="color:green;">{{ count($tomorrow_appointment) }}
                        </h3>
                        <!-- <small class="text-success fw-medium"><i class='bx bx-up-arrow-alt'></i> +28.42%</small> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Total Revenue -->
    <div class="col-12 col-lg-8 order-2 order-md-3 order-lg-2 mb-4" style="display: none;">
        <div class="card">
            <div class="row row-bordered g-0">
                <div class="col-md-8">
                    <h5 class="card-header m-0 me-2 pb-3">Total Revenue</h5>
                    <div id="totalRevenueChart" class="px-2"></div>
                </div>
                <div class="col-md-4">
                    <div class="card-body">
                        <div class="text-center">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button"
                                    id="growthReportId" data-bs-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">
                                    2022
                                </button>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="growthReportId">
                                    <a class="dropdown-item" href="javascript:void(0);">2021</a>
                                    <a class="dropdown-item" href="javascript:void(0);">2020</a>
                                    <a class="dropdown-item" href="javascript:void(0);">2019</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="growthChart"></div>
                    <div class="text-center fw-medium pt-3 mb-2">62% Company Growth</div>

                    <div class="d-flex px-xxl-4 px-lg-2 p-4 gap-xxl-3 gap-lg-1 gap-3 justify-content-between">
                        <div class="d-flex">
                            <div class="me-2">
                                <span class="badge bg-label-primary p-2"><i
                                        class="bx bx-dollar text-primary"></i></span>
                            </div>
                            <div class="d-flex flex-column">
                                <small>2022</small>
                                <h6 class="mb-0">$32.5k</h6>
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="me-2">
                                <span class="badge bg-label-info p-2"><i class="bx bx-wallet text-info"></i></span>
                            </div>
                            <div class="d-flex flex-column">
                                <small>2021</small>
                                <h6 class="mb-0">$41.2k</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/ Total Revenue -->

</div>
<div class="row">
    <!-- Order Statistics -->
    <div class="col-md-6 col-lg-4 col-xl-8 order-0 mb-4">
        <div class="card h-100" style="background-color: var(--bg-card-1);">
            <div class="card-header d-flex align-items-center justify-content-between pb-0">
                <div class="card-title mb-0">

                    <h5 class="m-0 me-2"> Appoinment Stats</h5>

                </div>
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <select id="chartFilter" class="form-select">
            <option value="day">Day </option>
            <option value="month">Month </option>
            <option value="year">Year </option>
        </select>
    </div>
</div>
            </div>
            <div class="card-body" >
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <!-- <div class="d-flex flex-column align-items-center gap-1">
                        <h2 class="mb-2">8,258</h2>
                        <span>Total Orders</span>
                    </div>
                    <div id="orderStatisticsChart"></div> -->


<canvas id="appointmentChart" style="height: 300px;"></canvas>
                </div>

            </div>
        </div>
    </div>
    <!--/ Order Statistics -->



    <!-- Transactions -->
    <div class="col-md-6 col-lg-4 order-2 mb-4" >
        <div class="card h-100" style="background-color: var(--bg-card-3);">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title m-0 me-2">Recent Stats</h5>
<form id="filterForm">
   <div class="d-flex gap-2">
    <select id="month" name="month" required class="form-select w-auto">
        <option value="">Select Month</option>
        @for ($i = 1; $i <= 12; $i++)
            <option value="{{ $i }}"
                {{ (request()->input('month', now()->month) == $i) ? 'selected' : '' }}>
                {{ \Carbon\Carbon::create()->month($i)->format('F') }}
            </option>
        @endfor
    </select>

    <select id="year" name="year" class="form-select w-auto">
        @for ($y = now()->year; $y >= 2020; $y--)
            <option value="{{ $y }}"
                {{ (request()->input('year', now()->year) == $y) ? 'selected' : '' }}>
                {{ $y }}
            </option>
        @endfor
    </select>
</div>
</form>

            </div>

            <div class="card-body">
              <p id="noDataMessage" style="display:none; text-align: center; color: red;font-weight:bold;">No history available</p>
                <canvas id="donutChart" height="200"></canvas>
            </div>
        </div>
    </div>
    <!--/ Transactions -->
</div>


<!--  Expens Block -->

<div class="row">
    <!-- Order Statistics -->
    <div class="col-md-6 col-lg-4 col-xl-4 order-0 mb-4" style="height:420px;" >
        <div class="card h-100" style="background-color: #F0F8FF;">
            <div class="card-header d-flex align-items-center justify-content-between pb-0">
                <div class="card-title mb-0">

                    <h5 class="m-0 me-2"> Exp Stats</h5>

                </div>
                <form id="filterFormexp">
<div class="d-flex gap-2">
    <select id="emonth" name="emonth" required class="form-select w-auto">
        <option value="">Select Month</option>
        @for ($i = 1; $i <= 12; $i++)
            <option value="{{ $i }}"
                {{ (request()->input('month', now()->month) == $i) ? 'selected' : '' }}>
                {{ \Carbon\Carbon::create()->month($i)->format('F') }}
            </option>
        @endfor
    </select>

    <select id="eyear" name="eyear" class="form-select w-auto">
        @for ($y = now()->year; $y >= 2020; $y--)
            <option value="{{ $y }}"
                {{ (request()->input('year', now()->year) == $y) ? 'selected' : '' }}>
                {{ $y }}
            </option>
        @endfor
    </select>
</div>
</form>
            </div>
            <div class="card-body" >
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <!-- <div class="d-flex flex-column align-items-center gap-1">
                        <h2 class="mb-2">8,258</h2>
                        <span>Total Orders</span>
                    </div>
                    <div id="orderStatisticsChart"></div> -->


<canvas id="expenseChart" width="400" height="100"></canvas>
                </div>

            </div>
        </div>
    </div>
    <!--/ Order Statistics -->



    <!-- Transactions -->
    <div class="col-md-6 col-lg-4 order-2 mb-4" >
        <div class="card h-100" style="background-color: #EEFBE7">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title m-0 me-2">Recent Stats</h5>
<form id="filterForm1">
   <div class="d-flex gap-2">
    <select id="month" name="month" required class="form-select w-auto">
        <option value="">Select Month</option>
        @for ($i = 1; $i <= 12; $i++)
            <option value="{{ $i }}"
                {{ (request()->input('month', now()->month) == $i) ? 'selected' : '' }}>
                {{ \Carbon\Carbon::create()->month($i)->format('F') }}
            </option>
        @endfor
    </select>

    <select id="year" name="year" class="form-select w-auto">
        @for ($y = now()->year; $y >= 2020; $y--)
            <option value="{{ $y }}"
                {{ (request()->input('year', now()->year) == $y) ? 'selected' : '' }}>
                {{ $y }}
            </option>
        @endfor
    </select>
</div>
</form>

            </div>

            <div class="card-body">
              <p id="noDataMessage" style="display:none; text-align: center; color: red;font-weight:bold;">No history available</p>
                <!-- <canvas id="donutChart" height="200"></canvas> -->
            </div>
        </div>
    </div>
    <!--/ Transactions -->
</div>
<!-- New Block End -->




@endsection
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    let appointmentChart;
    const ctx = document.getElementById('appointmentChart').getContext('2d');

    function loadChart(filter = 'day') {
        fetch(`/appointment-chart-data?filter=${filter}`)
            .then(res => res.json())
            .then(data => {
                if (appointmentChart) {
                    appointmentChart.destroy();
                }

               appointmentChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: data.labels,
        datasets: [{
            label: 'Appointments',
            data: data.data,
            backgroundColor: '#5DA5DA',
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            x: {
                grid: {
                    display: false  // 🔴 hide X-axis grid lines
                }
            },
            y: {
                beginAtZero: true,
                grid: {
                    display: false  // 🔴 hide Y-axis grid lines
                }
            }
        }
    }
});
            });
    }

    document.getElementById('chartFilter').addEventListener('change', function () {
        loadChart(this.value);
    });

    loadChart(); // initial
});
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const ctx = document.getElementById('donutChart').getContext('2d');
    const chartCanvas = document.getElementById('donutChart');
    const noDataMessage = document.getElementById('noDataMessage');
    let donutChart = null;

    async function loadChartData(month = null) {
        const now = new Date();
        const selectedMonth = month ?? (now.getMonth() + 1);
        const year = now.getFullYear();

        try {
            const response = await fetch(`appointments/illness-chart?month=${selectedMonth}&year=${year}`);
            const data = await response.json();

            // Check for empty data
            if (!data || !data.labels || data.labels.length === 0) {
                chartCanvas.style.display = "none";
                noDataMessage.style.display = "block";
                if (donutChart) donutChart.destroy();
                return;
            }

            // Hide "No data" and show chart
            chartCanvas.style.display = "block";
            noDataMessage.style.display = "none";

            if (donutChart) donutChart.destroy();

            donutChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Illness Distribution (%)',
                        data: data.percentages,
                        backgroundColor: [
                            '#4A90E2', '#F5A623', '#7ED6DF', '#2C3E50',
                            '#B2912F', '#B276B2', '#DECF3F', '#F15854'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'right' },
                        tooltip: {
                            callbacks: {
                                label: ctx => `${ctx.label}: ${ctx.raw}%`
                            }
                        }
                    }
                }
            });

        } catch (error) {
            console.error("Error fetching chart data:", error);
            chartCanvas.style.display = "none";
            noDataMessage.textContent = "Error loading data.";
            noDataMessage.style.display = "block";
        }
    }

    // Load initial chart
    const monthSelect = document.getElementById('month');
    loadChartData(monthSelect.value);

    // Reload on month change
    monthSelect.addEventListener('change', () => {
        loadChartData(monthSelect.value);
    });
});
</script>

<!-- Expenses Chart -->


 <script>
    document.addEventListener("DOMContentLoaded", function () {
        const ctx = document.getElementById('expenseChart')?.getContext('2d');
        let expenseChart;

        function fetchChartData(year = '', month = '') {
            const url = `{{ route('expenses.chart.data') }}?year=${year}&month=${month}`;

            fetch(url)
                .then(response => response.text())
                .then(text => {
                    try {
                        const data = JSON.parse(text);
                        const labels = data.map(item => item.name);
                        const totals = data.map(item => item.total);

                        const backgroundColors = [
                            '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0',
                            '#9966FF', '#FF9F40', '#F7464A', '#46BFBD'
                        ];

                        if (expenseChart) expenseChart.destroy();

                        expenseChart = new Chart(ctx, {
                            type: 'doughnut',
                            data: {
                                labels: labels,
                                datasets: [{
                                    label: 'Expenses',
                                    data: totals,
                                    backgroundColor: backgroundColors
                                }]
                            },
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: {
                                        position: 'right'
                                    }
                                }
                            }
                        });
                    } catch (e) {
                        console.error("JSON Parse Error:", e.message);
                        console.log("Server Response:", text);
                    }
                })
                .catch(err => console.error("Fetch Error:", err));
        }

        /* document.getElementById('filterFormexp').addEventListener('submit', function (e) {
            e.preventDefault();
            const year = document.getElementById('eyear').value;
            const month = document.getElementById('emonth').value;
            fetchChartData(year, month);
        }); */
    const yearSelect = document.getElementById('eyear');
    const monthSelect = document.getElementById('emonth');
    fetchChartData(yearSelect.value,monthSelect.value);

    // Reload on month change
    monthSelect.addEventListener('change', () => {
        fetchChartData(yearSelect.value,monthSelect.value);
    });

     yearSelect.addEventListener('change', () => {
        fetchChartData(yearSelect.value,monthSelect.value);
    });

       // fetchChartData(); // Load on page load
    });
    </script>
</body>
