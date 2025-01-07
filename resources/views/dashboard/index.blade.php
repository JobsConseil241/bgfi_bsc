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
{{--                                <p class="mb-2">Notre <span class="font-weight-bold">{{ Auth::user()->role }}</span></p>--}}
{{--                                <h4 class="text-primary mb-1">$48.9k</h4>--}}
{{--                                <a href="javascript:;" class="btn btn-primary">View Sales</a>--}}
                            </div>
                        </div>
                        <div class="col-5 text-center text-sm-left">
                            <div class="card-body pb-0 px-0 px-md-4">
                                <img
                                    src="{{url('public/assets/backend/img/illustrations/card-advance-sale.png')}}"
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
                            <div class="col-md-6 col-6">
                                <div class="d-flex align-items-center">
                                    <div class="badge rounded-pill bg-label-primary me-3 p-2">
                                        <i class="ti ti-chart-pie-2 ti-sm"></i>
                                    </div>
                                    <div class="card-info">
                                        <h5 class="mb-0">{{$no_faqs}}</h5>
                                        <small>FAQs Actifs</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-6">
                                <div class="d-flex align-items-center">
                                    <div class="badge rounded-pill bg-label-info me-3 p-2">
                                        <i class="ti ti-users ti-sm"></i>
                                    </div>
                                    <div class="card-info">
                                        <h5 class="mb-0">{{$no_agences}}</h5>
                                        <small>Agences</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-6 mt-4">
                                <div class="d-flex align-items-center">
                                    <div class="badge rounded-pill bg-label-danger me-3 p-2">
                                        <i class="ti ti-shopping-cart ti-sm"></i>
                                    </div>
                                    <div class="card-info">
                                        <h5 class="mb-0">{{$no_res_avis->count()}}</h5>
                                        <small>Reponses Pour les Avis</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-6  mt-4">
                                <div class="d-flex align-items-center">
                                    <div class="badge rounded-pill bg-label-success me-3 p-2">
                                        <i class="ti ti-currency-dollar ti-sm"></i>
                                    </div>
                                    <div class="card-info">
                                        <h5 class="mb-0">{{$no_res_recla->count()}}</h5>
                                        <small>Reponses Pour les Reclamation</small>
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
                    <!-- Generated Leads -->
{{--                    <div class="col-xl-12 mb-4 col-md-6">--}}
{{--                        <div class="card">--}}
{{--                            <div class="card-body">--}}
{{--                                <div class="">--}}
{{--                                    <div class="d-flex flex-column">--}}
{{--                                        <div class="card-title mb-auto">--}}
{{--                                            <h5 class="mb-1 text-nowrap">FAQ Le Plus aimé</h5>--}}
{{--                                            <small>Rapport Quotidien</small>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                    <div style="width: 100%; height: 200px">--}}
{{--                                        <canvas id="faqLikesChart" style=" margin-top: 30px" width="400" height="200"></canvas>--}}
{{--                                    </div>--}}
{{--                                    <script>--}}
{{--                                        const ctxe = document.getElementById('faqLikesChart').getContext('2d');--}}
{{--                                        const myChart = new Chart(ctxe, {--}}
{{--                                            type: 'bar',--}}
{{--                                            data: {--}}
{{--                                                labels: <?php echo json_encode($agencyNames); ?>, // Noms des agences--}}
{{--                                                datasets: [{--}}
{{--                                                    label: 'Nombre de likes par FAQ',--}}
{{--                                                    data: <?php echo json_encode($likes); ?>, // Nombre de likes--}}
{{--                                                    backgroundColor: 'rgba(13, 67, 122, 0.6)',--}}
{{--                                                    borderColor: 'rgba(13, 67, 122, 1)',--}}
{{--                                                    borderWidth: 1,--}}
{{--                                                    borderRadius: 10, // Arrondi des coins--}}
{{--                                                    borderSkipped: false // Appliquer l'arrondi sur tous les coins--}}
{{--                                                }]--}}
{{--                                            },--}}
{{--                                            options: {--}}
{{--                                                responsive: true,--}}
{{--                                                plugins: {--}}
{{--                                                    tooltip: {--}}
{{--                                                        callbacks: {--}}
{{--                                                            title: function(tooltipItems) {--}}
{{--                                                                return 'FAQ: ' + <?php echo json_encode($faqTitles); ?>[tooltipItems[0].dataIndex]; // Titre de la FAQ--}}
{{--                                                            },--}}
{{--                                                            label: function(tooltipItem) {--}}
{{--                                                                return 'Likes: ' + tooltipItem.raw;--}}
{{--                                                            }--}}
{{--                                                        }--}}
{{--                                                    },--}}
{{--                                                    legend: {--}}
{{--                                                        display: true,--}}
{{--                                                    },--}}
{{--                                                },--}}
{{--                                                scales: {--}}
{{--                                                    y: {--}}
{{--                                                        beginAtZero: true,--}}
{{--                                                    }--}}
{{--                                                }--}}
{{--                                            }--}}
{{--                                        });--}}
{{--                                    </script>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                    <!--/ Generated Leads -->

                    <!-- Generated Leads -->
                    <div class="col-xl-12 mb-4 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="">
                                    <div class="d-flex flex-column">
                                        <div class="card-title mb-auto">
                                            <h5 class="mb-1 text-nowrap">Formulaires Renseignés</h5>
                                            <small>Rapport Quotidien</small>
                                        </div>
                                    </div>
                                    <div style="width: 100%; height: 400px">
                                        <canvas id="chartCanvas" width="800" height="400"></canvas>
                                    </div>
                                    <script>
                                        // Données envoyées depuis le contrôleur
                                        const chartDatasds = @json($data_ad);

                                        // Transformation des données pour le graphique
                                        const labels = chartDatasds.map(item => `${item.type}`);
                                        const counts = chartDatasds.map(item => item.formulaire_count);

                                        // Couleurs dynamiques
                                        const colors = chartDatasds.map(() => `hsl(${Math.random() * 360}, 70%, 60%)`);

                                        // Initialisation du graphique
                                        const ctxded = document.getElementById('chartCanvas').getContext('2d');
                                        new Chart(ctxded, {
                                            type: 'pie',
                                            data: {
                                                labels: labels,
                                                datasets: [{
                                                    data: counts,
                                                    backgroundColor: colors,
                                                }]
                                            },
                                            options: {
                                                responsive: true,
                                                plugins: {
                                                    legend: {
                                                        position: 'top',
                                                    },
                                                },
                                            }
                                        });
                                    </script>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--/ Generated Leads -->

                    <div class="col-xl-12 col-lg-12 mb-4 order-2 order-xl-0">
                        <div class="card h-100">
                            <div class="card-header d-flex justify-content-between">
                                <div class="card-title mb-0">
                                    <h5 class="mb-0">Services actifs par agences</h5>
                                    <small class="text-muted">BGFI</small>
                                </div>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled mb-0">
                                    @foreach($agences as $dt)
                                        <li class="mb-4">
                                            <div class="d-flex align-items-start">
                                                <div class="badge bg-label-secondary p-2 me-3 rounded">
                                                    <i class="ti ti-shadow ti-sm"></i>
                                                </div>
                                                <div class="d-flex justify-content-between w-100 flex-wrap gap-2">
                                                    <div class="me-2">
                                                        <h6 class="mb-0">{{ $dt->libelle }}</h6>
                                                        <small class="text-muted">{{ strtolower($dt->libelle) }}</small>
                                                    </div>
                                                    <div class="d-flex align-items-center">
                                                        <p class="mb-0">
                                                            @php
                                                                $count = 0;
                                                                if ($dt->has_faq === 1) {
                                                                   $count ++;
                                                                } if ($dt->has_consult === 1) {
                                                                   $count ++;
                                                                } if ($dt->has_reclame === 1) {
                                                                   $count ++;
                                                                } if ($dt->has_avis === 1) {
                                                                   $count ++;
                                                                }
                                                            @endphp
                                                        </p>
                                                        <div class="ms-3 badge bg-label-success">{{ $count }} services </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Generated Leads -->
{{--                    <div class="col-xl-12 mb-4 col-md-6">--}}
{{--                        <div class="card">--}}
{{--                            <div class="card-body">--}}
{{--                                <div class="">--}}
{{--                                    <div class="d-flex flex-column">--}}
{{--                                        <div class="card-title mb-auto">--}}
{{--                                            <h5 class="mb-1 text-nowrap">FAQ Le Moins aimé</h5>--}}
{{--                                            <small>Rapport Quotidien</small>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                    <div style="width: 100%; height: 200px">--}}
{{--                                        <canvas id="faqDislikesChart" style=" margin-top: 30px" width="400" height="200"></canvas>--}}
{{--                                    </div>--}}
{{--                                    <script>--}}
{{--                                        const ctxes = document.getElementById('faqDislikesChart').getContext('2d');--}}
{{--                                        const myCharte = new Chart(ctxes, {--}}
{{--                                            type: 'bar',--}}
{{--                                            data: {--}}
{{--                                                labels: <?php echo json_encode($agencyName); ?>, // Noms des agences--}}
{{--                                                datasets: [{--}}
{{--                                                    label: 'Nombre de dislikes par FAQ',--}}
{{--                                                    data: <?php echo json_encode($lik); ?>, // Nombre de likes--}}
{{--                                                    backgroundColor: 'rgba(13, 67, 122, 0.6)',--}}
{{--                                                    borderColor: 'rgba(13, 67, 122, 1)',--}}
{{--                                                    borderWidth: 1,--}}
{{--                                                    borderRadius: 10, // Arrondi des coins--}}
{{--                                                    borderSkipped: false // Appliquer l'arrondi sur tous les coins--}}
{{--                                                }]--}}
{{--                                            },--}}
{{--                                            options: {--}}
{{--                                                responsive: true,--}}
{{--                                                plugins: {--}}
{{--                                                    tooltip: {--}}
{{--                                                        callbacks: {--}}
{{--                                                            title: function(tooltipItems) {--}}
{{--                                                                return 'FAQ: ' + <?php echo json_encode($faqTitle); ?>[tooltipItems[0].dataIndex]; // Titre de la FAQ--}}
{{--                                                            },--}}
{{--                                                            label: function(tooltipItem) {--}}
{{--                                                                return 'Likes: ' + tooltipItem.raw;--}}
{{--                                                            }--}}
{{--                                                        }--}}
{{--                                                    },--}}
{{--                                                    legend: {--}}
{{--                                                        display: true,--}}
{{--                                                    },--}}
{{--                                                },--}}
{{--                                                scales: {--}}
{{--                                                    y: {--}}
{{--                                                        beginAtZero: true,--}}
{{--                                                    }--}}
{{--                                                }--}}
{{--                                            }--}}
{{--                                        });--}}
{{--                                    </script>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                    <!--/ Generated Leads -->
                </div>
            </div>

            <!-- Revenue Report -->
            <div class="col-12 col-xl-8 mb-4">
                <div class="card">
                    <div class="card-body p-0">
                        <div class="row row-bordered g-0">
                            <div class="col-md-12 position-relative p-4">

                                <div class="">
                                    <label for="agence">Choisir une agence:</label>
                                    <select name="agence_id" id="agence" class="form-select-sm">
                                        @foreach($agences as $age)
                                            <option value="{{$age->id}}">{{$age->libelle}}</option>
                                        @endforeach
                                    </select>
                                </div>


                                <table class="datatables-basic table table-hover">
                                    <thead>
                                    <tr>
                                        <td>Agence</td>
                                        <th>FAQ Name</th>
                                        <th>FAQ No</th>
                                        <th>Total Views</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                            <div class="col-md-12 position-relative p-4">
                                <div class="card-header p-0 text-wrap">
                                    <h5 class="m-0 mb-4 card-title">Consultations Par agence</h5>
                                </div>
                                <div class="d-flex flex-row gap-3 mt-4" style="display: flex; flex-direction: row; gap: 10px; flex-wrap: wrap">
                                    @foreach($chartsData as $agencyName => $data)
                                        <div style="width: 280px; height: 300px">
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
            </div>
            <!--/ Revenue Report -->
        </div>
    </div>
    <!-- / Content -->
    @push('scripts')
        <script>

            $(function () {
                var dt_basic_table = $('.datatables-basic'),
                    dt_basic;

                // DataTable with buttons
                // --------------------------------------------------------------------

                if (dt_basic_table.length) {
                    dt_basic = dt_basic_table.DataTable({
                        // ajax: assetsPath + 'json/table-datatable.json',
                        data: @json($stats),
                        language: {
                            sProcessing: "Traitement en cours...",
                            sSearch: "Rechercher&nbsp;:",
                            sLengthMenu: "Afficher _MENU_ &eacute;l&eacute;ments",
                            sInfo: "Affichage de l'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
                            sInfoEmpty: "Affichage de l'&eacute;l&eacute;ment 0 &agrave; 0 sur 0 &eacute;l&eacute;ment",
                            sInfoFiltered: "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
                            sLoadingRecords: "Chargement en cours...",
                            sZeroRecords: "Aucun &eacute;l&eacute;ment &agrave; afficher",
                            sEmptyTable: "Aucune donn&eacute;e disponible dans le tableau",
                            oPaginate: {
                                sFirst: "Premier",
                                sPrevious: "Pr&eacute;c&eacute;dent",
                                sNext: "Suivant",
                                sLast: "Dernier"
                            },
                            oAria: {
                                sSortAscending: ": activer pour trier la colonne par ordre croissant",
                                sSortDescending: ": activer pour trier la colonne par ordre d&eacute;croissant"
                            }
                        },
                        columns: [
                            { data: 'agence_name' },
                            { data: 'faq_name' },
                            { data: 'faq_no' },
                            { data: 'total_views' }
                        ],
                        columnDefs: [
                            {
                                targets: [1,3],
                                searchable: true,
                                visible: true,
                                render: function (data, type, full, meta) {
                                    return (
                                        '<b>'+data+'</b>'
                                    );
                                }
                            },
                            {
                                targets: [0,2],
                                searchable: true,
                                visible: true,
                            },
                        ],
                        order: [[2, 'desc']],
                        dom: '<"card-header flex-column flex-md-row"<"head-label text-center"><"dt-action-buttons text-end pt-3 pt-md-0"B>><"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                        displayLength: 7,
                        lengthMenu: [7, 10, 25, 50, 75, 100],
                        buttons: [
                            {
                                extend: 'collection',
                                className: 'btn btn-label-primary dropdown-toggle me-2 waves-effect waves-light',
                                text: '<i class="ti ti-file-export me-sm-1"></i> <span class="d-none d-sm-inline-block">Exporter</span>',
                                buttons: [
                                    {
                                        extend: 'print',
                                        text: '<i class="ti ti-printer me-1" ></i>Imprimer',
                                        className: 'dropdown-item',
                                        exportOptions: {
                                            columns: [0,1,2,3],
                                            // prevent avatar to be display
                                            format: {
                                                body: function (inner, coldex, rowdex) {
                                                    if (inner.length <= 0) return inner;
                                                    var el = $.parseHTML(inner);
                                                    var result = '';
                                                    $.each(el, function (index, item) {
                                                        if (item.classList !== undefined && item.classList.contains('user-name')) {
                                                            result = result + item.lastChild.firstChild.textContent;
                                                        } else if (item.innerText === undefined) {
                                                            result = result + item.textContent;
                                                        } else result = result + item.innerText;
                                                    });
                                                    return result;
                                                }
                                            }
                                        },
                                        customize: function (win) {
                                            //customize print view for dark
                                            $(win.document.body)
                                                .css('color', config.colors.headingColor)
                                                .css('border-color', config.colors.borderColor)
                                                .css('background-color', config.colors.bodyBg);
                                            $(win.document.body)
                                                .find('table')
                                                .addClass('compact')
                                                .css('color', 'inherit')
                                                .css('border-color', 'inherit')
                                                .css('background-color', 'inherit');
                                        }
                                    },
                                    {
                                        extend: 'csv',
                                        text: '<i class="ti ti-file-text me-1" ></i>Csv',
                                        className: 'dropdown-item',
                                        exportOptions: {
                                            columns: [0,1,2,3],
                                            // prevent avatar to be display
                                            format: {
                                                body: function (inner, coldex, rowdex) {
                                                    if (inner.length <= 0) return inner;
                                                    var el = $.parseHTML(inner);
                                                    var result = '';
                                                    $.each(el, function (index, item) {
                                                        if (item.classList !== undefined && item.classList.contains('user-name')) {
                                                            result = result + item.lastChild.firstChild.textContent;
                                                        } else if (item.innerText === undefined) {
                                                            result = result + item.textContent;
                                                        } else result = result + item.innerText;
                                                    });
                                                    return result;
                                                }
                                            }
                                        }
                                    },
                                    {
                                        extend: 'excel',
                                        text: '<i class="ti ti-file-spreadsheet me-1"></i>Excel',
                                        className: 'dropdown-item',
                                        exportOptions: {
                                            columns: [0,1,2,3],
                                            // prevent avatar to be display
                                            format: {
                                                body: function (inner, coldex, rowdex) {
                                                    if (inner.length <= 0) return inner;
                                                    var el = $.parseHTML(inner);
                                                    var result = '';
                                                    $.each(el, function (index, item) {
                                                        if (item.classList !== undefined && item.classList.contains('user-name')) {
                                                            result = result + item.lastChild.firstChild.textContent;
                                                        } else if (item.innerText === undefined) {
                                                            result = result + item.textContent;
                                                        } else result = result + item.innerText;
                                                    });
                                                    return result;
                                                }
                                            }
                                        }
                                    },
                                    {
                                        extend: 'pdf',
                                        text: '<i class="ti ti-file-description me-1"></i>Pdf',
                                        className: 'dropdown-item',
                                        exportOptions: {
                                            columns: [0,1,2,3],
                                            // prevent avatar to be display
                                            format: {
                                                body: function (inner, coldex, rowdex) {
                                                    if (inner.length <= 0) return inner;
                                                    var el = $.parseHTML(inner);
                                                    var result = '';
                                                    $.each(el, function (index, item) {
                                                        if (item.classList !== undefined && item.classList.contains('user-name')) {
                                                            result = result + item.lastChild.firstChild.textContent;
                                                        } else if (item.innerText === undefined) {
                                                            result = result + item.textContent;
                                                        } else result = result + item.innerText;
                                                    });
                                                    return result;
                                                }
                                            }
                                        }
                                    },
                                    {
                                        extend: 'copy',
                                        text: '<i class="ti ti-copy me-1" ></i>Copy',
                                        className: 'dropdown-item',
                                        exportOptions: {
                                            columns: [0,1,2,3],
                                            // prevent avatar to be display
                                            format: {
                                                body: function (inner, coldex, rowdex) {
                                                    if (inner.length <= 0) return inner;
                                                    var el = $.parseHTML(inner);
                                                    var result = '';
                                                    $.each(el, function (index, item) {
                                                        if (item.classList !== undefined && item.classList.contains('user-name')) {
                                                            result = result + item.lastChild.firstChild.textContent;
                                                        } else if (item.innerText === undefined) {
                                                            result = result + item.textContent;
                                                        } else result = result + item.innerText;
                                                    });
                                                    return result;
                                                }
                                            }
                                        }
                                    }
                                ]
                            }
                        ],
                        responsive: {
                            details: {
                                display: $.fn.dataTable.Responsive.display.modal({
                                    header: function (row) {
                                        var data = row.data();
                                        return 'Details of ' + data['full_name'];
                                    }
                                }),
                                type: 'column',
                                renderer: function (api, rowIdx, columns) {
                                    var data = $.map(columns, function (col, i) {
                                        return col.title !== '' // ? Do not show row in modal popup if title is blank (for check box)
                                            ? '<tr data-dt-row="' +
                                            col.rowIndex +
                                            '" data-dt-column="' +
                                            col.columnIndex +
                                            '">' +
                                            '<td>' +
                                            col.title +
                                            ':' +
                                            '</td> ' +
                                            '<td>' +
                                            col.data +
                                            '</td>' +
                                            '</tr>'
                                            : '';
                                    }).join('');

                                    return data ? $('<table class="table"/><tbody />').append(data) : false;
                                }
                            }
                        }
                    });
                    $('div.head-label').html('<h5 class="card-title mb-0">Consultations Faqs par Agence</h5>');

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $('#agence').change(function () {

                        let val = $('#agence').val();

                        $.ajax({
                            url: "{{ route('marketingData') }}", // Laravel route
                            type: "GET",                     // HTTP method
                            data: {
                                key: val               // Query parameters
                            },
                            dataType: "json",              // Expected response format
                            success: function(data) {
                                // Update the page with the response
                                if(data.status){
                                    dt_basic.clear();
                                    dt_basic.rows.add(data.response);
                                    dt_basic.draw();
                                }
                            },
                            error: function(xhr) {
                                // Handle errors
                                $('#result').html('<p>Error occurred!</p>');
                                console.error(xhr.responseText);
                            }
                        });
                    });
                }
            })
        </script>
    @endpush
@endsection
