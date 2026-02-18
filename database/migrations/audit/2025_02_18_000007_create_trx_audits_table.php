<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrxAuditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Transaksi Audit Utama - menghubungkan Unit, Instrumen, dan Periode
        Schema::create('trx_audits', function (Blueprint $table) {
            $table->id();
            $table->string('audit_code', 50)->unique()->comment('Kode audit: AMI-2025-FTIK');
            $table->foreignId('period_id')->constrained('mst_periods');
            $table->foreignId('instrument_id')->constrained('mst_instruments');
            $table->foreignId('unit_id')->constrained('ref_units')
                  ->comment('Unit yang diaudit (Prodi/Fakultas)');
            
            // Status siklus PPEPP
            $table->enum('phase', ['planning', 'implementation', 'evaluation', 'control', 'improvement', 'completed'])
                  ->default('planning')->comment('Fase PPEPP saat ini');
            $table->enum('status', ['draft', 'ongoing', 'completed', 'verified', 'approved'])
                  ->default('draft');
            
            // Skor dan Predikat
            $table->decimal('self_assessment_score', 8, 2)->nullable()->comment('Skor evaluasi diri');
            $table->decimal('audit_score', 8, 2)->nullable()->comment('Skor hasil audit');
            $table->decimal('final_score', 8, 2)->nullable()->comment('Skor akhir');
            $table->enum('predicate', ['not_accredited', 'accredited', 'good', 'very_good', 'excellent'])
                  ->nullable()->comment('Predikat akreditasi');
            
            // Status kunci tiap fase (untuk workflow enforcement)
            $table->timestamp('planning_locked_at')->nullable();
            $table->foreignId('planning_locked_by')->nullable()->constrained('users');
            $table->timestamp('implementation_locked_at')->nullable();
            $table->foreignId('implementation_locked_by')->nullable()->constrained('users');
            $table->timestamp('evaluation_locked_at')->nullable();
            $table->foreignId('evaluation_locked_by')->nullable()->constrained('users');
            
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['period_id', 'unit_id']);
            $table->index('phase');
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
        Schema::dropIfExists('trx_audits');
    }
}
