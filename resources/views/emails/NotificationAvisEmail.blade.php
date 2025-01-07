<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Un Avis Ajouté</title>
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
        table {
             border-collapse: collapse;
             width: 100%;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
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
        <h1>Avis du Client</h1>
    </div>

    <!-- Corps de l'email -->
    <div class="email-body">
        <h2>Bonjour M/Mme {{ $user->name }} ,</h2>
        <p>Un nouvel avis a été soumis par un utilisateur, Voici les détails de l'avis :</p>

        <table>
            <thead>
            <tr>
                <th>Champ</th>
                <th>Valeur</th>
            </tr>
            </thead>
            <tbody>
            @foreach($data as $key => $value)
                <tr>
                    <td>{{ ucfirst($key) }}</td>
                    <td>
                        @if(is_array(json_decode($value, true)))
                            {{ implode(', ', json_decode($value, true)) }}
                        @else
                            {{ $value }}
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <p>vous pouvez visiter cela sur votre dashboard en cliquant sur le bouton ci-dessous :</p>

        <p style="text-align: center;">
            <a href="{{ route('RecapitulatifAvis') }}" class="button" style="text-decoration: none; color: white">Tableau de Board</a>
        </p>

        <p>Merci encore pour votre confiance !</p>

        <p>Cordialement, <br> BGFIBank Gabon, Votre partenaire pour l'avenir</p>
    </div>

    <!-- Section du Footer -->
    <div class="email-footer">
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Tous droits réservés.</p>
    </div>
</div>
</body>
</html>
