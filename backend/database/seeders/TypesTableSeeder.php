<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypesTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('types')->insert([
            ['name' => 'Фильм'],
            ['name' => 'Сериал'],
            ['name' => 'Мультфильм'],
            ['name' => 'Аниме'],
        ]);
    }
}
