<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnActiveAndResetPasswordFlgTableUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('migration')->table('users', function (Blueprint $table) {
            $table->string('reset_password_flg')->default(0)->after('password');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('migration')->table('users', function ($table) {
            $table->dropColumn('reset_password_flg');
        });
    }
}
