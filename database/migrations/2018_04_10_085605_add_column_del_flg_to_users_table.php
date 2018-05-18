<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnDelFlgToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('migration')->table('users', function (Blueprint $table) {
            $table->integer('del_flg')->default(0)->after('reset_password_flg');
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
            $table->dropColumn('del_flg');
        });
    }
}
