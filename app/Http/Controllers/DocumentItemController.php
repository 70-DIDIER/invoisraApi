<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class DocumentItemController extends Controller
{
    #[OA\Get(
        path: '/api/v1/documents/{document}/items',
        summary: 'Liste des articles d\'un document',
        security: [['sanctum' => []]],
        tags: ['Articles'],
    )]
    #[OA\Response(
        response: 200,
        description: 'Liste des articles',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: '#/components/schemas/DocumentItem'),
        ),
    )]
    public function index(Request $request, Document $document): JsonResponse
    {
        if ($document->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Non autorisé.'], 403);
        }

        return response()->json($document->items);
    }

    #[OA\Post(
        path: '/api/v1/documents/{document}/items',
        summary: 'Ajouter un article',
        security: [['sanctum' => []]],
        tags: ['Articles'],
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(properties: [
            new OA\Property(property: 'designation', type: 'string', example: 'Robinets'),
            new OA\Property(property: 'quantity', type: 'number', format: 'float', example: 3),
            new OA\Property(property: 'unit_price', type: 'number', format: 'float', example: 45.50),
        ]),
    )]
    #[OA\Response(
        response: 201,
        description: 'Article créé',
        content: new OA\JsonContent(ref: '#/components/schemas/DocumentItem'),
    )]
    public function store(Request $request, Document $document): JsonResponse
    {
        if ($document->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Non autorisé.'], 403);
        }

        $validated = $request->validate([
            'designation' => 'required|string|max:255',
            'quantity' => 'required|numeric|min:0',
            'unit_price' => 'required|numeric|min:0',
        ]);

        $validated['total_price'] = $validated['quantity'] * $validated['unit_price'];

        $item = $document->items()->create($validated);

        return response()->json($item, 201);
    }

    #[OA\Get(
        path: '/api/v1/documents/{document}/items/{item}',
        summary: 'Afficher un article',
        security: [['sanctum' => []]],
        tags: ['Articles'],
    )]
    #[OA\Response(
        response: 200,
        description: 'Détails de l\'article',
        content: new OA\JsonContent(ref: '#/components/schemas/DocumentItem'),
    )]
    public function show(Request $request, Document $document, DocumentItem $item): JsonResponse
    {
        if ($document->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Non autorisé.'], 403);
        }

        if ($item->document_id !== $document->id) {
            return response()->json(['message' => 'Cet article n\'appartient pas à ce document.'], 404);
        }

        return response()->json($item);
    }

    #[OA\Put(
        path: '/api/v1/documents/{document}/items/{item}',
        summary: 'Modifier un article',
        security: [['sanctum' => []]],
        tags: ['Articles'],
    )]
    #[OA\RequestBody(
        content: new OA\JsonContent(properties: [
            new OA\Property(property: 'designation', type: 'string'),
            new OA\Property(property: 'quantity', type: 'number'),
            new OA\Property(property: 'unit_price', type: 'number'),
        ]),
    )]
    #[OA\Response(
        response: 200,
        description: 'Article mis à jour',
        content: new OA\JsonContent(ref: '#/components/schemas/DocumentItem'),
    )]
    public function update(Request $request, Document $document, DocumentItem $item): JsonResponse
    {
        if ($document->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Non autorisé.'], 403);
        }

        if ($item->document_id !== $document->id) {
            return response()->json(['message' => 'Cet article n\'appartient pas à ce document.'], 404);
        }

        $validated = $request->validate([
            'designation' => 'sometimes|string|max:255',
            'quantity' => 'sometimes|numeric|min:0',
            'unit_price' => 'sometimes|numeric|min:0',
        ]);

        if (isset($validated['quantity']) || isset($validated['unit_price'])) {
            $quantity = $validated['quantity'] ?? $item->quantity;
            $unitPrice = $validated['unit_price'] ?? $item->unit_price;
            $validated['total_price'] = $quantity * $unitPrice;
        }

        $item->update($validated);

        return response()->json($item);
    }

    #[OA\Delete(
        path: '/api/v1/documents/{document}/items/{item}',
        summary: 'Supprimer un article',
        security: [['sanctum' => []]],
        tags: ['Articles'],
    )]
    #[OA\Response(response: 200, description: 'Article supprimé')]
    public function destroy(Request $request, Document $document, DocumentItem $item): JsonResponse
    {
        if ($document->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Non autorisé.'], 403);
        }

        if ($item->document_id !== $document->id) {
            return response()->json(['message' => 'Cet article n\'appartient pas à ce document.'], 404);
        }

        $item->delete();

        return response()->json(['message' => 'Article supprimé avec succès.']);
    }
}
