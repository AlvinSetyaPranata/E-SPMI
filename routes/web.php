<?php

use App\Modules\Core\Models\Role;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Public routes
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// Authenticated routes
Route::middleware(['auth'])->group(function () {
    
    // Dashboard - All authenticated users
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    // Core Module - Admin only (Superadmin & LPM Admin)
    Route::middleware(['role:' . Role::ROLE_SUPERADMIN . ',' . Role::ROLE_LPM_ADMIN])->prefix('core')->group(function () {
        Route::get('/users', function () {
            return Inertia::render('Core/Users/Index');
        })->name('core.users.index');

        Route::get('/roles', function () {
            return Inertia::render('Core/Roles/Index');
        })->name('core.roles.index');

        Route::get('/units', function () {
            return Inertia::render('Core/Units/Index');
        })->name('core.units.index');

        Route::get('/activity-logs', function () {
            return Inertia::render('Core/ActivityLogs/Index');
        })->name('core.logs.index');
    });

    // Standar Module - Admin & LPM
    Route::middleware(['role:' . Role::ROLE_SUPERADMIN . ',' . Role::ROLE_LPM_ADMIN])->prefix('standar')->group(function () {
        Route::get('/instruments', function () {
            return Inertia::render('Standar/Instruments/Index');
        })->name('standar.instruments.index');

        Route::get('/metrics', function () {
            return Inertia::render('Standar/Metrics/Index');
        })->name('standar.metrics.index');

        Route::get('/periods', function () {
            return Inertia::render('Standar/Periods/Index');
        })->name('standar.periods.index');
    });

    // Audit Module - Admin, LPM & Auditor
    Route::middleware(['role:' . Role::ROLE_SUPERADMIN . ',' . Role::ROLE_LPM_ADMIN . ',' . Role::ROLE_AUDITOR])->prefix('audit')->group(function () {
        Route::get('/schedules', function () {
            return Inertia::render('Audit/Schedules/Index');
        })->name('audit.schedules.index');

        Route::get('/assignments', function () {
            return Inertia::render('Audit/Assignments/Index');
        })->name('audit.assignments.index');

        Route::get('/working-papers', function () {
            return Inertia::render('Audit/WorkingPapers/Index');
        })->name('audit.working-papers.index');

        Route::get('/results', function () {
            return Inertia::render('Audit/Results/Index');
        })->name('audit.results.index');
    });

    // Pelaksanaan Module - All authenticated users
    Route::prefix('pelaksanaan')->group(function () {
        Route::get('/evidences', function () {
            return Inertia::render('Pelaksanaan/Evidences/Index');
        })->name('pelaksanaan.evidences.index');

        Route::get('/self-assessment', function () {
            return Inertia::render('Pelaksanaan/SelfAssessment/Index');
        })->name('pelaksanaan.self-assessment.index');
    });

    // Pengendalian Module - Admin, LPM, Auditor & Auditee
    Route::middleware(['role:' . Role::ROLE_SUPERADMIN . ',' . Role::ROLE_LPM_ADMIN . ',' . Role::ROLE_AUDITOR . ',' . Role::ROLE_AUDITEE])->prefix('pengendalian')->group(function () {
        Route::get('/findings', function () {
            return Inertia::render('Pengendalian/Findings/Index');
        })->name('pengendalian.findings.index');

        Route::get('/ptk', function () {
            return Inertia::render('Pengendalian/Ptk/Index');
        })->name('pengendalian.ptk.index');

        Route::get('/rtm', function () {
            return Inertia::render('Pengendalian/Rtm/Index');
        })->name('pengendalian.rtm.index');

        Route::get('/action-plans', function () {
            return Inertia::render('Pengendalian/ActionPlans/Index');
        })->name('pengendalian.action-plans.index');
    });

    // Analytics Module - All authenticated users
    Route::prefix('analytics')->group(function () {
        Route::get('/iku-dashboard', function () {
            return Inertia::render('Analytics/IkuDashboard/Index');
        })->name('analytics.iku-dashboard.index');

        Route::get('/iku-indicators', function () {
            return Inertia::render('Analytics/IkuIndicators/Index');
        })->name('analytics.iku-indicators.index');

        Route::get('/executive-reports', function () {
            return Inertia::render('Analytics/ExecutiveReports/Index');
        })->name('analytics.executive-reports.index');
    });
});

require __DIR__.'/auth.php';
