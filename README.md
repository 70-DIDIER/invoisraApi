<p align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
</p>

# Invoiça API

API RESTful de gestion de devis et factures destinée aux artisans (plombiers, électriciens, soudeurs, menuisiers, etc.).

---

## Stack technique

| Technologie | Version |
|---|---|
| PHP | ^8.3 |
| Laravel | ^13.8 |
| Sanctum | ^4.3 (auth API token) |
| Socialite | ^5.28 (Google OAuth) |
| DomPDF | ^3.1 (génération PDF) |
| L5-Swagger | ^11.1 (documentation OpenAPI) |
| SQLite / MySQL | tests / prod |

---

## Fonctionnalités

- Authentification par email + mot de passe (Sanctum)
- Authentification via Google OAuth (Socialite)
- Gestion de l'entreprise (logo, signature, tampon)
- CRUD clients avec recherche et pagination
- CRUD devis (`quote`) et factures (`invoice`) avec filtres
- Articles de documents avec auto-calcul du total
- Génération de PDF (template complet : logo, articles, totaux, signature)
- Envoi des documents par email avec PDF en pièce jointe
- Upload de fichiers (logo, signature, tampon)
- Documentation OpenAPI (Swagger UI)
- 38 tests PHPUnit (76 assertions)

---

## Modèle de données

```
User (1) ──► Company (1)
  │
  ├──► Client (N)
  │
  └──► Document (N) ──► DocumentItem (N)
          │
          ├──► Company (1)
          └──► Client (1)
```

### Entités

| Table | Description |
|---|---|
| `users` | Comptes utilisateurs (email, password, google_id, avatar) |
| `companies` | Entreprise du user (nom, adresse, téléphone, logo, signature, tampon) |
| `clients` | Clients du user (nom, téléphone, adresse) |
| `documents` | Devis et factures (type, numéro unique, totaux, dates, notes) |
| `document_items` | Lignes d'un document (désignation, quantité, prix unitaire, total) |

---

## Installation

```bash
# Cloner le projet
git clone <url> invoica-api
cd invoica-api

# Installer les dépendances PHP
composer install

# Copier la configuration
cp .env.example .env

# Générer la clé d'application
php artisan key:generate

# Créer le lien de stockage
php artisan storage:link

# Configurer la base de données dans .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=invoica
DB_USERNAME=root
DB_PASSWORD=

# Lancer les migrations
php artisan migrate

# (Optionnel) Remplir avec des données de test
php artisan db:seed

# Lancer le serveur
php artisan serve
```

---

## Authentification Google

