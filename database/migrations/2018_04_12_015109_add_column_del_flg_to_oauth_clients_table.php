<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnDelFlgToOauthClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('migration')->table('oauth_clients', function (Blueprint $table) {
            $table->string('del_flg')->default(0)->after('revoked');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('migration')->table('oauth_clients', function ($table) {
            $table->dropColumn('del_flg');
        });
    }
}
