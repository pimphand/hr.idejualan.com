<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterEmployees extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->string('beliefs')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('address')->nullable();
            $table->string('work_status')->nullable();
            $table->string('gender')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn('beliefs');
            $table->dropColumn('marital_status');
            $table->dropColumn('address');
            $table->dropColumn('work_status');
            $table->dropColumn('gender');
        });
    }
}
