<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de Réception</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .logo {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo img {
            max-width: 150px;
        }
        .email-header {
            text-align: center;
            background-color: #0D437A;
            color: #ffffff;
            padding: 10px 0;
            border-radius: 8px 8px 0 0;
        }
        .email-body {
            padding: 20px;
            color: #333333;
            line-height: 1.6;
        }
        .email-footer {
            text-align: center;
            font-size: 12px;
            color: #777777;
            padding: 10px;
            margin-top: 20px;
            border-top: 1px solid #dddddd;
        }
        .button {
            display: inline-block;
            background-color: #0D437A;
            color: #ffffff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
        }
    </style>
</head>
<body>
<div class="email-container">
    <!-- Section du Logo -->
    <div class="logo">
        <img src="https://groupebgfibank.com/assets/imgs/logo-bgfibank.png" alt="Logo de votre entreprise">
    </div>

    <!-- Section du Header -->
    <div class="email-header">
        <h1>Confirmation de Réception</h1>
    </div>

    <!-- Corps de l'email -->
    <div class="email-body">
        <h2>Bonjour Cher client,</h2>
        <p>Nous vous remercions d'avoir soumis votre message. Nous l'avons bien reçu et nous vous répondrons dans les plus brefs délais.</p>

        <p>Si vous avez des questions supplémentaires ou besoin d'une assistance immédiate, n'hésitez pas à répondre à cet email.</p>

        <p>En attendant, vous pouvez visiter notre site web en cliquant sur le bouton ci-dessous :</p>

        <p style="text-align: center;">
            <a href="https://votresiteweb.com" class="button">Visiter notre site web</a>
        </p>

        <p>Merci encore pour votre confiance !</p>

        <p>Cordialement, <br> L'équipe de {{ config('app.name') }}</p>
    </div>

    <!-- Section du Footer -->
    <div class="email-footer">
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Tous droits réservés.</p>
    </div>
</div>
</body>
</html>
