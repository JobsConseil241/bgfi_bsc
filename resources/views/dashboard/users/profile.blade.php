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
                    <li class="breadcrumb-item active">Page de Profile</li>
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
                </div>
                <div class="content">
                    <div class="grid grid-cols-12">
                        <div class="col-span-12 lg:col-span-12 2xl:col-span-12">
                            <!-- BEGIN: Personal Information -->
                            <div class="intro-y box">
                                <div class="flex items-center px-1 border-b border-slate-200/60 dark:border-darkmode-400">
                                    <h5 class="font-medium text-base mr-auto">
                                        Information Personnelle
                                    </h5>
                                </div>
                                <div class="p-2">
                                    <div class="grid grid-cols-12 gap-x-5">
                                        <div class="col-span-12 xl:col-span-6">
                                            <div>
                                                <label for="email" class="form-label">Email</label>
                                                <input id="email" type="email" class="form-control" placeholder="" value="{{ $data->email }}" >
                                            </div>
                                            <div class="mt-3">
                                                <label for="nom" class="form-label">Nom</label>
                                                <input id="nom" type="text" class="form-control" placeholder="" value="{{ $data->name }}" >
                                            </div>
                                            <div class="mt-3">
                                                <label for="role" class="form-label">Role</label>
                                                <select id="role" class="form-select" disabled>
                                                    <option value="">--- Selectionner un role ---</option>
                                                    <option @selected($data->role === 'responsable') value="{{$data->role}}">responsable</option>
                                                </select>
                                            </div>
                                            <div class="mt-3">
                                                <label for="password" class="form-label">Mot de Passe</label>
                                                <input id="password" type="password" class="form-control" placeholder="" value="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex flex-row justify-end mt-4">
                                        <button type="button" class="btn btn-primary w-20 mr-auto" id="updateUser">Enregistrer</button>
                                    </div>
                                </div>
                            </div>
                            <!-- END: Personal Information -->
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    @push('scripts')
        <script>
            $(document).ready(function () {
                $('#updateUser').click(function (e) {
                    e.preventDefault(); // Empêche le rafraîchissement par défaut

                    // Récupérer les valeurs des champs
                    const email = $('#email').val();
                    const name = $('#nom').val();
                    const role = $('#role').val();
                    const password = $('#password').val();

                    // Envoi de l'AJAX
                    $.ajax({
                        url: "{{ route('profile.update', $data->id) }}", // Endpoint pour la mise à jour
                        type: "PATCH", // Méthode HTTP
                        data: {
                            _token: "{{ csrf_token() }}", // CSRF Token
                            email: email,
                            name: name,
                            role: role,
                            password: password
                        },
                        success: function (response) {

                            Swal.fire({
                                title: "Supprimé!",
                                text: "l'utilisateur a été supprimé",
                                icon: "success"
                            });

                            location.reload(); // Recharger la page
                        },
                        error: function (xhr) {
                            alert('Une erreur est survenue : ' + xhr.responseText);
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection
