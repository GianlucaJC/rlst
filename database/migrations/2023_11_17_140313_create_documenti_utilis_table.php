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
        Schema::create('documenti_utili', function (Blueprint $table) {
            $table->id();
			$table->integer('dele');
			$table->integer('id_funzionario')->index();
			$table->integer('id_prov')->index();
			$table->text('motivo_visita');
			$table->date('data_visita');
			$table->text('indirizzo')->nullable();
			$table->string('cap',40)->nullable();
			$table->text('comune')->nullable();
			$table->string('provincia',10)->nullable();
			$table->text('note')->nullable();
			$table->string('filename',50);
			$table->string('file_user',100);
			$table->string('url_completo',100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documenti_utili');
    }
};

