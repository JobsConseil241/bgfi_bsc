# CAHIER DES CHARGES
## BGFI Corner BSC v2 — Plateforme Digitale d'Assistance Bancaire

---

**Client :** BGFI Bank (Banque Gabonaise et Française Internationale)
**Version :** 2.0
**Date :** 27 février 2026
**Statut :** En cours de développement
**Branche :** `wha+otp`

---

## TABLE DES MATIERES

1. [Contexte et objectifs](#1-contexte-et-objectifs)
2. [Périmètre fonctionnel](#2-périmètre-fonctionnel)
3. [Architecture technique](#3-architecture-technique)
4. [Module 1 — Portail Client en Agence (BSC)](#4-module-1--portail-client-en-agence-bsc)
5. [Module 2 — Chatbot WhatsApp BGFI Bank](#5-module-2--chatbot-whatsapp-bgfi-bank)
6. [Module 3 — Chatbot WhatsApp AMIE MUTUELLE](#6-module-3--chatbot-whatsapp-amie-mutuelle)
7. [Module 4 — Système OTP (Airtel)](#7-module-4--système-otp-airtel)
8. [Module 5 — Pipeline d'import des données bancaires](#8-module-5--pipeline-dimport-des-données-bancaires)
9. [Module 6 — Tableau de bord administrateur](#9-module-6--tableau-de-bord-administrateur)
10. [Module 7 — Statistiques de pertinence](#10-module-7--statistiques-de-pertinence)
11. [Modèle de données](#11-modèle-de-données)
12. [Intégrations et services tiers](#12-intégrations-et-services-tiers)
13. [Contraintes et règles transversales](#13-contraintes-et-règles-transversales)
14. [Sécurité](#14-sécurité)
15. [Points d'attention et divergences](#15-points-dattention-et-divergences)

---

## 1. CONTEXTE ET OBJECTIFS

### 1.1 Contexte

BGFI Bank, institution bancaire majeure au Gabon, souhaite moderniser l'expérience client en agence et à distance via une plateforme digitale multi-canal. L'application est destinée à être déployée sur des **bornes tactiles** dans les agences bancaires ainsi qu'accessible via **WhatsApp** pour la consultation de solde à distance.

### 1.2 Objectifs

| # | Objectif | Description |
|---|----------|-------------|
| O1 | Améliorer la satisfaction client | Offrir un portail interactif en agence (FAQ, avis, réclamations) |
| O2 | Digitaliser la consultation de solde | Permettre aux clients de consulter leur solde via borne tactile ou WhatsApp |
| O3 | Sécuriser l'accès aux données | Authentification par OTP (SMS) avant toute consultation de solde |
| O4 | Centraliser le pilotage | Dashboard administrateur avec statistiques et gestion multi-agences |
| O5 | Automatiser l'import des données | Pipeline automatisé d'import des comptes bancaires depuis fichiers CSV/TXT |
| O6 | Multi-tenant | Chaque agence dispose de son propre espace personnalisable |

### 1.3 Périmètre géographique

- **Pays :** Gabon
- **Langue :** Français exclusivement
- **Devise :** XAF (Franc CFA) par défaut
- **Indicatif téléphonique :** +241

---

## 2. PERIMETRE FONCTIONNEL

L'application couvre **7 modules fonctionnels** :

```
┌─────────────────────────────────────────────────────────────────┐
│                    BGFI Corner BSC v2                           │
├──────────────────┬──────────────────┬───────────────────────────┤
│  PORTAIL CLIENT  │  CHATBOTS WHATSAPP│  BACK-OFFICE            │
│  (Borne agence)  │                   │                          │
│                  │  ┌──────────────┐ │  ┌────────────────────┐  │
│  - FAQ           │  │ BGFI Bank    │ │  │ Dashboard Admin    │  │
│  - Avis          │  │ (WaAPI)      │ │  │ Gestion agences    │  │
│  - Réclamation   │  └──────────────┘ │  │ Gestion users      │  │
│  - Consultation  │  ┌──────────────┐ │  │ Stats pertinence   │  │
│    solde         │  │ AMIE Mutuelle│ │  │ Récapitulatifs     │  │
│                  │  │ (Whapi)      │ │  │ Marketing          │  │
│                  │  └──────────────┘ │  └────────────────────┘  │
├──────────────────┴──────────────────┴───────────────────────────┤
│  SERVICES TRANSVERSAUX                                          │
│  - OTP (Airtel SMS + Twilio Verify)                             │
│  - Import CSV/TXT automatisé                                    │
│  - Tracking & analytics                                         │
└─────────────────────────────────────────────────────────────────┘
```

---

## 3. ARCHITECTURE TECHNIQUE

### 3.1 Stack technologique

| Composant | Technologie | Version |
|-----------|-------------|---------|
| Framework backend | Laravel | 11.x |
| Langage | PHP | 8.x |
| Base de données | MySQL | — |
| Frontend public | Bootstrap 5 + MDB UI Kit | — |
| Frontend admin | Template Sneat/Bootstrap | — |
| JavaScript | jQuery + SweetAlert2 + Chart.js | — |
| Cache | Laravel Cache (fichier/Redis) | — |
| Authentification API | Laravel Sanctum | 4.0 |
| Gestion des rôles | Spatie Laravel Permission | 6.9 |
| Génération PDF | Barryvdh DomPDF | 3.1 |
| Client HTTP | Guzzle | 7.9 |

### 3.2 Services externes

| Service | Fournisseur | Usage |
|---------|-------------|-------|
| WhatsApp API (BGFI) | WaAPI (waapi.app) | Envoi/réception messages chatbot BGFI |
| WhatsApp API (AMIE) | Whapi (gate.whapi.cloud) | Envoi/réception messages chatbot AMIE |
| OTP SMS (chatbot) | Twilio Verify v2 | Vérification par SMS pour les chatbots |
| OTP SMS (portail) | Airtel Gabon | Vérification par SMS pour le portail web |
| Email transactionnel | SMTP Laravel | Notifications avis/réclamations |

### 3.3 Schéma d'architecture

```
                    ┌──────────────┐
                    │  Client Web  │
                    │ (Borne agence)│
                    └──────┬───────┘
                           │ HTTPS
                    ┌──────▼───────┐
                    │   Laravel    │
                    │  Application │◄────── Cron (bgfi:import)
                    └──┬───┬───┬──┘           │
                       │   │   │        ┌─────▼──────┐
              ┌────────┘   │   └──────┐ │ Fichiers   │
              │            │          │ │ CSV/TXT    │
        ┌─────▼────┐ ┌────▼────┐ ┌───▼──────┐ └────────────┘
        │  MySQL   │ │ WaAPI   │ │  Twilio  │
        │  (BDD)   │ │ / Whapi │ │  Verify  │
        └──────────┘ └────┬────┘ └──────────┘
                          │
                    ┌─────▼──────┐
                    │  WhatsApp  │
                    │  (Client)  │
                    └────────────┘
```

---

## 4. MODULE 1 — PORTAIL CLIENT EN AGENCE (BSC)

### 4.1 Description générale

Application web responsive déployée sur bornes tactiles dans les agences BGFI Bank. Chaque agence dispose de son propre espace accessible via une URL dédiée.

**Pattern URL :** `/agence/{nom_agence}`

### 4.2 Écran d'accueil

| Élément | Description |
|---------|-------------|
| Affichage | Carousel plein écran avec visuels marketing configurables par agence |
| CTA | Bouton "Appuyez Pour Commencer" |
| Transition | Loader spinner puis affichage du menu de services |
| Inactivité | Timer configurable par agence (`agence.delais` en secondes) |
| Expiration | Alerte SweetAlert2 avec compte à rebours de **3 secondes** puis retour au carousel |
| Détection activité | Événements : `mousemove`, `click`, `keydown`, `touchstart` |

### 4.3 Menu de services

Quatre services disponibles, **activables/désactivables indépendamment** par agence :

| Service | Flag BDD | URL | Description |
|---------|----------|-----|-------------|
| FAQ | `has_faq` | `/agence/{nom}/faq` | Questions fréquentes |
| Consultation de compte | `has_consult` | Modal intégré | Consultation de solde avec OTP |
| Réclamation | `has_reclame` | `/agence/{nom}/reclamation` | Formulaire de plainte |
| Avis | `has_avis` | `/agence/{nom}/avis` | Questionnaire de satisfaction |

> Les services désactivés affichent un overlay "Bientôt Disponible".

### 4.4 Personnalisation par agence (Settings)

| Paramètre | Description | Défaut |
|-----------|-------------|--------|
| `titre` | Titre affiché | "BGFIBank" |
| `stitre` | Sous-titre | "Votre partenaire pour l'avenir" |
| `titre_service` | Titre du menu services | — |
| `faq_logo` / `consult_logo` / `recla_logo` / `avis_logo` | Images des boutons | Configurables |
| `faq_stitre` / `consult_stitre` / `recla_stitre` / `avis_stitre` | Libellés boutons | Configurables |
| `delais` | Délai d'inactivité (secondes) | Variable par agence |

### 4.5 Sous-module FAQ

**Fonctionnalités :**
- Affichage de toutes les FAQ associées à l'agence avec leurs réponses
- Système de Like / Dislike par FAQ
- Tracking des visites (création d'un enregistrement `Consultation` avec `module='faq'`)
- Statistiques par FAQ : nombre de vues, likes, dislikes

**Flux utilisateur :**
```
[Menu] → [Liste FAQ] → [Clic sur une FAQ] → [Affichage réponse]
                                             → [Like / Dislike]
```

### 4.6 Sous-module Consultation de Solde (Portail Web)

**Flux détaillé :**

```
1. [Clic "Je consulte mon compte"]
        │
        ▼ Tracking : POST /save-feedback/{agence}/consultation
        │
2. [Modal - Saisie du numéro de compte]
        │
        ▼ Recherche côté client (JS)
        │
3. ├── [Compte introuvable] → Alerte "Compte introuvable"
   │
   └── [Compte trouvé]
        │
        ▼ POST /otp/send { phone: "+{telephone}" }
        │
4. [Spinner "Envoi OTP en cours..."]
        │
        ▼
5. [Champ OTP affiché + téléphone masqué]
        │
        ▼ POST /otp/verify { phone, code }
        │
6. ├── [Échec OTP] → Message d'erreur + Bouton Retour
   │
   └── [Succès OTP]
        │
        ▼ POST /send-airtel-sms { compte, phone, nom, solde, sexe, agence_id }
        │
7. [Message "SMS envoyé ! Les détails ont été envoyés au numéro associé."]
        │
        ▼ [Bouton Retour → réinitialisation]
```

**Règles de masquage du téléphone :**
```
2 premiers chiffres + *** + 2 derniers chiffres
Exemple : 07******37
```

### 4.7 Sous-module Avis (Satisfaction Client)

**Caractéristiques :**
- Formulaire dynamique multi-étapes (wizard)
- Champs chargés depuis la BDD (`ChampFormulaire` associés au `Formulaire` de type `avis`)
- Ordonnancement par `position ASC`
- Navigation Précédent / Suivant avec validation HTML5 native

**Types de champs supportés :**

| Type | Comportement |
|------|-------------|
| `select` | Liste déroulante. Cas spécial `nationalite` : JSON de pays avec Gabon pré-sélectionné |
| `radio` | Boutons radio avec options depuis BDD |
| `checkbox` | Cases à cocher, valeurs sérialisées en JSON si multiples |
| `emoji` | 4 niveaux visuels : Décevant / Médiocre / Bien / Parfait |
| `textarea` | Zone de texte libre |
| `tel` | Numéro de téléphone, max 9 caractères |
| `age` | Nombre, min 18, max 100 |
| Autre | Input standard `type={champ.type}`, required |

**Post-soumission :**
1. Masquage du formulaire, affichage du message de succès
2. Proposition Like / Dislike sur le service
3. Like → tracking `interesse=1` → redirection accueil
4. Dislike → tracking `pas_interesse=1` → redirection accueil
5. Email envoyé à tous les utilisateurs de rôle `responsable`

**Sauvegarde :**
- Chaque réponse stockée dans `ReponseAvis` avec : `sender_no`, `reponse`, `agence`, `adresse_ip`, `id_champs`, `status=1`
- Valeurs null remplacées par `'-'`
- Tableaux de valeurs sérialisés en JSON
- Numéro de soumission = nombre de soumissions existantes + 1

**Timer d'inactivité :** 10 secondes fixes → redirection vers `/agence/{nom}?click=yes`

### 4.8 Sous-module Réclamation

**Fonctionnement identique au module Avis** avec les spécificités suivantes :
- Champs chargés depuis `Formulaire` de type `reclamation`
- Sauvegarde dans `ReponseReclamation`
- **Email de confirmation de réception** envoyé au client si un champ contient un email valide (`filter_var(FILTER_VALIDATE_EMAIL)`)
- Email de notification envoyé à tous les responsables

---

## 5. MODULE 2 — CHATBOT WHATSAPP BGFI BANK

### 5.1 Description

Chatbot conversationnel WhatsApp permettant aux clients BGFI Bank de consulter leur solde bancaire de manière sécurisée, avec vérification OTP par SMS.

### 5.2 Infrastructure

| Paramètre | Valeur |
|-----------|--------|
| API WhatsApp | WaAPI (`waapi.app/api/v1`) |
| OTP | Twilio Verify v2 (SMS) |
| Stockage session | Laravel Cache, TTL 30 minutes |
| Anti-doublon | Cache 5 minutes par `message_id` |
| Webhook | `POST /sms/receive/whatsapp/whapi` |

### 5.3 Machine à états

```
┌─────────────────────────────────────────────────────────────┐
│                                                             │
│  [Nouveau contact] ──► showWelcome() ──► État: 'menu'      │
│                                                             │
│  État 'menu'                                                │
│  ├── "1" ou "menu"    → showMenu()       → État: 'menu'    │
│  ├── "2" ou "solde"   → requestAccount() → État: 'waiting_account' │
│  ├── "3" ou "quitter" → showGoodbye()    → État: 'menu'    │
│  └── [autre]          → message erreur   → État: 'menu'    │
│                                                             │
│  État 'waiting_account'                                     │
│  ├── [≠ 10 chiffres]              → erreur format          │
│  ├── [introuvable, tentatives < 3] → erreur, tentatives++  │
│  ├── [introuvable, tentatives ≥ 3] → blocage → 'menu'     │
│  └── [trouvé] → sendTwilioOtp()                            │
│       ├── [échec envoi]   → erreur → 'menu'                │
│       └── [OTP envoyé]    → État: 'waiting_otp'            │
│                                                             │
│  État 'waiting_otp'                                         │
│  ├── [code < 4 chiffres]          → erreur "trop court"    │
│  ├── [code approuvé]              → showBalance() → 'menu' │
│  ├── [tentatives OTP ≥ 3]         → blocage → 'menu'       │
│  ├── [code expiré]                → message → 'menu'       │
│  └── [code incorrect]             → erreur, tentatives++   │
│                                                             │
└─────────────────────────────────────────────────────────────┘
```

### 5.4 Règles métier

| Règle | Valeur |
|-------|--------|
| Format numéro de compte | Exactement 10 chiffres (nettoyage des non-numériques) |
| Tentatives max compte introuvable | 3 |
| Tentatives max OTP incorrect | 3 |
| Minimum chiffres pour code OTP | 4 |
| Durée session | 30 minutes |

### 5.5 Règles de masquage

| Donnée | Règle | Exemple |
|--------|-------|---------|
| Téléphone | 4 premiers + `***` + 2 derniers | `2417***37` |
| N° de compte | 4 premiers + `***` + 3 derniers | `1000***456` |

### 5.6 Civilité

| Valeur `sexe` | Civilité affichée |
|----------------|-------------------|
| `M` | Monsieur |
| `F` | Madame |
| Null / Autre | Pas de civilité |

### 5.7 Formatage du solde

```
number_format($solde, 0, ',', ' ')
Exemple : 2 450 000 XAF
```

### 5.8 Messages du chatbot

| Situation | Message |
|-----------|---------|
| Bienvenue | "BGFI Bank — Ensemble, Bâtissons Votre Avenir — Bonjour ! Je suis votre assistant bancaire. Comment puis-je vous aider ?" |
| Menu | "MENU BGFI — 1 Menu / 2 Solde / 3 Quitter — Urgence : *880*" |
| Demande compte | "Consultation Solde — Numéro de compte (10 chiffres) — Ex : 1000123456" |
| Compte invalide | "Numéro invalide. Saisissez 10 chiffres. Exemple : 1000123456" |
| Compte introuvable | "Compte introuvable. Tentative {n}/3" |
| Trop de tentatives (compte) | "Trop de tentatives. Contactez le *880*. Retour au menu..." |
| OTP envoyé | "Compte : {nom} — SMS envoyé au {téléphone_masqué} — Saisissez le code reçu — 3 tentatives max" |
| Échec envoi OTP | "Erreur envoi OTP. Contactez le *880*. Retour au menu..." |
| Code trop court | "Code trop court. Saisissez le code complet :" |
| Code incorrect | "Code incorrect. Tentative {n}/3 — {restant} essais restants" |
| Code expiré | "Code expiré. Tapez '2' pour relancer. Retour au menu..." |
| Accès bloqué (OTP) | "Accès bloqué après 3 tentatives. Contactez le *880*. Retour au menu..." |
| Affichage solde | "SOLDE COMPTE — Bonjour {Civilité} {Nom} — {compte_masqué} — {solde_formaté} {devise} — {date/heure}" |
| Au revoir | "Au revoir ! Merci d'avoir utilisé BGFI Bank. Urgence : *880*. À bientôt !" |
| Option non reconnue | "Option non reconnue. Choisissez : 1 Menu / 2 Solde / 3 Quitter" |

### 5.9 Format téléphone pour Twilio

```
Entrée : "077750737" ou "24177750737" ou "+24177750737"
Sortie : "+24177750737"
```

---

## 6. MODULE 3 — CHATBOT WHATSAPP AMIE MUTUELLE

### 6.1 Description

Chatbot WhatsApp parallèle pour l'organisme AMIE MUTUELLE (mutuelle d'assurance). Architecture similaire au chatbot BGFI mais avec sa propre API WhatsApp et ses spécificités.

### 6.2 Infrastructure

| Paramètre | Valeur |
|-----------|--------|
| API WhatsApp | Whapi (`gate.whapi.cloud`) |
| OTP | Twilio Verify v2 (SMS) |
| Session | Laravel Cache, TTL 30 minutes, clé `mutuelle_session_{numero}` |
| Anti-doublon | Cache 5 minutes par `processed_msg_{message_id}` |
| Webhook | `POST /webhook/whapi` |
| Widget web | Wotnot.io intégré |

### 6.3 Machine à états

```
┌─────────────────────────────────────────────────────────────────┐
│                                                                 │
│  [Première interaction] ──► Bienvenue + Menu interactif         │
│                                                                 │
│  État 'menu'                                                    │
│  ├── Bouton "Consulter Solde" → État: 'waiting_name'            │
│  ├── Bouton "Informations"    → Infos contact                   │
│  ├── Bouton "Contact"         → Boutons Appeler / Site Web      │
│  ├── "menu/retour/annuler"    → Menu                            │
│  ├── "help/aide/?"            → Message d'aide                  │
│  ├── "bonjour/salut/..."      → Accueil + Menu                  │
│  └── [non reconnu]            → "Je n'ai pas compris..."        │
│                                                                 │
│  État 'waiting_name'                                            │
│  ├── [< 3 caractères]         → Erreur                          │
│  └── [valide]                 → Sauvegarde nom → 'waiting_code' │
│                                                                 │
│  État 'waiting_code'                                            │
│  ├── [< 4 caractères]         → Erreur                          │
│  ├── [invalide, tentatives < 3] → Erreur, tentatives++          │
│  ├── [invalide, tentatives ≥ 3] → Blocage + Email alerte → menu│
│  └── [valide]                 → OTP Twilio → 'waiting_otp'      │
│                                                                 │
│  État 'waiting_otp'                                             │
│  ├── [code approuvé] → showBalance() → PDF → 'balance_shown'   │
│  ├── [tentatives ≥ 3] → Blocage → menu                         │
│  ├── [expiré]         → Retour menu                             │
│  └── [incorrect]      → Erreur, tentatives++                    │
│                                                                 │
│  État 'balance_shown'                                           │
│  ├── Bouton "Reçu PDF" → Envoi document PDF                    │
│  └── Bouton "Menu"     → Retour menu                            │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

### 6.4 Règles de correspondance membre

| Champ | Règle de matching |
|-------|-------------------|
| Code | Comparaison exacte, insensible à la casse (`strtoupper`) |
| Nom | Normalisation Unicode → ASCII → majuscules, puis matching souple : égalité exacte OU contenance bidirectionnelle |

> Les **deux** (nom ET code) doivent correspondre.

### 6.5 Génération du relevé PDF

- **Template :** `pdf.mutuelle-compte`
- **Données :** Nom, numéro adhérent, solde, date (d/m/Y), date/heure (d/m/Y H:i:s)
- **Stockage :** `storage/app/public/releves/releve-mutuelle-{Ymd-His}.pdf`
- **Envoi :** Via Whapi `POST /messages/document` avec légende

### 6.6 Alertes

- **Email d'alerte** envoyé à `secretariat@mutuelle.ga` après 3 tentatives de code incorrectes (mail `AlerteProspectMail`)

### 6.7 Messages interactifs

Le chatbot AMIE utilise des **boutons interactifs WhatsApp** (Cloud API) :
- Boutons `quick_reply` pour navigation
- Boutons `call` avec numéro de téléphone
- Boutons `url` avec lien web

### 6.8 Source de données

> **Note :** Les membres sont actuellement **codés en dur** dans le contrôleur. Migration vers base de données requise pour la production.

---

## 7. MODULE 4 — SYSTEME OTP (AIRTEL)

### 7.1 Description

Système OTP autonome utilisé par le portail web (consultation de solde en agence). Distinct du système Twilio utilisé par les chatbots.

### 7.2 Configuration

| Paramètre | Valeur |
|-----------|--------|
| Fournisseur SMS | Airtel Gabon |
| URL API | `https://messaging.airtel.ga:9002/smshttp/qs/` |
| Identifiant | `BGFI` |
| Longueur OTP | 6 chiffres |
| Durée de validité | 10 minutes |
| Tentatives max | 3 |
| Stockage | Laravel Cache |

### 7.3 Endpoints API

| Route | Méthode | Auth | Description |
|-------|---------|------|-------------|
| `POST /otp/send` | POST | CSRF | Envoyer un OTP (web) |
| `POST /otp/verify` | POST | CSRF | Vérifier un OTP (web) |
| `POST /otp/check` | POST | CSRF | Vérifier si OTP valide existe |
| `POST /otp/invalidate` | POST | CSRF | Invalider un OTP |
| `POST /api/otp/send` | POST | Aucune | Envoyer un OTP (API) |
| `POST /api/otp/verify` | POST | Aucune | Vérifier un OTP (API) |
| `POST /api/otp/check` | POST | Aucune | Vérifier si OTP existe |
| `POST /api/otp/invalidate` | POST | Aucune | Invalider un OTP |

### 7.4 Règles de validation

**Envoi :**
- `phone` : obligatoire, regex `^(\+?241|0)?[0-9]{8,9}$`
- `message` : optionnel, max 500 caractères

**Vérification :**
- `phone` : obligatoire, même regex
- `code` : obligatoire, regex `^[0-9]{4,8}$`

### 7.5 Algorithme de génération

1. Formater le numéro au format `241XXXXXXXX`
2. Générer un code numérique de 6 chiffres : `rand(100000, 999999)`
3. Message par défaut : *"Votre code de vérification BGFI est : {code}. Valide pendant 10 minutes. Ne partagez jamais ce code."*
4. Envoyer via API Airtel (requête GET, timeout 30s connect / 60s total)
5. Stocker en cache : clé `otp_{telephone}`, TTL 10 minutes

### 7.6 Algorithme de vérification

1. Formater le numéro
2. Vérifier que le code est entièrement numérique
3. Récupérer depuis le cache
4. Si absent → "Code OTP expiré ou inexistant"
5. Incrémenter les tentatives
6. Si tentatives > 3 → supprimer du cache → "Trop de tentatives"
7. Si code correct → supprimer du cache → succès
8. Si code incorrect → mettre à jour le cache → retourner `attempts_remaining`

### 7.7 Formatage téléphone Airtel

```
Suppression des non-numériques
Si commence par "0" → remplacer par "241"
Si ne commence pas par "241" → préfixer par "241"
Résultat : 241XXXXXXXX (sans "+")
```

---

## 8. MODULE 5 — PIPELINE D'IMPORT DES DONNEES BANCAIRES

### 8.1 Description

Système automatisé d'import des données de comptes bancaires depuis des fichiers CSV/TXT déposés par BGFI Bank.

### 8.2 Options d'intégration proposées à BGFI

| # | Option | Description |
|---|--------|-------------|
| 1 | API REST (recommandée) | `GET /api/accounts/{numero_compte}` avec Bearer token |
| 2 | Accès BDD en lecture seule | MySQL, PostgreSQL, Oracle, SQL Server |
| 3 | Fichier CSV/TXT (implémentée) | Dépôt dans un dossier, traitement automatique par cron |

### 8.3 Commande Artisan

```
php artisan bgfi:import
```

### 8.4 Planification

| Paramètre | Valeur |
|-----------|--------|
| Fréquence | Toutes les heures |
| Plage horaire | 12h00 — 16h00 |
| Dossier source | `database/bgfi_data/` |
| Dossier archive | `database/bgfi_data/archives/` |

### 8.5 Algorithme d'import

```
1. Vérifier l'existence du dossier source
2. Chercher tous les fichiers .csv et .txt
3. Pour chaque fichier :
   a. Détection et conversion d'encodage
      (UTF-8, Windows-1252, ISO-8859-1, ISO-8859-15)
      Suppression du BOM UTF-8
   b. Détection automatique du séparateur
      (virgule / tabulation / point-virgule / pipe)
      → Choisir celui qui produit ≥ 5 champs
   c. Lecture de l'en-tête
      Normalisation : minuscules, trim espaces/guillemets
   d. Validation de l'en-tête
      Colonnes obligatoires : numero_compte, nom_complet,
      telephone, solde
   e. TRUNCATE table bgfi_comptes_tampon
   f. Lecture ligne par ligne :
      - Ignorer lignes vides
      - Ignorer si < 4 champs (log warning)
      - devise → défaut 'XAF'
      - sexe → normaliser M/F ou null
      - Ignorer si numero_compte vide
      - INSERT dans bgfi_comptes_tampon
   g. CALL sp_maj_bgfi_comptes() (stored procedure)
      → UPSERT vers bgfi_comptes
      → TRUNCATE bgfi_comptes_tampon
   h. Archivage : déplacer vers archives/YYYYMMDD_HHIISS_{nom}
4. Log du bilan (importés, erreurs)
```

### 8.6 Format de fichier attendu

**Colonnes obligatoires :**

| Colonne | Type | Contrainte |
|---------|------|------------|
| `numero_compte` | string | Non vide, unique |
| `nom_complet` | string | Non vide |
| `telephone` | string | Format gabonais |
| `solde` | decimal | Casté en float |

**Colonnes optionnelles :**

| Colonne | Type | Défaut |
|---------|------|--------|
| `devise` | string(3) | `XAF` |
| `sexe` | string(1) | `null` (accepte M ou F uniquement) |

### 8.7 Procédure stockée `sp_maj_bgfi_comptes()`

```sql
-- UPSERT : INSERT ou UPDATE depuis la table tampon
INSERT INTO bgfi_comptes (numero_compte, nom_complet, telephone, solde, devise, sexe, created_at, updated_at)
SELECT numero_compte, nom_complet, telephone, solde, devise, sexe, NOW(), NOW()
FROM bgfi_comptes_tampon
ON DUPLICATE KEY UPDATE
    nom_complet = VALUES(nom_complet),
    telephone   = VALUES(telephone),
    solde       = VALUES(solde),
    devise      = VALUES(devise),
    sexe        = VALUES(sexe),
    updated_at  = NOW();

-- Nettoyage de la table tampon
TRUNCATE TABLE bgfi_comptes_tampon;
```

---

## 9. MODULE 6 — TABLEAU DE BORD ADMINISTRATEUR

### 9.1 Authentification

| Élément | Description |
|---------|-------------|
| URL | `/login` |
| Champs | Email + Mot de passe |
| Option | "Se souvenir de moi" |
| Sécurité | Middleware `auth`, hachage bcrypt |
| Récupération mot de passe | Non implémenté |

### 9.2 Dashboard principal — Métriques

| Métrique | Source |
|----------|--------|
| Nombre de FAQs actives | `Faq::where('status', 1)->count()` |
| Nombre d'agences | `Agence::all()->count()` |
| Réponses Avis | `ReponseAvis::groupBy('sender_no')->count()` |
| Réponses Réclamations | `ReponseReclamation::groupBy('sender_no')->count()` |
| Consultations de solde | `Consultation::where('module', 'consultation')->sum('visite')` |
| Messages SMS envoyés | `Consultation::where('module', 'message')->sum('visite')` |

### 9.3 Graphiques (Chart.js)

| Graphique | Type | Données |
|-----------|------|---------|
| Formulaires renseignés | Camembert (pie) | Par type : avis / réclamation |
| Services actifs par agence | Liste compteur | 0-4 services par agence |
| Consultations par agence | Camembert (pie) | Par agence et par module |
| FAQ views par agence | DataTable | Filtrable par agence, export Print/CSV/Excel/PDF/Copy |

### 9.4 Gestion des utilisateurs

| Action | Route | Comportement |
|--------|-------|-------------|
| Lister | `GET /users` | Utilisateurs avec `status=1` |
| Créer | `POST /users/add` | Hash du mot de passe, `status=1` |
| Modifier | `POST /users/edit` | Mot de passe optionnel |
| Supprimer | `POST /users/delete/{id}` | **Soft delete** : `status=0` |

**Rôles :**
- `responsable` : reçoit les emails de notification (avis/réclamations)
- `admin` : accès complet au tableau de bord

### 9.5 Modules de gestion CRUD

| Module | Entité | Fonctionnalités |
|--------|--------|----------------|
| Agences | `Agence` | Créer, modifier, supprimer des agences |
| FAQ | `Faq` / `ReponseFaq` | Gestion des FAQ et réponses par agence |
| Formulaires | `Formulaire` / `ChampFormulaire` / `OptionChamp` | Constructeur de formulaires dynamiques |
| Récapitulatifs | `ReponseAvis` / `ReponseReclamation` | Consultation et export des réponses |
| Marketing | `Marketing` | Gestion des visuels du carousel |
| Settings | `Setting` | Configuration par agence |

---

## 10. MODULE 7 — STATISTIQUES DE PERTINENCE

### 10.1 Description

Module analytique permettant de mesurer l'engagement des clients sur les différents services du portail.

### 10.2 Statistiques par module

#### FAQ (`/pertinence/faq`)
- **Métriques :** `total_views`, `total_likes`, `total_dislikes`
- **Agrégation :** Par agence + par FAQ
- **Filtrage :** Par agence (AJAX) et par période (date début / date fin)

#### Avis (`/pertinence/avis`)
- **Métriques :** `total_views`, `total_interet` (likes), `total_desinteret` (dislikes)
- **Agrégation :** Par agence
- **Filtrage :** Par période

#### Réclamation (`/pertinence/reclamation`)
- **Métriques :** Identiques au module Avis
- **Filtrage :** Par période

#### Consultation & Messages (`/pertinence/consultation`)
- **Métriques :** Visites consultations + messages SMS envoyés
- **Agrégation :** Par agence et par module (`consultation` / `message`)
- **Filtrage :** Par période

### 10.3 Système de tracking

| Module | Événement | Champ |
|--------|-----------|-------|
| FAQ | Visite | `visite=1` |
| Avis | Visite | `visite=1` |
| Avis | Like | `visite=1, interesse=1` |
| Avis | Dislike | `visite=1, pas_interesse=1` |
| Réclamation | Visite | `visite=1` |
| Consultation | Vue | `visite=1` |
| Message | SMS envoyé | `visite=1` (module `message`) |

### 10.4 Récupération de l'IP client

Gestion de la chaîne de proxies (compatible Cloudflare) :
1. `HTTP_CF_CONNECTING_IP` (Cloudflare)
2. `HTTP_CLIENT_IP`
3. `HTTP_X_FORWARDED_FOR`
4. `REMOTE_ADDR`

---

## 11. MODELE DE DONNEES

### 11.1 Table `bgfi_comptes` (comptes bancaires)

| Colonne | Type | Contrainte | Description |
|---------|------|------------|-------------|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT | Identifiant |
| `numero_compte` | VARCHAR(20) | NOT NULL, UNIQUE | Numéro de compte |
| `nom_complet` | VARCHAR(255) | NOT NULL | Nom complet du client |
| `telephone` | VARCHAR(20) | NOT NULL | Téléphone du client |
| `solde` | DECIMAL(15,2) | NOT NULL, DEFAULT 0 | Solde du compte |
| `devise` | VARCHAR(3) | NOT NULL, DEFAULT 'XAF' | Code devise ISO 4217 |
| `sexe` | VARCHAR(1) | NULL | 'M' ou 'F' |
| `created_at` | TIMESTAMP | — | Date de création |
| `updated_at` | TIMESTAMP | — | Date de mise à jour |

### 11.2 Table `bgfi_comptes_tampon` (staging import)

Même structure que `bgfi_comptes` sans `updated_at` ni contrainte UNIQUE.

### 11.3 Autres tables principales

| Table | Description |
|-------|-------------|
| `agences` | Agences bancaires |
| `users` | Utilisateurs admin (soft delete via `status`) |
| `faqs` / `reponse_faqs` | FAQ et réponses |
| `faq_statistiques` | Vues, likes, dislikes par FAQ/agence |
| `formulaires` | Formulaires dynamiques (type : avis/réclamation) |
| `champ_formulaires` | Champs des formulaires |
| `option_champs` | Options des champs (select, radio, checkbox) |
| `reponse_avis` | Réponses aux formulaires d'avis |
| `reponse_reclamations` | Réponses aux formulaires de réclamation |
| `consultations` | Tracking des visites par module/agence |
| `marketings` | Éléments marketing (carousel) |
| `settings` | Configuration par agence |
| `mouchards` | Tracking visiteurs |

---

## 12. INTEGRATIONS ET SERVICES TIERS

### 12.1 Tableau des dépendances externes

| Service | Fournisseur | Usage | Configuration requise |
|---------|-------------|-------|----------------------|
| WhatsApp (BGFI) | WaAPI | Chatbot BGFI | `WAAPI_API_TOKEN`, `WAAPI_INSTANCE_ID` |
| WhatsApp (AMIE) | Whapi | Chatbot AMIE | `WAAPI_WHAPI_TOKEN` |
| OTP chatbots | Twilio Verify v2 | SMS OTP | `TWILIO_SID`, `TWILIO_TOKEN`, `TWILIO_VERIFY_SID` |
| OTP portail | Airtel Gabon | SMS OTP | `AIRTEL_BASE_URL`, `AIRTEL_USERNAME`, `AIRTEL_PASSWORD` |
| Widget chatbot | Wotnot.io | Chatbot web AMIE | Script JS externe |
| Email | SMTP | Notifications | Config Laravel standard |

### 12.2 Webhooks exposés

| URL | Méthode | Source | Contrôleur |
|-----|---------|--------|-----------|
| `/sms/receive/whatsapp/whapi` | POST | WaAPI | `BGFIBankController@handleWebhook` |
| `/webhook/whapi` | POST | Whapi | `AmieController@handleMessages` |
| `/sms/receive` | POST | Vonage | `SendSMSController@receive` |

> Ces routes sont **exclues de la vérification CSRF** dans `VerifyCsrfToken`.

---

## 13. CONTRAINTES ET REGLES TRANSVERSALES

### 13.1 Limites et seuils

| Contrainte | Valeur |
|------------|--------|
| Tentatives max compte introuvable | 3 |
| Tentatives max OTP incorrect | 3 |
| Durée session chatbot (cache) | 30 minutes |
| Durée validité OTP Airtel | 10 minutes |
| Longueur OTP Airtel | 6 chiffres |
| Minimum digits code OTP (chatbot) | 4 |
| Inactivité portail | Configurable par agence |
| Inactivité formulaires | 10 secondes (fixe) |
| Compte à rebours redirection | 3 secondes |
| Âge minimum (formulaire) | 18 ans |
| Âge maximum (formulaire) | 100 ans |
| Longueur min nom (AMIE) | 3 caractères |
| Longueur min code (AMIE) | 4 caractères |
| Plage horaire import CRON | 12h00 — 16h00 |
| Fréquence import CRON | Toutes les heures |
| Anti-doublon messages | 5 minutes (cache) |

### 13.2 Formats téléphoniques

| Contexte | Format sortie | Exemple |
|----------|---------------|---------|
| Airtel SMS | `241XXXXXXXX` (sans +) | `24177750737` |
| Twilio Verify | `+241XXXXXXXX` (avec +) | `+24177750737` |
| Validation regex | `^(\+?241\|0)?[0-9]{8,9}$` | — |

---

## 14. SECURITE

### 14.1 Mesures implémentées

| Mesure | Description |
|--------|-------------|
| Authentification admin | Email + mot de passe (bcrypt) |
| Protection CSRF | Active sur toutes les routes sauf webhooks |
| Masquage données sensibles | Téléphone et numéro de compte masqués à l'affichage |
| OTP non retourné en réponse | Code supprimé des réponses API en production |
| Soft delete utilisateurs | Pas de suppression physique des comptes admin |
| Limite de tentatives | 3 tentatives max pour compte et OTP |
| Expiration automatique | Sessions chatbot (30 min) et OTP (10 min) |
| Anti-doublon messages | Cache 5 minutes par message_id |
| Détection IP | Support Cloudflare et proxies |
| API Sanctum | Protection route `/api/user` |

### 14.2 Routes sans CSRF (webhooks)

Les routes suivantes sont exclues de la vérification CSRF pour recevoir les webhooks :
- `/sms/*`
- `/webhook/*`
- `/api/*`

---

## 15. POINTS D'ATTENTION ET DIVERGENCES

### 15.1 Anomalies identifiées

| # | Anomalie | Impact | Recommandation |
|---|---------|--------|----------------|
| 1 | **Données comptes chargées côté client** : `BgfiCompte::all()` passé au JavaScript du portail | Sécurité : exposition de tous les comptes au navigateur ; Performance : volumétrie importante | Implémenter une recherche côté serveur via AJAX |
| 2 | **Incohérence longueur numéro de compte** : chatbot valide 10 chiffres, vue `consultationFormView` valide 12 caractères, migration élargie à VARCHAR(20) | Confusion utilisateur | Harmoniser à 10 chiffres partout ou rendre configurable |
| 3 | **Double système OTP** : Twilio Verify (chatbots) + Airtel SMS/Cache (portail) | Complexité de maintenance | Évaluer l'unification vers un seul système |
| 4 | **Membres AMIE codés en dur** | Impossible en production | Migrer vers table BDD dédiée |
| 5 | **Module consultation portail partiellement implémenté** | Soumission AJAX commentée dans `consultationFormView` | Finaliser ou retirer |
| 6 | **Récupération mot de passe non implémentée** | UX admin incomplète | Implémenter le flux de réinitialisation |
| 7 | **Routes API OTP sans authentification** | Risque d'abus | Ajouter rate limiting et/ou authentification API |

---

*Document généré le 27 février 2026*
