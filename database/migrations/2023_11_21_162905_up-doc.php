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
		

        Schema::table('documenti_utili', function (Blueprint $table) {
            $table->text("frazione")->after("comune")->nullable();
			$table->string('filename', 50)->nullable()->change();
			$table->string('file_user', 50)->nullable()->change();
		});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
