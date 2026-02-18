<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivityLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Audit Trail - Immutable logs untuk semua aksi kritis (SPBE Compliance)
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null')
                  ->comment('Who - Pengguna yang melakukan aksi');
            $table->string('action', 50)->comment('What - Aksi: CREATE, READ, UPDATE, DELETE, LOGIN, EXPORT');
            $table->string('entity_type', 100)->comment('What - Model/Entity yang diakses: Audit, Evidence, dll');
            $table->unsignedBigInteger('entity_id')->nullable()->comment('What - ID record yang diakses');
            $table->json('old_values')->nullable()->comment('Data sebelum perubahan');
            $table->json('new_values')->nullable()->comment('Data setelah perubahan');
            $table->string('ip_address', 45)->nullable()->comment('Where - IP address pengguna');
            $table->text('user_agent')->nullable()->comment('Where - Browser/User agent');
            $table->string('url', 500)->nullable()->comment('Where - URL yang diakses');
            $table->string('method', 10)->nullable()->comment('HTTP Method: GET, POST, PUT, DELETE');
            $table->text('description')->nullable();
            $table->timestamp('created_at');
            
            $table->index(['entity_type', 'entity_id']);
            $table->index('user_id');
            $table->index('action');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activity_logs');
    }
}
