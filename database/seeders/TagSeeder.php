<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('tags')->insert([
            [
                'user_id' => 1,
                'tag' => '和食',
                'icon' => '和',
                'type' => '1',
            ],
            [
                'user_id' => 1,
                'tag' => '洋食',
                'icon' => '洋',
                'type' => '1',
            ],
            [
                'user_id' => 1,
                'tag' => '中華',
                'icon' => '中',
                'type' => '1',
            ],
            [
                'user_id' => 1,
                'tag' => '主菜',
                'icon' => '主',
                'type' => '2',
            ],
            [
                'user_id' => 1,
                'tag' => '副菜',
                'icon' => '副',
                'type' => '2',
            ],
            [
                'user_id' => 1,
                'tag' => '汁物',
                'icon' => '汁',
                'type' => '2',
            ],
            [
                'user_id' => 1,
                'tag' => 'お菓子',
                'icon' => '菓',
                'type' => '2',
            ],
        ]);
    }
}
