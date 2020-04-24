<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SoftDeletesFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('currencies', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('currency_conversations', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('default_options', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('categories', function (Blueprint $table) {
            $table->softDeletes();
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
            $table->dropColumn('deleted_at');
        });
        Schema::table('currencies', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
        });
        Schema::table('currency_conversations', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
        });
        Schema::table('default_options', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
        });
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
        });
    }
}
