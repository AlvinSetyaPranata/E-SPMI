<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrxRtmTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Rapat Tinjauan Manajemen (RTM)
        Schema::create('trx_rtm', function (Blueprint $table) {
            $table->id();
            $table->foreignId('period_id')->constrained('mst_periods');
            $table->string('rtm_code', 50)->unique();
            $table->string('title');
            $table->text('description')->nullable();
            
            $table->enum('level', ['university', 'faculty', 'department'])
                  ->comment('Level RTM: Universitas, Fakultas, Jurusan');
            $table->foreignId('unit_id')->nullable()->constrained('ref_units')
                  ->comment('Unit yang mengadakan RTM (null jika level universitas)');
            
            // Jadwal dan lokasi
            $table->dateTime('meeting_date');
            $table->string('location')->nullable();
            $table->string('meeting_link')->nullable()->comment('Link online meeting jika hybrid/online');
            
            // Agenda
            $table->json('agenda')->nullable()->comment('Agenda rapat dalam format JSON');
            $table->text('meeting_notes')->nullable()->comment('Notulen rapat');
            
            // Status
            $table->enum('status', ['draft', 'scheduled', 'ongoing', 'completed', 'cancelled'])
                  ->default('draft');
            
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['period_id', 'level']);
            $table->index('meeting_date');
        });

        // Daftar Hadir RTM
        Schema::create('trx_rtm_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rtm_id')->constrained('trx_rtm')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users');
            $table->enum('role', ['chairman', 'secretary', 'member', 'guest'])
                  ->comment('Peran dalam RTM');
            $table->enum('attendance_status', ['present', 'absent', 'excused', 'late'])
                  ->default('present');
            $table->text('notes')->nullable();
            $table->timestamp('check_in_at')->nullable();
            $table->string('signature_file')->nullable()->comment('File tanda tangan digital');
            $table->timestamps();
            
            $table->unique(['rtm_id', 'user_id']);
        });

        // Action Plan dari RTM
        Schema::create('trx_rtm_action_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rtm_id')->constrained('trx_rtm')->onDelete('cascade');
            $table->text('action_description');
            $table->foreignId('responsible_id')->constrained('users')
                  ->comment('Penanggung jawab');
            $table->foreignId('unit_id')->nullable()->constrained('ref_units')
                  ->comment('Unit terkait');
            $table->date('due_date');
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled'])
                  ->default('pending');
            $table->text('result')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users');
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
            
            $table->index(['rtm_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trx_rtm_action_plans');
        Schema::dropIfExists('trx_rtm_attendances');
        Schema::dropIfExists('trx_rtm');
    }
}
