<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Tabel struktur organisasi universitas (Fakultas, Prodi, Biro, Lembaga)
        Schema::create('ref_units', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique()->comment('Kode unit: FTIK, TI, SI, dll');
            $table->string('name');
            $table->enum('type', ['university', 'faculty', 'department', 'program_study', 'bureau', 'lpm', 'other'])
                  ->comment('Tipe unit: universitas, fakultas, jurusan, prodi, biro, lpm, dll');
            $table->foreignId('parent_id')->nullable()->constrained('ref_units')->onDelete('set null')
                  ->comment('Parent unit untuk hierarki (contoh: Prodi di bawah Fakultas)');
            $table->text('description')->nullable();
            $table->string('head_name')->nullable()->comment('Nama pimpinan unit');
            $table->string('head_nip', 50)->nullable()->comment('NIP pimpinan unit');
            $table->string('contact_email')->nullable();
            $table->string('contact_phone', 30)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('type');
            $table->index('parent_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ref_units');
    }
}
