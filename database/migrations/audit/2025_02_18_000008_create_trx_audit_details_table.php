<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrxAuditDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Detail Penilaian per Butir Indikator
        Schema::create('trx_audit_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('audit_id')->constrained('trx_audits')->onDelete('cascade');
            $table->foreignId('metric_id')->constrained('mst_metrics');
            
            // Nilai dan Penilaian
            $table->text('self_assessment_value')->nullable()->comment('Nilai evaluasi diri');
            $table->text('audit_value')->nullable()->comment('Nilai hasil audit');
            $table->decimal('score', 8, 2)->nullable()->comment('Skor yang diberikan');
            
            // Status temuan
            $table->enum('finding_status', ['compliant', 'observation', 'minor_nc', 'major_nc'])
                  ->nullable()
                  ->comment('Sesuai, Observasi(OB), Ketidaksesuaian Minor(KTS), Ketidaksesuaian Mayor(KTS)');
            
            // Komentar
            $table->text('self_assessment_note')->nullable();
            $table->text('auditor_note')->nullable()->comment('Catatan auditor');
            $table->text('auditee_response')->nullable()->comment('Tanggapan auditee');
            
            // Metadata
            $table->foreignId('assessed_by')->nullable()->constrained('users');
            $table->timestamp('assessed_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users');
            $table->timestamp('verified_at')->nullable();
            
            $table->timestamps();
            
            $table->unique(['audit_id', 'metric_id']);
            $table->index('finding_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trx_audit_details');
    }
}
