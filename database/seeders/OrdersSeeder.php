<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrdersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('orders')->insert([
            'table' => 'Столик №1',
            'shift_worker' => 5,
            'smena_id' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('orders')->insert([
            'table' => 'Столик №1',
            'shift_worker' => 8,
            'smena_id' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('orders')->insert([
            'table' => 'Столик №1',
            'shift_worker' => 6,
            'smena_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('orders')->insert([
            'table' => 'Столик №1',
            'shift_worker' => 7,
            'smena_id' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
