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
                    <li class="breadcrumb-item active">Gestion de la page</li>
                </ol>
            </nav>
            <!-- Collapsible Section -->
            <div class="row my-4">
                @if(Session::has('message'))
                    <div class="alert alert-{{Session::get('status')}} mt-4" role="alert">
                        {{Session::get('message')}}
                    </div>
                @endif
                <div class="col">
                    <h6>Paramètres de la Page</h6>
                    <div class="mb-3">
                        <label for="agence" class="form-label">Selectionne l'agence</label>
                        <select id="agence" class="form-select" name="agence">
                            <option value="0">-- Selectionne Une Agence --</option>
                            @foreach($agences as $data)
                                <option value="{{$data->id}}">{{$data->libelle}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="accordion" id="collapsibleSection">
                        <div class="card accordion-item">
                            <h2 class="accordion-header" id="headingDeliveryAddress">
                                <button
                                    type="button"
                                    class="accordion-button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#accueil"
                                    aria-expanded="true"
                                    aria-controls="accueil">
                                    Page D'accueil
                                </button>
                            </h2>
                            <div
                                id="accueil"
                                class="accordion-collapse collapse show"
                                data-bs-parent="#accueil">
                                <form action="{{ route('saveSetting') }}"  method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="accordion-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label" for="titre_accueil">Titre</label>
                                                <input
                                                    type="text"
                                                    id="titre_accueil"
                                                    name="titre_accueil"
                                                    class="form-control"
                                                    placeholder="BGFIBANK" />
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label" for="sous_titre">Sous-Titre</label>
                                                <input
                                                    type="text"
                                                    id="sous_titre"
                                                    name="sous_titre"
                                                    class="form-control"
                                                    placeholder="votre partenaire pour l'avenir"
                                                    aria-label="votre partenaire pour l'avenir" />
                                            </div>
                                            <div class="col-md-6" style="display: none">
                                                <input
                                                    type="text"
                                                    name="id_agence"
                                                    class="form-control id_agence"
                                                    placeholder="" />
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label" for="title_service">Titre de selection de services</label>
                                                <input
                                                    type="text"
                                                    name="title_service"
                                                    class="form-control"
                                                    id="title_service"
                                                    placeholder="Veuillez selectionner un services">
                                            </div>
                                            <div class="col-md-12">
                                                <label for="logo" class="form-label">Logo</label>
                                                <input class="form-control" type="file" id="logo" name="logo">
                                            </div>

                                            <div class="col-md-12 mt-3">
                                                <button type="submit" class="btn btn-primary me-sm-3 me-1">Enregistrer</button>
                                                <button type="reset" class="btn btn-label-secondary">Supprimer</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="card accordion-item">
                            <h2 class="accordion-header" id="faq">
                                <button
                                    type="button"
                                    class="accordion-button collapsed"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#faqCadran"
                                    aria-expanded="false"
                                    aria-controls="faqCadran">
                                    Modifier le cadran de la faq
                                </button>
                            </h2>
                            <div
                                id="faqCadran"
                                class="accordion-collapse collapse"
                                aria-labelledby="faq"
                                data-bs-parent="#collapsibleSection">
                                <form action="{{ route('saveSettingFaq') }}"  method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="accordion-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label" for="titre_faq">Titre FAQ</label>
                                                <input
                                                    type="text"
                                                    id="titre_faq"
                                                    name="titre_faq"
                                                    class="form-control"
                                                    placeholder="FAQ" />
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label" for="sous_titre_faq">Sous-Titre FAQ</label>
                                                <input
                                                    type="text"
                                                    id="sous_titre_faq"
                                                    name="sous_titre_faq"
                                                    class="form-control"
                                                    placeholder="sous titre"
                                                    aria-label="votre partenaire pour l'avenir" />
                                            </div>
                                            <div class="col-md-6" style="display: none">
                                                <input
                                                    type="text"
                                                    name="id_agence"
                                                    class="form-control id_agence"
                                                    placeholder="" />
                                            </div>
                                            <div class="col-md-12">
                                                <label for="logoFAQ" class="form-label">Logo</label>
                                                <input class="form-control" type="file" id="logoFAQ" name="logoFAQ">
                                            </div>

                                            <div class="col-md-12 mt-3">
                                                <button type="submit" class="btn btn-primary me-sm-3 me-1">Enregistrer</button>
                                                <button type="reset" class="btn btn-label-secondary">Supprimer</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="card accordion-item">
                            <h2 class="accordion-header" id="consultation">
                                <button
                                    type="button"
                                    class="accordion-button collapsed"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#consultCadran"
                                    aria-expanded="false"
                                    aria-controls="consultCadran">
                                    Modifier le cadran de consultation de comptes bancaires
                                </button>
                            </h2>
                            <div
                                id="consultCadran"
                                class="accordion-collapse collapse"
                                aria-labelledby="consultation"
                                data-bs-parent="#collapsibleSection">
                                <form action="{{ route('saveSettingConsultation') }}"  method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="accordion-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label" for="titre_consult">Titre Consultation</label>
                                                <input
                                                    type="text"
                                                    id="titre_consult"
                                                    name="titre_consult"
                                                    class="form-control"
                                                    placeholder="Consultation title" />
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label" for="sous_titre_consult">Sous-Titre Consultation</label>
                                                <input
                                                    type="text"
                                                    id="sous_titre_consult"
                                                    name="sous_titre_consult"
                                                    class="form-control"
                                                    placeholder="sous titre"
                                                    aria-label="votre partenaire pour l'avenir" />
                                            </div>
                                            <div class="col-md-6" style="display: none">
                                                <input
                                                    type="text"
                                                    name="id_agence"
                                                    class="form-control id_agence"
                                                    placeholder="" />
                                            </div>
                                            <div class="col-md-12">
                                                <label for="logoConsult" class="form-label">Logo</label>
                                                <input class="form-control" type="file" id="logoConsult" name="logoConsult">
                                            </div>

                                            <div class="col-md-12 mt-3">
                                                <button type="submit" class="btn btn-primary me-sm-3 me-1">Enregistrer</button>
                                                <button type="reset" class="btn btn-label-secondary">Supprimer</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="card accordion-item">
                            <h2 class="accordion-header" id="avis">
                                <button
                                    type="button"
                                    class="accordion-button collapsed"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#avisCadran"
                                    aria-expanded="false"
                                    aria-controls="avisCadran">
                                    Modifier le cadran des avis
                                </button>
                            </h2>
                            <div
                                id="avisCadran"
                                class="accordion-collapse collapse"
                                aria-labelledby="avis"
                                data-bs-parent="#collapsibleSection">
                                <form action="{{ route('saveSettingAvis') }}"  method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="accordion-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label" for="titre_avis">Titre Avis</label>
                                                <input
                                                    type="text"
                                                    id="titre_avis"
                                                    name="titre_avis"
                                                    class="form-control"
                                                    placeholder="Avis title" />
                                            </div>
                                            <div class="col-md-6" style="display: none">
                                                <input
                                                    type="text"
                                                    name="id_agence"
                                                    class="form-control id_agence"
                                                    placeholder="" />
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label" for="sous_titre_avis">Sous-Titre Avis</label>
                                                <input
                                                    type="text"
                                                    id="sous_titre_avis"
                                                    name="sous_titre_avis"
                                                    class="form-control"
                                                    placeholder="sous titre"
                                                    aria-label="votre partenaire pour l'avenir" />
                                            </div>
                                            <div class="col-md-12">
                                                <label for="logoAvis" class="form-label">Logo</label>
                                                <input class="form-control" type="file" id="logoAvis" name="logoAvis">
                                            </div>

                                            <div class="col-md-12 mt-3">
                                                <button type="submit" class="btn btn-primary me-sm-3 me-1">Enregistrer</button>
                                                <button type="reset" class="btn btn-label-secondary">Supprimer</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="card accordion-item">
                            <h2 class="accordion-header" id="recla">
                                <button
                                    type="button"
                                    class="accordion-button collapsed"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#reclaCadran"
                                    aria-expanded="false"
                                    aria-controls="reclaCadran">
                                    Modifier cadran des réclamations
                                </button>
                            </h2>
                            <div
                                id="reclaCadran"
                                class="accordion-collapse collapse"
                                aria-labelledby="recla"
                                data-bs-parent="#collapsibleSection">
                                <form action="{{ route('saveSettingReclamation') }}"  method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="accordion-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label" for="titre_recla">Titre Reclamation</label>
                                                <input
                                                    type="text"
                                                    id="titre_recla"
                                                    name="titre_recla"
                                                    class="form-control"
                                                    placeholder="Avis title" />
                                            </div>
                                            <div class="col-md-6" style="display: none">
                                                <input
                                                    type="text"
                                                    name="id_agence"
                                                    class="form-control id_agence"
                                                    placeholder="" />
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label" for="sous_titre_recla">Sous-Titre Reclamation</label>
                                                <input
                                                    type="text"
                                                    id="sous_titre_recla"
                                                    name="sous_titre_recla"
                                                    class="form-control"
                                                    placeholder="sous titre"
                                                    aria-label="votre partenaire pour l'avenir" />
                                            </div>
                                            <div class="col-md-12">
                                                <label for="logoRecla" class="form-label">Logo</label>
                                                <input class="form-control" type="file" id="logoRecla" name="logoRecla">
                                            </div>

                                            <div class="col-md-12 mt-3">
                                                <button type="submit" class="btn btn-primary me-sm-3 me-1">Enregistrer</button>
                                                <button type="reset" class="btn btn-label-secondary">Supprimer</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- / Content -->

    @push('scripts')
        <script>
            $(document).ready(function() {
                // Set up the CSRF token for Laravel requests
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                // Event listener for select change
                $('#agence').change(function() {
                    var selectedValue = $(this).val();

                    if (selectedValue !== 0) {
                        $('.id_agence').val(selectedValue)
                    } else {
                        $('.id_agence').val('')
                    }

                    if (selectedValue !== 0) {
                        $.ajax({
                            url: '/dashboard/page-manager/agence',
                            type: 'GET',
                            data: { id: selectedValue },
                            success: function(response) {
                                $('#titre_accueil').val(response.titre);
                                $('#sous_titre').val(response.stitre);
                                $('#title_service').val(response.titre_service);
                                $('#titre_faq').val(response.faq_titre);
                                $('#sous_titre_faq').val(response.faq_stitre);
                                $('#titre_avis').val(response.avis_titre);
                                $('#sous_titre_avis').val(response.avis_stitre);
                                $('#titre_consult').val(response.consult_titre);
                                $('#sous_titre_consult').val(response.consult_stitre);
                                $('#titre_recla').val(response.recla_titre);
                                $('#sous_titre_recla').val(response.recla_stitre);
                            },
                            error: function(xhr, status, error) {
                                console.error('Error fetching data:', error);
                            }
                        });
                    } else {

                    }
                });
            });
        </script>
    @endpush
@endsection
