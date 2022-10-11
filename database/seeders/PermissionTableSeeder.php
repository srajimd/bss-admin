<?php

  

namespace Database\Seeders;

  

use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Permission;

  

class PermissionTableSeeder extends Seeder

{

    /**

     * Run the database seeds.

     *

     * @return void

     */

    public function run()

    {

        $permissions = [

           'role-list',

           'role-create',

           'role-edit',

           'role-delete',

           'admin-list',

           'admin-create',

           'admin-edit',

           'admin-delete',

           'user-list',

           'user-create',

           'user-edit',

           'user-delete',

           'topic-list',

           'topic-create',

           'topic-edit',

           'topic-delete',

           'course-list',

           'course-create',

           'course-edit',

           'course-delete'

        ];

     

        foreach ($permissions as $permission) {

             Permission::create(['name' => $permission, 'guard_name' => 'admin']);

        }

    }

}