<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMstPeriodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Master Periode Audit (Tahun Akademik)
        Schema::create('mst_periods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('academic_year', 20)->comment('Tahun akademik: 2024/2025');
            $table->enum('semester', ['odd', 'even', 'annual'])->comment('Ganjil, Genap, atau Tahunan');
            $table->date('start_date');
            $table->date('end_date');
            
            // Tahapan PPEPP dalam periode ini
            $table->timestamp('planning_start')->nullable()->comment('Mulai Penetapan');
            $table->timestamp('planning_end')->nullable()->comment('Akhir Penetapan');
            $table->timestamp('implementation_start')->nullable()->comment('Mulai Pelaksanaan');
            $table->timestamp('implementation_end')->nullable()->comment('Akhir Pelaksanaan');
            $table->timestamp('evaluation_start')->nullable()->comment('Mulai Evaluasi (Audit)');
            $table->timestamp('evaluation_end')->nullable()->comment('Akhir Evaluasi');
            $table->timestamp('control_start')->nullable()->comment('Mulai Pengendalian (PTK)');
            $table->timestamp('control_end')->nullable()->comment('Akhir Pengendalian');
            $table->timestamp('improvement_start')->nullable()->comment('Mulai Peningkatan (RTM)');
            $table->timestamp('improvement_end')->nullable()->comment('Akhir Peningkatan');
            
            $table->enum('status', ['draft', 'active', 'completed', 'archived'])->default('draft');
            $table->boolean('is_locked')->default(false)->comment('Lock periode yang sedang berjalan');
            $table->foreignId('instrument_id')->constrained('mst_instruments')
                  ->comment('Instrumen yang digunakan pada periode ini');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['academic_year', 'semester']);
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
        Schema::dropIfExists('mst_periods');
    }
}
