
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BGFIBank</title>
    <!-- Font Awesome -->


    <!-- MDB -->
    <link rel="shortcut icon" type="image/x-icon" href="{{url('assets/frontend/img/bgfi.jpg')}}">
    <link href="{{url('assets/frontend/css/mdb.min.css')}}" rel="stylesheet" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Font Awesome -->
    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
        rel="stylesheet"
    />
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
    <link rel="stylesheet" href="{{url('assets/frontend/css/style.css') }}">

    <link rel="stylesheet" type="text/css" href="{{url('assets/frontend/css/fontawesome.css')}}">
    <link rel="stylesheet" type="text/css" href="{{url('assets/frontend/css/all.min.css')}}">

    <!-- CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css" />

    <!-- JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>

    <style>

        .bg-home{
            background-image: url('{{url('assets/frontend/img/Fond-1.webp') }}');
            background-position: center;
            background-size: cover;
            background-attachment: fixed;
        }
        #bg-other{
            {{--background-image: url('{{url('assets/frontend/img/fond2.webp') }}');--}}
            {{--background-position: center;--}}
            {{--background-size: cover;--}}
            {{--background-attachment: fixed;--}}
            height: 75vh !important;
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
            left: 185px !important;
        }
        .light-blues{
            color:#48b2c5;
            left: 185px !important;
        }
        .light-brown {
            color:#b2b88f;
            left: 41px !important;
        }
        .blue{
            color:#2563a2;
        }.
    </style>
    <style>
        /* Carousel styling */
        #introCarousel,
        .carousel-inner,
        .carousel-item,
        .carousel-item.active {
            height: 100vh;
        }

        .carousel-item:nth-child(1) {
            background-image: url('https://mdbootstrap.com/img/Photos/Others/images/76.jpg');
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center center;
        }

        .carousel-item:nth-child(2) {
            background-image: url('https://mdbootstrap.com/img/Photos/Others/images/77.jpg');
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center center;
        }

        .carousel-item:nth-child(3) {
            background-image: url('https://mdbootstrap.com/img/Photos/Others/images/78.jpg');
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center center;
        }

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
    <style type="text/css">

        .active{
            display: block;
        }
        /* Style the tab content */
        .tabcontent {
            display: none;

        }
        #avis{
            display: block;
        }


        .card {
            position: relative;
            display: flex;
            flex-direction: column;
            min-width: 0;
            word-wrap: break-word;
            background-color: #fff;
            background-clip: border-box;
            border: 1px solid rgba(0,0,0,.125);
            border-radius: .5rem;
        }
        .card {
            border: 0;
            box-shadow: 0 10px 15px -3px rgba(0,0,0,.07),0 4px 6px -2px rgba(0,0,0,.05);
        }

        .card-bodye {
            flex: 1 1 auto;
            padding: 1.5rem;
        }

        .tab1 {
            display: none;
        }

        .step {
            opacity: 1 !important;
        }
        .step1{
            opacity: 1;
        }
        .step1.active {
            opacity: 1;
        }

        /* Mark the steps that are finished and valid: */
        .step1.finish {
            background-color: #04AA6D;
        }



        .tab3 {
            display: none;
        }
        .step3{
            opacity: 1;
        }
        .step3.active {
            opacity: 1;
        }

        /* Mark the steps that are finished and valid: */
        .step3.finish {
            background-color: #04AA6D;
        }




        @media only screen and (max-width:1920px) and (max-height:1280px) {
            .taille{
                font-size:35px;
            }


            #ckeckb{
                margin-top:-19px;
            }

        }
        @media only screen and (max-height:600px) {
            .taille{
                font-size:17px;
            }

            .taille2{
                font-size:17px;
            }
            #ckeckb{
                margin-top:0px;
            }
        }
    </style>


    <style type="text/css">


        .toast__containern {
            display: table-cell;
            vertical-align: middle;
            z-index: 100000000;


        }

        .toast__celln{
            display:inline-block;
        }

        .add-marginn{
            margin-top:20px;
        }

        .toast__svgn{
            fill:#fff;
        }

        .toastn {
            text-align:left;
            padding: 21px 0;
            background-color:#fff;
            border-radius:4px;
            max-width: 500px;
            top: 0px;
            position:relative;
            box-shadow: 1px 7px 14px -5px rgba(0,0,0,0.2);
        }


        .toastn:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            border-top-left-radius:4px;
            border-bottom-left-radius: 4px;

        }

        .toast__iconn{
            position:absolute;
            top:50%;
            left:22px;
            transform:translateY(-50%);
            width:14px;
            height:14px;
            padding: 7px;
            border-radius:50%;
            display:inline-block;
        }

        .toast__typen {
            color: #3e3e3e;
            font-weight: 700;
            margin-top: 0;
            margin-bottom: 8px;
        }

        .toast__messagen {
            font-size: 14px;
            margin-top: 0;
            margin-bottom: 0;
            color: #878787;
        }

        .toast__contentn{
            padding-left:70px;
            padding-right:60px;
        }

        .toast__closen {
            position: absolute;
            right: 22px;
            top: 50%;
            width: 14px;
            cursor:pointer;
            height: 14px;
            fill:#878787;
            transform: translateY(-50%);
        }

        .toast--green .toast__icon{
            background-color:#2BDE3F;
        }

        .toast--green:before{
            background-color:#2BDE3F;
        }

        .toast--blue .toast__icon{
            background-color:#1D72F3;
        }

        .toast--bluen:before{
            background-color:#1D72F3;
        }

        .toast--yellown .toast__iconn{
            background-color:#b2b88f
        }

        .toast--yellown:before{
            background-color:#b2b88f;
        }
        .iti{
            width: 100% !important;
        }
        .checkbox-grid-container {
            display: flex;
            justify-content: center; /* Centre horizontalement la grille */
            align-items: center;     /* Centre verticalement la grille */
        }

        .checkbox-grid {
            display: grid;
            grid-template-columns: 1fr 1fr; /* Deux colonnes de taille égale */
            gap: 20px 20px; /* Espacement vertical et horizontal */
            width: 50%;
        }

        .checkbox-grid div {
            display: flex;
            align-items: center; /* Aligne verticalement les checkboxes et les labels */
        }

        .checkbox-grid input[type="checkbox"] {
            margin-right: 10px; /* Espacement entre la checkbox et le label */
        }
        .custom-radio-grid-container {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .custom-radio-grid {
            display: flex;
            gap: 20px 83px; /* Espace entre les boutons radio */
            justify-content: center; /* Centre les éléments */
        }

        .custom-radio {
            display: flex;
            align-items: center;
            flex-direction: column; /* Affichage vertical pour chaque option */
            text-align: center;
        }

        .custom-radio input[type="radio"] {
            display: none; /* Cache les boutons radio par défaut */
        }

        .custom-radio label {
            display: flex;
            flex-direction: column;
            align-items: center;
            cursor: pointer;
        }

        .custom-radio img {
            width: 170px;
            height: 170px;
            margin-bottom: 5px; /* Espace sous l'image */
            display: none; /* Cache les images par défaut */
        }

        .custom-radio img.unchecked {
            display: block; /* Affiche l'image non cochée par défaut */
        }

        /* Change d'image lorsque la radio est cochée */
        .custom-radio input[type="radio"]:checked + label img.unchecked {
            display: none; /* Cache l'image non cochée */
        }

        .custom-radio input[type="radio"]:checked + label img.checked {
            display: block; /* Affiche l'image cochée */
        }

        .custom-radio span {
            margin-top: 5px;
            font-size: 20px; /* Taille du texte */
            color: #105095;
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

<div id="loader">
    <div class="spinner"></div>
</div>

<div id="faq" class="tabcontent" style="display: none;" >
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top navbar-light bg-transparent shadow-0">
        <!-- Container wrapper -->
        <div class="container-fluid">
            <!-- Toggle button -->
            <div class="img text-start" style="margin-left: -35px; margin-top: -150px;">
                <img src="{{url('assets/backend/dist/img/Logo55.png') }}" style="height:100px;width:100px;margin-top:150px;margin-left:50px" class="img-fluid logo" alt="Fissure in Sandstone">
            </div>
            <button class="navbar-toggler" type="button" data-mdb-toggle="collapse" data-mdb-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <svg class="svg-inline--fa fa-bars" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="bars" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg=""><path fill="currentColor" d="M0 96C0 78.3 14.3 64 32 64H416c17.7 0 32 14.3 32 32s-14.3 32-32 32H32C14.3 128 0 113.7 0 96zM0 256c0-17.7 14.3-32 32-32H416c17.7 0 32 14.3 32 32s-14.3 32-32 32H32c-17.7 0-32-14.3-32-32zM448 416c0 17.7-14.3 32-32 32H32c-17.7 0-32-14.3-32-32s14.3-32 32-32H416c17.7 0 32 14.3 32 32z"></path></svg><!-- <i class="fas fa-bars"></i> Font Awesome fontawesome.com -->
            </button>

            <!-- Collapsible wrapper -->
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Navbar brand -->

                <!-- Left links -->
                <ul class="navbar-nav ms-auto me-auto text-end mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="#" style="cursor: default; color:#105095;">

                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <h3>&nbsp; &nbsp; &nbsp; &nbsp;</h3>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <h3>&nbsp;</h3>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <h3>&nbsp;</h3>
                        </a>
                    </li>
                </ul>
                <!-- Left links -->
            </div>
        </div>
        <!-- Container wrapper -->
    </nav>
    <!-- Navbar -->
    <div class="container-fluid">
        <div align="center">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="#" style="cursor: default; color: #105095;">
                        <h1 class="bienvenu">
                            JE CONSULTE MON SOLDE
                        </h1>
                    </a>
                </li>
            </ul>
        </div>
        <br><br>
        <div class="position-relative overflow-hidden p-md-5 text-center row bg-yother h-100" id="bg-other">
            <div class="col-8 mx-auto my-auto text-center card p-5">
                <form id="dynamicForm" action="#" method="POST">
                    @csrf
                    <div>
                        <div align="center">
                            <label style="color: #0D437A;font-weight: bold;font-size:25px; margin-bottom: 15px" for="account_number">Numéro de compte</label>
                            <input  type="text" id="account_number"  name="" class="form-control" style="border-color: #0F5095;height:50px; width: 100% !important" required>
                        </div>

                        <div class="mt-4">
                            <button type="reset" class="btn btn-outline-primary btn-rounded border-0 m-1 bienvenu btn-lg" style="width: 150px; color: #1266f1; background: rgb(193, 212, 236);">Annuler</button>
                            <button type="submit" class="btn btn-outline-success btn-rounded border-0 m-1 bienvenu btn-lg" style="width: 150px; color: #1266f1; background: rgb(193, 212, 236);" >Soumettre</button>
                        </div>
                    </div>
                </form>

                <div id="successMessage" style="display:none;">

                    <div class="text-center text-light pt-3">
                        <h1 class="display-6 text-muted h1 bienvenu"><strong style="color: #105095; margin-bottom: 15px">Merci d’avoir répondu à notre questionnaire de satisfaction.</strong></h1>
                        <h1 class="display-6 text-muted h1 bienvenu"><strong style="color: #b2b88f;">À bientôt dans votre agence BGFI</strong></h1>

                        <br>
                        <font style="color: #b2b88f;"><h2>Ce service vous a-t-il été utile ?</h2></font>
                        <table align="center" style="margin-top: 20px" width="300px"><tbody><tr><td>
                                    <svg id="oui_o" onclick="handleLike()" class="svg-inline--fa fa-thumbs-up" style="height: 30px;color: #105095; margin-right: 45px" aria-hidden="true" focusable="false" data-prefix="far" data-icon="thumbs-up" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M96 191.1H32c-17.67 0-32 14.33-32 31.1v223.1c0 17.67 14.33 31.1 32 31.1h64c17.67 0 32-14.33 32-31.1V223.1C128 206.3 113.7 191.1 96 191.1zM512 227c0-36.89-30.05-66.92-66.97-66.92h-99.86C354.7 135.1 360 113.5 360 100.8c0-33.8-26.2-68.78-70.06-68.78c-46.61 0-59.36 32.44-69.61 58.5c-31.66 80.5-60.33 66.39-60.33 93.47c0 12.84 10.36 23.99 24.02 23.99c5.256 0 10.55-1.721 14.97-5.26c76.76-61.37 57.97-122.7 90.95-122.7c16.08 0 22.06 12.75 22.06 20.79c0 7.404-7.594 39.55-25.55 71.59c-2.046 3.646-3.066 7.686-3.066 11.72c0 13.92 11.43 23.1 24 23.1h137.6C455.5 208.1 464 216.6 464 227c0 9.809-7.766 18.03-17.67 18.71c-12.66 .8593-22.36 11.4-22.36 23.94c0 15.47 11.39 15.95 11.39 28.91c0 25.37-35.03 12.34-35.03 42.15c0 11.22 6.392 13.03 6.392 22.25c0 22.66-29.77 13.76-29.77 40.64c0 4.515 1.11 5.961 1.11 9.456c0 10.45-8.516 18.95-18.97 18.95h-52.53c-25.62 0-51.02-8.466-71.5-23.81l-36.66-27.51c-4.315-3.245-9.37-4.811-14.38-4.811c-13.85 0-24.03 11.38-24.03 24.04c0 7.287 3.312 14.42 9.596 19.13l36.67 27.52C235 468.1 270.6 480 306.6 480h52.53c35.33 0 64.36-27.49 66.8-62.2c17.77-12.23 28.83-32.51 28.83-54.83c0-3.046-.2187-6.107-.6406-9.122c17.84-12.15 29.28-32.58 29.28-55.28c0-5.311-.6406-10.54-1.875-15.64C499.9 270.1 512 250.2 512 227z"></path></svg>

                                    <svg id="non_o" onclick="handleDislike()" class="svg-inline--fa fa-thumbs-down" style="height: 30px;color: #105095;" aria-hidden="true" focusable="false" data-prefix="far" data-icon="thumbs-down" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M128 288V64.03c0-17.67-14.33-31.1-32-31.1H32c-17.67 0-32 14.33-32 31.1v223.1c0 17.67 14.33 31.1 32 31.1h64C113.7 320 128 305.7 128 288zM481.5 229.1c1.234-5.092 1.875-10.32 1.875-15.64c0-22.7-11.44-43.13-29.28-55.28c.4219-3.015 .6406-6.076 .6406-9.122c0-22.32-11.06-42.6-28.83-54.83c-2.438-34.71-31.47-62.2-66.8-62.2h-52.53c-35.94 0-71.55 11.87-100.3 33.41L169.6 92.93c-6.285 4.71-9.596 11.85-9.596 19.13c0 12.76 10.29 24.04 24.03 24.04c5.013 0 10.07-1.565 14.38-4.811l36.66-27.51c20.48-15.34 45.88-23.81 71.5-23.81h52.53c10.45 0 18.97 8.497 18.97 18.95c0 3.5-1.11 4.94-1.11 9.456c0 26.97 29.77 17.91 29.77 40.64c0 9.254-6.392 10.96-6.392 22.25c0 13.97 10.85 21.95 19.58 23.59c8.953 1.671 15.45 9.481 15.45 18.56c0 13.04-11.39 13.37-11.39 28.91c0 12.54 9.702 23.08 22.36 23.94C456.2 266.1 464 275.2 464 284.1c0 10.43-8.516 18.93-18.97 18.93H307.4c-12.44 0-24 10.02-24 23.1c0 4.038 1.02 8.078 3.066 11.72C304.4 371.7 312 403.8 312 411.2c0 8.044-5.984 20.79-22.06 20.79c-12.53 0-14.27-.9059-24.94-28.07c-24.75-62.91-61.74-99.9-80.98-99.9c-13.8 0-24.02 11.27-24.02 23.99c0 7.041 3.083 14.02 9.016 18.76C238.1 402 211.4 480 289.9 480C333.8 480 360 445 360 411.2c0-12.7-5.328-35.21-14.83-59.33h99.86C481.1 351.9 512 321.9 512 284.1C512 261.8 499.9 241 481.5 229.1z"></path></svg>
                                </td></tr></tbody></table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <nav class="navbar navbar-expand-lg fixed-bottom navbar-light">
        <div class="container-fluid">
            <!-- Toggle button -->
            <a class="navbar-brand mt-lg-0 tablinks" href="/agence/venus?click=yes">
                <span class="btn text-light" style="font-size:20px; background-color: #b2b88f; border-radius: 25px;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-bottom: 4px;">
                      <path d="M15 19L8 12L15 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Accueil
                </span>
            </a>
        </div>
        <!-- Container wrapper -->
    </nav>

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="/assets/frontend/js/zoom.js"></script>
<!-- MDB -->
<script
    type="text/javascript"
    src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.2/mdb.umd.min.js"
></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // A $( document ).ready() block.
    let currentStep = 1;
    const url = window.location.href;
    const parsedUrl = new URL(url);
    const pathParts = parsedUrl.pathname.split('/');
    const agence = pathParts[2]; // Ici, '123'

    window.addEventListener('load', function () {
        const bgImage = new Image();
        bgImage.src = "/assets/frontend/img/fond2.webp"; // Remplacez par le chemin de votre image

        // Ajoutez un écouteur pour vérifier si l'image est bien chargée
        bgImage.onload = function () {
            // Applique l'image de fond à la div
            const content = document.getElementById("bg-other");
            content.style.backgroundImage = `url('${bgImage.src}')`;
            content.style.backgroundSize = "cover";
            content.style.backgroundPosition = "center";
            content.style.backgroundRepeat = "no-repeat";
            content.style.height = "75vh !important";

            // Cache le loader et affiche le contenu une fois l'image chargée
            document.getElementById('loader').style.display = 'none';
            document.getElementById('faq').style.display = 'block';
            content.style.opacity = 1;
        };
    });

    // Fonction pour passer à l'étape suivante

    jQuery( document ).ready(function() {

        // Récupérer l'URL actuelle
        const url = window.location.href;
        const parsedUrl = new URL(url);
        const pathParts = parsedUrl.pathname.split('/');
        const agence = pathParts[2]; // Ici, '123'


        jQuery(".startHome").click(function (event) {
            event.preventDefault();
            jQuery("#first").hide()
            jQuery('#avis').removeClass('d-none')
            checkActivity();
        })

        // Soumission du formulaire via AJAX
        $("#dynamicForm").submit(function (event) {

            event.preventDefault(); // Empêche le rechargement de la page

            // Collecte des données du formulaire
            var formData = $(this).serialize();
            var compteBancaire = document.getElementById("account_number").value;

            console.log(compteBancaire.length)
            if (compteBancaire.length === 12 && !isNaN(compteBancaire)) {
                Swal.fire({
                    title: "Rentrer votre numero de telephone",
                    input: "text",
                    inputAttributes: {
                        autocapitalize: "off"
                    },
                    showCancelButton: true,
                    confirmButtonText: "Verifier",
                    showLoaderOnConfirm: true,
                    preConfirm: async (login) => {
                        try {
                            const githubUrl = `
                                https://api.github.com/users/${login}
                              `;
                            const response = await fetch(githubUrl);
                            if (!response.ok) {
                                return Swal.showValidationMessage(`
                                  ${JSON.stringify(await response.json())}
                                `);
                            }
                            return response.json();
                        } catch (error) {
                            Swal.showValidationMessage(`
                                Request failed: ${error}
                              `);
                        }
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: `${result.value.login}'s avatar`,
                            imageUrl: result.value.avatar_url
                        });
                    }
                });
            } else {
                // Sinon, afficher un message d'erreur
                Swal.fire({
                    icon: 'error',  // 'error' pour une alerte de type danger
                    title: 'Action ratée',  // Titre de l'alerte
                    text: 'Entrez un numero de compte valide.',  // Message d'erreur
                    confirmButtonText: 'Essayer à nouveau',  // Texte du bouton de confirmation
                });
            }

            // Soumission AJAX
            // $.ajax({
            //     type: 'POST',
            //     url: '/agence/' + agence + '/avis', // Remplace par l'URL de ton endpoint
            //     data: formData,
            //     success: function (response) {
            //         if (response.status === 200) {
            //             $('#dynamicForm').hide();
            //             $('#successMessage').show();
            //         }
            //     },
            //     error: function (xhr, status, error) {
            //         // Gérer l'erreur ici
            //         alert('Erreur lors de la soumission du formulaire: ' + error);
            //         console.error(xhr);
            //     }
            // });
        });
    })

    const INACTIVITY_TIME = 10000; // 10 secondes
    let inactivityTimer;
    let swalInstance = null;
    let shouldRedirect = true;


    function warnAndRedirect() {
        let timerInterval;

        shouldRedirect = true;

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
                    window.location.href = '/agence/' + agence + '?click=yes';

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

    function handleLike() {
        Swal.fire({
            title: 'Merci pour votre avis !',
            text: 'Vous avez aimé cette réponse.',
            icon: 'success',
            showCancelButton: true,
            confirmButtonText: 'Confirmer',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                // Changer la couleur du SVG de like
                document.getElementById(`oui_o`).style.color = '#b2b88f';
                document.getElementById(`non_o`).style.color = '#105095'; // Reset dislike color if it was previously clicked
                // Appel AJAX pour enregistrer le like
                saveFeedback('like');
            }
        });
    }

    // Fonction pour gérer le Dislike
    function handleDislike() {
        Swal.fire({
            title: 'Merci pour votre avis !',
            text: 'Vous n\'avez pas aimé cette réponse.',
            icon: 'error',
            showCancelButton: true,
            confirmButtonText: 'Confirmer',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                // Changer la couleur du SVG de dislike
                document.getElementById(`non_o`).style.color = '#b2b88f';
                document.getElementById(`oui_o`).style.color = '#105095'; // Reset like color if it was previously clicked
                // Appel AJAX pour enregistrer le dislike
                saveFeedback('dislike');
            }
        });
    }

    function saveFeedback(type) {


        var token = $('meta[name="csrf-token"]').attr('content');
        // Remplacez par un véritable appel AJAX ici
        $.ajax({
            url: '/save-feedback/' + agence + '/avis',
            method: 'POST',
            data: { feedback: type, "_token": token, },
            success: function(response) {
                if(response.status === 200) {
                    window.location.href = '/agence/' + agence + '?click=yes';
                }
            }
        });
    }

    resetInactivityTimer()
</script>
</body>
