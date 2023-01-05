<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\Permission;
use Illuminate\Support\Facades\DB;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     */
    public function run()
    {
        \DB::table('permissions')->delete();
        
        Permission::firstOrCreate([
            'key'        => 'browse_admin',
            'table_name' => 'admin',
        ]);
        // return 1;
        $keys = [
            // 'browse_admin',
            'browse_bread',
            'browse_database',
            'browse_media',
            'browse_compass',
            'browse_clear-cache',
        ];

        foreach ($keys as $key) {
            Permission::firstOrCreate([
                'key'        => $key,
                'table_name' => null,
            ]);
        }

        Permission::generateFor('menus');

        Permission::generateFor('roles');

        Permission::generateFor('users');

        Permission::generateFor('settings');


        Permission::generateFor('people');
        // Permission::generateFor('busines');
        $keys = [
            'browse_busines',
            'add_busines',
            'edit_busines',
            'read_busines',
            'user_busines'            
        ];

        foreach ($keys as $key) {
            Permission::firstOrCreate([
                'key'        => $key,
                'table_name' => 'busines',
            ]);
        }

        $keys = [
            'browse_services',
            'add_services',
            'edit_services',
            'delete_services',
            'browse_plans',        
            'add_plans'  ,
            'edit_plans',
            'delete_plans',
        ];

        foreach ($keys as $key) {
            Permission::firstOrCreate([
                'key'        => $key,
                'table_name' => 'services',
            ]);
        }


        Permission::generateFor('days');
        Permission::generateFor('providers');
        Permission::generateFor('categories');
        Permission::generateFor('articles');


        $keys = [
            'browse_vaults',
            'add_vaults',
            'open_vaults',
            'movements_vaults',
            'close_vaults',
            'print_vaults',
            
        ];

        foreach ($keys as $key) {
            Permission::firstOrCreate([
                'key'        => $key,
                'table_name' => 'vaults',
            ]);
        }

        $keys = [
            'browse_cashiers',
            'add_cashiers',
            // 'open_cashiers',
            // 'movements_cashiers',
            // 'close_vaults',
            // 'print_vaults',
            
        ];
        foreach ($keys as $key) {
            Permission::firstOrCreate([
                'key'        => $key,
                'table_name' => 'cashiers',
            ]);
        }

        
        // Permission::generateFor('cashiers');

        $keys = [
            'browse_clients',
            'edit_clients',
            'add_clients',
            'delete_clients',
            'print_clients',
            
        ];

        foreach ($keys as $key) {
            Permission::firstOrCreate([
                'key'        => $key,
                'table_name' => 'clients',
            ]);
        }



        //para el almacen
        $keys = [
            'browse_wherehouses',
            'add_wherehouses',
            'delete_wherehouses',
            
        ];

        foreach ($keys as $key) {
            Permission::firstOrCreate([
                'key'        => $key,
                'table_name' => 'wherehouses',
            ]);
        }



        
        

    }
}
