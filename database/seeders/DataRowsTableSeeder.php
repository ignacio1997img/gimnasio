<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DataRowsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('data_rows')->delete();
        
        \DB::table('data_rows')->insert(array (
            0 => 
            array (
                'id' => 1,
                'data_type_id' => 1,
                'field' => 'id',
                'type' => 'number',
                'display_name' => 'ID',
                'required' => 1,
                'browse' => 0,
                'read' => 0,
                'edit' => 0,
                'add' => 0,
                'delete' => 0,
                'details' => '{}',
                'order' => 1,
            ),
            1 => 
            array (
                'id' => 2,
                'data_type_id' => 1,
                'field' => 'name',
                'type' => 'text',
                'display_name' => 'Name',
                'required' => 1,
                'browse' => 1,
                'read' => 1,
                'edit' => 1,
                'add' => 1,
                'delete' => 1,
                'details' => '{}',
                'order' => 2,
            ),
            2 => 
            array (
                'id' => 3,
                'data_type_id' => 1,
                'field' => 'email',
                'type' => 'text',
                'display_name' => 'Email',
                'required' => 1,
                'browse' => 1,
                'read' => 1,
                'edit' => 1,
                'add' => 1,
                'delete' => 1,
                'details' => '{}',
                'order' => 3,
            ),
            3 => 
            array (
                'id' => 4,
                'data_type_id' => 1,
                'field' => 'password',
                'type' => 'password',
                'display_name' => 'Password',
                'required' => 1,
                'browse' => 0,
                'read' => 0,
                'edit' => 1,
                'add' => 1,
                'delete' => 0,
                'details' => '{}',
                'order' => 4,
            ),
            4 => 
            array (
                'id' => 5,
                'data_type_id' => 1,
                'field' => 'remember_token',
                'type' => 'text',
                'display_name' => 'Remember Token',
                'required' => 0,
                'browse' => 0,
                'read' => 0,
                'edit' => 0,
                'add' => 0,
                'delete' => 0,
                'details' => '{}',
                'order' => 5,
            ),
            5 => 
            array (
                'id' => 6,
                'data_type_id' => 1,
                'field' => 'created_at',
                'type' => 'timestamp',
                'display_name' => 'Created At',
                'required' => 0,
                'browse' => 1,
                'read' => 1,
                'edit' => 0,
                'add' => 0,
                'delete' => 0,
                'details' => '{}',
                'order' => 6,
            ),
            6 => 
            array (
                'id' => 7,
                'data_type_id' => 1,
                'field' => 'updated_at',
                'type' => 'timestamp',
                'display_name' => 'Updated At',
                'required' => 0,
                'browse' => 0,
                'read' => 0,
                'edit' => 0,
                'add' => 0,
                'delete' => 0,
                'details' => '{}',
                'order' => 7,
            ),
            7 => 
            array (
                'id' => 8,
                'data_type_id' => 1,
                'field' => 'avatar',
                'type' => 'image',
                'display_name' => 'Avatar',
                'required' => 0,
                'browse' => 1,
                'read' => 1,
                'edit' => 1,
                'add' => 1,
                'delete' => 1,
                'details' => '{}',
                'order' => 8,
            ),
            8 => 
            array (
                'id' => 9,
                'data_type_id' => 1,
                'field' => 'user_belongsto_role_relationship',
                'type' => 'relationship',
                'display_name' => 'Role',
                'required' => 0,
                'browse' => 1,
                'read' => 1,
                'edit' => 1,
                'add' => 1,
                'delete' => 0,
                'details' => '{"model":"TCG\\\\Voyager\\\\Models\\\\Role","table":"roles","type":"belongsTo","column":"role_id","key":"id","label":"display_name","pivot_table":"roles","pivot":"0","taggable":"0"}',
                'order' => 10,
            ),
            9 => 
            array (
                'id' => 10,
                'data_type_id' => 1,
                'field' => 'user_belongstomany_role_relationship',
                'type' => 'relationship',
                'display_name' => 'Roles',
                'required' => 0,
                'browse' => 1,
                'read' => 1,
                'edit' => 1,
                'add' => 1,
                'delete' => 0,
                'details' => '{"model":"TCG\\\\Voyager\\\\Models\\\\Role","table":"roles","type":"belongsToMany","column":"id","key":"id","label":"display_name","pivot_table":"user_roles","pivot":"1","taggable":"0"}',
                'order' => 11,
            ),
            10 => 
            array (
                'id' => 11,
                'data_type_id' => 1,
                'field' => 'settings',
                'type' => 'hidden',
                'display_name' => 'Settings',
                'required' => 0,
                'browse' => 0,
                'read' => 0,
                'edit' => 0,
                'add' => 0,
                'delete' => 0,
                'details' => '{}',
                'order' => 12,
            ),
            11 => 
            array (
                'id' => 12,
                'data_type_id' => 2,
                'field' => 'id',
                'type' => 'number',
                'display_name' => 'ID',
                'required' => 1,
                'browse' => 0,
                'read' => 0,
                'edit' => 0,
                'add' => 0,
                'delete' => 0,
                'details' => NULL,
                'order' => 1,
            ),
            12 => 
            array (
                'id' => 13,
                'data_type_id' => 2,
                'field' => 'name',
                'type' => 'text',
                'display_name' => 'Name',
                'required' => 1,
                'browse' => 1,
                'read' => 1,
                'edit' => 1,
                'add' => 1,
                'delete' => 1,
                'details' => NULL,
                'order' => 2,
            ),
            13 => 
            array (
                'id' => 14,
                'data_type_id' => 2,
                'field' => 'created_at',
                'type' => 'timestamp',
                'display_name' => 'Created At',
                'required' => 0,
                'browse' => 0,
                'read' => 0,
                'edit' => 0,
                'add' => 0,
                'delete' => 0,
                'details' => NULL,
                'order' => 3,
            ),
            14 => 
            array (
                'id' => 15,
                'data_type_id' => 2,
                'field' => 'updated_at',
                'type' => 'timestamp',
                'display_name' => 'Updated At',
                'required' => 0,
                'browse' => 0,
                'read' => 0,
                'edit' => 0,
                'add' => 0,
                'delete' => 0,
                'details' => NULL,
                'order' => 4,
            ),
            15 => 
            array (
                'id' => 16,
                'data_type_id' => 3,
                'field' => 'id',
                'type' => 'number',
                'display_name' => 'ID',
                'required' => 1,
                'browse' => 0,
                'read' => 0,
                'edit' => 0,
                'add' => 0,
                'delete' => 0,
                'details' => NULL,
                'order' => 1,
            ),
            16 => 
            array (
                'id' => 17,
                'data_type_id' => 3,
                'field' => 'name',
                'type' => 'text',
                'display_name' => 'Name',
                'required' => 1,
                'browse' => 1,
                'read' => 1,
                'edit' => 1,
                'add' => 1,
                'delete' => 1,
                'details' => NULL,
                'order' => 2,
            ),
            17 => 
            array (
                'id' => 18,
                'data_type_id' => 3,
                'field' => 'created_at',
                'type' => 'timestamp',
                'display_name' => 'Created At',
                'required' => 0,
                'browse' => 0,
                'read' => 0,
                'edit' => 0,
                'add' => 0,
                'delete' => 0,
                'details' => NULL,
                'order' => 3,
            ),
            18 => 
            array (
                'id' => 19,
                'data_type_id' => 3,
                'field' => 'updated_at',
                'type' => 'timestamp',
                'display_name' => 'Updated At',
                'required' => 0,
                'browse' => 0,
                'read' => 0,
                'edit' => 0,
                'add' => 0,
                'delete' => 0,
                'details' => NULL,
                'order' => 4,
            ),
            19 => 
            array (
                'id' => 20,
                'data_type_id' => 3,
                'field' => 'display_name',
                'type' => 'text',
                'display_name' => 'Display Name',
                'required' => 1,
                'browse' => 1,
                'read' => 1,
                'edit' => 1,
                'add' => 1,
                'delete' => 1,
                'details' => NULL,
                'order' => 5,
            ),
            20 => 
            array (
                'id' => 21,
                'data_type_id' => 1,
                'field' => 'role_id',
                'type' => 'text',
                'display_name' => 'Role',
                'required' => 0,
                'browse' => 1,
                'read' => 1,
                'edit' => 1,
                'add' => 1,
                'delete' => 1,
                'details' => '{}',
                'order' => 9,
            ),
            21 => 
            array (
                'id' => 22,
                'data_type_id' => 5,
                'field' => 'id',
                'type' => 'text',
                'display_name' => 'Id',
                'required' => 1,
                'browse' => 1,
                'read' => 0,
                'edit' => 0,
                'add' => 0,
                'delete' => 0,
                'details' => '{}',
                'order' => 1,
            ),
            22 => 
            array (
                'id' => 23,
                'data_type_id' => 5,
                'field' => 'ci',
                'type' => 'number',
                'display_name' => 'Ci',
                'required' => 1,
                'browse' => 1,
                'read' => 1,
                'edit' => 1,
                'add' => 1,
                'delete' => 1,
                'details' => '{"validation":{"rule":"required|max:191|unique:people"},"display":{"width":6}}',
                'order' => 2,
            ),
            23 => 
            array (
                'id' => 24,
                'data_type_id' => 5,
                'field' => 'first_name',
                'type' => 'text',
                'display_name' => 'Nombre',
                'required' => 0,
                'browse' => 1,
                'read' => 1,
                'edit' => 1,
                'add' => 1,
                'delete' => 1,
                'details' => '{"validation":{"rule":"required|max:191"},"display":{"width":6}}',
                'order' => 3,
            ),
            24 => 
            array (
                'id' => 25,
                'data_type_id' => 5,
                'field' => 'last_name',
                'type' => 'text',
                'display_name' => 'Apellido',
                'required' => 0,
                'browse' => 1,
                'read' => 1,
                'edit' => 1,
                'add' => 1,
                'delete' => 1,
                'details' => '{"validation":{"rule":"required|max:191"},"display":{"width":6}}',
                'order' => 4,
            ),
            25 => 
            array (
                'id' => 26,
                'data_type_id' => 5,
                'field' => 'birthdate',
                'type' => 'date',
                'display_name' => 'Fecha de nacimiento',
                'required' => 0,
                'browse' => 1,
                'read' => 1,
                'edit' => 1,
                'add' => 1,
                'delete' => 0,
                'details' => '{"validation":{"rule":"required|max:191"},"display":{"width":6}}',
                'order' => 5,
            ),
            26 => 
            array (
                'id' => 27,
                'data_type_id' => 5,
                'field' => 'email',
                'type' => 'text',
                'display_name' => 'Email',
                'required' => 0,
                'browse' => 0,
                'read' => 1,
                'edit' => 1,
                'add' => 1,
                'delete' => 0,
                'details' => '{"validation":{"rule":"max:191"},"display":{"width":6}}',
                'order' => 6,
            ),
            27 => 
            array (
                'id' => 28,
                'data_type_id' => 5,
                'field' => 'phone',
                'type' => 'number',
                'display_name' => 'Telefono',
                'required' => 0,
                'browse' => 1,
                'read' => 1,
                'edit' => 1,
                'add' => 1,
                'delete' => 1,
                'details' => '{"validation":{"rule":"max:191"},"display":{"width":6}}',
                'order' => 7,
            ),
            28 => 
            array (
                'id' => 29,
                'data_type_id' => 5,
                'field' => 'address',
                'type' => 'text_area',
                'display_name' => 'Dirección',
                'required' => 0,
                'browse' => 0,
                'read' => 1,
                'edit' => 1,
                'add' => 1,
                'delete' => 0,
                'details' => '{"validation":{"rule":"max:191"}}',
                'order' => 12,
            ),
            29 => 
            array (
                'id' => 30,
                'data_type_id' => 5,
                'field' => 'photo',
                'type' => 'image',
                'display_name' => 'Fotografia',
                'required' => 0,
                'browse' => 1,
                'read' => 1,
                'edit' => 1,
                'add' => 1,
                'delete' => 1,
                'details' => '{"display":{"width":6},"resize":{"width":"1000","height":null},"quality":"70%","upsize":true,"thumbnails":[{"name":"medium","scale":"50%"},{"name":"small","scale":"25%"},{"name":"cropped","crop":{"width":"300","height":"250"}}]}',
                'order' => 11,
            ),
            30 => 
            array (
                'id' => 31,
                'data_type_id' => 5,
                'field' => 'gender',
                'type' => 'select_dropdown',
                'display_name' => 'Genero',
                'required' => 0,
                'browse' => 0,
                'read' => 1,
                'edit' => 1,
                'add' => 1,
                'delete' => 1,
                'details' => '{"options":{"0":"Femenino","1":"Masculino","":"Seleccione una opci\\u00f3n"},"validation":{"rule":"required"},"display":{"width":6}}',
                'order' => 8,
            ),
            31 => 
            array (
                'id' => 32,
                'data_type_id' => 5,
                'field' => 'weight',
                'type' => 'number',
                'display_name' => 'Peso',
                'required' => 0,
                'browse' => 0,
                'read' => 1,
                'edit' => 1,
                'add' => 1,
                'delete' => 1,
                'details' => '{"validation":{"rule":"max:191"},"display":{"width":6}}',
                'order' => 9,
            ),
            32 => 
            array (
                'id' => 33,
                'data_type_id' => 5,
                'field' => 'height',
                'type' => 'number',
                'display_name' => 'Altura',
                'required' => 0,
                'browse' => 0,
                'read' => 1,
                'edit' => 1,
                'add' => 1,
                'delete' => 1,
                'details' => '{"validation":{"rule":"max:191"},"display":{"width":6}}',
                'order' => 10,
            ),
            33 => 
            array (
                'id' => 34,
                'data_type_id' => 5,
                'field' => 'status',
                'type' => 'checkbox',
                'display_name' => 'Estado',
                'required' => 1,
                'browse' => 1,
                'read' => 1,
                'edit' => 1,
                'add' => 0,
                'delete' => 0,
                'details' => '{"on":"Activo","off":"Inactivo","checked":true}',
                'order' => 13,
            ),
            34 => 
            array (
                'id' => 35,
                'data_type_id' => 5,
                'field' => 'created_at',
                'type' => 'timestamp',
                'display_name' => 'Creado',
                'required' => 0,
                'browse' => 1,
                'read' => 1,
                'edit' => 0,
                'add' => 0,
                'delete' => 0,
                'details' => '{}',
                'order' => 14,
            ),
            35 => 
            array (
                'id' => 36,
                'data_type_id' => 5,
                'field' => 'updated_at',
                'type' => 'timestamp',
                'display_name' => 'Updated At',
                'required' => 0,
                'browse' => 0,
                'read' => 0,
                'edit' => 0,
                'add' => 0,
                'delete' => 0,
                'details' => '{}',
                'order' => 15,
            ),
            36 => 
            array (
                'id' => 37,
                'data_type_id' => 5,
                'field' => 'deleted_at',
                'type' => 'timestamp',
                'display_name' => 'Deleted At',
                'required' => 0,
                'browse' => 0,
                'read' => 0,
                'edit' => 0,
                'add' => 0,
                'delete' => 0,
                'details' => '{}',
                'order' => 16,
            ),
            37 => 
            array (
                'id' => 38,
                'data_type_id' => 6,
                'field' => 'id',
                'type' => 'text',
                'display_name' => 'Id',
                'required' => 1,
                'browse' => 1,
                'read' => 0,
                'edit' => 0,
                'add' => 0,
                'delete' => 0,
                'details' => '{}',
                'order' => 1,
            ),
            38 => 
            array (
                'id' => 39,
                'data_type_id' => 6,
                'field' => 'name',
                'type' => 'text',
                'display_name' => 'Nombre',
                'required' => 0,
                'browse' => 1,
                'read' => 1,
                'edit' => 1,
                'add' => 1,
                'delete' => 0,
                'details' => '{}',
                'order' => 3,
            ),
            39 => 
            array (
                'id' => 40,
                'data_type_id' => 6,
                'field' => 'image',
                'type' => 'text',
                'display_name' => 'Image',
                'required' => 0,
                'browse' => 0,
                'read' => 0,
                'edit' => 0,
                'add' => 0,
                'delete' => 0,
                'details' => '{}',
                'order' => 4,
            ),
            40 => 
            array (
                'id' => 41,
                'data_type_id' => 6,
                'field' => 'description',
                'type' => 'text',
                'display_name' => 'Description',
                'required' => 0,
                'browse' => 0,
                'read' => 0,
                'edit' => 0,
                'add' => 0,
                'delete' => 0,
                'details' => '{}',
                'order' => 5,
            ),
            41 => 
            array (
                'id' => 42,
                'data_type_id' => 6,
                'field' => 'status',
                'type' => 'checkbox',
                'display_name' => 'Estado',
                'required' => 1,
                'browse' => 1,
                'read' => 1,
                'edit' => 1,
                'add' => 1,
                'delete' => 0,
                'details' => '{"on":"Activo","of":"Inactivo","checked":true}',
                'order' => 6,
            ),
            42 => 
            array (
                'id' => 43,
                'data_type_id' => 6,
                'field' => 'ip',
                'type' => 'text',
                'display_name' => 'Ip',
                'required' => 0,
                'browse' => 0,
                'read' => 0,
                'edit' => 0,
                'add' => 0,
                'delete' => 0,
                'details' => '{}',
                'order' => 7,
            ),
            43 => 
            array (
                'id' => 44,
                'data_type_id' => 6,
                'field' => 'mac',
                'type' => 'text',
                'display_name' => 'Mac',
                'required' => 0,
                'browse' => 0,
                'read' => 0,
                'edit' => 0,
                'add' => 0,
                'delete' => 0,
                'details' => '{}',
                'order' => 8,
            ),
            44 => 
            array (
                'id' => 45,
                'data_type_id' => 6,
                'field' => 'register_user',
                'type' => 'text',
                'display_name' => 'Register User',
                'required' => 0,
                'browse' => 0,
                'read' => 0,
                'edit' => 0,
                'add' => 0,
                'delete' => 0,
                'details' => '{}',
                'order' => 2,
            ),
            45 => 
            array (
                'id' => 46,
                'data_type_id' => 6,
                'field' => 'created_at',
                'type' => 'timestamp',
                'display_name' => 'Creado',
                'required' => 0,
                'browse' => 1,
                'read' => 0,
                'edit' => 0,
                'add' => 0,
                'delete' => 0,
                'details' => '{}',
                'order' => 9,
            ),
            46 => 
            array (
                'id' => 47,
                'data_type_id' => 6,
                'field' => 'updated_at',
                'type' => 'timestamp',
                'display_name' => 'Updated At',
                'required' => 0,
                'browse' => 0,
                'read' => 0,
                'edit' => 0,
                'add' => 0,
                'delete' => 0,
                'details' => '{}',
                'order' => 10,
            ),
            47 => 
            array (
                'id' => 48,
                'data_type_id' => 6,
                'field' => 'deleted_at',
                'type' => 'timestamp',
                'display_name' => 'Deleted At',
                'required' => 0,
                'browse' => 0,
                'read' => 0,
                'edit' => 0,
                'add' => 0,
                'delete' => 0,
                'details' => '{}',
                'order' => 11,
            ),
            48 => 
            array (
                'id' => 49,
                'data_type_id' => 7,
                'field' => 'id',
                'type' => 'text',
                'display_name' => 'Id',
                'required' => 1,
                'browse' => 1,
                'read' => 0,
                'edit' => 0,
                'add' => 0,
                'delete' => 0,
                'details' => '{}',
                'order' => 1,
            ),
            49 => 
            array (
                'id' => 50,
                'data_type_id' => 7,
                'field' => 'name',
                'type' => 'text',
                'display_name' => 'Plan',
                'required' => 0,
                'browse' => 1,
                'read' => 1,
                'edit' => 1,
                'add' => 1,
                'delete' => 0,
                'details' => '{}',
                'order' => 2,
            ),
            50 => 
            array (
                'id' => 51,
                'data_type_id' => 7,
                'field' => 'description',
                'type' => 'text',
                'display_name' => 'Description',
                'required' => 0,
                'browse' => 0,
                'read' => 0,
                'edit' => 0,
                'add' => 0,
                'delete' => 0,
                'details' => '{}',
                'order' => 3,
            ),
            51 => 
            array (
                'id' => 52,
                'data_type_id' => 7,
                'field' => 'status',
                'type' => 'checkbox',
                'display_name' => 'Estado',
                'required' => 0,
                'browse' => 1,
                'read' => 0,
                'edit' => 1,
                'add' => 0,
                'delete' => 0,
                'details' => '{"on":"Activo","off":"Inactivo","checked":true}',
                'order' => 4,
            ),
            52 => 
            array (
                'id' => 53,
                'data_type_id' => 7,
                'field' => 'created_at',
                'type' => 'timestamp',
                'display_name' => 'Created At',
                'required' => 0,
                'browse' => 0,
                'read' => 0,
                'edit' => 0,
                'add' => 0,
                'delete' => 0,
                'details' => '{}',
                'order' => 5,
            ),
            53 => 
            array (
                'id' => 54,
                'data_type_id' => 7,
                'field' => 'updated_at',
                'type' => 'timestamp',
                'display_name' => 'Updated At',
                'required' => 0,
                'browse' => 0,
                'read' => 0,
                'edit' => 0,
                'add' => 0,
                'delete' => 0,
                'details' => '{}',
                'order' => 6,
            ),
            54 => 
            array (
                'id' => 55,
                'data_type_id' => 7,
                'field' => 'deleted_at',
                'type' => 'timestamp',
                'display_name' => 'Deleted At',
                'required' => 0,
                'browse' => 0,
                'read' => 0,
                'edit' => 0,
                'add' => 0,
                'delete' => 0,
                'details' => '{}',
                'order' => 7,
            ),
            55 => 
            array (
                'id' => 56,
                'data_type_id' => 8,
                'field' => 'id',
                'type' => 'text',
                'display_name' => 'Id',
                'required' => 1,
                'browse' => 1,
                'read' => 0,
                'edit' => 0,
                'add' => 0,
                'delete' => 0,
                'details' => '{}',
                'order' => 1,
            ),
            56 => 
            array (
                'id' => 57,
                'data_type_id' => 8,
                'field' => 'name',
                'type' => 'text',
                'display_name' => 'Día',
                'required' => 0,
                'browse' => 1,
                'read' => 1,
                'edit' => 1,
                'add' => 1,
                'delete' => 0,
                'details' => '{}',
                'order' => 2,
            ),
            57 => 
            array (
                'id' => 58,
                'data_type_id' => 8,
                'field' => 'description',
                'type' => 'text',
                'display_name' => 'Description',
                'required' => 0,
                'browse' => 0,
                'read' => 0,
                'edit' => 0,
                'add' => 0,
                'delete' => 0,
                'details' => '{}',
                'order' => 3,
            ),
            58 => 
            array (
                'id' => 59,
                'data_type_id' => 8,
                'field' => 'status',
                'type' => 'checkbox',
                'display_name' => 'Estado',
                'required' => 1,
                'browse' => 1,
                'read' => 0,
                'edit' => 1,
                'add' => 0,
                'delete' => 0,
                'details' => '{"on":"Activo","off":"Inactivo","checked":true}',
                'order' => 4,
            ),
            59 => 
            array (
                'id' => 60,
                'data_type_id' => 8,
                'field' => 'created_at',
                'type' => 'timestamp',
                'display_name' => 'Created At',
                'required' => 0,
                'browse' => 0,
                'read' => 0,
                'edit' => 0,
                'add' => 0,
                'delete' => 0,
                'details' => '{}',
                'order' => 5,
            ),
            60 => 
            array (
                'id' => 61,
                'data_type_id' => 8,
                'field' => 'updated_at',
                'type' => 'timestamp',
                'display_name' => 'Updated At',
                'required' => 0,
                'browse' => 0,
                'read' => 0,
                'edit' => 0,
                'add' => 0,
                'delete' => 0,
                'details' => '{}',
                'order' => 6,
            ),
            61 => 
            array (
                'id' => 62,
                'data_type_id' => 8,
                'field' => 'deleted_at',
                'type' => 'timestamp',
                'display_name' => 'Deleted At',
                'required' => 0,
                'browse' => 0,
                'read' => 0,
                'edit' => 0,
                'add' => 0,
                'delete' => 0,
                'details' => '{}',
                'order' => 7,
            ),
            62 => 
            array (
                'id' => 75,
                'data_type_id' => 1,
                'field' => 'email_verified_at',
                'type' => 'timestamp',
                'display_name' => 'Email Verified At',
                'required' => 0,
                'browse' => 1,
                'read' => 1,
                'edit' => 1,
                'add' => 1,
                'delete' => 1,
                'details' => '{}',
                'order' => 6,
            ),
            63 => 
            array (
                'id' => 78,
                'data_type_id' => 10,
                'field' => 'id',
                'type' => 'text',
                'display_name' => 'Id',
                'required' => 1,
                'browse' => 1,
                'read' => 0,
                'edit' => 0,
                'add' => 0,
                'delete' => 0,
                'details' => '{}',
                'order' => 1,
            ),
            64 => 
            array (
                'id' => 79,
                'data_type_id' => 10,
                'field' => 'nit',
                'type' => 'text',
                'display_name' => 'Nit',
                'required' => 0,
                'browse' => 1,
                'read' => 1,
                'edit' => 1,
                'add' => 1,
                'delete' => 1,
                'details' => '{"validation":{"rule":"required|max:191|unique:busines"},"display":{"width":6}}',
                'order' => 2,
            ),
            65 => 
            array (
                'id' => 80,
                'data_type_id' => 10,
                'field' => 'name',
                'type' => 'text',
                'display_name' => 'Nombre',
                'required' => 0,
                'browse' => 1,
                'read' => 1,
                'edit' => 1,
                'add' => 1,
                'delete' => 1,
                'details' => '{"validation":{"rule":"required|max:191|unique:busines"},"display":{"width":6}}',
                'order' => 3,
            ),
            66 => 
            array (
                'id' => 81,
                'data_type_id' => 10,
                'field' => 'responsible',
                'type' => 'text',
                'display_name' => 'Responsable',
                'required' => 0,
                'browse' => 1,
                'read' => 1,
                'edit' => 1,
                'add' => 1,
                'delete' => 1,
                'details' => '{"validation":{"rule":"required|max:191"},"display":{"width":6}}',
                'order' => 4,
            ),
            67 => 
            array (
                'id' => 82,
                'data_type_id' => 10,
                'field' => 'phone',
                'type' => 'text',
                'display_name' => 'Telefono',
                'required' => 0,
                'browse' => 1,
                'read' => 1,
                'edit' => 1,
                'add' => 1,
                'delete' => 1,
                'details' => '{"validation":{"rule":"required|max:191"},"display":{"width":6}}',
                'order' => 5,
            ),
            68 => 
            array (
                'id' => 83,
                'data_type_id' => 10,
                'field' => 'email',
                'type' => 'text',
                'display_name' => 'Email',
                'required' => 0,
                'browse' => 1,
                'read' => 1,
                'edit' => 1,
                'add' => 1,
                'delete' => 1,
                'details' => '{"validation":{"rule":"required|max:191"},"display":{"width":6}}',
                'order' => 6,
            ),
            69 => 
            array (
                'id' => 84,
                'data_type_id' => 10,
                'field' => 'address',
                'type' => 'text_area',
                'display_name' => 'Dirección',
                'required' => 0,
                'browse' => 1,
                'read' => 1,
                'edit' => 1,
                'add' => 1,
                'delete' => 1,
                'details' => '{"validation":{"rule":"max:191"}}',
                'order' => 8,
            ),
            70 => 
            array (
                'id' => 85,
                'data_type_id' => 10,
                'field' => 'image',
                'type' => 'image',
                'display_name' => 'Imagen',
                'required' => 0,
                'browse' => 1,
                'read' => 0,
                'edit' => 1,
                'add' => 1,
                'delete' => 0,
                'details' => '{"display":{"width":6},"resize":{"width":"1000","height":null},"quality":"70%","upsize":true,"thumbnails":[{"name":"medium","scale":"50%"},{"name":"small","scale":"25%"},{"name":"cropped","crop":{"width":"300","height":"250"}}]}',
                'order' => 7,
            ),
            71 => 
            array (
                'id' => 86,
                'data_type_id' => 10,
                'field' => 'status',
                'type' => 'checkbox',
                'display_name' => 'Estado',
                'required' => 1,
                'browse' => 1,
                'read' => 0,
                'edit' => 1,
                'add' => 0,
                'delete' => 0,
                'details' => '{"on":"Activo","off":"Inactivo","checked":true}',
                'order' => 9,
            ),
            72 => 
            array (
                'id' => 87,
                'data_type_id' => 10,
                'field' => 'created_at',
                'type' => 'timestamp',
                'display_name' => 'Created At',
                'required' => 0,
                'browse' => 0,
                'read' => 0,
                'edit' => 0,
                'add' => 0,
                'delete' => 1,
                'details' => '{}',
                'order' => 10,
            ),
            73 => 
            array (
                'id' => 88,
                'data_type_id' => 10,
                'field' => 'updated_at',
                'type' => 'timestamp',
                'display_name' => 'Updated At',
                'required' => 0,
                'browse' => 0,
                'read' => 0,
                'edit' => 0,
                'add' => 0,
                'delete' => 0,
                'details' => '{}',
                'order' => 11,
            ),
            74 => 
            array (
                'id' => 89,
                'data_type_id' => 10,
                'field' => 'deleted_at',
                'type' => 'timestamp',
                'display_name' => 'Deleted At',
                'required' => 0,
                'browse' => 0,
                'read' => 0,
                'edit' => 0,
                'add' => 0,
                'delete' => 0,
                'details' => '{}',
                'order' => 12,
            ),
        ));
        
        
    }
}