<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
         Schema::create('campaign_status', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('campaigns', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('name', 100);
            $table->string('description', 255); 
            $table->integer('status_id')->unsigned()->default(1);
            $table->integer('questions');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('status_id')->references('id')->on('campaign_status');
        });
        Schema::create('campaign_questions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('campaign_id')->unsigned();
            $table->string('label', 100);
            $table->string('options', 255);
            $table->string('type', 255);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('campaign_id')->references('id')->on('campaigns');
        });
        Schema::create('campaign_questions_answers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('question_id')->unsigned();
            $table->string('value', 255);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('question_id')->references('id')->on('campaign_questions');
        });
    }

    /**
     * Reverse the migrations. php artisan make:seeder UsersTableSeeder php artisan make:migration create_campaigns_table

     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schema::dropIfExists('campaigns');
        Schema::dropIfExists('campaign_status');
        Schema::dropIfExists('campaign_questions');
        Schema::dropIfExists('campaign_questions_answers');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
