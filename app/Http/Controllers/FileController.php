<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use OpenApi\Attributes as OA;

class FileController extends Controller
{
    #[OA\Post(
        path: '/api/v1/company/upload',
        summary: 'Uploader un fichier (logo, signature, tampon)',
        security: [['sanctum' => []]],
        tags: ['Fichiers'],
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\MediaType(
            mediaType: 'multipart/form-data',
            schema: new OA\Schema(properties: [
                new OA\Property(property: 'file', type: 'string', format: 'binary'),
                new OA\Property(property: 'type', type: 'string', enum: ['logo', 'signature', 'stamp']),
            ]),
        ),
    )]
    #[OA\Response(
        response: 200,
        description: 'Fichier uploadé',
        content: new OA\JsonContent(properties: [
            new OA\Property(property: 'url', type: 'string'),
            new OA\Property(property: 'path', type: 'string'),
            new OA\Property(property: 'type', type: 'string'),
        ]),
    )]
    public function upload(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'type' => 'required|in:logo,signature,stamp',
        ]);

        $company = $request->user()->company;

        if (! $company) {
            return response()->json(['message' => 'Vous devez d\'abord créer votre entreprise.'], 400);
        }

        $path = $request->file('file')->store("companies/{$company->id}", 'public');

        $url = Storage::url($path);

        $company->update([$validated['type'] => $url]);

        return response()->json([
            'url' => $url,
            'path' => $path,
            'type' => $validated['type'],
        ]);
    }

    #[OA\Delete(
        path: '/api/v1/company/upload/{type}',
        summary: 'Supprimer un fichier uploadé',
        security: [['sanctum' => []]],
        tags: ['Fichiers'],
    )]
    #[OA\Parameter(
        name: 'type',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'string', enum: ['logo', 'signature', 'stamp']),
    )]
    #[OA\Response(response: 200, description: 'Fichier supprimé')]
    public function destroy(Request $request, string $type): JsonResponse
    {
        $company = $request->user()->company;

        if (! $company) {
            return response()->json(['message' => 'Aucune entreprise trouvée.'], 404);
        }

        if (! in_array($type, ['logo', 'signature', 'stamp'])) {
            return response()->json(['message' => 'Type invalide.'], 422);
        }

        $oldPath = $company->{$type};

        if ($oldPath) {
            $relativePath = str_replace('/storage/', '', $oldPath);
            Storage::disk('public')->delete($relativePath);
        }

        $company->update([$type => null]);

        return response()->json(['message' => 'Fichier supprimé avec succès.']);
    }
}
