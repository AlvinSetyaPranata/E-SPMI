<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrxAuditorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Penugasan Auditor (Matrix Auditor)
        Schema::create('trx_auditors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('audit_id')->constrained('trx_audits')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')
                  ->comment('Auditor yang ditugaskan');
            $table->enum('role', ['lead', 'member'])->default('member')
                  ->comment('Ketua atau anggota tim auditor');
            $table->text('assigned_scope')->nullable()->comment('Ruang lingkup penugasan (standar yang diaudit)');
            
            // Jadwal visitasi
            $table->date('visit_date_start')->nullable();
            $table->date('visit_date_end')->nullable();
            $table->time('visit_time_start')->nullable();
            $table->time('visit_time_end')->nullable();
            $table->string('visit_location')->nullable();
            
            $table->foreignId('assigned_by')->constrained('users');
            $table->timestamp('assigned_at');
            $table->enum('status', ['pending', 'accepted', 'rejected', 'completed'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
            
            $table->unique(['audit_id', 'user_id']);
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
        Schema::dropIfExists('trx_auditors');
    }
}
