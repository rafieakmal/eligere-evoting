<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('votes', function (Blueprint $table) {
            $table->id();
            $table->string('owner_id');
            $table->string('title');
            $table->string('slug')->unique();
            $table->date('date');
            $table->time('time');
            $table->string('description');
            $table->boolean('is_verified')->default(0);
            $table->boolean('is_one_ip')->default(0);
            $table->boolean('is_password')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('votes');
    }
};
