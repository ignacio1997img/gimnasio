<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PlansTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('plans')->delete();
        
        \DB::table('plans')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Mensual',
                'description' => NULL,
                'status' => NULL,
                'created_at' => '2022-06-27 10:35:40',
                'updated_at' => '2022-06-27 10:35:40',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Quincenal',
                'description' => NULL,
                'status' => NULL,
                'created_at' => '2022-06-27 10:47:20',
                'updated_at' => '2022-06-27 10:47:20',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Semanal',
                'description' => NULL,
                'status' => NULL,
                'created_at' => '2022-06-27 10:47:59',
                'updated_at' => '2022-06-27 10:47:59',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Diario',
                'description' => NULL,
                'status' => NULL,
                'created_at' => '2022-06-27 10:48:13',
                'updated_at' => '2022-06-27 10:48:13',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}