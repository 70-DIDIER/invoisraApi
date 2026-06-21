<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use OpenApi\Attributes as OA;

class AuthController extends Controller
{
    #[OA\Post(
        path: '/api/v1/register',
        summary: 'Inscription',
        tags: ['Authentification'],
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(properties: [
            new OA\Property(property: 'name', type: 'string', example: 'Jean Dupont'),
            new OA\Property(property: 'email', type: 'string', format: 'email', example: 'jean@example.com'),
            new OA\Property(property: 'password', type: 'string', format: 'password', example: 'password'),
            new OA\Property(property: 'password_confirmation', type: 'string', example: 'password'),
        ]),
    )]
    #[OA\Response(
        response: 201,
        description: 'Utilisateur créé avec succès',
        content: new OA\JsonContent(properties: [
            new OA\Property(property: 'user', ref: '#/components/schemas/User'),
            new OA\Property(property: 'token', type: 'string'),
        ]),
    )]
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    #[OA\Post(
        path: '/api/v1/login',
        summary: 'Connexion',
        tags: ['Authentification'],
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(properties: [
            new OA\Property(property: 'email', type: 'string', format: 'email', example: 'jean@example.com'),
            new OA\Property(property: 'password', type: 'string', format: 'password', example: 'password'),
        ]),
    )]
    #[OA\Response(
        response: 200,
        description: 'Connexion réussie',
        content: new OA\JsonContent(properties: [
            new OA\Property(property: 'user', ref: '#/components/schemas/User'),
            new OA\Property(property: 'token', type: 'string'),
        ]),
    )]
    #[OA\Response(response: 422, description: 'Identifiants invalides')]
    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (! $user || ! Hash::check($validated['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Les identifiants fournis sont incorrects.'],
            ]);
        }

        $user->tokens()->delete();

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }

    #[OA\Post(
        path: '/api/v1/logout',
        summary: 'Déconnexion',
        security: [['sanctum' => []]],
        tags: ['Authentification'],
    )]
    #[OA\Response(response: 200, description: 'Déconnecté avec succès')]
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Déconnecté avec succès.']);
    }

    #[OA\Get(
        path: '/api/v1/me',
        summary: 'Profil utilisateur',
        security: [['sanctum' => []]],
        tags: ['Authentification'],
    )]
    #[OA\Response(
        response: 200,
        description: 'Utilisateur connecté',
        content: new OA\JsonContent(ref: '#/components/schemas/User'),
    )]
    public function me(Request $request): JsonResponse
    {
        return response()->json($request->user()->load('company'));
    }
}
