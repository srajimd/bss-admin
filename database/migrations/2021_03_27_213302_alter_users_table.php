<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {           
            $table->boolean('status')->after('remember_token')->default(true);            
        });

        Schema::table('admins', function (Blueprint $table) {           
            $table->boolean('status')->after('remember_token')->default(true);            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {           
            $table->dropColumn('status');            
        });

        Schema::table('admins', function (Blueprint $table) {           
            $table->dropColumn('status');            
        });
    }
}
