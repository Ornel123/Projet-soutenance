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
        Schema::create('u_e_s', function (Blueprint $table) {
            $table->id();
            $table->string("code");
            $table->string("intitule");
            $table->string("semestre");
            $table->integer("credit");
            $table->boolean("ue_optionelle");
            $table->boolean("tp_optionel");
            $table->unsignedBigInteger("classe_id");

            $table->timestamps();

            $table->foreign("classe_id")->references("id")->on("classes");

            $table->integer("prof_id")->default(0);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('u_e_s');
    }
};
