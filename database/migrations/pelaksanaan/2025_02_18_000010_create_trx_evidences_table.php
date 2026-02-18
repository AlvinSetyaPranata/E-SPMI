<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrxEvidencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Repository Bukti Terpusat
        Schema::create('trx_evidences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('audit_id')->constrained('trx_audits')->onDelete('cascade');
            $table->foreignId('audit_detail_id')->nullable()->constrained('trx_audit_details')->onDelete('set null')
                  ->comment('Detail audit yang terkait (jika sudah ada)');
            
            // Dokumen info
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('file_name');
            $table->string('file_path', 500);
            $table->string('file_type', 50)->comment('MIME type');
            $table->bigInteger('file_size')->comment('Ukuran dalam bytes');
            $table->string('file_extension', 20);
            
            // Kategorisasi dan tagging
            $table->foreignId('metric_id')->nullable()->constrained('mst_metrics')
                  ->comment('Standar/indikator yang direferensi');
            $table->json('related_metrics')->nullable()->comment('Standar lain yang terkait (Many-to-Many)');
            $table->enum('category', ['curriculum', 'research', 'community_service', 'student', 'facility', 'management', 'other'])
                  ->nullable();
            
            // Status dan versioning
            $table->integer('version')->default(1);
            $table->foreignId('previous_version_id')->nullable()->constrained('trx_evidences')
                  ->comment('Referensi ke versi sebelumnya');
            $table->enum('status', ['draft', 'submitted', 'verified', 'rejected', 'revised'])
                  ->default('draft');
            
            // Sumber data (manual atau integrasi)
            $table->enum('source', ['manual_upload', 'siakad', 'hris', 'finance', 'other'])
                  ->default('manual_upload');
            $table->json('source_metadata')->nullable()->comment('Metadata dari sistem sumber');
            
            // Sanggah data (jika data dari integrasi tidak sesuai)
            $table->boolean('is_disputed')->default(false);
            $table->text('dispute_reason')->nullable();
            $table->foreignId('dispute_verified_by')->nullable()->constrained('users');
            
            $table->foreignId('uploaded_by')->constrained('users');
            $table->timestamp('uploaded_at');
            $table->foreignId('verified_by')->nullable()->constrained('users');
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['audit_id', 'status']);
            $table->index('category');
            $table->index('uploaded_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trx_evidences');
    }
}
