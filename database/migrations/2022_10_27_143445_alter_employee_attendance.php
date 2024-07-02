<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterEmployeeAttendance extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employee_attendances', function (Blueprint $table) {
            $table->double('latitude')->after('status')->nullable();
            $table->double('longitude')->after('latitude')->nullable();
            $table->string('selfie_image')->after('longitude')->nullable();
            $table->string('address')->after('selfie_image')->nullable();
            $table->string('ip_address')->after('address')->nullable();
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
            $table->dropColumn('latitude');
            $table->dropColumn('longitude');
            $table->dropColumn('selfie_image');
            $table->dropColumn('address');
            $table->dropColumn('ip_address');

        });
    }
}
