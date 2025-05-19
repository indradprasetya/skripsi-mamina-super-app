<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('m_makanan', function (Blueprint $table) {
            $table->id('id_makanan');
            $table->foreignId('id_kategori')->constrained('m_kategori_makanan', 'id_kategori')->onDelete('cascade');
            $table->string('nama_makanan');
            $table->decimal('kalori', 8, 2);
            $table->decimal('protein', 8, 2);
            $table->decimal('karbohidrat', 8, 2);
            $table->decimal('lemak', 8, 2);
            $table->decimal('serat', 8, 2)->nullable();
            $table->decimal('vitamin_a', 8, 2)->nullable();
            $table->decimal('vitamin_c', 8, 2)->nullable();
            $table->decimal('kalsium', 8, 2)->nullable();
            $table->decimal('zat_besi', 8, 2)->nullable();
            $table->string('satuan');
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('m_makanan');
    }
};
