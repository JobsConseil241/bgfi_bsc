@extends('layouts.admin')

@section('content')
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('indexDashboard') }}">Accueil</a>
                    </li>
{{--                    <li class="breadcrumb-item">--}}
{{--                        <a href="javascript:void(0);">Library</a>--}}
{{--                    </li>--}}
                    <li class="breadcrumb-item active">Agences</li>
                </ol>
            </nav>
            <!-- DataTable with Buttons -->
            <div class="card">
                <div class="card-datatable table-responsive pt-0">
                    @if(Session::has('message'))
                        <div class="alert alert-{{Session::get('status')}} mt-4" role="alert">
                            {{Session::get('message')}}
                        </div>
                    @endif
                    <table class="datatables-basic table table-hover">
                        <thead>
                        <tr>
                            <th></th>
                            <th></th>
                            <th>id</th>
                            <th>libelle</th>
                            <th>FAQ</th>
                            <th>Consultation</th>
                            <th>Reclamation</th>
                            <th>Avis</th>
                            <th>delais</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <!-- Modal to add new record -->
            <div class="offcanvas offcanvas-end" id="add-new-record">
                <div class="offcanvas-header border-bottom">
                    <h5 class="offcanvas-title" id="exampleModalLabel">Nouvelle Agence</h5>
                    <button
                        type="button"
                        class="btn-close text-reset"
                        data-bs-dismiss="offcanvas"
                        aria-label="Close"></button>
                </div>

                <div class="offcanvas-body flex-grow-1">
                    <form class="add-new-record pt-0 row g-2" id="form-add-new-record" method="post" action="{{ route('AddAgences') }}">
                        @csrf
                        <div class="col-sm-12">
                            <label class="form-label" for="nom">Libelle</label>
                            <div class="input-group input-group-merge">
                                <input
                                    type="text"
                                    id="nom"
                                    class="form-control dt-full-nom"
                                    name="nom"
                                    placeholder="venus"
                                    required
                                    aria-label="venus"
                                    aria-describedby="nom" />
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <label class="form-label" for="delay">Delais d'attente</label>
                            <div class="input-group input-group-merge">
                                <input
                                    type="number"
                                    id="delay"
                                    name="delay"
                                    required
                                    class="form-control dt-delay"
                                    placeholder="100"
                                    aria-label="100"
                                    aria-describedby="delay" />
                            </div>
                            <div class="form-text">le délais d'attente sur l'interface d'accueil</div>
                        </div>
                        <div class="col-sm-12 pt-1">
                            <small class="text-dark fw-medium d-block">Faq</small>
                            <div class="form-check form-check-inline mt-1">
                                <input class="form-check-input dt-full-faq" type="radio" name="faq" id="faq_oui" value="1">
                                <label class="form-check-label" for="faq_oui">Oui</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input dt-full-faq" type="radio" name="faq" id="faq_non" value="0" checked>
                                <label class="form-check-label" for="faq_non">Non</label>
                            </div>
                            <div class="form-text">Activer le module FAQ sur le Corner ?</div>
                        </div>
                        <div class="col-sm-12 pt-1">
                            <small class="text-dark fw-medium d-block">Consultation</small>
                            <div class="form-check form-check-inline mt-1">
                                <input class="form-check-input dt-full-cons" type="radio" name="consult" id="cons_oui" value="1">
                                <label class="form-check-label" for="cons_oui">Oui</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input dt-full-cons" type="radio" name="consult" id="cons_non" value="0" checked>
                                <label class="form-check-label" for="cons_non">Non</label>
                            </div>
                            <div class="form-text">Activer le module Consultation sur le Corner ?</div>
                        </div>
                        <div class="col-sm-12 pt-1">
                            <small class="text-dark fw-medium d-block">Reclamation</small>
                            <div class="form-check form-check-inline mt-1">
                                <input class="form-check-input dt-full-recla" type="radio" name="recla" id="recla_oui" value="1">
                                <label class="form-check-label" for="recla_oui">Oui</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input dt-full-recla" type="radio" name="recla" id="recla_non" value="0" checked>
                                <label class="form-check-label" for="recla_non">Non</label>
                            </div>
                            <div class="form-text">Activer le module Reclamation sur le Corner ?</div>
                        </div>
                        <div class="col-sm-12 pt-1">
                            <small class="text-dark fw-medium d-block">Avis</small>
                            <div class="form-check form-check-inline mt-1">
                                <input class="form-check-input dt-full-avis" type="radio" name="avis" id="avis_oui" value="1">
                                <label class="form-check-label" for="avis_oui">Oui</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input dt-full-avis" type="radio" name="avis" id="avis_non" value="0" checked>
                                <label class="form-check-label" for="avis_non">Non</label>
                            </div>
                            <div class="form-text">Activer le module Avis sur le Corner ?</div>
                        </div>
                        <div class="col-sm-12 mt-2">
                            <button type="submit" class="btn btn-primary data-submit me-sm-3 me-1">Submit</button>
                            <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="offcanvas">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
            <!--/ DataTable with Buttons -->


            <!-- Modal -->
            <div class="modal fade" id="EditModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalCenterTitle">Edit Agence</h5>
                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <form class="add-new-record pt-0 row g-2" id="editForm" method="post" action="{{ route('ModifyAgences') }}">
                            @csrf
                            <div class="modal-body">
                                    <div class="col-sm-12">
                                        <label class="form-label" for="nome">Libelle</label>
                                        <div class="input-group input-group-merge">
                                            <input
                                                type="text"
                                                id="nome"
                                                class="form-control dt-full-nom"
                                                name="nome"
                                                placeholder="venus"
                                                required
                                                aria-label="venus"
                                                aria-describedby="nom" />
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <label class="form-label" for="delaye">Delais d'attente</label>
                                        <div class="input-group input-group-merge">
                                            <input
                                                type="number"
                                                id="delaye"
                                                name="delaye"
                                                required
                                                class="form-control dt-delay"
                                                placeholder="100"
                                                aria-label="100"
                                                aria-describedby="delay" />

                                        </div>
                                        <input
                                            type="number"
                                            id="id"
                                            name="id"
                                            class="form-control dt-delay"
                                            placeholder="100"
                                            aria-label="100"
                                            aria-describedby="delay" style="display: none" />
                                        <div class="form-text">le délais d'attente sur l'interface d'accueil</div>
                                    </div>
                                    <div class="col-sm-12 pt-1">
                                        <small class="text-dark fw-medium d-block">Faq</small>
                                        <div class="form-check form-check-inline mt-1">
                                            <input class="form-check-input dt-full-faq" type="radio" name="faqe" id="faq_ouie" value="1">
                                            <label class="form-check-label" for="faq_oui">Oui</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input dt-full-faq" type="radio" name="faqe" id="faq_none" value="0" checked>
                                            <label class="form-check-label" for="faq_non">Non</label>
                                        </div>
                                        <div class="form-text">Activer le module FAQ sur le Corner ?</div>
                                    </div>
                                    <div class="col-sm-12 pt-1">
                                        <small class="text-dark fw-medium d-block">Consultation</small>
                                        <div class="form-check form-check-inline mt-1">
                                            <input class="form-check-input dt-full-cons" type="radio" name="consulte" id="cons_ouie" value="1">
                                            <label class="form-check-label" for="cons_oui">Oui</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input dt-full-cons" type="radio" name="consulte" id="cons_none" value="0" checked>
                                            <label class="form-check-label" for="cons_non">Non</label>
                                        </div>
                                        <div class="form-text">Activer le module Consultation sur le Corner ?</div>
                                    </div>
                                    <div class="col-sm-12 pt-1">
                                        <small class="text-dark fw-medium d-block">Reclamation</small>
                                        <div class="form-check form-check-inline mt-1">
                                            <input class="form-check-input dt-full-recla" type="radio" name="reclae" id="recla_ouie" value="1">
                                            <label class="form-check-label" for="recla_oui">Oui</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input dt-full-recla" type="radio" name="reclae" id="recla_none" value="0" checked>
                                            <label class="form-check-label" for="recla_non">Non</label>
                                        </div>
                                        <div class="form-text">Activer le module Reclamation sur le Corner ?</div>
                                    </div>
                                    <div class="col-sm-12 pt-1">
                                        <small class="text-dark fw-medium d-block">Avis</small>
                                        <div class="form-check form-check-inline mt-1">
                                            <input class="form-check-input dt-full-avis" type="radio" name="avise" id="avis_ouie" value="1">
                                            <label class="form-check-label" for="avis_oui">Oui</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input dt-full-avis" type="radio" name="avise" id="avis_none" value="0" checked>
                                            <label class="form-check-label" for="avis_non">Non</label>
                                        </div>
                                        <div class="form-text">Activer le module Avis sur le Corner ?</div>
                                    </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                                    Fermer
                                </button>
                                <button  type="submit" class="btn btn-primary">Enregistrer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- / Content -->
    @push('scripts')
        <script>

            /**
             * DataTables Basic
             */
            let agences = {!! json_encode($data, JSON_UNESCAPED_SLASHES ); !!};

            'use strict';

            let fv, offCanvasEl;
            document.addEventListener('DOMContentLoaded', function (e) {
                (function () {
                    const formAddNewRecord = document.getElementById('form-add-new-record');

                    setTimeout(() => {
                        const newRecord = document.querySelector('.create-new'),
                            offCanvasElement = document.querySelector('#add-new-record');

                        // To open offCanvas, to add new record
                        if (newRecord) {
                            newRecord.addEventListener('click', function () {
                                offCanvasEl = new bootstrap.Offcanvas(offCanvasElement);
                                // Empty fields on offCanvas open
                                // (offCanvasElement.querySelector('.dt-full-nom').value = ''),
                                // (offCanvasElement.querySelector('.dt-delay').value = ''),
                                // (offCanvasElement.querySelector('.dt-full-faq').value = ''),
                                // (offCanvasElement.querySelector('.dt-full-cons').value = ''),
                                // (offCanvasElement.querySelector('.dt-full-recla').value = ''),
                                // (offCanvasElement.querySelector('.dt-full-avis').value = ''),
                                // Open offCanvas with form
                                offCanvasEl.show();
                            });
                        }
                    }, 200);

                })();
            });

            // datatable (jquery)
            $(function () {
                var dt_basic_table = $('.datatables-basic'),
                    dt_basic;

                // DataTable with buttons
                // --------------------------------------------------------------------

                if (dt_basic_table.length) {
                    dt_basic = dt_basic_table.DataTable({
                        data: agences,
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
                            { data: '' },
                            { data: 'id' },
                            { data: 'id' },
                            { data: 'libelle' },
                            { data: 'has_faq' },
                            { data: 'has_consult' },
                            { data: 'has_reclame' },
                            { data: 'has_avis' },
                            { data: 'delais' },
                            { data: 'status' },
                            { data: '' }
                        ],
                        columnDefs: [
                            {
                                // For Responsive
                                className: 'control',
                                orderable: false,
                                searchable: false,
                                responsivePriority: 2,
                                targets: 0,
                                render: function (data, type, full, meta) {
                                    return '';
                                }
                            },
                            {
                                // For Checkboxes
                                targets: 1,
                                orderable: false,
                                searchable: false,
                                responsivePriority: 3,
                                checkboxes: true,
                                render: function () {
                                    return '<input type="checkbox" class="dt-checkboxes form-check-input">';
                                },
                                checkboxes: {
                                    selectAllRender: '<input type="checkbox" class="form-check-input">'
                                }
                            },
                            {
                                targets: 2,
                                searchable: false,
                                visible: false
                            },
                            {
                                targets: 3,
                                render: function (data, type, full, meta) {
                                    return '<span class="" style="font-weight: bold">'+data+'</span>';
                                }
                            },
                            {
                                targets: [4,5,6,7],
                                render: function (data, type, full, meta) {
                                    if(data === 1 ){
                                        return (
                                            '<span class="badge bg-label-success"> Active </span>'
                                        );
                                    } else {
                                        return (
                                            '<span class="badge bg-label-danger"> Inactive </span>'
                                        );
                                    }

                                }
                            },
                            {
                                targets: 8,
                                render: function (data, type, full, meta) {
                                    return '<span class="" style="font-weight: bold">'+data+'</span>';
                                }
                            },
                            {
                                // Label
                                targets: -2,
                                render: function (data, type, full, meta) {
                                    var $status_number = full['status'];
                                    var $status = {
                                        1: { title: 'Active', class: 'bg-label-success' },
                                        2: { title: 'Professional', class: ' bg-label-primary' },
                                        0: { title: 'Inactive', class: ' bg-label-danger' },
                                        4: { title: 'Resigned', class: ' bg-label-warning' },
                                        5: { title: 'Applied', class: ' bg-label-info' }
                                    };
                                    if (typeof $status[$status_number] === 'undefined') {
                                        return data;
                                    }
                                    return (
                                        '<span class="badge ' + $status[$status_number].class + '">' + $status[$status_number].title + '</span>'
                                    );
                                }
                            },
                            {
                                // Actions
                                targets: -1,
                                title: 'Actions',
                                orderable: false,
                                searchable: false,
                                render: function (data, type, full, meta) {
                                    return (
                                        '<a href="/agence/'+ full.libelle.toLowerCase() +'" class="btn btn-sm btn-icon " target="_blank"><i class="text-primary ti ti-eye"></i></a>'+
                                        '<a href="javascript:;" class="btn btn-sm btn-icon item-edit edit-btn"><i class="text-primary ti ti-pencil"></i></a>'+
                                        '<a href="javascript:;" class="btn btn-sm btn-icon delete-record"><i class="text-danger ti ti-trash"></i></a>'
                                    );
                                }
                            }
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
                                            columns: [3, 4, 5, 6, 7],
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
                                            columns: [3, 4, 5, 6, 7],
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
                                            columns: [3, 4, 5, 6, 7],
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
                                            columns: [3, 4, 5, 6, 7],
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
                                            columns: [3, 4, 5, 6, 7],
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
                            },
                            {
                                text: '<i class="ti ti-plus me-sm-1"></i> <span class="d-none d-sm-inline-block">Ajouter Agence</span>',
                                className: 'create-new btn btn-primary waves-effect waves-light'
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
                    $('div.head-label').html('<h5 class="card-title mb-0">Liste des Agences</h5>');
                }

                // Delete Record
                $('.datatables-basic tbody').on('click', '.delete-record', function () {
                    var row = $(this).closest('tr');
                    var rowData = $('.datatables-basic').DataTable().row(row).data();
                    //
                    var token = $('meta[name="csrf-token"]').attr('content');

                    Swal.fire({
                        title: "Êtes-vous sûr?",
                        text: " Vouloir supprimer cette Agence ",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#162738",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Oui, Supprimer!",
                        preConfirm: async (login) => {
                            try{
                                const url = '/dashboard/agences/'+ rowData.id +'/delete';

                                // Envoi de la requête POST avec le CSRF token
                                const response = await fetch(url, {
                                    method: 'POST', // Méthode POST
                                    headers: {
                                        'Content-Type': 'application/json', // Spécifie le type des données
                                        'X-CSRF-TOKEN': token // En-tête pour le token CSRF
                                    },
                                });

                                // Vérifie si la réponse est correcte (statut 200-299)
                                if (!response.ok) {
                                    const errorResponse = await response.json(); // Récupère la réponse d'erreur
                                    return Swal.showValidationMessage(`Erreur : ${JSON.stringify(errorResponse)}`);
                                }

                                // Si tout est correct, retourne les données JSON
                                return response.json();
                            } catch (error) {
                                // Gestion des erreurs
                                Swal.showValidationMessage(`La requête a échoué : ${error.message} veuillez ressayer`);
                            }
                        },
                        allowOutsideClick: () => !Swal.isLoading()
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                title: "Supprimé!",
                                text: "l'utilisateur a été supprimé",
                                icon: "success"
                            });

                            location.reload();
                        }
                    });
                })

                // Filter form control to default size
                // ? setTimeout used for multilingual table initialization
                setTimeout(() => {
                    $('.dataTables_filter .form-control').removeClass('form-control-sm');
                    $('.dataTables_length .form-select').removeClass('form-select-sm');
                }, 300);

                // Add an event listener for the edit button
                $('.datatables-basic ').on('click', '.edit-btn', function() {
                    var row = $(this).closest('tr');
                    var rowData = $('.datatables-basic').DataTable().row(row).data();

                    // Now, you can use the rowData for editing
                    console.log("Edit data:", rowData.has_avis == 1);
                    // console.log($(this).closest('tr'));

                    // Example: Open a modal to edit the row's data
                    $("#nome").val(rowData.libelle);
                    $("#id").val(rowData.id);
                    $("#delaye").val(rowData.delais);
                    (rowData.has_avis == 1 ) ? $("#avis_ouie").prop('checked', true) : $("#avis_none").prop('checked', true);
                    (rowData.has_consult == 1 ) ? $("#cons_ouie").prop('checked', true) : $("#cons_none").prop('checked', true);
                    (rowData.has_faq == 1 ) ? $("#faq_ouie").prop('checked', true) : $("#faq_none").prop('checked', true);
                    (rowData.has_reclame == 1 ) ? $("#recla_ouie").prop('checked', true) : $("#recla_none").prop('checked', true);
                    $('#EditModal').modal('show');
                    // Populate the modal with rowData for editing
                });

            });

        </script>
    @endpush
@endsection
