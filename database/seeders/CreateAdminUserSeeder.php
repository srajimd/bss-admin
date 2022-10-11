<?php

  

namespace Database\Seeders;

  

use Illuminate\Database\Seeder;

use App\Models\Admin;

use Spatie\Permission\Models\Role;

use Spatie\Permission\Models\Permission;

  

class CreateAdminUserSeeder extends Seeder

{

    /**

     * Run the database seeds.

     *

     * @return void

     */

    public function run()

    {

        $admin = Admin::create([

            'name' => 'Krish S', 

            'email' => 'admin@gmail.com',

            'password' => bcrypt('12345678')

        ]);

    

        $role = Role::create(['guard_name' => 'admin', 'name' => 'super-master-admin']);

     

        $permissions = Permission::pluck('id','id')->all();

   

        $role->syncPermissions($permissions);

     

        $admin->assignRole([$role->id]);

    }

}