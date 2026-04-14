
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BGFIBank</title>
    <!-- Font Awesome -->

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- MDB -->
    <link rel="shortcut icon" type="image/x-icon" href="{{url('/assets/frontend/img/bgfi.jpg')}}">
    <link href="{{url('/assets/frontend/css/mdb.min.css')}}" rel="stylesheet" />
    <!-- Font Awesome -->
    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
        rel="stylesheet"
    />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">

    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap"
        rel="stylesheet"
    />
    <!-- MDB -->
    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.2/mdb.min.css"
        rel="stylesheet"
    />
    <link rel="stylesheet" href="{{url('/assets/frontend/css/style.css') }}">

    <link rel="stylesheet" type="text/css" href="{{url('/assets/frontend/css/fontawesome.css')}}">
    <link rel="stylesheet" type="text/css" href="{{url('/assets/frontend/css/all.min.css')}}">

    <style>
        a.disabled {
            position: relative; /* Nécessaire pour positionner ::before */
            display: inline-block;
            text-decoration: none;
            pointer-events: none; /* Désactive le clic */
            cursor: not-allowed;
            width: 70% !important;
            height: 98%;
        }

        a.disabled::before {
            content: "Bientôt Disponible"; /* Texte affiché */
            position: absolute;
            top: 50%; /* Centre verticalement */
            left: 50%; /* Centre horizontalement */
            transform: translate(-50%, -50%); /* Ajuste pour centrer */
            background-color: black;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 1rem;
            font-weight: bold;
            z-index: 2; /* Positionne au-dessus de l'image */
            text-align: center;
        }

        a.disabled::after {
            content: ""; /* Fond noir semi-transparent */
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6); /* Opacité ajustable */
            z-index: 1; /* Juste en dessous du texte */
        }

        a.disabled img {
            opacity: 0.5;
            width: 100% !important;
        }

        a.disabled .light-blue {
            color: #48b2c5;
            left: 30px !important;
        }

        a.disabled .light-blues {
            left: 30px !important;
        }

        .bg-home{
            background-image: url('{{url('/assets/frontend/img/Fond-1.webp') }}');
            background-position: center;
            background-size: cover;
            background-attachment: fixed;
        }
        .bg-other{
            background-image: url('{{url('/assets/frontend/img/fond2.webp') }}');
            background-position: center;
            background-size: cover;
            background-attachment: fixed;
        }
        .card-sbtitle{
            position:absolute;
            bottom:60px;
            left: 80px;
            width:300px;
            text-align:left;
            line-height:30px;
            font-family:limerick;
            font-size: 35px;
        }
        .light-blue{
            color:#48b2c5;
            left: 176px !important;
        }
        .light-blues{
            color:#48b2c5;
            left: 180px !important;
        }
        .light-brown {
            color: #b2b88f;
            left: 37px !important;
            line-height: 38px;
            font-size: 32px;
        }
        .blue{
            color:#2563a2;
        }.
    </style>
    <style>
        /*!* Carousel styling *!*/
        /*#introCarousel,*/
        /*.carousel-inner,*/
        /*.carousel-item,*/
        /*.carousel-item.active {*/
        /*    height: 100vh;*/
        /*}*/

        /*.carousel-item:nth-child(1) {*/
        /*    background-image: url('https://mdbootstrap.com/img/Photos/Others/images/76.jpg');*/
        /*    background-repeat: no-repeat;*/
        /*    background-size: cover;*/
        /*    background-position: center center;*/
        /*}*/

        /*.carousel-item:nth-child(2) {*/
        /*    background-image: url('https://mdbootstrap.com/img/Photos/Others/images/77.jpg');*/
        /*    background-repeat: no-repeat;*/
        /*    background-size: cover;*/
        /*    background-position: center center;*/
        /*}*/

        /*.carousel-item:nth-child(3) {*/
        /*    background-image: url('https://mdbootstrap.com/img/Photos/Others/images/78.jpg');*/
        /*    background-repeat: no-repeat;*/
        /*    background-size: cover;*/
        /*    background-position: center center;*/
        /*}*/

        /* Height for devices larger than 576px */
        @media (min-width: 992px) {
            #introCarousel {
                margin-top: -58.59px;
            }
        }

        .navbar .nav-link {
            color: #fff !important;
        }
    </style>
    <style>
        #loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #ffffff; /* Couleur de fond */
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        /* Style pour le spinner */
        .spinner {
            border: 8px solid #f3f3f3; /* Couleur du cercle externe */
            border-top: 8px solid #0D437A; /* Couleur de l'animation */
            border-radius: 50%;
            width: 60px;
            height: 60px;
            animation: spin 1s linear infinite;
        }

        /* Animation de rotation */
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>

