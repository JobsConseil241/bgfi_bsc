@extends('layouts.admin')

@section('content')
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">

            <!-- DataTable with Buttons -->
            <div class="card">
                <h5 class="card-header">Recapitulif des reponses (Avis)</h5>
                <div class="card-datatable table-responsive pt-0">
                    @if(Session::has('message'))
                        <div class="alert alert-{{Session::get('status')}} mt-4" role="alert">
                            {{Session::get('message')}}
                        </div>
                    @endif
                    <table id="responsesTable" class="display datatables-basic table">
                        <thead>
                            <tr id="tableHeaders"></tr>
                        </thead>
                    </table>
                </div>
            </div>

        </div>
    </div>
    <!-- / Content -->
    @push('scripts')
        <script>

            {{--let columns = {!! json_encode($columns, JSON_UNESCAPED_SLASHES ); !!};--}}
            {{--let groupedResponses = {!! json_encode($groupedResponses, JSON_UNESCAPED_SLASHES ); !!};--}}

            $.ajax({
                url: '/dashboard/recapitulatifs/avis/data',
                method: 'GET',
                success: function (response) {
                    let columns = response.columns;

                    // Forcer en tableau si ce n'est pas déjà le cas
                    // if (!Array.isArray(columns)) {
                    //     columns = Object.values(columns);
                    // }
                    //
                    // const columnDefs = columns.map(col => ({ data: col, title: col }));

                    // Construire les en-têtes dynamiques
                    // response.columns.forEach(function (col) {
                    //     $('#tableHeaders').append(`<th>${col}</th>`);
                    //     columns.push({ data: col, title: col });
                    // });
                    const datas = Object.values(response.data)

                    const formattedData = datas.map(row => {
                        const formattedRow = {};
                        for (const [key, value] of Object.entries(row)) {
                            if (typeof value === 'string' && value.startsWith('[') && value.endsWith(']')) {
                                try {
                                    // Si la chaîne représente un tableau JSON, la convertir
                                    const parsedArray = JSON.parse(value);
                                    if (Array.isArray(parsedArray)) {
                                        formattedRow[key] = parsedArray.join(', '); // Transforme en chaîne lisible
                                    } else {
                                        formattedRow[key] = value; // Garde la valeur originale si ce n'est pas un tableau
                                    }
                                } catch (e) {
                                    formattedRow[key] = value; // En cas d'erreur, garder la valeur telle quelle
                                }
                            } else {
                                formattedRow[key] = value; // Garde la valeur telle quelle
                            }
                        }
                        return formattedRow;
                    });

                    // Initialiser DataTables avec colonnes et données dynamiques
                    $('#responsesTable').DataTable({
                        data: formattedData,
                        columns: columns,
                        columnDefs: [
                            {
                                targets: [0,1,2,3,4,5,6,7],
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
                        buttons: [
                            {
                                extend: 'csvHtml5',
                                text: 'Exporter CSV',
                                className: 'btn btn-md btn-primary mt-3 mr-4'
                            },
                            {
                                extend: 'pdfHtml5',
                                text: 'Exporter PDF',
                                className: 'btn btn-md btn-primary mt-3 mr-4',
                                orientation: 'landscape', // Orientation paysage pour les larges tables
                                pageSize: 'A4'
                            },
                            {
                                extend: 'print',
                                text: 'Imprimer',
                                className: 'btn btn-md btn-primary mt-3 '
                            }
                        ],
                        paging: true, // Activer la pagination
                        searching: true, // Activer la barre de recherche
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
                                        orientation: 'landscape', // Set orientation to landscape
                                        extend: 'print',
                                        text: '<i class="ti ti-printer me-1" ></i>Imprimer',
                                        className: 'dropdown-item',
                                        exportOptions: {
                                            columns: [0,1,2,3,4,5],
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
                                            columns: [0,1,2,3,4,5,6,7],
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
                                            columns: [0,1,2,3,4,5,6,7],
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
                                        orientation: 'landscape', // Set orientation to landscape
                                        customize: function (doc) {
                                            // Optional: Add customizations to the PDF document
                                            doc.styles.tableHeader.fontSize = 10; // Example: Adjust header font size
                                            doc.defaultStyle.fontSize = 8; // Example: Adjust default font size
                                        },
                                        exportOptions: {
                                            columns: [0,1,2,3,4,5,6,7],
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
                                            columns: [0,1,2,3,4,5,6,7],
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
                        ],
                        responsive: true,
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
                    });
                }
            });        // datatable (jquery)

        </script>
    @endpush
@endsection
