<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('titre');
            $table->string('slug')->unique();
            $table->text('description');
            $table->decimal('prix_base', 10, 2);
            $table->integer('delai_livraison');
            $table->integer('revisions')->default(0);
            $table->enum('statut', ['actif', 'en_pause', 'en_attente_approbation', 'rejete']);
            $table->integer('ventes')->default(0);
            $table->decimal('note_moyenne', 3, 2)->default(0.0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
