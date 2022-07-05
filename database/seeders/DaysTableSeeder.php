<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DaysTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('days')->delete();
        
        \DB::table('days')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Lunes',
                'description' => NULL,
                'status' => 1,
                'created_at' => '2022-06-27 11:28:41',
                'updated_at' => '2022-06-27 11:28:41',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Martes',
                'description' => NULL,
                'status' => 1,
                'created_at' => '2022-06-27 11:29:06',
                'updated_at' => '2022-06-27 11:29:06',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Miércoles',
                'description' => NULL,
                'status' => 1,
                'created_at' => '2022-06-27 11:29:14',
                'updated_at' => '2022-06-27 11:29:14',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Jueves',
                'description' => NULL,
                'status' => 1,
                'created_at' => '2022-06-27 11:29:24',
                'updated_at' => '2022-06-27 11:29:24',
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Viernes',
                'description' => NULL,
                'status' => 1,
                'created_at' => '2022-06-27 11:29:34',
                'updated_at' => '2022-06-27 11:29:34',
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'Sábado',
                'description' => NULL,
                'status' => 1,
                'created_at' => '2022-06-27 11:29:48',
                'updated_at' => '2022-06-27 11:29:48',
                'deleted_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'Domingo',
                'description' => NULL,
                'status' => 1,
                'created_at' => '2022-06-27 11:30:00',
                'updated_at' => '2022-06-27 11:30:00',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}