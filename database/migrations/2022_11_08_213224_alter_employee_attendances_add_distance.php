<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterEmployeeAttendancesAddDistance extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employee_attendances', function (Blueprint $table) {
            $table->string('distance')->nullable();
            $table->string('permission_reason')->nullable();
            $table->string('status_by_hr')->default('approved');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employee_attendances', function (Blueprint $table) {
            $table->dropColumn('distance');
            $table->dropColumn('permission_reason');
            $table->dropColumn('status_by_hr');
        });
    }
}
