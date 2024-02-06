<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsReplyColumnToMailBoxTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mail_box', function (Blueprint $table) {
            $table->unsignedBigInteger('reply_to')->nullable()->default(null);;
            $table->foreign('reply_to')
                ->references('id')
                ->on('mail_box');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mail_box', function (Blueprint $table) {
            //
        });
    }
}
