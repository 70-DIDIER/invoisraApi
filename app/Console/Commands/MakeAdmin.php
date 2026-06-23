<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class MakeAdmin extends Command
{
    protected $signature = 'make:admin
        {--name= : Nom de l\'administrateur}
        {--email= : Email de l\'administrateur}
        {--password= : Mot de passe de l\'administrateur}';

    protected $description = 'Créer un super administrateur';

    public function handle()
    {
        $name = $this->option('name') ?? $this->ask('Nom de l\'administrateur');
        $email = $this->option('email') ?? $this->ask('Email de l\'administrateur');
        $password = $this->option('password') ?? $this->secret('Mot de passe');

        if (User::where('email', $email)->exists()) {
            $user = User::where('email', $email)->first();
            $user->update([
                'name' => $name,
                'password' => bcrypt($password),
                'is_admin' => true,
            ]);
            $this->info("L'utilisateur {$email} est maintenant super administrateur.");
            return;
        }

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt($password),
            'is_admin' => true,
        ]);

        $this->info("Super administrateur créé avec succès : {$user->email}");
    }
}
