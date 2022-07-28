<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\Permission;
use TCG\Voyager\Models\Role;

class PermissionRoleTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::where('name', 'admin')->firstOrFail();

        $permissions = Permission::all();

        $role->permissions()->sync(
            $permissions->pluck('id')->all()
        );


        //############## Administrador ####################
        $role = Role::where('name', 'administrador')->firstOrFail();
        $permissions = Permission::whereRaw('table_name = "admin" or
                                            table_name = "vaults" or
                                            table_name = "cashiers" or
                                            table_name = "providers" or
                                            table_name = "categories" or
                                            table_name = "articles" or
                                            table_name = "people" or
                                            table_name = "services" or
                                            table_name = "wherehouses" or
                                            `key` = "add_clients" or
                                            `key` = "browse_clear-cache"')->get();
        $role->permissions()->sync($permissions->pluck('id')->all());

        //############## caja_cajero ######################
        $role = Role::where('name', 'caja_cajero')->firstOrFail();
        $permissions = Permission::whereRaw('table_name = "admin" or
                                             table_name = "clients" or
                                             table_name = "people" or
                                            `key` = "browse_wherehouses" or
                                            `key` = "browse_clear-cache"')->get();
        $role->permissions()->sync($permissions->pluck('id')->all());
    }
}