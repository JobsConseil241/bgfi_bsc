
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <style>

        .bg-home{
            background-image: url('{{url('assets/frontend/img/Fond-1.webp') }}');
            background-position: center;
            background-size: cover;
            background-attachment: fixed;
        }
        .bg-other{
            {{--background-image: url('{{url('assets/frontend/img/fond2.webp') }}');--}}
            {{--background-position: center;--}}
            {{--background-size: cover;--}}
            {{--background-attachment: fixed;--}}
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






        .tab1 {
            display: none;
        }
        .step1{
            opacity: 0.5;
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
            opacity: 0.5;
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

<div id="faq" class="tabcontent" style="display: none;">
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
                            FOIRE AUX QUESTIONS
                        </h1>
                    </a>
                </li>
            </ul>
        </div>
        <br><br>
        <div class="position-relative overflow-hidden p-md-5 text-center row bg-yother" id="bg-other">
            <div align="center" class="col-9 mx-auto my-auto text-start">
                <input type="text" id="search-text" class="form-control rounded" style="height:50px;margin-bottom:5px;width:80%;margin-left:10%" placeholder="Tapez une recherche...">
                <!--ACCORDEON-->
                <div class="accordion mt-0 pt-0" id="FAqAccordion" style="height:650px;overflow-y:auto">
                    @foreach($Faq as $dt)
                        @if ($loop->first)
                            <div class="accordion-item rounded mb-0" style="border-radius:50px 50px 0 0;"    >
                                <h2 class="accordion-header" id="faq{{$dt->id}}">
                                    <button
                                        data-mdb-collapse-init
                                        type="button"
                                        class="accordion-button bienvenu taille"
                                        style="border-radius:50px 50px 0 0; color:#b2b88f"
                                        data-mdb-target="#collapse{{$dt->id}}"
                                        aria-expanded="true"
                                        aria-controls="collapse{{$dt->id}}"
                                    >
                                        {{$dt->titre}}
                                    </button>
                                </h2>
                                <div id="collapse{{$dt->id}}" class="accordion-collapse collapse" aria-labelledby="faq{{$dt->id}}" data-mdb-parent="#FAqAccordion">
                                    <div class="accordion-body">
                                        <ul>
                                            @foreach($dt->reponses as $rp)
                                                <li>{{$rp->reponse}}</li>
                                            @endforeach
                                        </ul>

                                        <br>
                                        <font style="color: black;font-size: 18px;  margin-left: 20px">Cette réponse vous a-t-elle été utile ?</font>
                                        <table style="margin-top: 10px; margin-left: 20px" width="300px"><tbody><tr><td>
                                                    <svg id="like-{{ $dt->id }}" onclick="handleLike({{ $dt->id }})" class="svg-inline--fa fa-thumbs-up" style="height: 30px;color: #105095; margin-right: 45px" aria-hidden="true" focusable="false" data-prefix="far" data-icon="thumbs-up" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M96 191.1H32c-17.67 0-32 14.33-32 31.1v223.1c0 17.67 14.33 31.1 32 31.1h64c17.67 0 32-14.33 32-31.1V223.1C128 206.3 113.7 191.1 96 191.1zM512 227c0-36.89-30.05-66.92-66.97-66.92h-99.86C354.7 135.1 360 113.5 360 100.8c0-33.8-26.2-68.78-70.06-68.78c-46.61 0-59.36 32.44-69.61 58.5c-31.66 80.5-60.33 66.39-60.33 93.47c0 12.84 10.36 23.99 24.02 23.99c5.256 0 10.55-1.721 14.97-5.26c76.76-61.37 57.97-122.7 90.95-122.7c16.08 0 22.06 12.75 22.06 20.79c0 7.404-7.594 39.55-25.55 71.59c-2.046 3.646-3.066 7.686-3.066 11.72c0 13.92 11.43 23.1 24 23.1h137.6C455.5 208.1 464 216.6 464 227c0 9.809-7.766 18.03-17.67 18.71c-12.66 .8593-22.36 11.4-22.36 23.94c0 15.47 11.39 15.95 11.39 28.91c0 25.37-35.03 12.34-35.03 42.15c0 11.22 6.392 13.03 6.392 22.25c0 22.66-29.77 13.76-29.77 40.64c0 4.515 1.11 5.961 1.11 9.456c0 10.45-8.516 18.95-18.97 18.95h-52.53c-25.62 0-51.02-8.466-71.5-23.81l-36.66-27.51c-4.315-3.245-9.37-4.811-14.38-4.811c-13.85 0-24.03 11.38-24.03 24.04c0 7.287 3.312 14.42 9.596 19.13l36.67 27.52C235 468.1 270.6 480 306.6 480h52.53c35.33 0 64.36-27.49 66.8-62.2c17.77-12.23 28.83-32.51 28.83-54.83c0-3.046-.2187-6.107-.6406-9.122c17.84-12.15 29.28-32.58 29.28-55.28c0-5.311-.6406-10.54-1.875-15.64C499.9 270.1 512 250.2 512 227z"></path></svg>

                                                    <svg id="dislike-{{ $dt->id }}" onclick="handleDislike({{ $dt->id }})" class="svg-inline--fa fa-thumbs-down" style="height: 30px;color: #105095;" aria-hidden="true" focusable="false" data-prefix="far" data-icon="thumbs-down" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M128 288V64.03c0-17.67-14.33-31.1-32-31.1H32c-17.67 0-32 14.33-32 31.1v223.1c0 17.67 14.33 31.1 32 31.1h64C113.7 320 128 305.7 128 288zM481.5 229.1c1.234-5.092 1.875-10.32 1.875-15.64c0-22.7-11.44-43.13-29.28-55.28c.4219-3.015 .6406-6.076 .6406-9.122c0-22.32-11.06-42.6-28.83-54.83c-2.438-34.71-31.47-62.2-66.8-62.2h-52.53c-35.94 0-71.55 11.87-100.3 33.41L169.6 92.93c-6.285 4.71-9.596 11.85-9.596 19.13c0 12.76 10.29 24.04 24.03 24.04c5.013 0 10.07-1.565 14.38-4.811l36.66-27.51c20.48-15.34 45.88-23.81 71.5-23.81h52.53c10.45 0 18.97 8.497 18.97 18.95c0 3.5-1.11 4.94-1.11 9.456c0 26.97 29.77 17.91 29.77 40.64c0 9.254-6.392 10.96-6.392 22.25c0 13.97 10.85 21.95 19.58 23.59c8.953 1.671 15.45 9.481 15.45 18.56c0 13.04-11.39 13.37-11.39 28.91c0 12.54 9.702 23.08 22.36 23.94C456.2 266.1 464 275.2 464 284.1c0 10.43-8.516 18.93-18.97 18.93H307.4c-12.44 0-24 10.02-24 23.1c0 4.038 1.02 8.078 3.066 11.72C304.4 371.7 312 403.8 312 411.2c0 8.044-5.984 20.79-22.06 20.79c-12.53 0-14.27-.9059-24.94-28.07c-24.75-62.91-61.74-99.9-80.98-99.9c-13.8 0-24.02 11.27-24.02 23.99c0 7.041 3.083 14.02 9.016 18.76C238.1 402 211.4 480 289.9 480C333.8 480 360 445 360 411.2c0-12.7-5.328-35.21-14.83-59.33h99.86C481.1 351.9 512 321.9 512 284.1C512 261.8 499.9 241 481.5 229.1z"></path></svg>
                                                </td></tr></tbody></table>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if($loop->iteration !== 1)
                                <div class="accordion-item rounded mb-0" style="border-radius:50px 50px 0 0;" >
                                    <h2 class="accordion-header" id="faq{{$dt->id}}">
                                        <button
                                            data-mdb-collapse-init
                                            type="button"
                                            class="accordion-button collapsed bienvenu taille"
                                            style="border-radius:50px 50px 0 0; color:#b2b88f"
                                            data-mdb-target="#collapse{{$dt->id}}"
                                            aria-expanded="false"
                                            aria-controls="collapse{{$dt->id}}"
                                        >
                                            {{$dt->titre}}
                                        </button>
                                    </h2>
                                    <div id="collapse{{$dt->id}}" class="accordion-collapse collapse" aria-labelledby="faq{{$dt->id}}" data-mdb-parent="#FAqAccordion">
                                        <div class="accordion-body">
                                            <ul>
                                                @foreach($dt->reponses as $rp)
                                                    <li>{{$rp->reponse}}</li>
                                                @endforeach
                                            </ul>

                                            <br>
                                            <font style="color: black;font-size: 18px;  margin-left: 20px">Cette réponse vous a-t-elle été utile ?</font>
                                            <table style="margin-top: 10px; margin-left: 20px" width="300px"><tbody><tr><td>
                                                        <svg id="like-{{ $dt->id }}" onclick="handleLike({{ $dt->id }})" class="svg-inline--fa fa-thumbs-up" style="height: 30px;color: #105095; margin-right: 45px" aria-hidden="true" focusable="false" data-prefix="far" data-icon="thumbs-up" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M96 191.1H32c-17.67 0-32 14.33-32 31.1v223.1c0 17.67 14.33 31.1 32 31.1h64c17.67 0 32-14.33 32-31.1V223.1C128 206.3 113.7 191.1 96 191.1zM512 227c0-36.89-30.05-66.92-66.97-66.92h-99.86C354.7 135.1 360 113.5 360 100.8c0-33.8-26.2-68.78-70.06-68.78c-46.61 0-59.36 32.44-69.61 58.5c-31.66 80.5-60.33 66.39-60.33 93.47c0 12.84 10.36 23.99 24.02 23.99c5.256 0 10.55-1.721 14.97-5.26c76.76-61.37 57.97-122.7 90.95-122.7c16.08 0 22.06 12.75 22.06 20.79c0 7.404-7.594 39.55-25.55 71.59c-2.046 3.646-3.066 7.686-3.066 11.72c0 13.92 11.43 23.1 24 23.1h137.6C455.5 208.1 464 216.6 464 227c0 9.809-7.766 18.03-17.67 18.71c-12.66 .8593-22.36 11.4-22.36 23.94c0 15.47 11.39 15.95 11.39 28.91c0 25.37-35.03 12.34-35.03 42.15c0 11.22 6.392 13.03 6.392 22.25c0 22.66-29.77 13.76-29.77 40.64c0 4.515 1.11 5.961 1.11 9.456c0 10.45-8.516 18.95-18.97 18.95h-52.53c-25.62 0-51.02-8.466-71.5-23.81l-36.66-27.51c-4.315-3.245-9.37-4.811-14.38-4.811c-13.85 0-24.03 11.38-24.03 24.04c0 7.287 3.312 14.42 9.596 19.13l36.67 27.52C235 468.1 270.6 480 306.6 480h52.53c35.33 0 64.36-27.49 66.8-62.2c17.77-12.23 28.83-32.51 28.83-54.83c0-3.046-.2187-6.107-.6406-9.122c17.84-12.15 29.28-32.58 29.28-55.28c0-5.311-.6406-10.54-1.875-15.64C499.9 270.1 512 250.2 512 227z"></path></svg>

                                                        <svg id="dislike-{{ $dt->id }}" onclick="handleDislike({{ $dt->id }})" class="svg-inline--fa fa-thumbs-down" style="height: 30px;color: #105095;" aria-hidden="true" focusable="false" data-prefix="far" data-icon="thumbs-down" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M128 288V64.03c0-17.67-14.33-31.1-32-31.1H32c-17.67 0-32 14.33-32 31.1v223.1c0 17.67 14.33 31.1 32 31.1h64C113.7 320 128 305.7 128 288zM481.5 229.1c1.234-5.092 1.875-10.32 1.875-15.64c0-22.7-11.44-43.13-29.28-55.28c.4219-3.015 .6406-6.076 .6406-9.122c0-22.32-11.06-42.6-28.83-54.83c-2.438-34.71-31.47-62.2-66.8-62.2h-52.53c-35.94 0-71.55 11.87-100.3 33.41L169.6 92.93c-6.285 4.71-9.596 11.85-9.596 19.13c0 12.76 10.29 24.04 24.03 24.04c5.013 0 10.07-1.565 14.38-4.811l36.66-27.51c20.48-15.34 45.88-23.81 71.5-23.81h52.53c10.45 0 18.97 8.497 18.97 18.95c0 3.5-1.11 4.94-1.11 9.456c0 26.97 29.77 17.91 29.77 40.64c0 9.254-6.392 10.96-6.392 22.25c0 13.97 10.85 21.95 19.58 23.59c8.953 1.671 15.45 9.481 15.45 18.56c0 13.04-11.39 13.37-11.39 28.91c0 12.54 9.702 23.08 22.36 23.94C456.2 266.1 464 275.2 464 284.1c0 10.43-8.516 18.93-18.97 18.93H307.4c-12.44 0-24 10.02-24 23.1c0 4.038 1.02 8.078 3.066 11.72C304.4 371.7 312 403.8 312 411.2c0 8.044-5.984 20.79-22.06 20.79c-12.53 0-14.27-.9059-24.94-28.07c-24.75-62.91-61.74-99.9-80.98-99.9c-13.8 0-24.02 11.27-24.02 23.99c0 7.041 3.083 14.02 9.016 18.76C238.1 402 211.4 480 289.9 480C333.8 480 360 445 360 411.2c0-12.7-5.328-35.21-14.83-59.33h99.86C481.1 351.9 512 321.9 512 284.1C512 261.8 499.9 241 481.5 229.1z"></path></svg>
                                                    </td></tr></tbody></table>
                                        </div>
                                    </div>

                                    <script>
                                        jQuery(document).ready(function() {
                                            jQuery('#collapse{{$dt->id}}').on('shown.bs.collapse', function () {
                                                handleView({{$dt->id}})
                                                // Actions à effectuer lorsque l'accordéon est ouvert
                                            });
                                        });
                                    </script>
                                </div>
                        @endif
                    @endforeach
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
<script src="{{url('assets/frontend/js/zoom.js')}}"></script>
<!-- MDB -->
<script
    type="text/javascript"
    src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.2/mdb.umd.min.js"
></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
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

    let agences = @json($agence)

    const url = window.location.href;
    const parsedUrl = new URL(url);
    const pathParts = parsedUrl.pathname.split('/');
    const agence = pathParts[2]; // Ici, '123'

    // A $( document ).ready() block.
    jQuery( document ).ready(function() {

        jQuery(".startHome").click(function (event) {
            event.preventDefault();
            jQuery("#first").hide()
            jQuery('#avis').removeClass('d-none')
            resetInactivityTimer();
        })


        jQuery('#search-text').on('keyup', function () {
            var searchText = jQuery(this).val().toLowerCase(); // Récupérer la valeur de l'input et la convertir en minuscule

            // Parcourir tous les items de l'accordéon
            jQuery('.accordion-item').each(function () {
                var title = jQuery(this).find('.accordion-button').text().toLowerCase(); // Récupérer le texte du titre de l'item en minuscule

                // Afficher l'item si le titre contient le texte recherché, sinon le cacher
                if (title.includes(searchText)) {
                    jQuery(this).show();
                } else {
                    jQuery(this).hide();
                }
            });
        })
    });

    const INACTIVITY_TIME = agences.delais * 1000; // 10 secondes
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
                    // window.location.href = "https://votre-page-de-redirection.com";
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

    function handleLike(id) {
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
                document.getElementById(`like-${id}`).style.color = '#b2b88f';
                document.getElementById(`dislike-${id}`).style.color = '#105095'; // Reset dislike color if it was previously clicked
                // Appel AJAX pour enregistrer le like
                saveFeedback(id, 'like');
            }
        });
    }

    // Fonction pour gérer le Dislike
    function handleDislike(id) {
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
                document.getElementById(`dislike-${id}`).style.color = '#b2b88f';
                document.getElementById(`like-${id}`).style.color = '#105095'; // Reset like color if it was previously clicked
                // Appel AJAX pour enregistrer le dislike
                saveFeedback(id, 'dislike');
            }
        });
    }

    function handleView(id) {
        saveFeedback(id, 'view');
    }


    function saveFeedback(id, type) {


        var token = $('meta[name="csrf-token"]').attr('content');
        // Remplacez par un véritable appel AJAX ici
        $.ajax({
            url: '/save-faq-feedback/' + agence + '/' + id,
            method: 'POST',
            data: { feedback: type, "_token": token, },
            success: function(response) {
                if(response.status === 200 &&  !response.data) {
                    Swal.fire({
                        icon: "success",
                        title: "Succès",
                        text: "Enregistré avec Succès !"
                    });
                }
            }
        });
    }

    resetInactivityTimer()
</script>
</body>
