<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrxFindingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Temuan Audit (KTS/OB) dan PTK (Permintaan Tindakan Koreksi)
        Schema::create('trx_findings', function (Blueprint $table) {
            $table->id();
            $table->string('ptk_code', 50)->unique()->comment('Kode PTK: PTK-2025-001');
            $table->foreignId('audit_id')->constrained('trx_audits');
            $table->foreignId('audit_detail_id')->constrained('trx_audit_details');
            
            // Klasifikasi temuan
            $table->enum('type', ['observation', 'minor_nc', 'major_nc'])
                  ->comment('OB: Observasi, Minor NC: Ketidaksesuaian Minor, Major NC: Ketidaksesuaian Mayor');
            $table->enum('category', ['documentation', 'implementation', 'system', 'resource'])
                  ->nullable()->comment('Kategori: Dokumentasi, Pelaksanaan, Sistem, Sumber Daya');
            
            // Deskripsi temuan
            $table->text('finding_description')->comment('Deskripsi temuan');
            $table->text('reference_requirement')->comment('Ketentuan/acuan yang dilanggar');
            $table->text('objective_evidence')->comment('Bukti objektif');
            $table->foreignId('identified_by')->constrained('users')
                  ->comment('Auditor yang menemukan');
            $table->timestamp('identified_at');
            
            // Analisis Akar Masalah (Root Cause Analysis)
            $table->text('root_cause_analysis')->nullable()->comment('Analisis akar masalah oleh auditee');
            $table->text('corrective_action_plan')->nullable()->comment('Rencana tindakan korektif');
            $table->text('preventive_action_plan')->nullable()->comment('Rencana tindakan preventif');
            $table->foreignId('submitted_by')->nullable()->constrained('users');
            $table->timestamp('submitted_at')->nullable();
            
            // Due date dan status
            $table->date('due_date')->comment('Batas waktu penyelesaian');
            $table->enum('status', ['open', 'in_progress', 'waiting_verification', 'closed', 'overdue', 'escalated'])
                  ->default('open');
            
            // Verifikasi penyelesaian
            $table->text('verification_note')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users');
            $table->timestamp('verified_at')->nullable();
            
            // Lampiran bukti perbaikan
            $table->json('attachment_files')->nullable()->comment('Path file bukti perbaikan');
            
            // Escalation (jika melewati due date)
            $table->boolean('is_escalated')->default(false);
            $table->timestamp('escalated_at')->nullable();
            $table->foreignId('escalated_to')->nullable()->constrained('users')
                  ->comment('Dieskalasi ke pimpinan');
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['audit_id', 'status']);
            $table->index('due_date');
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
        Schema::dropIfExists('trx_findings');
    }
}
