<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('videos', function (Blueprint $table) {           
            $table->string('file_identity')->after('file_name')->nullable();
        });

        Schema::table('documents', function (Blueprint $table) {           
            $table->string('file_identity')->after('file_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('videos', function (Blueprint $table) {           
            $table->dropColumn('file_identity');            
        });

        Schema::table('documents', function (Blueprint $table) {           
            $table->dropColumn('file_identity');            
        });
    }
}
