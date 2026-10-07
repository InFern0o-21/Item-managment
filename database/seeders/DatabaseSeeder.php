<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        

        DB::table('items')->insert([
            'name'        => Str::random(8),
            'description' => Str::random(20),
            'price'       => rand(100, 10000),
            'quantity'    => rand(1, 100),
            'image'       => null,
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
    }
}
