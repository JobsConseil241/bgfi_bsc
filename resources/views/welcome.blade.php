
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
    <!-- STYLE -->
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
            left:80px;
            width:300px;
            text-align:left;
            line-height:30px;
            font-family:limerick;
            font-size: 35px;
        }
        .light-blue{
            color:#48b2c5;
        }
        .light-brown {
            color:#b2b88f
        }
        .blue{
            color:#2563a2
        }.
    </style>
    <div id="avis" class="tabcontent">

        <div class="container-fluid">
            <div class="position-relative overflow-hidden p-md-5 text-center vh-100 row bg-home d-flex">
                <div class="col-md-2 p-md-5 mx-auto my-5">

                    <div class="img text-start" style="margin-left: -15px; margin-top: -120px">
                        <img style="margin-top:10px;margin-left:-10px;" src="/assets/backend/dist/img/Logo.png55" class="img-fluid logo" alt="Fissure in Sandstone">
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

                <div class="col-md-7 mx-auto pt-5 acCadre" style="margin-top:-65px">
                    <div class="row">
                        <div style="position:relative" class="col-md-6 mb-2 text-start">
                            <span style="margin-left:10%" class="text-light textNormal">Veuillez sélectionner un service</span>
                        </div>
                        <div class="col-md-6"></div>


                        <div style="position: relative;visibility:visible" class="col-6 mb-3 text-end">
                            <a onmousedown="return mise_a_jourfaq(faq)" class="tablinks" onclick="openCity(event, 'faq')">
                                <img style="width:90%" src="/assets/backend/dist/img/Homme-sans-texte.jpg41" class="img-fluid" alt="Fissure in Sandstone">
                                <span class="card-sbtitle light-blue">Je cherche des réponses</span>
                            </a>

                        </div>

                        <!--<a class="tablinks" href="http://10.20.20.41:8080/OnlineBankingGB/#!/login?agence=venus">-->


                        <div style="position: relative;visibility:visible" onmousedown="return mise_a_jourconsultation(consultation)" class="col-6 mb-2 text-start">
                            <!--<a class="tablinks" href="https://ga.bgfionline.com/ga_retail/index.ebk">-->
                            <img src="/assets/backend/dist/img/Femme-ordi-sans-texte.jpg08" style="width:90%" class="img-fluid" alt="Fissure in Sandstone">
                            <span class="card-sbtitle light-brown">Je consulte mon compte</span>

                        </div>


                        <div style="position: relative;visibility:visible" onmousedown="return mise_a_jourreclame(reclame)" class="col-6 mb-3 text-end">
                            <a class="tablinks" onclick="openCity(event, 'reclame')">
                                <img src="/assets/backend/dist/img/Dame-2-sans-texte.jpg09" style="width:90%" class="img-fluid" alt="Fissure in Sandstone">
                                <span class="card-sbtitle blue">Je fais une réclamation </span>
                            </a>
                        </div>


                        <div style="position: relative;visibility:visible" onmousedown="return mise_a_jouravis(avis)" class="col-6 mb-2 text-start">
                            <a class="tablinks" onclick="openCity(event, 'avis1')">
                                <img src="/assets/backend/dist/img/Dame-3-sans-texte (1).jpg34" style="width:90%" class="img-fluid" alt="Fissure in Sandstone">
                                <span class="card-sbtitle light-blue">Je donne mon avis sur ma banque</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
