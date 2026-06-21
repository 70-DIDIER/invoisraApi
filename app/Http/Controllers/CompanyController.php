<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class CompanyController extends Controller
{
    #[OA\Get(
        path: '/api/v1/company',
        summary: 'Afficher l\'entreprise',
        security: [['sanctum' => []]],
        tags: ['Entreprise'],
    )]
    #[OA\Response(
        response: 200,
        description: 'Détails de l\'entreprise',
        content: new OA\JsonContent(ref: '#/components/schemas/Company'),
    )]
    #[OA\Response(response: 404, description: 'Aucune entreprise trouvée')]
    public function show(Request $request): JsonResponse
    {
        $company = $request->user()->company;

        if (! $company) {
            return response()->json(['message' => 'Aucune entreprise trouvée.'], 404);
        }

        return response()->json($company);
    }

    #[OA\Post(
        path: '/api/v1/company',
        summary: 'Créer l\'entreprise',
        security: [['sanctum' => []]],
        tags: ['Entreprise'],
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(properties: [
            new OA\Property(property: 'name', type: 'string', example: 'Brico Pro'),
            new OA\Property(property: 'phone', type: 'string', example: '0123456789'),
            new OA\Property(property: 'address', type: 'string', example: '10 rue de Paris'),
            new OA\Property(property: 'manager_name', type: 'string', example: 'Jean Dupont'),
            new OA\Property(property: 'logo', type: 'string', nullable: true),
            new OA\Property(property: 'email', type: 'string', nullable: true),
            new OA\Property(property: 'signature', type: 'string', nullable: true),
            new OA\Property(property: 'stamp', type: 'string', nullable: true),
        ]),
    )]
    #[OA\Response(
        response: 201,
        description: 'Entreprise créée',
        content: new OA\JsonContent(ref: '#/components/schemas/Company'),
    )]
    #[OA\Response(response: 409, description: 'Entreprise déjà existante')]
    public function store(Request $request): JsonResponse
    {
        if ($request->user()->company) {
            return response()->json(['message' => 'Vous avez déjà une entreprise.'], 409);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'address' => 'required|string|max:255',
            'manager_name' => 'required|string|max:255',
            'siret' => 'nullable|string|max:14',
            'vat_number' => 'nullable|string|max:20',
            'signature' => 'nullable|string|max:255',
            'stamp' => 'nullable|string|max:255',
        ]);

        $company = $request->user()->company()->create($validated);

        return response()->json($company, 201);
    }

    #[OA\Put(
        path: '/api/v1/company',
        summary: 'Modifier l\'entreprise',
        security: [['sanctum' => []]],
        tags: ['Entreprise'],
    )]
    #[OA\RequestBody(
        content: new OA\JsonContent(properties: [
            new OA\Property(property: 'name', type: 'string'),
            new OA\Property(property: 'phone', type: 'string'),
            new OA\Property(property: 'address', type: 'string'),
            new OA\Property(property: 'manager_name', type: 'string'),
        ]),
    )]
    #[OA\Response(
        response: 200,
        description: 'Entreprise mise à jour',
        content: new OA\JsonContent(ref: '#/components/schemas/Company'),
    )]
    public function update(Request $request): JsonResponse
    {
        $company = $request->user()->company;

        if (! $company) {
            return response()->json(['message' => 'Aucune entreprise trouvée.'], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'logo' => 'nullable|string|max:255',
            'phone' => 'sometimes|string|max:255',
            'email' => 'nullable|email|max:255',
            'address' => 'sometimes|string|max:255',
            'manager_name' => 'sometimes|string|max:255',
            'siret' => 'nullable|string|max:14',
            'vat_number' => 'nullable|string|max:20',
            'signature' => 'nullable|string|max:255',
            'stamp' => 'nullable|string|max:255',
        ]);

        $company->update($validated);

        return response()->json($company);
    }
}
