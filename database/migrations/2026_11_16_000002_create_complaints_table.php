<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();

            // BR-01 & BR-08: kategori wajib ada & tidak boleh dihapus selama masih dipakai
            $table->foreignId('category_id')
                ->constrained('categories')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->string('reporter_name', 100);
            $table->string('location', 150);          // BR-07: teks manual, bukan GPS
            $table->date('reported_at');
            $table->text('description');
            $table->string('status', 20)->default('baru')->index(); // BR-03 & BR-04
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
