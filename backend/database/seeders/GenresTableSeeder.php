<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GenresTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('genres')->insert([
            ['id_type' => '1', 'name' => 'Вестерн'],
            ['id_type' => '1', 'name' => 'Криминал'],
            ['id_type' => '1', 'name' => 'Фантастика'],
            ['id_type' => '1', 'name' => 'Ужасы'],
            ['id_type' => '1', 'name' => 'Документальный'],
            ['id_type' => '1', 'name' => 'Семейный'],
            ['id_type' => '1', 'name' => 'Боевик'],
            ['id_type' => '1', 'name' => 'Приключения'],
            ['id_type' => '1', 'name' => 'Комедия'],
            ['id_type' => '1', 'name' => 'Мюзикл'],
            ['id_type' => '1', 'name' => 'Для взрослых'],
            ['id_type' => '1', 'name' => 'Фэнтези'],
            ['id_type' => '1', 'name' => 'Военный'],
            ['id_type' => '1', 'name' => 'Драма'],
            ['id_type' => '1', 'name' => 'Мелодрама'],
            ['id_type' => '1', 'name' => 'Детский'],
            ['id_type' => '1', 'name' => 'Концерт'],
            ['id_type' => '1', 'name' => 'Биография'],
            ['id_type' => '1', 'name' => 'Детектив'],
            ['id_type' => '1', 'name' => 'Спорт'],
            ['id_type' => '1', 'name' => 'Триллер'],
            ['id_type' => '1', 'name' => 'История'],
        ]);
    }
}
