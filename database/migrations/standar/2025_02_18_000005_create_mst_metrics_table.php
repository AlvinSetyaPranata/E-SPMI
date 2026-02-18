<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMstMetricsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Master Metrics/Standar dengan struktur hirarkis (N-Level Hierarchy)
        // Standar Utama -> Sub-Standar -> Indikator -> Sub-Indikator
        Schema::create('mst_metrics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instrument_id')->constrained('mst_instruments')->onDelete('cascade');
            $table->string('code', 50)->comment('Kode standar: PS-1, PS-2.1, dll');
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('type', ['standard', 'sub_standard', 'indicator', 'sub_indicator'])
                  ->comment('Tipe node dalam hierarki');
            $table->foreignId('parent_id')->nullable()->constrained('mst_metrics')->onDelete('cascade')
                  ->comment('Parent untuk hierarki (Adjacency List pattern)');
            $table->integer('order_no')->default(0)->comment('Urutan tampilan');
            
            // Atribut untuk indikator terminal (leaf node)
            $table->enum('data_type', ['numeric', 'percentage', 'boolean', 'text', 'file', 'choice'])
                  ->nullable()->comment('Tipe data input untuk indikator');
            $table->json('data_options')->nullable()->comment('Opsi untuk tipe choice');
            $table->decimal('weight', 5, 2)->nullable()->comment('Bobot penilaian');
            $table->decimal('target_value', 15, 4)->nullable()->comment('Target nilai');
            $table->json('target_per_level')->nullable()->comment('Target berbeda per jenjang: {"D3": 70, "S1": 80, "S2": 85}');
            
            // Referensi dan dokumen pendukung
            $table->text('reference_document')->nullable()->comment('Dokumen acuan yang diperlukan');
            $table->text('assessment_guide')->nullable()->comment('Panduan penilaian');
            $table->boolean('is_required')->default(true)->comment('Wajib diisi atau opsional');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            
            $table->unique(['instrument_id', 'code']);
            $table->index(['instrument_id', 'parent_id']);
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mst_metrics');
    }
}
