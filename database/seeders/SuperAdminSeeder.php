<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superadmin = Admin::create([
            'name' => 'Nurdin', // Ganti sesuai kebutuhan
            'email' => 'admin@mail.com', // Email super-admin
            'position' => 'Super Admin',
            'password' => Hash::make('adminnurdin'), // Ganti dengan password yang kuat
        ]);

        $superadmin->assignRole('super-admin');

        $this->command->info('Super Admin created successfully!');
    }
}
