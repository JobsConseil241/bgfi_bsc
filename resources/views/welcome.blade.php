
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BGFIBank</title>
    <!-- Font Awesome -->


    <!-- MDB -->
    <link rel="shortcut icon" type="image/x-icon" href="/assets/frontend/img/bgfi.jpg">
    <link href="/assets/frontend/css/mdb.min.css" rel="stylesheet" />
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
    <link rel="stylesheet" href="/assets/frontend/css/style.css">

    <link rel="stylesheet" type="text/css" href="fontawesome/css/fontawesome.css">
    <link rel="stylesheet" type="text/css" href="fontawesome/css/all.min.css">
    <style>
        .bg-home{
            background-image: url('/assets/frontend/img/Fond-1.jpg');
            background-position: center;
            background-size: cover;
            background-attachment: fixed;
        }
        .bg-other{
            background-image: url('/assets/frontend/img/fond2.jpg');
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

</head>
<body>
<!--Main Navigation-->
<header id="first">
    <style>
        /* Carousel styling */
        #introCarousel,
        .carousel-inner,
        .carousel-item,
        .carousel-item.active {
            height: 100vh;
        }

        .carousel-item:nth-child(1) {
            background-image: url('/assets/frontend/img/advert/one.jpeg');
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center center;
        }

        .carousel-item:nth-child(2) {
            background-image: url('/assets/frontend/img/advert/two.jpeg');
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center center;
        }

        .carousel-item:nth-child(3) {
            background-image: url('/assets/frontend/img/advert/three.jpeg');
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center center;
        }

        .carousel-item:nth-child(4) {
            background-image: url('/assets/frontend/img/advert/four.jpeg');
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

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark d-none d-lg-block" style="z-index: 2000;">
        <div class="container-fluid">
            <!-- Navbar brand -->
            <a class="navbar-brand nav-link" target="_blank">
                <img style="" src="{{ asset('/public/assets/backend/dist/img/Logo55.png') }}" class="" height="30"
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
            <li data-mdb-target="#introCarousel" data-mdb-slide-to="0" class="active"></li>
            <li data-mdb-target="#introCarousel" data-mdb-slide-to="1"></li>
            <li data-mdb-target="#introCarousel" data-mdb-slide-to="2"></li>
            <li data-mdb-target="#introCarousel" data-mdb-slide-to="3"></li>
        </div>

        <!-- Inner -->
        <div class="carousel-inner">
            <!-- Single item -->
            <div class="carousel-item active">
                <div class="mask" style="background-color: rgba(0, 0, 0, 0.6);">
                    <div class="d-flex justify-content-center align-items-center h-100">
                        <div class="text-white text-center" data-mdb-theme="dark">
                            <h1 class="mb-3">BIENVENUE SUR VOTRE ESPACE CLIENT <br> DE VOTRE BANQUE BGFI</h1>
                            <h5 class="mb-4">Votre Partenanire pour l'avenir !</h5>
                            <a class="btn btn-secondary btn-rounded btn-lg m-2 startHome" data-mdb-ripple-init href="#"
                               target="_blank" role="button">Appuyez Pour Commencer !</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Single item -->
            <div class="carousel-item">
                <div class="mask" style="background-color: rgba(0, 0, 0, 0.3);">
                    <div class="d-flex justify-content-center align-items-center h-100">
                        <div class="text-white text-center" data-mdb-theme="dark">
                            <h1 class="mb-3">BIENVENUE SUR VOTRE ESPACE CLIENT <br> DE VOTRE BANQUE BGFI</h1>
                            <h5 class="mb-4">Votre Partenanire pour l'avenir !</h5>
                            <a class="btn btn-secondary btn-rounded btn-lg m-2 startHome" data-mdb-ripple-init href="#"
                               target="_blank" role="button">Appuyez Pour Commencer !</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Single item -->
            <div class="carousel-item">
                <div class="mask" style="background-color: rgba(0, 0, 0, 0.3);">
                    <div class="d-flex justify-content-center align-items-center h-100">
                        <div class="text-white text-center" data-mdb-theme="dark">
                            <h1 class="mb-3">BIENVENUE SUR VOTRE ESPACE CLIENT <br> DE VOTRE BANQUE BGFI</h1>
                            <h5 class="mb-4">Votre Partenanire pour l'avenir !</h5>
                            <a class="btn btn-secondary btn-rounded btn-lg m-2 startHome" data-mdb-ripple-init href="#"
                               target="_blank" role="button">Appuyez Pour Commencer !</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Single item -->
            <div class="carousel-item">
                <div class="mask" style="
                                     background: linear-gradient(
                                     45deg,
                                     rgba(29, 236, 197, 0.7),
                                     rgba(91, 14, 214, 0.7) 100%
                                     );
                                     ">
                    <div class="d-flex justify-content-center align-items-center h-100" data-mdb-theme="dark">
                        <div class="text-white text-center" data-mdb-theme="dark">
                            <h1 class="mb-3">BIENVENUE SUR VOTRE ESPACE CLIENT <br> DE VOTRE BANQUE BGFI</h1>
                            <h5 class="mb-4">Votre Partenanire pour l'avenir !</h5>
                            <a class="btn btn-secondary btn-rounded btn-lg m-2 startHome" data-mdb-ripple-init href="#"
                               target="_blank" role="button" >Appuyez Pour Commencer !</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Inner -->
    </div>
    <!-- Carousel wrapper -->
</header>
<!--Main Navigation-->

<div id="avis" class="tabcontent d-none">

    <div class="container-fluid">
        <div class="position-relative overflow-hidden p-md-5 text-center vh-100 row bg-home d-flex">
            <div class="col-md-2 p-md-5 mx-auto my-5">

                <div class="img text-start" style="margin-left: -15px; margin-top: -120px">
                    <img style="margin-top:10px;margin-left:-10px;" src="/assets/backend/dist/img/Logo55.png" class="img-fluid logo" alt="Fissure in Sandstone">
                </div>
                <div class="welcome">
                    <div style="position:absolute;top:60%">
                        <div class="text-start">
                            <h1 class="text-light h1 bienvenue"><strong>BGFIBank</strong></h1>
                        </div>
                        <div class="text-start text-light">
                            <small class="textNormal">Votre partenaire pour l'avenir</small>
                        </div>
                        <div class="text-start">

                        </div>

                        <img src="/assets/frontend/img/Les certificats.png" class="text-start mb-3 mt-3" width="250px" style="margin-left: 0px;">
                        <p><img src="/assets/frontend/img/Tarait.png" class="text-start mb-5" width="250px" style="margin-left: 0px;"></p>
                    </div>

                </div>
            </div>

            <div class="col-md-7 mx-auto pt-5 acCadre">
                <div class="row">
                    <div style="position:relative" class="col-md-12 mb-2 text-start">
                        <h6 style="margin-left:15%" class="text-light font-weight-bold">Veuillez sélectionner un service</h6>
                    </div>

                    <div style="position: relative;visibility:visible" class="col-6 mb-3 text-end">
                        <a onmousedown="return mise_a_jourfaq(faq)" class="tablinks" onclick="openCity(event, 'faq')" href="/agence/venus/faq">
                            <img style="width:70%" src="/assets/backend/dist/img/Homme-sans-texte.jpg41" class="img-fluid" alt="Fissure in Sandstone">
                            <span class="card-sbtitle light-blues">Je cherche des réponses</span>
                        </a>

                    </div>

                    <!--<a class="tablinks" href="http://10.20.20.41:8080/OnlineBankingGB/#!/login?agence=venus">-->


                    <div style="position: relative;visibility:visible" onmousedown="return mise_a_jourconsultation(consultation)" class="col-6 mb-2 text-start">
                        <!--<a class="tablinks" href="https://ga.bgfionline.com/ga_retail/index.ebk">-->
                        <img src="/assets/backend/dist/img/Femme-ordi-sans-texte.jpg08" style="width:70%" class="img-fluid" alt="Fissure in Sandstone">
                        <span class="card-sbtitle light-brown">Je consulte mon compte</span>

                    </div>


                    <div style="position: relative;visibility:visible" onmousedown="return mise_a_jourreclame(reclame)" class="col-6 mb-3 text-end">
                        <a class="tablinks" onclick="openCity(event, 'reclame')">
                            <img src="/assets/backend/dist/img/Dame-2-sans-texte.jpg09" style="width:70%" class="img-fluid" alt="Fissure in Sandstone">
                            <span class="card-sbtitle light-blue">Je fais une réclamation </span>
                        </a>
                    </div>


                    <div style="position: relative;visibility:visible" onmousedown="return mise_a_jouravis(avis)" class="col-6 mb-2 text-start">
                        <a class="tablinks" onclick="openCity(event, 'avis1')">
                            <img src="/assets/backend/dist/img/Dame-3-sans-texte (1).jpg34" style="width:70%" class="img-fluid" alt="Fissure in Sandstone">
                            <span class="card-sbtitle light-brown">Je donne mon avis sur ma banque</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="/assets/frontend/js/zoom.js"></script>
<!-- MDB -->
<script
    type="text/javascript"
    src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.2/mdb.umd.min.js"
></script>

<script>
    // A $( document ).ready() block.
    jQuery( document ).ready(function() {

        jQuery(".startHome").click(function (event) {
            event.preventDefault();
            jQuery("#first").hide()
            jQuery('#avis').removeClass('d-none')
            checkActivity();
        })



        var activite_detectee = false;
        var intervalle = 1000;
        var temps_inactivite = 10 * 1000;
        var inactivite_persistante = true;
        // On crée la fonction qui teste toutes les x secondes l'activité du visiteur via activite_detectee
        function testerActivite() {
            // On teste la variable activite_detectee
            // Si une activité a été détectée [On réinitialise activite_detectee, temps_inactivite et inactivite_persistante]
            if(activite_detectee) {
                activite_detectee = false;
                temps_inactivite = 10 * 1000;
                inactivite_persistante = false;
            }
            // Si aucune activité n'a été détectée [on actualise le statut du visiteur et on teste/met à jour la valeur du temps d'inactivité]
            else {
                statut('inactif');
                // Si l'inactivite est persistante [on met à jour temps_inactivite]
                if(inactivite_persistante) {
                    temps_inactivite -= intervalle;
                    // Si le temps d'inactivite dépasse les 30 secondes
                    if(temps_inactivite == 0){
                        jQuery("#first").show()
                        jQuery('#avis').addClass('d-none')
                    }


                }
                // Si l'inactivite est nouvelle [on met à jour inactivite_persistante]
                else
                    inactivite_persistante = true;
            }
            // On relance la fonction ce qui crée une boucle
            setTimeout('testerActivite();', intervalle);
        }

        var timeout = false;
        function checkActivity() {
            clearTimeout(timeout);
            timeout = setTimeout(function () {
                jQuery("#first").show()
                jQuery('#avis').addClass('d-none')
                clearTimeout(timeout)
            }, 10000);
        }
        document.addEventListener('keydown', checkActivity);
        document.addEventListener('mousedown', checkActivity);
        document.addEventListener('mousemove', checkActivity);
    });
</script>
</body>

