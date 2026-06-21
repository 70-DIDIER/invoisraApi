<?php

namespace App\Http\Controllers;

use App\Mail\DocumentMail;
use App\Models\Document;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use OpenApi\Attributes as OA;

class DocumentController extends Controller
{
    #[OA\Get(
        path: '/api/v1/documents',
        summary: 'Liste des documents',
        security: [['sanctum' => []]],
        tags: ['Documents'],
    )]
    #[OA\Parameter(name: 'search', in: 'query', schema: new OA\Schema(type: 'string'))]
    #[OA\Parameter(name: 'type', in: 'query', schema: new OA\Schema(type: 'string', enum: ['quote', 'invoice']))]
    #[OA\Parameter(name: 'client_id', in: 'query', schema: new OA\Schema(type: 'integer'))]
    #[OA\Parameter(name: 'per_page', in: 'query', schema: new OA\Schema(type: 'integer', default: 15))]
    #[OA\Response(
        response: 200,
        description: 'Liste paginée des documents',
        content: new OA\JsonContent(properties: [
            new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/Document')),
            new OA\Property(property: 'links', type: 'object'),
        ]),
    )]
    public function index(Request $request): JsonResponse
    {
        $query = $request->user()->documents()->with('client:id,name');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('number', 'like', "%{$search}%")
                  ->orWhere('project_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        $perPage = min((int) $request->input('per_page', 15), 100);
        $documents = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json($documents);
    }

    #[OA\Post(
        path: '/api/v1/documents',
        summary: 'Créer un document (devis/facture)',
        security: [['sanctum' => []]],
        tags: ['Documents'],
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(properties: [
            new OA\Property(property: 'client_id', type: 'integer', example: 1),
            new OA\Property(property: 'type', type: 'string', enum: ['quote', 'invoice'], example: 'quote'),
            new OA\Property(property: 'project_name', type: 'string', example: 'Rénovation cuisine'),
            new OA\Property(property: 'issue_date', type: 'string', format: 'date', example: '2026-06-21'),
            new OA\Property(property: 'valid_until', type: 'string', format: 'date', nullable: true),
            new OA\Property(property: 'notes', type: 'string', nullable: true),
            new OA\Property(property: 'subtotal', type: 'number', nullable: true),
            new OA\Property(property: 'labor_cost', type: 'number', nullable: true),
            new OA\Property(property: 'transport_cost', type: 'number', nullable: true),
            new OA\Property(property: 'other_cost', type: 'number', nullable: true),
            new OA\Property(property: 'total', type: 'number', nullable: true),
            new OA\Property(property: 'total_in_words', type: 'string', nullable: true),
        ]),
    )]
    #[OA\Response(
        response: 201,
        description: 'Document créé',
        content: new OA\JsonContent(ref: '#/components/schemas/Document'),
    )]
    public function store(Request $request): JsonResponse
    {
        $company = $request->user()->company;

        if (! $company) {
            return response()->json(['message' => 'Vous devez d\'abord créer votre entreprise.'], 400);
        }

        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'type' => 'required|in:quote,invoice',
            'project_name' => 'required|string|max:255',
            'issue_date' => 'required|date',
            'valid_until' => 'nullable|date|after_or_equal:issue_date',
            'notes' => 'nullable|string',
            'subtotal' => 'nullable|numeric|min:0',
            'labor_cost' => 'nullable|numeric|min:0',
            'transport_cost' => 'nullable|numeric|min:0',
            'other_cost' => 'nullable|numeric|min:0',
            'total' => 'nullable|numeric|min:0',
            'total_in_words' => 'nullable|string',
            'pdf_template' => 'nullable|string|max:50',
        ]);

        $validated['user_id'] = $request->user()->id;
        $validated['company_id'] = $company->id;

        $prefix = $validated['type'] === 'invoice' ? 'INV-' : 'QTE-';
        $validated['number'] = $prefix . now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

        $document = Document::create($validated);

        return response()->json($document->load('client:id,name', 'items'), 201);
    }

    #[OA\Get(
        path: '/api/v1/documents/{document}',
        summary: 'Afficher un document',
        security: [['sanctum' => []]],
        tags: ['Documents'],
    )]
    #[OA\Response(
        response: 200,
        description: 'Détails du document avec articles',
        content: new OA\JsonContent(ref: '#/components/schemas/Document'),
    )]
    public function show(Request $request, Document $document): JsonResponse
    {
        if ($document->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Non autorisé.'], 403);
        }

        return response()->json($document->load('client', 'company', 'items'));
    }

    #[OA\Put(
        path: '/api/v1/documents/{document}',
        summary: 'Modifier un document',
        security: [['sanctum' => []]],
        tags: ['Documents'],
    )]
    #[OA\RequestBody(
        content: new OA\JsonContent(properties: [
            new OA\Property(property: 'project_name', type: 'string'),
            new OA\Property(property: 'notes', type: 'string', nullable: true),
            new OA\Property(property: 'total', type: 'number'),
        ]),
    )]
    #[OA\Response(
        response: 200,
        description: 'Document mis à jour',
        content: new OA\JsonContent(ref: '#/components/schemas/Document'),
    )]
    public function update(Request $request, Document $document): JsonResponse
    {
        if ($document->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Non autorisé.'], 403);
        }

        $validated = $request->validate([
            'client_id' => 'sometimes|exists:clients,id',
            'type' => 'sometimes|in:quote,invoice',
            'project_name' => 'sometimes|string|max:255',
            'issue_date' => 'sometimes|date',
            'valid_until' => 'nullable|date|after_or_equal:issue_date',
            'notes' => 'nullable|string',
            'subtotal' => 'nullable|numeric|min:0',
            'labor_cost' => 'nullable|numeric|min:0',
            'transport_cost' => 'nullable|numeric|min:0',
            'other_cost' => 'nullable|numeric|min:0',
            'total' => 'nullable|numeric|min:0',
            'total_in_words' => 'nullable|string',
            'pdf_template' => 'nullable|string|max:50',
        ]);

        $document->update($validated);

        return response()->json($document->load('client:id,name', 'items'));
    }

    #[OA\Delete(
        path: '/api/v1/documents/{document}',
        summary: 'Supprimer un document',
        security: [['sanctum' => []]],
        tags: ['Documents'],
    )]
    #[OA\Response(response: 200, description: 'Document supprimé')]
    public function destroy(Request $request, Document $document): JsonResponse
    {
        if ($document->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Non autorisé.'], 403);
        }

        $document->delete();

        return response()->json(['message' => 'Document supprimé avec succès.']);
    }

    #[OA\Get(
        path: '/api/v1/documents/{document}/download',
        summary: 'Télécharger le PDF',
        security: [['sanctum' => []]],
        tags: ['Documents'],
    )]
    #[OA\Response(
        response: 200,
        description: 'PDF en base64',
        content: new OA\JsonContent(properties: [
            new OA\Property(property: 'filename', type: 'string'),
            new OA\Property(property: 'content', type: 'string', description: 'PDF en base64'),
            new OA\Property(property: 'content_type', type: 'string'),
        ]),
    )]
    public function download(Request $request, Document $document): JsonResponse
    {
        if ($document->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Non autorisé.'], 403);
        }

        $document->load('client', 'company', 'items');

        $pdf = Pdf::loadView('pdfs.document', ['document' => $document]);
        $pdfContent = $pdf->output();
        $filename = "{$document->number}.pdf";

        $base64 = base64_encode($pdfContent);

        return response()->json([
            'filename' => $filename,
            'content' => $base64,
            'content_type' => 'application/pdf',
        ]);
    }

    #[OA\Post(
        path: '/api/v1/documents/{document}/email',
        summary: 'Envoyer le document par email',
        security: [['sanctum' => []]],
        tags: ['Documents'],
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(properties: [
            new OA\Property(property: 'email', type: 'string', format: 'email', example: 'client@example.com'),
        ]),
    )]
    #[OA\Response(response: 200, description: 'Email envoyé')]
    public function email(Request $request, Document $document): JsonResponse
    {
        if ($document->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Non autorisé.'], 403);
        }

        $validated = $request->validate([
            'email' => 'required|email',
        ]);

        $document->load('client', 'company', 'items');

        $pdf = Pdf::loadView('pdfs.document', ['document' => $document]);
        $filename = "{$document->number}.pdf";
        $pdfPath = storage_path("app/temp/{$filename}");
        $pdf->save($pdfPath);

        try {
            Mail::to($validated['email'])->send(new DocumentMail($document, $pdfPath));
        } finally {
            if (file_exists($pdfPath)) {
                unlink($pdfPath);
            }
        }

        return response()->json(['message' => 'Email envoyé avec succès.']);
    }
}
