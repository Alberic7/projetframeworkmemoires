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
    Schema::create('memoires', function (Blueprint $table) {
        $table->id();
        $table->string('titre');
        $table->text('resume');
        $table->string('mots_cles');
        $table->string('encadreur');
        $table->string('fichier_pdf');
        $table->string('statut')->default('soumis'); // soumis, en_verification, valide, rejete
        $table->text('motif_rejet')->nullable();
        $table->boolean('telechargement_autorise')->default(true);
        $table->boolean('acces_restreint')->default(false);
        $table->date('date_embargo')->nullable();
        $table->decimal('score_similarite', 5, 2)->nullable();
        
        $table->foreignId('etudiant_id')->constrained('users')->cascadeOnDelete();
        $table->foreignId('filiere_id')->constrained('filieres')->restrictOnDelete();
        $table->foreignId('annee_academique_id')->constrained('annees_academiques')->restrictOnDelete();
        $table->timestamps();

        $table->fullText(['titre', 'resume', 'mots_cles']);
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('memoires');
    }
};