<div id="loader" style="display: none">
    <div class="spinner"></div>
</div>

<!--Main Navigation-->
<header id="first" style="display: none">
    <style>
        /* Carousel styling */
        #introCarousel,
        .carousel-inner,
        .carousel-item,
        .carousel-item.active {
            height: 100vh;
        }

        @foreach ($market as $key => $image)
            .carousel-item:nth-child({{ $key + 1 }}) {
                background-image: url('{{ Storage::url($image->thumb_url) }}');
                background-repeat: no-repeat;
                background-size: cover;
                background-position: center center;
            }
        @endforeach



        /* Height for devices larger than 576px */
        @media (min-width: 992px) {
            #introCarousel {
                margin-top: -58.59px;
            }
        }

        .navbar .nav-link {
            color: #fff !important;
        }
    </style>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark d-none d-lg-block" style="z-index: 2000;">
        <div class="container-fluid">
            <!-- Navbar brand -->
            <a class="navbar-brand nav-link" target="_blank">
                <img style="" src="{{url('/assets/backend/dist/img/Logo55.png') }}" class="" height="30"
                     loading="lazy" alt="Logo BGFI">
            </a>
            <button class="navbar-toggler" type="button" data-mdb-collapse-init data-mdb-target="#navbarExample01"
                    aria-controls="navbarExample01" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarExample01">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                </ul>

                <ul class="navbar-nav list-inline">
                    <!-- Icons -->
                    <li class="">
                        <a class="nav-link" href="https://www.youtube.com/channel/UC5CF7mLQZhvx8O5GODZAhdA" rel="nofollow"
                           target="_blank">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </li>
                    <li class="">
                        <a class="nav-link" href="https://www.facebook.com/mdbootstrap" rel="nofollow" target="_blank">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="https://twitter.com/MDBootstrap" rel="nofollow" target="_blank">
                            <i class="fab fa-twitter"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="https://github.com/mdbootstrap/mdb-ui-kit" rel="nofollow" target="_blank">
                            <i class="fab fa-github"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Navbar -->

    <!-- Carousel wrapper -->
    <div id="introCarousel" class="carousel slide carousel-fade shadow-2-strong" data-mdb-carousel-init data-mdb-ride="carousel">
        <!-- Indicators -->
        <div class="carousel-indicators">
            @foreach ($market as $key => $image)
                <li data-mdb-target="#introCarousel" data-mdb-slide-to="{{$key}}" {{ $key == 0 ? 'class = active' : '' }}></li>
            @endforeach
        </div>

        <!-- Inner -->
        <div class="carousel-inner">

            @foreach ($market as $key => $image)
                <div class="carousel-item startHome {{ $key == 0 ? 'active' : '' }}">
                    <div class="mask" style="background-color: rgba(0, 0, 0, 0.3);">
                        <div class="d-flex justify-content-center align-items-center h-100">
                            <div class="text-white text-center" data-mdb-theme="dark">
                                <h1 class="mb-3">BIENVENUE SUR VOTRE ESPACE CLIENT <br> DE VOTRE BANQUE BGFI</h1>
                                <h5 class="mb-4">Votre Partenaire pour l'avenir !</h5>
                                <a class="btn btn-secondary btn-rounded btn-lg m-2 startHome" data-mdb-ripple-init href="#"
                                   target="_blank" role="button">Appuyez Pour Commencer !</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
        <!-- Inner -->
    </div>
    <!-- Carousel wrapper -->
</header>
<!--Main Navigation-->