1. Créez un projet sur [Google Cloud Console](https://console.cloud.google.com/)
2. Activez l'API Google OAuth 2.0
3. Créez des identifiants OAuth (URI de redirection : `http://localhost:8000/api/v1/auth/google/callback`)
4. Renseignez dans `.env` :

```bash
GOOGLE_CLIENT_ID=xxx.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=GOCSPX-xxx
GOOGLE_REDIRECT_URI=http://localhost:8000/api/v1/auth/google/callback
```

---

## Documentation API (Swagger)

Après avoir lancé le serveur :

```bash
php artisan l5-swagger:generate
```

Accédez à l'interface Swagger UI :

```
http://localhost:8000/api/documentation
```

Pour regénérer la documentation après des modifications :

```bash
php artisan l5-swagger:generate
```

---

## Routes API

Toutes les routes sont préfixées par `/api/v1`.

### Authentification

| Méthode | Route | Description | Auth |
|---|---|---|---|
| `POST` | `/register` | Inscription | ✗ |
| `POST` | `/login` | Connexion | ✗ |
| `POST` | `/logout` | Déconnexion | ✓ |
| `GET` | `/me` | Profil utilisateur | ✓ |

### Google OAuth

| Méthode | Route | Description | Auth |
|---|---|---|---|
| `GET` | `/auth/google/redirect` | URL de redirection Google | ✗ |
| `GET` | `/auth/google/callback` | Callback Google OAuth | ✗ |

### Entreprise

| Méthode | Route | Description | Auth |
|---|---|---|---|
| `GET` | `/company` | Afficher l'entreprise | ✓ |
| `POST` | `/company` | Créer l'entreprise | ✓ |
| `PUT` | `/company` | Modifier l'entreprise | ✓ |

### Fichiers

| Méthode | Route | Description | Auth |
|---|---|---|---|
| `POST` | `/company/upload` | Uploader un fichier (logo/signature/tampon) | ✓ |
| `DELETE` | `/company/upload/{type}` | Supprimer un fichier | ✓ |

### Clients

| Méthode | Route | Description | Auth |
|---|---|---|---|
| `GET` | `/clients?search=&per_page=` | Liste des clients (paginnée) | ✓ |
| `POST` | `/clients` | Créer un client | ✓ |
| `GET` | `/clients/{client}` | Afficher un client | ✓ |
| `PUT` | `/clients/{client}` | Modifier un client | ✓ |
| `DELETE` | `/clients/{client}` | Supprimer un client | ✓ |

### Documents

| Méthode | Route | Description | Auth |
|---|---|---|---|
| `GET` | `/documents?type=&client_id=&search=&per_page=` | Liste des documents | ✓ |
| `POST` | `/documents` | Créer un document | ✓ |
| `GET` | `/documents/{document}` | Afficher un document | ✓ |
| `PUT` | `/documents/{document}` | Modifier un document | ✓ |
| `DELETE` | `/documents/{document}` | Supprimer un document | ✓ |
| `GET` | `/documents/{document}/download` | Télécharger le PDF | ✓ |
| `POST` | `/documents/{document}/email` | Envoyer par email | ✓ |

### Articles

| Méthode | Route | Description | Auth |
|---|---|---|---|
| `GET` | `/documents/{document}/items` | Liste des articles | ✓ |
| `POST` | `/documents/{document}/items` | Ajouter un article | ✓ |
| `GET` | `/documents/{document}/items/{item}` | Afficher un article | ✓ |
| `PUT` | `/documents/{document}/items/{item}` | Modifier un article | ✓ |
| `DELETE` | `/documents/{document}/items/{item}` | Supprimer un article | ✓ |

> **Note :** Le `total_price` est automatiquement calculé : `quantity × unit_price`.

---

## Utilisation rapide

```bash
# Inscription
curl -X POST http://localhost:8000/api/v1/register \
  -H "Content-Type: application/json" \
  -d '{"name":"Artisan","email":"artisan@test.fr","password":"password","password_confirmation":"password"}'

# Connexion
curl -X POST http://localhost:8000/api/v1/login \
  -H "Content-Type: application/json" \
  -d '{"email":"artisan@test.fr","password":"password"}'

# Utiliser le token reçu dans les requêtes suivantes
TOKEN="1|xxxxxx"

# Créer l'entreprise
curl -X POST http://localhost:8000/api/v1/company \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer $TOKEN" \
  -d '{"name":"Brico Pro","phone":"0123456789","address":"10 rue de Paris","manager_name":"Jean Dupont"}'

# Créer un client
curl -X POST http://localhost:8000/api/v1/clients \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer $TOKEN" \
  -d '{"name":"Client Test","phone":"0600000000"}'

# Créer un devis
curl -X POST http://localhost:8000/api/v1/documents \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer $TOKEN" \
  -d '{"client_id":1,"type":"quote","project_name":"Rénovation cuisine","issue_date":"2026-06-21"}'

# Ajouter un article
curl -X POST http://localhost:8000/api/v1/documents/1/items \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer $TOKEN" \
  -d '{"designation":"Robinets","quantity":3,"unit_price":45.50}'

# Télécharger le PDF
curl -X GET http://localhost:8000/api/v1/documents/1/download \
  -H "Authorization: Bearer $TOKEN"

# Envoyer par email
curl -X POST http://localhost:8000/api/v1/documents/1/email \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer $TOKEN" \
  -d '{"email":"client@example.com"}'
```

---

## Tests

```bash
# Lancer tous les tests (base SQLite en mémoire)
php artisan test

# Résultat attendu : 38 passed (77 assertions)
```

### Tests disponibles

| Fichier | Tests | Couverture |
|---|---|---|
| `AuthTest` | 6 | register, login, logout, me, unauthorized |
| `CompanyTest` | 6 | CRUD, upload, suppression fichiers |
| `ClientTest` | 8 | CRUD, search, pagination, permissions |
| `DocumentTest` | 10 | CRUD, filtres, search, download PDF, permissions |
| `DocumentItemTest` | 6 | CRUD, auto-calcul total |

---

## Structure du projet

```
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── AuthController.php
│   │       ├── ClientController.php
│   │       ├── CompanyController.php
│   │       ├── DocumentController.php
│   │       ├── DocumentItemController.php
│   │       ├── FileController.php
│   │       └── SocialiteController.php
│   ├── Mail/
│   │       └── DocumentMail.php
│   ├── Models/
│   │       ├── User.php
│   │       ├── Company.php
│   │       ├── Client.php
│   │       ├── Document.php
│   │       └── DocumentItem.php
│   └── Swagger/
│           ├── OpenApiSpec.php
│           └── Schemas.php
├── config/
│   ├── l5-swagger.php
│   ├── sanctum.php
│   └── services.php
├── database/
│   ├── factories/
│   ├── migrations/
│   └── seeders/
├── resources/views/
│   ├── emails/document.blade.php
│   └── pdfs/document.blade.php
├── routes/
│   └── api.php
└── tests/
    └── Feature/
        ├── AuthTest.php
        ├── ClientTest.php
        ├── CompanyTest.php
        ├── DocumentItemTest.php
        └── DocumentTest.php
```

---

## Commandes utiles

```bash
# Regénérer les schémas de base de données
php artisan migrate:fresh
php artisan db:seed

# Regénérer la documentation Swagger
php artisan l5-swagger:generate

# Vider le cache
php artisan optimize:clear
```
