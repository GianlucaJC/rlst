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
        Schema::table('users', function (Blueprint $table) {
            $table->text("utentefillea",60)->after("name")->nullable();
			$table->integer("id_prov_associate")->after("utentefillea");
			$table->text("tb_default",10)->after("id_prov_associate")->nullable();
			$table->integer("admin_pro")->after("tb_default");
			$table->integer("admin_reg")->after("admin_pro");
			$table->integer("admin_naz")->after("admin_reg");
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
