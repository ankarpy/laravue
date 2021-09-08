<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignAcceptedAnswerIdToQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('questions', function (Blueprint $table) {
            // #MYSQLREF Use this
            Schema::table('questions', function (Blueprint $table) {
                $table->foreign('accepted_answer_id')
                    ->references('id')
                    ->on('answers')
                    ->onDelete('SET NULL');
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropForeign(['accepted_answer_id']);
        });
    }
}
