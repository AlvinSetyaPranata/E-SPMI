<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMstInstrumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Master Instrumen Audit (contoh: Instrumen AMI 2025, Instrumen IAPS 4.0)
        Schema::create('mst_instruments', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique()->comment('Kode instrumen: AMI-2025, IAPS-4.0');
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('type', ['institutional', 'study_program', 'internal'])
                  ->comment('Jenis: akreditasi institusi, prodi, atau audit internal');
            $table->string('reference_regulation')->nullable()->comment('Referensi regulasi: SN-Dikti, BAN-PT, dll');
            $table->enum('status', ['draft', 'active', 'archived'])->default('draft');
            $table->date('effective_date')->nullable();
            $table->date('expired_date')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('type');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mst_instruments');
    }
}
