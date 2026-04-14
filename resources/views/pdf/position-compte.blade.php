<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Position de Compte</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #000;
            text-align: center;
        }
        .container {
            width: 100%;
            margin: auto;
        }
        .logo {
            position: absolute;
            left: 0;
            top: 0;
            width: 100px;
        }
        .title {
            font-size: 18px;
            font-weight: bold;
            color: #004A99;
            margin-top: 10px;
            text-transform: uppercase;
        }
        .header-line {
            border: 2px solid #004A99;
            width: 100%;
            margin-top: 10px;
        }
        .solde {
            font-size: 20px;
            margin-top: 100px;
        }
        .date {
            font-style: italic;
            margin: 30px 0;
        }
        .merci {
            font-weight: bold;
            font-size: 16px;
        }
        footer {
            font-size: 10px;
            color: #003366;
            line-height: 1.4;
            position: absolute;
            bottom: 20px;
            left: 0;
            right: 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="{{ public_path('logo-bgfi.jpg') }}" alt="Logo BGFI" class="logo">
        <h2 class="title">POSITION DE COMPTE DU {{ $date }}</h2>
        <hr class="header-line">

        <p class="solde">Le solde de votre compte est de {{ $solde }} Francs CFA</p>
        <p class="date">«{{ $dateHeure }}»</p>
        <p class="merci">Merci de votre fidélité.</p>

        <footer>
            <strong>BGFI Bank Gabon SA</strong>,<br>
            Avec Conseil d’Administration au capital social de 100 002 994 350 - N°statistique 071282 F - RCCM Libreville 2010 B 09140 - NIF 771 282<br>
            Siège social: 1295 Bld de l’Indépendance - BP: 2253 Libreville Gabon Télex 5265 GO - SWIFT: BGFIGALI
        </footer>
    </div>
</body>
</html>
