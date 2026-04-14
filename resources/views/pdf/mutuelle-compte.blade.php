<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Position de Compte</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #000;
            text-align: center;
            padding: 40px 20px;
            margin: 0;
            background: #ffffff;
        }
        .container {
            max-width: 800px;
            margin: auto;
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
            margin: 20px 0;
        }
        .solde {
            font-size: 20px;
            margin-top: 60px;
            margin-bottom: 40px;
        }
        .date {
            font-style: italic;
            margin: 30px 0;
        }
        .merci {
            font-weight: bold;
            font-size: 16px;
            margin: 40px 0;
        }
        footer {
            font-size: 10px;
            color: #003366;
            line-height: 1.6;
            margin-top: 80px;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="title">Position de Compte du {{ $date }}</h2>
        <hr class="header-line">

        <p class="solde">Le solde de votre compte est de {{ $solde }} </p>
        <p class="date">«{{ $dateHeure }}»</p>
        <p class="merci">Merci de votre fidélité.</p>

        <footer>
            <strong>Mutuelle</strong><br>
            Siège social: [Adresse] - BP: [Code Postal] [Ville] [Pays]<br>
            Téléphone: [Numéro] - Email: [Email]
        </footer>
    </div>
</body>
</html>
