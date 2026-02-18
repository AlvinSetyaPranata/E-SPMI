<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMstIkuIndicatorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Master 8 IKU (Indikator Kinerja Utama) Perguruan Tinggi
        Schema::create('mst_iku_indicators', function (Blueprint $table) {
            $table->id();
            $table->integer('code')->unique()->comment('Kode IKU 1-8');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('formula')->nullable()->comment('Rumus perhitungan');
            $table->string('measurement_unit')->nullable()->comment('Satuan pengukuran: %, orang, dll');
            $table->decimal('target_national', 10, 2)->nullable()->comment('Target nasional');
            $table->json('target_per_year')->nullable()->comment('Target per tahun: {"2024": 80, "2025": 85}');
            $table->enum('data_source', ['manual', 'siakad', 'hris', 'finance', 'sister'])
                  ->comment('Sumber data IKU');
            $table->boolean('is_active')->default(true);
            $table->integer('order_no')->default(0);
            $table->timestamps();
        });

        // Data aktual IKU per periode
        Schema::create('trx_iku_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('iku_indicator_id')->constrained('mst_iku_indicators');
            $table->foreignId('period_id')->constrained('mst_periods');
            $table->foreignId('unit_id')->nullable()->constrained('ref_units')
                  ->comment('Null untuk level universitas');
            
            $table->decimal('target', 12, 4)->nullable();
            $table->decimal('actual', 12, 4)->nullable();
            $table->decimal('achievement', 8, 2)->nullable()->comment('Persentase pencapaian');
            $table->text('analysis')->nullable()->comment('Analisis pencapaian');
            
            $table->enum('status', ['draft', 'verified', 'approved'])->default('draft');
            $table->foreignId('input_by')->constrained('users');
            $table->foreignId('verified_by')->nullable()->constrained('users');
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
            
            $table->unique(['iku_indicator_id', 'period_id', 'unit_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trx_iku_data');
        Schema::dropIfExists('mst_iku_indicators');
    }
}
