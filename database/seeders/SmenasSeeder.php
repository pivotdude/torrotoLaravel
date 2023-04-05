<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SmenasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('smenas')->insert([
            'start' => now(),
            'end' => now(),
            'active' => 0,
            'created_at' => now(),
            'updated_at' => now(),
            'user_id' => 4,
        ]);
        DB::table('smenas')->insert([
            'start' => now(),
            'end' => now(),
            'active' => 1,
            'user_id' => 4,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
