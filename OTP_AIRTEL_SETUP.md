# Configuration du système OTP avec Airtel

## Variables d'environnement requises

Ajoutez les variables suivantes dans votre fichier `.env` :

```env
# Configuration Airtel SMS
AIRTEL_BASE_URL=https://messaging.airtel.ga:9002/smshttp/qs/
AIRTEL_USERNAME=BGFI
AIRTEL_PASSWORD=bgfi@012
AIRTEL_ORIGIN_ADDR=BGFI

# Configuration OTP
OTP_LENGTH=6
OTP_EXPIRY_MINUTES=10
OTP_MAX_ATTEMPTS=3
```

## Routes disponibles

### Routes Web (avec CSRF)

- `POST /otp/send` - Envoyer un code OTP
- `POST /otp/verify` - Vérifier un code OTP
- `POST /otp/check` - Vérifier si un OTP valide existe
- `POST /otp/invalidate` - Invalider un OTP existant

### Routes API (sans CSRF)

- `POST /api/otp/send` - Envoyer un code OTP
- `POST /api/otp/verify` - Vérifier un code OTP
- `POST /api/otp/check` - Vérifier si un OTP valide existe
- `POST /api/otp/invalidate` - Invalider un OTP existant

## Exemples d'utilisation

### Envoyer un code OTP

```bash
curl -X POST http://localhost/otp/send \
  -H "Content-Type: application/json" \
  -H "X-CSRF-TOKEN: votre-token-csrf" \
  -d '{
    "phone": "24177750737"
  }'
```

Avec un message personnalisé :

```bash
curl -X POST http://localhost/otp/send \
  -H "Content-Type: application/json" \
  -H "X-CSRF-TOKEN: votre-token-csrf" \
  -d '{
    "phone": "24177750737",
    "message": "Votre code de vérification est : {code}. Valide 10 minutes."
  }'
```

### Vérifier un code OTP

```bash
curl -X POST http://localhost/otp/verify \
  -H "Content-Type: application/json" \
  -H "X-CSRF-TOKEN: votre-token-csrf" \
  -d '{
    "phone": "24177750737",
    "code": "123456"
  }'
```

### Vérifier si un OTP existe

```bash
curl -X POST http://localhost/otp/check \
  -H "Content-Type: application/json" \
  -H "X-CSRF-TOKEN: votre-token-csrf" \
  -d '{
    "phone": "24177750737"
  }'
```

### Invalider un OTP

```bash
curl -X POST http://localhost/otp/invalidate \
  -H "Content-Type: application/json" \
  -H "X-CSRF-TOKEN: votre-token-csrf" \
  -d '{
    "phone": "24177750737"
  }'
```

## Réponses API

### Envoi OTP - Succès

```json
{
  "success": true,
  "message": "Code OTP envoyé avec succès",
  "data": {
    "expires_in_minutes": 10
  }
}
```

### Envoi OTP - Erreur

```json
{
  "success": false,
  "message": "Échec de l'envoi du code OTP",
  "error": "Message d'erreur détaillé"
}
```

### Vérification OTP - Succès

```json
{
  "success": true,
  "message": "Code OTP vérifié avec succès",
  "data": {
    "verified": true,
    "attempts_remaining": 2
  }
}
```

### Vérification OTP - Échec

```json
{
  "success": false,
  "message": "Code OTP incorrect. 2 tentative(s) restante(s).",
  "data": {
    "verified": false,
    "attempts_remaining": 2
  }
}
```

## Format des numéros de téléphone

Le système accepte les formats suivants :
- `24177750737` (format complet avec indicatif)
- `077750737` (format local avec 0)
- `+24177750737` (format international)

Le système formatera automatiquement le numéro pour l'API Airtel.

## Sécurité

- Les codes OTP sont stockés dans le cache avec expiration (10 minutes par défaut)
- Limitation du nombre de tentatives (3 par défaut)
- Les codes OTP sont supprimés après vérification réussie ou expiration
- Les codes OTP ne sont jamais retournés dans les réponses API (sauf en développement)

## Intégration dans le code

### Utilisation dans un contrôleur

```php
use App\Services\OtpService;

class MonController extends Controller
{
    public function __construct(private OtpService $otpService)
    {
    }

    public function envoyerOtp(Request $request)
    {
        $result = $this->otpService->sendOtp($request->phone);
        
        if ($result['success']) {
            // OTP envoyé avec succès
            return response()->json(['message' => 'Code envoyé']);
        }
        
        // Gérer l'erreur
        return response()->json(['error' => $result['message']], 500);
    }

    public function verifierOtp(Request $request)
    {
        $result = $this->otpService->verifyOtp($request->phone, $request->code);
        
        if ($result['valid']) {
            // Code OTP valide
            return response()->json(['message' => 'Code vérifié']);
        }
        
        // Code invalide
        return response()->json(['error' => $result['message']], 400);
    }
}
```

### Utilisation directe du service Airtel

```php
use App\Services\AirtelSmsService;

$airtelService = app(AirtelSmsService::class);
$result = $airtelService->sendSms('24177750737', 'Votre message ici');

if ($result['success']) {
    // SMS envoyé avec succès
}
```

## Notes importantes

1. **API locale** : L'API Airtel est configurée pour fonctionner en local (`messaging.airtel.ga:9002`)
2. **Timeout** : Le timeout est fixé à 30 secondes pour les requêtes HTTP
3. **Logs** : Toutes les opérations sont loggées pour faciliter le débogage
4. **Cache** : Les codes OTP sont stockés dans le cache Laravel (Redis/File selon votre configuration)

## Tests

Pour tester l'envoi d'un SMS directement avec l'API Airtel :

```bash
curl -G "https://messaging.airtel.ga:9002/smshttp/qs/" \
  --data-urlencode "REQUESTTYPE=SMSSubmitReq" \
  --data-urlencode "MOBILENO=24177750737" \
  --data-urlencode "USERNAME=BGFI" \
  --data-urlencode "PASSWORD=bgfi@012" \
  --data-urlencode "ORIGIN_ADDR=BGFI" \
  --data-urlencode "TYPE=0" \
  --data-urlencode "MESSAGE=Test message"
```

