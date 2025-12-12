<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // Buat 1 akun admin untuk keperluan development
        $email = 'admin@example.com';

        $user = User::firstOrCreate(
            ['email' => $email], // cek berdasarkan email
            [
                'name' => 'Administrator',
                'password' => Hash::make('password123'), // password default
                'role' => 'admin', // simpan juga di kolom users.role
            ]
        );

        // Assign role Spatie jika belum ada
        if (! $user->hasRole('admin')) {
            $user->assignRole($adminRole);
        }

        $this->command->info('✅ Akun admin berhasil dibuat (email: admin@example.com, password: password123)');
    }
}