<div id="avis" class="tabcontent d-none">

    <div class="container-fluid">
        <div class="position-relative overflow-hidden p-md-5 text-center vh-100 row bg-hoome d-flex" id="bg-home">
            <div class="col-md-2 p-md-5 mx-auto my-5">

                <div class="img text-start" style="margin-left: -15px; margin-top: -120px">
                    <img style="margin-top:10px;margin-left:-10px;" src="{{url('/assets/backend/dist/img/Logo55.png') }}" class="img-fluid logo" alt="Fissure in Sandstone">
                </div>
                <div class="welcome">
                    <div style="position:absolute;top:60%">
                        <div class="text-start">
                            <h1 class="text-light h1 bienvenue"><strong>{{ $param->titre ?? 'BGFIBank' }}</strong></h1>
                        </div>
                        <div class="text-start text-light">
                            <small class="textNormal">{{ $param->stitre ?? "Votre partenaire pour l'avenir" }}</small>
                        </div>
                        <div class="text-start">

                        </div>

                        <img src="{{url('/assets/frontend/img/Les certificats.png') }}" class="text-start mb-3 mt-3" width="250px" style="margin-left: 0px;">
                        <p><img src="{{url('/assets/frontend/img/Tarait.png')}}" class="text-start mb-5" width="250px" style="margin-left: 0px;"></p>
                    </div>

                </div>
            </div>

            <div class="col-md-7 mx-auto acCadre d-flex justify-content-center align-items-center" style="height: 100vh;">
                <div class="row mx-auto ">
                    <div style="position:relative" class="col-md-12 mb-2 text-start">
                        <h6 style="margin-left:15%" class="text-light font-weight-bold">{{ $param->titre_service ?? "Veuillez sélectionner un service" }}</h6>
                    </div>

                    <div style="position: relative;visibility:visible" class="col-6 mb-3 text-end">
                        <a href="/agence/{{ strtolower($agence->libelle) }}/faq" @if($agence->has_faq === 0) class="tablinks disabled" @endif>
                            <img style="width:70%" src="@if(isset($param->faq_logo)) {{asset('/settings/'. $param->faq_logo )}} @else {{url('/assets/backend/dist/img/Homme-sans-texte.jpg')}} @endif" class="img-fluid" alt="Fissure in Sandstone">
                            <span class="card-sbtitle light-blues">{{ $param->faq_stitre ?? 'Je cherche des réponses' }}</span>
                        </a>

                    </div>

                    <!--<a class="tablinks" href="http://10.20.20.41:8080/OnlineBankingGB/#!/login?agence=venus">-->


                    <div style="position: relative;visibility:visible" class="col-6 mb-2 text-start">
                        <a href="#" @if($agence->has_consult === 0) class="tablinks disabled" @endif id="consultData">
                            <img src="@if(isset($param->consult_logo)){{asset('/settings/'. $param->consult_logo )}} @else {{url('/assets/backend/dist/img/Femme-ordi-sans-texte.jpg')}} @endif" style="width:70%" class="img-fluid" alt="Fissure in Sandstone">
                            <span class="card-sbtitle light-brown">{{ $param->consult_stitre ?? 'Je consulte mon compte' }}</span>
                        </a>
                    </div>


                    <div style="position: relative;visibility:visible"  class="col-6 mb-3 text-end">
                        <a href="/agence/{{ strtolower($agence->libelle) }}/reclamation" @if($agence->has_reclame === 0) class="tablinks disabled" @endif>
                            <img src="@if(isset($param->recla_logo)) {{asset('/settings/'. $param->recla_logo )}} @else {{url('/assets/backend/dist/img/Dame-2-sans-texte.jpg')}} @endif" style="width:70%" class="img-fluid" alt="Fissure in Sandstone">
                            <span class="card-sbtitle light-blue">{{ $param->recla_stitre ?? 'Je fais une réclamation' }} </span>
                        </a>
                    </div>


                    <div style="position: relative;visibility:visible" class="col-6 mb-2 text-start">
                        <a href="/agence/{{ strtolower($agence->libelle) }}/avis" @if($agence->has_avis === 0) class="tablinks disabled" @endif>
                            <img src="@if(isset($param->avis_logo)) {{asset('/settings/'. $param->avis_logo )}} @else {{url('/assets/backend/dist/img/Dame-3-sans-texte (1).jpg')}} @endif" style="width:70%" class="img-fluid" alt="Fissure in Sandstone">
                            <span class="card-sbtitle light-brown">{{ $param->avis_stitre ?? 'Je donne mon avis sur ma banque' }}</span>
                        </a>
                    </div>

                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Informations du compte</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form method="post" id="form-check" autocomplete="off">
                                        <div data-mdb-input-init class="form-outline mb-1">
                                            <input type="text" id="compte" class="form-control form-control-lg"
                                                   inputmode="numeric" pattern="[0-9]*" maxlength="11"
                                                   autocomplete="off" />
                                            <label class="form-label" for="compte">Numero de Compte</label>
                                        </div>
                                        <small class="text-muted d-block mb-4">
                                            Saisir uniquement les 11 chiffres du compte (sans la clé)
                                        </small>

                                        <button data-mdb-ripple-init type="submit" class="btn btn-primary btn-block">Consulter Mon Compte</button>
                                    </form>
                                </div>
{{--                                <div class="modal-footer">--}}
{{--                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>--}}
{{--                                    <button type="button" class="btn btn-primary">Save changes</button>--}}
{{--                                </div>--}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="{{url('/assets/frontend/js/zoom.js')}}"></script>
<!-- MDB -->
<script
    type="text/javascript"
    src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.2/mdb.umd.min.js"
></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>

<script>

    let agence = @json($agence)

    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    window.addEventListener('load', function () {
        let value  = urlParams.get('click')
        if(!value){
            const dt = document.getElementById('first')
            dt.style.display = "block"
        }
    });

    let accounts = @json($accounts);

    // A $( document ).ready() block.
    jQuery( document ).ready(function() {

        console.log(accounts)

        const INACTIVITY_TIME = agence.delais * 1000; // 10 secondes
        let inactivityTimer;
        let swalInstance = null;
        let shouldRedirect = true;

        let value  = urlParams.get('click')

        if(value === 'yes'){
            document.getElementById('loader').style.display = 'flex';
            jQuery("#first").hide()

            const bgImage = new Image();
            bgImage.src = "/assets/frontend/img/Fond-1.webp"; // Remplacez par le chemin de votre image

            // Ajoutez un écouteur pour vérifier si l'image est bien chargée
            bgImage.onload = function () {
                // Applique l'image de fond à la div
                const content = document.getElementById("bg-home");
                content.style.backgroundImage = `url('${bgImage.src}')`;
                content.style.backgroundSize = "cover";
                content.style.backgroundPosition = "center";
                content.style.backgroundRepeat = "no-repeat";

                document.getElementById('loader').style.display = 'none';
                jQuery('#avis').removeClass('d-none')
                content.style.opacity = 1;
            };

            const url = new URL(window.location);
            url.search = ""; // Supprime tous les paramètres
            window.history.replaceState({}, document.title, url);

            resetInactivityTimer();
        }

        jQuery(".startHome").click(function (event) {
            event.preventDefault();
            jQuery("#first").hide()

            document.getElementById('loader').style.display = 'flex';

            const bgImage = new Image();
            bgImage.src = "/assets/frontend/img/Fond-1.webp"; // Remplacez par le chemin de votre image

            // Ajoutez un écouteur pour vérifier si l'image est bien chargée
            bgImage.onload = function () {
                // Applique l'image de fond à la div
                const content = document.getElementById("bg-home");
                content.style.backgroundImage = `url('${bgImage.src}')`;
                content.style.backgroundSize = "cover";
                content.style.backgroundPosition = "center";
                content.style.backgroundRepeat = "no-repeat";

                document.getElementById('loader').style.display = 'none';
                jQuery('#avis').removeClass('d-none')
                content.style.opacity = 1;
            };


            resetInactivityTimer();
        })


        function warnAndRedirect() {
            let timerInterval;

            shouldRedirect = true;

            if (jQuery('#exampleModal').hasClass('show')) {
                jQuery('#exampleModal').modal('hide');
            }

            swalInstance  =  Swal.fire({
                title: "Inactivité détectée",
                html: "Vous serez redirigé dans <b></b> secondes...",
                icon: "warning",
                timer: 3000,
                showConfirmButton: false,
                allowOutsideClick: false,
                allowEscapeKey: false,
                timerProgressBar: true,
                didOpen: () => {
                    Swal.showLoading();
                    const timer = Swal.getPopup().querySelector("b");
                    timerInterval = setInterval(() => {
                        timer.textContent = `${Swal.getTimerLeft()}`;
                    }, 100);
                },
                willClose: () => {
                    if (shouldRedirect){
                        clearInterval(timerInterval);
                        // window.location.href = "https://votre-page-de-redirection.com";
                        jQuery("#first").show()
                        jQuery('#avis').addClass('d-none')


                        window.removeEventListener("mousemove", resetInactivityTimer);
                        window.removeEventListener("click", resetInactivityTimer);
                        window.removeEventListener("keydown", resetInactivityTimer);
                        window.removeEventListener("touchstart", resetInactivityTimer);

                        clearTimeout(inactivityTimer);
                    }
                }
            })
        }

        function resetInactivityTimer() {
            // Efface le timer existant s'il y en a un
            clearTimeout(inactivityTimer);

            // Ferme l'alerte SweetAlert si elle est ouverte
            if (swalInstance && Swal.isVisible()) {
                shouldRedirect = false;
                swalInstance.close();
            }

            // Redémarre le timer
            inactivityTimer = setTimeout(warnAndRedirect, INACTIVITY_TIME);

            window.addEventListener("mousemove", resetInactivityTimer);
            window.addEventListener("click", resetInactivityTimer);
            window.addEventListener("keydown", resetInactivityTimer);
            window.addEventListener("touchstart", resetInactivityTimer);
        }

        function masquerNumero(phone) {
            if (phone.length < 6) return phone; 

            const start = phone.slice(0, 2);              
            const end = phone.slice(-2);                 
            const masked = "*".repeat(phone.length - 4);

            return `${start}${masked}${end}`;
        }

        const exampleModalEl = document.getElementById('exampleModal');
        const modalBody = exampleModalEl.querySelector('.modal-body');
        // Capturer un HTML "propre" sans les éléments injectés par MDB (form-notch, etc.)
        const _cleanWrapper = document.createElement('div');
        _cleanWrapper.innerHTML = modalBody.innerHTML;
        _cleanWrapper.querySelectorAll('.form-notch').forEach(el => el.remove());
        const originalFormContent = _cleanWrapper.innerHTML;

        // Réinitialiser le contenu du modal à chaque fermeture (croix, backdrop, inactivité, retour)
        exampleModalEl.addEventListener('hidden.bs.modal', function () {
            modalBody.innerHTML = originalFormContent;
            initFormListeners();
        });

        // Ré-initialise les composants MDB form-outline (sinon le label s'affiche mal après restauration du HTML)
        function reinitMdbInputs() {
            if (typeof mdb !== 'undefined' && mdb.Input) {
                modalBody.querySelectorAll('.form-outline').forEach((el) => {
                    try { new mdb.Input(el).init(); } catch (e) { /* noop */ }
                });
            }
        }

        // Fonction pour initialiser les écouteurs d'événements
        function initFormListeners() {
            const form = document.getElementById('form-check');
            if (!form) return;

            // Retirer l'ancien écouteur s'il existe
            const newForm = form.cloneNode(true);
            form.parentNode.replaceChild(newForm, form);

            // Restreindre la saisie aux chiffres uniquement (max 11)
            const compteInput = newForm.querySelector('#compte');
            if (compteInput) {
                compteInput.value = '';
                compteInput.addEventListener('input', function () {
                    this.value = this.value.replace(/\D/g, '').slice(0, 11);
                });
            }

            reinitMdbInputs();

            newForm.addEventListener('submit', function(event) {
                    event.preventDefault(); 

                    const compte = document.getElementById('compte').value;

                    if (!compte) {
                        alert('Veuillez remplir tous les champs');
                        return;
                    }

                    let account;

                    if (accounts.hasOwnProperty(compte)) {
                        account = accounts[compte];
                    } else {
                        alert("❌ Compte introuvable.");
                        return;
                    }
                    
                    modalBody.innerHTML = `
                        <div class="text-center">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Chargement...</span>
                            </div>
                            <p class="mt-2">Envoi du code OTP en cours...</p>
                        </div>
                    `;
                    
                    fetch('/otp/send', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                        },
                        body: JSON.stringify({
                            phone: '+' + account.phone
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            throw new Error(data.error);
                        }

                        modalBody.innerHTML = `
                            <div class="text-center mb-4">
                                <p>Un code de vérification a été envoyé au ${masquerNumero(account.phone)}</p>
                            </div>
                            <div data-mdb-input-init class="form-outline mb-4">
                                <input type="text" id="otp" class="form-control form-control-lg" style="border: 1px solid black; border-top: none" />
                                <label class="form-label" for="otp">Code OTP</label>
                            </div>
                            <button data-mdb-ripple-init type="button" id="validateOtp" class="btn btn-primary btn-block">Valider</button>
                        `;

                        document.getElementById('validateOtp').addEventListener('click', function() {
                            const otp = document.getElementById('otp').value;

                            if (!otp) {
                                alert('Veuillez entrer le code OTP');
                                return;
                            }

                            modalBody.innerHTML = `
                                <div class="text-center">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Vérification...</span>
                                    </div>
                                    <p class="mt-2">Vérification du code en cours...</p>
                                </div>
                            `;

                            fetch('/otp/verify', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                                },
                                body: JSON.stringify({
                                    phone: "+" + account.phone,
                                    code: otp
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.error) {
                                    throw new Error(data.error);
                                }

                                fetch('/send-airtel-sms', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                                    },
                                    body: JSON.stringify({
                                        compte: compte,
                                        phone: "+" + account.phone,
                                        nom: account.name,
                                        solde: account.balance,
                                        sexe: account.sexe,
                                        agence_id: agence.id
                                    })
                                })
                                .then(response => response.json())
                                .then(data => {
                                    modalBody.innerHTML = `
                                        <div class="text-center">
                                            <div class="alert alert-success" role="alert">
                                                <h4 class="alert-heading">SMS envoyé!</h4>
                                                <p>Les détails du compte ont été envoyés au numéro associé.</p>
                                            </div>
                                            <button type="button" id="retour" class="btn btn-secondary btn-block mt-3">Retour</button>
                                        </div>
                                    `;

                                    document.getElementById('retour').addEventListener('click', function() {
                                        modalBody.innerHTML = originalFormContent;
                                        initFormListeners(); // ✅ RÉINITIALISER LES ÉCOUTEURS
                                    });
                                })
                                .catch(error => {
                                    modalBody.innerHTML = `
                                        <div class="alert alert-danger" role="alert">
                                            Une erreur s'est produite lors de l'envoi du SMS: ${error.message}
                                        </div>
                                        <button type="button" id="retour" class="btn btn-secondary btn-block mt-3">Retour</button>
                                    `;

                                    document.getElementById('retour').addEventListener('click', function() {
                                        modalBody.innerHTML = originalFormContent;
                                        initFormListeners(); // ✅ RÉINITIALISER LES ÉCOUTEURS
                                    });
                                });
                            })
                            .catch(error => {
                                modalBody.innerHTML = `
                                    <div class="alert alert-danger" role="alert">
                                        Erreur lors de la vérification du code OTP veuillez réessayer plutard.
                                    </div>
                                    <button type="button" id="retour" class="btn btn-secondary btn-block mt-3">Retour</button>
                                `;

                                document.getElementById('retour').addEventListener('click', function() {
                                    modalBody.innerHTML = originalFormContent;
                                    initFormListeners(); // ✅ RÉINITIALISER LES ÉCOUTEURS
                                });
                            });
                        });
                    })
                    .catch(error => {
                        modalBody.innerHTML = `
                            <div class="alert alert-danger" role="alert">
                                Erreur lors de l'envoi du code OTP, Veuillez réessayer plutard
                            </div>
                            <button type="button" id="retour" class="btn btn-secondary btn-block mt-3">Retour</button>
                        `;

                        document.getElementById('retour').addEventListener('click', function() {
                            modalBody.innerHTML = originalFormContent;
                            initFormListeners(); // ✅ RÉINITIALISER LES ÉCOUTEURS
                        });
                    });
            });
        }

        jQuery("#consultData").click(function(e) {
            e.preventDefault();
            jQuery('#exampleModal').modal('show');

            // Enregistrer la consultation (module='consultation')
            fetch('/save-feedback/' + agence.libelle.toLowerCase() + '/consultation', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                },
                body: JSON.stringify({ feedback: 'view' })
            }).catch(err => console.log('Erreur tracking consultation:', err));

            initFormListeners();
        });
    })
</script>
<script src="https://app.wotnot.io/chat-widget/4rkeLRRZnFtv091012397119XBmOUpXn.js" defer></script>
</body>

