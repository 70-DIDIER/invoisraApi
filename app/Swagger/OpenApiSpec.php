<?php

namespace App\Swagger;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'Invoiça API',
    description: 'API de gestion de devis et factures pour artisans.',
    contact: new OA\Contact(email: 'contact@invoica.fr'),
)]
#[OA\Server(url: 'http://localhost:8000', description: 'Développement')]
#[OA\Server(url: L5_SWAGGER_CONST_HOST, description: 'Production')]
#[OA\Tag(name: 'Authentification', description: 'Inscription, connexion, déconnexion')]
#[OA\Tag(name: 'Google Auth', description: 'Authentification via Google OAuth')]
#[OA\Tag(name: 'Entreprise', description: "Gestion de l'entreprise")]
#[OA\Tag(name: 'Clients', description: 'Gestion des clients')]
#[OA\Tag(name: 'Documents', description: 'Gestion des devis et factures')]
#[OA\Tag(name: 'Articles', description: "Gestion des articles d'un document")]
#[OA\Tag(name: 'Fichiers', description: 'Upload de logo, signature et tampon')]
class OpenApiSpec {}
