<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // RBAC Roles untuk E-SPMI
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique()->comment('Nama role: superadmin, lpm_admin, auditor, auditee, etc');
            $table->string('display_name')->comment('Nama tampilan: LPM Administrator, Auditor Internal, etc');
            $table->text('description')->nullable();
            $table->string('guard_name', 50)->default('web');
            $table->boolean('is_system')->default(false)->comment('Role sistem tidak bisa dihapus');
            $table->timestamps();
        });

        // Tabel pivot: user - role - unit (RBAC granular berdasarkan unit)
        Schema::create('role_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('role_id')->constrained()->onDelete('cascade');
            $table->foreignId('unit_id')->nullable()->constrained('ref_units')->onDelete('cascade')
                  ->comment('Unit tempat role ini berlaku (contoh: Auditor untuk Fakultas Teknik)');
            $table->timestamp('valid_from')->nullable();
            $table->timestamp('valid_until')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->unique(['user_id', 'role_id', 'unit_id']);
            $table->index(['user_id', 'is_active']);
        });

        // Permissions
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('display_name');
            $table->string('group', 50)->comment('Grup permission: audit, standard, report, etc');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Role - Permission pivot
        Schema::create('permission_role', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permission_id')->constrained()->onDelete('cascade');
            $table->foreignId('role_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['permission_id', 'role_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permission_role');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('role_user');
        Schema::dropIfExists('roles');
    }
}
