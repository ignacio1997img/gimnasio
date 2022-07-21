<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MenuItemsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('menu_items')->delete();
        
        \DB::table('menu_items')->insert(array (
            0 => 
            array (
                'id' => 1,
                'menu_id' => 1,
                'title' => 'Inicio',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-home',
                'color' => '#000000',
                'parent_id' => NULL,
                'order' => 1,
                'created_at' => '2021-06-02 17:55:32',
                'updated_at' => '2022-07-06 01:48:36',
                'route' => 'voyager.dashboard',
                'parameters' => 'null',
            ),
            1 => 
            array (
                'id' => 2,
                'menu_id' => 1,
                'title' => 'Media',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-images',
                'color' => NULL,
                'parent_id' => 5,
                'order' => 3,
                'created_at' => '2021-06-02 17:55:32',
                'updated_at' => '2021-06-02 14:07:22',
                'route' => 'voyager.media.index',
                'parameters' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'menu_id' => 1,
                'title' => 'Users',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-person',
                'color' => NULL,
                'parent_id' => 11,
                'order' => 1,
                'created_at' => '2021-06-02 17:55:32',
                'updated_at' => '2021-06-02 14:08:02',
                'route' => 'voyager.users.index',
                'parameters' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'menu_id' => 1,
                'title' => 'Roles',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-lock',
                'color' => NULL,
                'parent_id' => 11,
                'order' => 2,
                'created_at' => '2021-06-02 17:55:32',
                'updated_at' => '2021-06-02 14:08:05',
                'route' => 'voyager.roles.index',
                'parameters' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'menu_id' => 1,
                'title' => 'Herramientas',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-tools',
                'color' => '#000000',
                'parent_id' => NULL,
                'order' => 10,
                'created_at' => '2021-06-02 17:55:32',
                'updated_at' => '2022-07-13 10:54:24',
                'route' => NULL,
                'parameters' => '',
            ),
            5 => 
            array (
                'id' => 6,
                'menu_id' => 1,
                'title' => 'Menu Builder',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-list',
                'color' => NULL,
                'parent_id' => 5,
                'order' => 1,
                'created_at' => '2021-06-02 17:55:33',
                'updated_at' => '2021-06-02 14:07:22',
                'route' => 'voyager.menus.index',
                'parameters' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'menu_id' => 1,
                'title' => 'Database',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-data',
                'color' => NULL,
                'parent_id' => 5,
                'order' => 2,
                'created_at' => '2021-06-02 17:55:33',
                'updated_at' => '2021-06-02 14:07:22',
                'route' => 'voyager.database.index',
                'parameters' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'menu_id' => 1,
                'title' => 'Compass',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-compass',
                'color' => NULL,
                'parent_id' => 5,
                'order' => 4,
                'created_at' => '2021-06-02 17:55:33',
                'updated_at' => '2021-06-02 14:07:22',
                'route' => 'voyager.compass.index',
                'parameters' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'menu_id' => 1,
                'title' => 'BREAD',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-bread',
                'color' => NULL,
                'parent_id' => 5,
                'order' => 5,
                'created_at' => '2021-06-02 17:55:33',
                'updated_at' => '2021-06-02 14:07:23',
                'route' => 'voyager.bread.index',
                'parameters' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'menu_id' => 1,
                'title' => 'Settings',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-settings',
                'color' => NULL,
                'parent_id' => 5,
                'order' => 6,
                'created_at' => '2021-06-02 17:55:33',
                'updated_at' => '2021-06-02 14:07:25',
                'route' => 'voyager.settings.index',
                'parameters' => NULL,
            ),
            10 => 
            array (
                'id' => 11,
                'menu_id' => 1,
                'title' => 'Seguridad',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-lock',
                'color' => '#000000',
                'parent_id' => NULL,
                'order' => 9,
                'created_at' => '2021-06-02 14:07:53',
                'updated_at' => '2022-07-13 10:54:24',
                'route' => NULL,
                'parameters' => '',
            ),
            11 => 
            array (
                'id' => 12,
                'menu_id' => 1,
                'title' => 'Limpiar cache',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-refresh',
                'color' => '#000000',
                'parent_id' => 5,
                'order' => 7,
                'created_at' => '2021-06-25 18:03:59',
                'updated_at' => '2021-06-25 18:04:03',
                'route' => 'clear.cache',
                'parameters' => NULL,
            ),
            12 => 
            array (
                'id' => 13,
                'menu_id' => 1,
                'title' => 'Parametros',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-params',
                'color' => '#000000',
                'parent_id' => NULL,
                'order' => 8,
                'created_at' => '2022-06-18 23:32:33',
                'updated_at' => '2022-07-13 10:54:24',
                'route' => NULL,
                'parameters' => '',
            ),
            13 => 
            array (
                'id' => 14,
                'menu_id' => 1,
                'title' => 'Personas',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-people',
                'color' => NULL,
                'parent_id' => 13,
                'order' => 1,
                'created_at' => '2022-06-18 23:50:57',
                'updated_at' => '2022-06-24 12:50:19',
                'route' => 'voyager.people.index',
                'parameters' => NULL,
            ),
            14 => 
            array (
                'id' => 17,
                'menu_id' => 1,
                'title' => 'Clientes',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'fa-solid fa-people-group',
                'color' => '#000000',
                'parent_id' => NULL,
                'order' => 4,
                'created_at' => '2022-06-24 15:41:50',
                'updated_at' => '2022-06-29 11:08:46',
                'route' => 'client.index',
                'parameters' => NULL,
            ),
            15 => 
            array (
                'id' => 18,
                'menu_id' => 1,
                'title' => 'Instructores',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'fa-solid fa-person-chalkboard',
                'color' => '#000000',
                'parent_id' => NULL,
                'order' => 5,
                'created_at' => '2022-06-24 15:45:25',
                'updated_at' => '2022-06-29 11:08:42',
                'route' => 'instructor.index',
                'parameters' => NULL,
            ),
            16 => 
            array (
                'id' => 19,
                'menu_id' => 1,
                'title' => 'Tipos de Planes',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'fa-solid fa-clock',
                'color' => NULL,
                'parent_id' => 13,
                'order' => 3,
                'created_at' => '2022-06-27 10:35:19',
                'updated_at' => '2022-06-27 10:56:06',
                'route' => 'voyager.plans.index',
                'parameters' => NULL,
            ),
            17 => 
            array (
                'id' => 20,
                'menu_id' => 1,
                'title' => 'Días',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'fa-solid fa-calendar-day',
                'color' => '#000000',
                'parent_id' => 13,
                'order' => 4,
                'created_at' => '2022-06-27 11:19:21',
                'updated_at' => '2022-06-27 11:21:19',
                'route' => 'voyager.days.index',
                'parameters' => 'null',
            ),
            18 => 
            array (
                'id' => 23,
                'menu_id' => 1,
                'title' => 'Bóveda',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-treasure',
                'color' => '#000000',
                'parent_id' => NULL,
                'order' => 2,
                'created_at' => '2022-06-29 11:07:30',
                'updated_at' => '2022-06-29 11:07:56',
                'route' => 'vaults.index',
                'parameters' => NULL,
            ),
            19 => 
            array (
                'id' => 24,
                'menu_id' => 1,
                'title' => 'Cajeros',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'fa-regular fa-money-bill-1',
                'color' => '#000000',
                'parent_id' => NULL,
                'order' => 3,
                'created_at' => '2022-06-29 11:08:29',
                'updated_at' => '2022-06-29 11:08:46',
                'route' => 'cashiers.index',
                'parameters' => NULL,
            ),
            20 => 
            array (
                'id' => 27,
                'menu_id' => 1,
                'title' => 'Categoria Productos',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'fa-solid fa-sitemap',
                'color' => '#000000',
                'parent_id' => 33,
                'order' => 2,
                'created_at' => '2022-07-06 12:46:12',
                'updated_at' => '2022-07-11 11:40:06',
                'route' => 'categories.index',
                'parameters' => NULL,
            ),
            21 => 
            array (
                'id' => 28,
                'menu_id' => 1,
                'title' => 'Articulos',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'fa-solid fa-prescription-bottle',
                'color' => '#000000',
                'parent_id' => 33,
                'order' => 3,
                'created_at' => '2022-07-06 12:50:26',
                'updated_at' => '2022-07-11 11:40:06',
                'route' => 'articles.index',
                'parameters' => 'null',
            ),
            22 => 
            array (
                'id' => 29,
                'menu_id' => 1,
                'title' => 'Almacen',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'fa-solid fa-store',
                'color' => '#000000',
                'parent_id' => 33,
                'order' => 4,
                'created_at' => '2022-07-09 17:51:36',
                'updated_at' => '2022-07-11 22:55:54',
                'route' => 'wherehouses.index',
                'parameters' => 'null',
            ),
            23 => 
            array (
                'id' => 30,
                'menu_id' => 1,
                'title' => 'Proveedores',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-truck',
                'color' => '#000000',
                'parent_id' => 33,
                'order' => 1,
                'created_at' => '2022-07-09 18:02:18',
                'updated_at' => '2022-07-11 11:40:06',
                'route' => 'providers.index',
                'parameters' => 'null',
            ),
            24 => 
            array (
                'id' => 32,
                'menu_id' => 1,
                'title' => 'Gimnasios',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'fa-solid fa-briefcase',
                'color' => NULL,
                'parent_id' => NULL,
                'order' => 11,
                'created_at' => '2022-07-11 08:57:54',
                'updated_at' => '2022-07-13 10:54:24',
                'route' => 'voyager.busines.index',
                'parameters' => NULL,
            ),
            25 => 
            array (
                'id' => 33,
                'menu_id' => 1,
                'title' => 'Parametros',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-basket',
                'color' => '#000000',
                'parent_id' => NULL,
                'order' => 7,
                'created_at' => '2022-07-11 11:39:50',
                'updated_at' => '2022-07-13 10:54:24',
                'route' => NULL,
                'parameters' => '',
            ),
            26 => 
            array (
                'id' => 35,
                'menu_id' => 1,
                'title' => 'Item Disponible',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'fa-solid fa-tags',
                'color' => '#000000',
                'parent_id' => NULL,
                'order' => 6,
                'created_at' => '2022-07-13 11:22:08',
                'updated_at' => '2022-07-13 11:23:14',
                'route' => 'wherehouses-items.itemDisponible',
                'parameters' => NULL,
            ),
            27 => 
            array (
                'id' => 36,
                'menu_id' => 1,
                'title' => 'Servicios',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'fa-solid fa-dumbbell',
                'color' => NULL,
                'parent_id' => 13,
                'order' => 2,
                'created_at' => '2022-07-20 16:08:15',
                'updated_at' => '2022-07-20 16:13:27',
                'route' => 'voyager.services.index',
                'parameters' => NULL,
            ),
        ));
        
        
    }
}