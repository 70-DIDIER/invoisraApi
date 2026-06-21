<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class ClientController extends Controller
{
    #[OA\Get(
        path: '/api/v1/clients',
        summary: 'Liste des clients',
        security: [['sanctum' => []]],
        tags: ['Clients'],
    )]
    #[OA\Parameter(name: 'search', in: 'query', schema: new OA\Schema(type: 'string'))]
    #[OA\Parameter(name: 'per_page', in: 'query', schema: new OA\Schema(type: 'integer', default: 15))]
    #[OA\Response(
        response: 200,
        description: 'Liste paginée des clients',
        content: new OA\JsonContent(properties: [
            new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/Client')),
            new OA\Property(property: 'links', type: 'object'),
            new OA\Property(property: 'total', type: 'integer'),
            new OA\Property(property: 'per_page', type: 'integer'),
        ]),
    )]
    public function index(Request $request): JsonResponse
    {
        $query = $request->user()->clients();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $perPage = min((int) $request->input('per_page', 15), 100);
        $clients = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json($clients);
    }

    #[OA\Post(
        path: '/api/v1/clients',
        summary: 'Créer un client',
        security: [['sanctum' => []]],
        tags: ['Clients'],
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(properties: [
            new OA\Property(property: 'name', type: 'string', example: 'Client Test'),
            new OA\Property(property: 'phone', type: 'string', nullable: true),
            new OA\Property(property: 'address', type: 'string', nullable: true),
        ]),
    )]
    #[OA\Response(
        response: 201,
        description: 'Client créé',
        content: new OA\JsonContent(ref: '#/components/schemas/Client'),
    )]
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
        ]);

        $client = $request->user()->clients()->create($validated);

        return response()->json($client, 201);
    }

    #[OA\Get(
        path: '/api/v1/clients/{client}',
        summary: 'Afficher un client',
        security: [['sanctum' => []]],
        tags: ['Clients'],
    )]
    #[OA\Response(
        response: 200,
        description: 'Détails du client',
        content: new OA\JsonContent(ref: '#/components/schemas/Client'),
    )]
    #[OA\Response(response: 403, description: 'Non autorisé')]
    public function show(Request $request, Client $client): JsonResponse
    {
        if ($client->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Non autorisé.'], 403);
        }

        return response()->json($client);
    }

    #[OA\Put(
        path: '/api/v1/clients/{client}',
        summary: 'Modifier un client',
        security: [['sanctum' => []]],
        tags: ['Clients'],
    )]
    #[OA\RequestBody(
        content: new OA\JsonContent(properties: [
            new OA\Property(property: 'name', type: 'string'),
            new OA\Property(property: 'phone', type: 'string', nullable: true),
            new OA\Property(property: 'address', type: 'string', nullable: true),
        ]),
    )]
    #[OA\Response(
        response: 200,
        description: 'Client mis à jour',
        content: new OA\JsonContent(ref: '#/components/schemas/Client'),
    )]
    public function update(Request $request, Client $client): JsonResponse
    {
        if ($client->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Non autorisé.'], 403);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
        ]);

        $client->update($validated);

        return response()->json($client);
    }

    #[OA\Delete(
        path: '/api/v1/clients/{client}',
        summary: 'Supprimer un client',
        security: [['sanctum' => []]],
        tags: ['Clients'],
    )]
    #[OA\Response(response: 200, description: 'Client supprimé')]
    public function destroy(Request $request, Client $client): JsonResponse
    {
        if ($client->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Non autorisé.'], 403);
        }

        $client->delete();

        return response()->json(['message' => 'Client supprimé avec succès.']);
    }
}
