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
        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->decimal("cc")->nullable();
            $table->decimal("tp")->nullable();
            $table->decimal("sn")->nullable();
            $table->string("annee_scolaire");

            $table->unsignedBigInteger("etudiant_id");
            $table->unsignedBigInteger("ue_id");

            $table->timestamps();

            $table->foreign("etudiant_id")->references("id")->on("etudiants");
            $table->foreign("ue_id")->references("id")->on("u_e_s");

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notes');
    }
};
