<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterFieldsPricePerHour extends Migration
{
    public function up(): void
    {
        // Perubahan tipe kolom membutuhkan doctrine/dbal
        Schema::table('fields', function (Blueprint $table) {
            $table->bigInteger('price_per_hour')->change();
        });
    }

    public function down(): void
    {
        Schema::table('fields', function (Blueprint $table) {
            $table->integer('price_per_hour')->change();
        });
    }
}