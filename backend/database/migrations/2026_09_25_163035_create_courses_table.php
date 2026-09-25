<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('livreur_id')->nullable()->constrained('livreurs');
            $table->string('adresse_depart', 255);
            $table->string('adresse_arrivee', 255);
            $table->decimal('montant', 10, 2);
            $table->enum('statut', ['en_attente', 'prise_en_charge', 'livree', 'annulee'])->default('en_attente');
            $table->timestamp('prise_en_charge_at')->nullable();
            $table->timestamp('livree_at')->nullable();
            $table->timestamp('annulee_at')->nullable();
            $table->string('motif_annulation', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
