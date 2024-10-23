@extends('layouts.admin')

@section('content')
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <!-- View sales -->
            <div class="col-xl-4 mb-4 col-lg-5 col-12">
                <div class="card">
                    <div class="d-flex align-items-end row">
                        <div class="col-7">
                            <div class="card-body text-nowrap">
                                <h5 class="card-title mb-0">Bon retour {{ Auth::user()->name }}! 🎉</h5>
                                <p class="mb-2">Notre <span class="font-weight-bold">{{ Auth::user()->role }}</span></p>
{{--                                <h4 class="text-primary mb-1">$48.9k</h4>--}}
{{--                                <a href="javascript:;" class="btn btn-primary">View Sales</a>--}}
                            </div>
                        </div>
                        <div class="col-5 text-center text-sm-left">
                            <div class="card-body pb-0 px-0 px-md-4">
                                <img
                                    src="{{url('assets/backend/img/illustrations/card-advance-sale.png')}}"
                                    height="140"
                                    alt="view sales" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- View sales -->

            <!-- Statistics -->
            <div class="col-xl-8 mb-4 col-lg-7 col-12">
                <div class="card h-100">
                    <div class="card-header">
                        <div class="d-flex justify-content-between mb-3">
                            <h5 class="card-title mb-0">Statistiques</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row gy-3">
                            <div class="col-md-3 col-6">
                                <div class="d-flex align-items-center">
                                    <div class="badge rounded-pill bg-label-primary me-3 p-2">
                                        <i class="ti ti-chart-pie-2 ti-sm"></i>
                                    </div>
                                    <div class="card-info">
                                        <h5 class="mb-0">230k</h5>
                                        <small>Sales</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="d-flex align-items-center">
                                    <div class="badge rounded-pill bg-label-info me-3 p-2">
                                        <i class="ti ti-users ti-sm"></i>
                                    </div>
                                    <div class="card-info">
                                        <h5 class="mb-0">8.549k</h5>
                                        <small>Customers</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="d-flex align-items-center">
                                    <div class="badge rounded-pill bg-label-danger me-3 p-2">
                                        <i class="ti ti-shopping-cart ti-sm"></i>
                                    </div>
                                    <div class="card-info">
                                        <h5 class="mb-0">1.423k</h5>
                                        <small>Products</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="d-flex align-items-center">
                                    <div class="badge rounded-pill bg-label-success me-3 p-2">
                                        <i class="ti ti-currency-dollar ti-sm"></i>
                                    </div>
                                    <div class="card-info">
                                        <h5 class="mb-0">$9745</h5>
                                        <small>Revenue</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Statistics -->

            <div class="col-xl-4 col-12">
                <div class="row">
                    <!-- Expenses -->
                    <div class="col-xl-6 mb-4 col-md-3 col-6">
                        <div class="card">
                            <div class="card-header pb-0">
                                <h5 class="card-title mb-0">82.5k</h5>
                                <small class="text-muted">Expenses</small>
                            </div>
                            <div class="card-body">
                                <div id="expensesChart"></div>
                                <div class="mt-md-2 text-center mt-lg-3 mt-3">
                                    <small class="text-muted mt-3">$21k Expenses more than last month</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--/ Expenses -->

                    <!-- Profit last month -->
                    <div class="col-xl-6 mb-4 col-md-3 col-6">
                        <div class="card">
                            <div class="card-header pb-0">
                                <h5 class="card-title mb-0">Profit</h5>
                                <small class="text-muted">Last Month</small>
                            </div>
                            <div class="card-body">
                                <div id="profitLastMonth"></div>
                                <div class="d-flex justify-content-between align-items-center mt-3 gap-3">
                                    <h4 class="mb-0">624k</h4>
                                    <small class="text-success">+8.24%</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--/ Profit last month -->

                    <!-- Generated Leads -->
                    <div class="col-xl-12 mb-4 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div class="d-flex flex-column">
                                        <div class="card-title mb-auto">
                                            <h5 class="mb-1 text-nowrap">Generated Leads</h5>
                                            <small>Monthly Report</small>
                                        </div>
                                        <div class="chart-statistics">
                                            <h3 class="card-title mb-1">4,350</h3>
                                            <small class="text-success text-nowrap fw-medium"
                                            ><i class="ti ti-chevron-up me-1"></i> 15.8%</small
                                            >
                                        </div>
                                    </div>
                                    <div id="generatedLeadsChart"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--/ Generated Leads -->
                </div>
            </div>

            <!-- Revenue Report -->
            <div class="col-12 col-xl-8 mb-4">
                <div class="card">
                    <div class="card-body p-0">
                        <div class="row row-bordered g-0">
                            <div class="col-md-12 position-relative p-4">
                                <div class="card-header d-inline-block p-0 text-wrap position-absolute">
                                    <h5 class="m-0 card-title">Consultations</h5>
                                </div>
                                <div style="width: 100%; height: 300px">
                                    <canvas id="consultationChart" style=" margin-top: 30px"></canvas>
                                </div>
                            </div>
                            <div class="col-md-12 position-relative p-4">
                                <div class="card-header p-0 text-wrap">
                                    <h5 class="m-0 card-title">Consultations Par agence</h5>
                                </div>
                                @foreach($chartsData as $agencyName => $data)
                                    <div style="width: 300px; height: 300px">
                                        <div style="">
                                            <span>{{ $agencyName }}</span>
                                            <canvas id="chart-{{ Str::slug($agencyName) }}" ></canvas>
                                        </div>

                                        <script>
                                            let ctx{{ Str::slug($agencyName) }} = document.getElementById('chart-{{ Str::slug($agencyName) }}').getContext('2d');

                                            const data{{ Str::slug($agencyName) }} = {
                                                labels: @json(array_column($data, 'module')),
                                                datasets: [{
                                                    label: 'Consultations',
                                                    data: @json(array_column($data, 'total_visits')),
                                                    backgroundColor: [
                                                        '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40',
                                                        '#C9CBCF', '#FF6384', '#36A2EB', '#FFCE56'
                                                    ],
                                                }]
                                            };

                                            new Chart(ctx{{ Str::slug($agencyName) }}, {
                                                type: 'pie',
                                                data: data{{ Str::slug($agencyName) }},
                                                options: {
                                                    responsive: true,
                                                    plugins: {
                                                        legend: {
                                                            position: 'bottom',
                                                        },
                                                        tooltip: {
                                                            callbacks: {
                                                                label: function(tooltipItem) {
                                                                    let label = data{{ Str::slug($agencyName) }}.labels[tooltipItem.dataIndex] || '';
                                                                    let value = data{{ Str::slug($agencyName) }}.datasets[0].data[tooltipItem.dataIndex];
                                                                    return `${label}: ${value}`;
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            });
                                        </script>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Revenue Report -->
        </div>
    </div>
    <!-- / Content -->
    @push('scripts')
        <script>
            let ctex = document.getElementById('consultationChart').getContext('2d');
            const consultationChart = new Chart(ctex, {
                type: 'bar', // You can also use 'line', 'pie', 'doughnut', etc.
                data: {
                    labels: @json($modules), // Pass module names
                    datasets: [
                        {
                            label: 'Visites',
                            data: @json($visites), // Pass visits data
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Intéressé',
                            data: @json($interesses), // Pass interested data
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Pas Intéressé',
                            data: @json($pas_interesses), // Pass uninterested data
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

        </script>
    @endpush
@endsection
