<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->get('admin') === 'yes') {
            $query->where('is_admin', true);
        } elseif ($request->get('admin') === 'no') {
            $query->where('is_admin', false);
        }

        $users = $query->withCount(['clients', 'documents', 'company'])
            ->latest()
            ->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->loadCount(['clients', 'documents']);
        $user->load(['company', 'clients' => function ($q) {
            $q->latest()->limit(5);
        }, 'documents' => function ($q) {
            $q->with('client:id,name')->latest()->limit(10);
        }]);

        return view('admin.users.show', compact('user'));
    }

    public function toggleAdmin(User $user)
    {
        $user->update(['is_admin' => !$user->is_admin]);
        return back()->with('success', "Statut administrateur mis à jour pour {$user->name}.");
    }

    public function destroy(User $user)
    {
        if ($user->is_admin && User::where('is_admin', true)->count() <= 1) {
            return back()->with('error', 'Impossible de supprimer le dernier administrateur.');
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Utilisateur supprimé.');
    }
}
