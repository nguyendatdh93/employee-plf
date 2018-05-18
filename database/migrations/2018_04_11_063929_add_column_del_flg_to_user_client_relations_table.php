<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnDelFlgToUserClientRelationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('migration')->table('user_client_relations', function (Blueprint $table) {
            $table->string('del_flg')->default(0)->after('client_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('migration')->table('user_client_relations', function ($table) {
            $table->dropColumn('del_flg');
        });
    }
}
