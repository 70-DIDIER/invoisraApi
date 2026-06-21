<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use OpenApi\Attributes as OA;

class SocialiteController extends Controller
{
    #[OA\Get(
        path: '/api/v1/auth/google/redirect',
        summary: 'URL d\'authentification Google',
        tags: ['Google Auth'],
    )]
    #[OA\Response(
        response: 200,
        description: 'URL de redirection Google',
        content: new OA\JsonContent(properties: [
            new OA\Property(property: 'url', type: 'string'),
        ]),
    )]
    public function redirect(): JsonResponse
    {
        $url = Socialite::driver('google')->stateless()->redirect()->getTargetUrl();

        return response()->json(['url' => $url]);
    }

    #[OA\Get(
        path: '/api/v1/auth/google/callback',
        summary: 'Callback Google OAuth',
        tags: ['Google Auth'],
    )]
    #[OA\Parameter(
        name: 'code',
        in: 'query',
        required: true,
        schema: new OA\Schema(type: 'string'),
    )]
    #[OA\Response(
        response: 200,
        description: 'Authentification réussie',
        content: new OA\JsonContent(properties: [
            new OA\Property(property: 'user', ref: '#/components/schemas/User'),
            new OA\Property(property: 'token', type: 'string'),
        ]),
    )]
    #[OA\Response(response: 401, description: 'Authentification Google échouée')]
    public function callback(Request $request): JsonResponse|RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
        } catch (\Exception) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Authentification Google échouée.'], 401);
            }
            $errorUrl = config('app.url') . '/auth/google/error';
            return redirect($errorUrl);
        }

        $user = User::where('google_id', $googleUser->getId())->first();

        if (! $user) {
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'auth_provider' => 'google',
                ]);
            } else {
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'password' => Hash::make(Str::password(32)),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'auth_provider' => 'google',
                ]);
            }
        }

        $user->tokens()->delete();

        $token = $user->createToken('auth-token')->plainTextToken;

        if ($request->expectsJson()) {
            return response()->json([
                'user' => $user,
                'token' => $token,
            ]);
        }

        $redirectUrl = 'invoica://auth/callback?' . http_build_query([
            'token' => $token,
            'user' => json_encode($user->only(['id', 'name', 'email'])),
        ]);

        return redirect($redirectUrl);
    }
}
