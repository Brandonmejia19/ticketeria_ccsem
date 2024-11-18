<?php

namespace Database\Seeders;

use App\Models\Ambulancia;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
class AmbulanciaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('')->insert([
            'name' => Str::random(10),
            'email' => Str::random(10) . '@example.com',
            'password' => Hash::make('password'),
        ]);
        Ambulancia::factory()
            ->count(50)
            ->hasPosts(1)
            ->create();
    }
}
