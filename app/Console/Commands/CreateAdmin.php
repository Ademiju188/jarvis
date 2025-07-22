<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateAdmin extends Command
{
    protected $signature = 'admin:create';
    protected $description = 'Create a default admin user';

    public function handle()
    {
        $admin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@jarvis.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        $this->info('Admin user created successfully!');
        $this->info('Email: admin@jarvis.com');
        $this->info('Password: password123');
    }
}
