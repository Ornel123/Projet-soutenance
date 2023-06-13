<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use League\CommonMark\Extension\Table\Table;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string("code")->unique();
            $table->string("intitule");
            $table->timestamps();
            $table->unsignedBigInteger("filiere_id");
            $table->unsignedBigInteger("niveau_id");

            $table->foreign("filiere_id")->references("id")->on("filieres");
            $table->foreign("niveau_id")->references("id")->on("niveaux");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('classes');
    }
};
