<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Unit;

class UnitSeeder extends Seeder
{
    public function run()
    {
        Unit::updateOrCreate(['short_name' => 'г'], ['name' => 'Грамм']);
        Unit::updateOrCreate(['short_name' => 'мл'], ['name' => 'Миллилитр']);
        Unit::updateOrCreate(['short_name' => 'шт'], ['name' => 'Штука']);
    }
}
