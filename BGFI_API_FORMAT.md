# Données requises de la part de BGFI Bank

## Contexte

Dans le cadre du développement du chatbot WhatsApp BGFI Bank (consultation de solde avec vérification OTP), nous avons besoin que BGFI Bank nous fournisse un accès aux données clients. Actuellement, les données sont codées en dur dans l'application à des fins de test. **Nous avons besoin d'une source de données réelle.**

---

## Tableau récapitulatif des données nécessaires

| # | Champ | Type de donnée | Format / Exemple | Obligatoire | Description |
|---|-------|----------------|------------------|-------------|-------------|
| 1 | `numero_compte` | string (10 car.) | `1000123456` | Oui | Numéro de compte unique du client |
| 2 | `nom_complet` | string | `Jean MBONGO` | Oui | Nom et prénom(s) du titulaire du compte |
| 3 | `telephone` | string | `24177750737` | Oui | Numéro de téléphone associé au compte (pour l'envoi OTP) |
| 4 | `solde` | number (entier ou décimal) | `2450000` | Oui | Solde actuel du compte |
| 5 | `devise` | string (3 car.) | `XAF` | Oui | Devise du compte (code ISO 4217) |

> **Note :** BGFI Bank est libre d'ajouter d'autres informations propres au client (ex : type de compte, agence, date d'ouverture, plafond, etc.) si elle le souhaite. Les 5 champs ci-dessus représentent le **minimum requis** pour le bon fonctionnement de notre application.

---

## Mode de mise à disposition des données

Nous proposons à BGFI Bank **deux options** pour nous fournir ces données :

### Option 1 : API REST (recommandée)

BGFI met à disposition une **API REST sécurisée** que notre application consomme.

**Endpoint attendu :** Vérification de compte / consultation de solde

- **Requête :**
```
GET /api/accounts/{numero_compte}
Authorization: Bearer {token_fourni_par_bgfi}
```

- **Réponse attendue :**
```json
{
  "success": true,
  "data": {
    "numero_compte": "1000123456",
    "nom_complet": "Jean MBONGO",
    "telephone": "24177750737",
    "solde": 2450000,
    "devise": "XAF"
  }
}
```

- **Réponse si compte introuvable :**
```json
{
  "success": false,
  "message": "Compte introuvable"
}
```

**Avantages :** Données en temps réel, sécurisé, découplé.

---

### Option 2 : Serveur de base de données

BGFI nous donne un accès **en lecture seule** à un serveur de base de données (MySQL, PostgreSQL, Oracle, SQL Server...).

**Informations de connexion nécessaires :**

| Paramètre | Description |
|-----------|-------------|
| `DB_HOST` | Adresse IP ou nom d'hôte du serveur |
| `DB_PORT` | Port de connexion |
| `DB_DATABASE` | Nom de la base de données |
| `DB_USERNAME` | Utilisateur (lecture seule) |
| `DB_PASSWORD` | Mot de passe |
| `DB_DRIVER` | Type de base (mysql, pgsql, oracle, sqlsrv) |

**Structure de table attendue (exemple) :**

```sql
TABLE comptes_clients (
    numero_compte   VARCHAR(10)     PRIMARY KEY,
    nom_complet     VARCHAR(255)    NOT NULL,
    telephone       VARCHAR(20)     NOT NULL,
    solde           DECIMAL(15,2)   NOT NULL,
    devise          VARCHAR(3)      DEFAULT 'XAF'
);
```

**Avantages :** Intégration directe, requêtes flexibles.

---

### Option 3 : Fichier texte (.txt) deposé dans un dossier

BGFI dépose un fichier `.txt` dans un répertoire partagé. Notre application le lit automatiquement et importe les données.

**Format du fichier :**
- Encodage : UTF-8
- Séparateur : point-virgule (`;`)
- Première ligne : en-tête (noms des colonnes)
- Une ligne par compte client

**Exemple de fichier :**
```
numero_compte;nom_complet;telephone;solde;devise
1000123456;Jean MBONGO;24177750737;2450000;XAF
1000234567;Marie EYENGA;24166889900;875000;XAF
1000345678;Paul OBAMA;24107345678;1200000;XAF
```

**Fonctionnement :**
1. BGFI dépose le fichier dans le dossier convenu
2. Un traitement automatique (cron) s'exécute entre 12h et 16h
3. Les données sont chargées dans une table tampon
4. Une procédure stockée met à jour la table principale (insertion ou mise à jour)
5. Le fichier traité est archivé automatiquement

**Avantages :** Simple à mettre en place, pas d'API à développer côté BGFI.

---

## Résumé de la demande

> Nous avons besoin que BGFI Bank nous fournisse, via **une API REST**, **un accès base de données en lecture seule**, ou **un fichier .txt déposé dans un dossier**, les 5 champs suivants pour chaque compte client :
>
> **Numéro de compte, Nom complet, Téléphone, Solde, Devise**
>
> Ces données sont indispensables au fonctionnement du chatbot WhatsApp de consultation de solde avec vérification OTP.
