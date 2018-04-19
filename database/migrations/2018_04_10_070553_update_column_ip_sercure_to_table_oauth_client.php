<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateColumnIpSercureToTableOauthClient extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('oauth_clients', function ($table) {
            $table->dropColumn('ip_secure');
        });

        Schema::table('oauth_clients', function (Blueprint $table) {
            $table->string('ip_secure')->nullable()->after('redirect');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
