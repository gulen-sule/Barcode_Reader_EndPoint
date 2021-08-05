<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class userDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_data')->insert(['first_name' => 'elif', 'last_name' => 'tam',
            'user_photo_path' => 'http://placeimg.com/640/480', 'id_number' => '42876543189',
            'uuid' => \Faker\Provider\Uuid::uuid()]);
    }
}
