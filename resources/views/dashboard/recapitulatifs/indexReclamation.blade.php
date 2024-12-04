@extends('layouts.admin')

@section('content')
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">

            <!-- DataTable with Buttons -->
            <div class="card">
                <h5 class="card-header">Recapitulif des reponses (Reclamations)</h5>
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
                url: '/dashboard/recapitulatifs/reclamation/data',
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

                    console.log("Columns:", columns);
                    console.log("Data:", formattedData);

                    // Initialiser DataTables avec colonnes et données dynamiques
                    $('#responsesTable').DataTable({
                        data: formattedData,
                        columns: columns,
                        dom: 'Bfrtip',
                        buttons: [
                            {
                                extend: 'csvHtml5',
                                text: 'Exporter CSV',
                                className: 'btn btn-xs btn-primary mt-3 mr-4'
                            },
                            {
                                extend: 'pdfHtml5',
                                text: 'Exporter PDF',
                                className: 'btn btn-xs btn-primary mt-3 mr-4',
                                orientation: 'landscape', // Orientation paysage pour les larges tables
                                pageSize: 'A4'
                            },
                            {
                                extend: 'print',
                                text: 'Imprimer',
                                className: 'btn btn-xs btn-primary mt-3 '
                            }
                        ],
                        paging: true, // Activer la pagination
                        searching: true, // Activer la barre de recherche
                        displayLength: 7,
                        lengthMenu: [7, 10, 25, 50, 75, 100],
                        responsive: true,
                        autoWidth: false,
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
