<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Crée ou met à jour les utilisateurs du personnel de bureau.
     */
    public function run(): void
    {
        $utilisateurs = [
            [
                'name' => 'Joyce BABAKÉ',
                'email' => 'babakejoyce@gmail.com',
                'password' => 'motdepasse123',
            ],
            [
                'name' => 'Marie Dupont',
                'email' => 'marie@floliv.com',
                'password' => 'motdepasse123',
            ],
            [
                'name' => 'Jean Martin',
                'email' => 'jean@floliv.com',
                'password' => 'motdepasse123',
            ],
            [
                'name' => 'Sophie Kouadio',
                'email' => 'sophie@floliv.com',
                'password' => 'motdepasse123',
            ],
        ];

        foreach ($utilisateurs as $u) {
            User::updateOrCreate(
                ['email' => $u['email']],   // on cherche par email
                [
                    'name' => $u['name'],
                    'password' => Hash::make($u['password']),
                    'email_verified_at' => now(),
                ]
            );
        }

        $this->command->info('✅ ' . count($utilisateurs) . ' utilisateurs ont été créés ou mis à jour.');
    }
}