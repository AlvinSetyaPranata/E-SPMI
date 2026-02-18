# E-SPMI (Enterprise Sistem Penjaminan Mutu Internal)

## Overview

E-SPMI adalah Sistem Informasi Penjaminan Mutu Internal berbasis arsitektur **Modular Monolith** untuk perguruan tinggi di Indonesia. Sistem ini mengikuti siklus PPEPP (Penetapan, Pelaksanaan, Evaluasi, Pengendalian, Peningkatan) sesuai dengan Permendiktisaintek No. 39 Tahun 2025.

## Architecture

### Modular Monolith Pattern
Sistem dibagi menjadi 6 modul vertikal dalam satu unit deployment:

```
app/Modules/
├── Core/           # Auth, RBAC, Organization, Activity Logs
├── Standar/        # Master data (Instruments, Metrics, Periods)
├── Audit/          # Audit transactions (TrxAudit, TrxAuditDetail, TrxAuditor)
├── Pelaksanaan/    # Evidence repository (TrxEvidence)
├── Pengendalian/   # PTK, RTM (TrxFinding, TrxRtm)
└── Analytics/      # IKU Dashboard (MstIkuIndicator, TrxIkuData)
```

### Database Schema

#### Core Module
- `ref_units` - Struktur organisasi (Fakultas, Prodi, Biro, LPM)
- `roles` - Master roles
- `role_user` - User-Role-Unit pivot (RBAC granular)
- `permissions` - Master permissions
- `permission_role` - Role-Permission pivot
- `activity_logs` - Immutable audit trail (SPBE compliance)

#### Standar Module
- `mst_instruments` - Instrumen audit (AMI, IAPS, etc)
- `mst_metrics` - Standar hirarkis (N-Level, Adjacency List)
- `mst_periods` - Periode akademik dengan timeline PPEPP

#### Audit Module
- `trx_audits` - Transaksi audit utama
- `trx_audit_details` - Nilai per indikator
- `trx_auditors` - Penugasan auditor (Matrix Auditor)

#### Pelaksanaan Module
- `trx_evidences` - Repository bukti dengan versioning

#### Pengendalian Module
- `trx_findings` - Temuan audit (OB, KTS Minor, KTS Mayor) & PTK
- `trx_rtm` - Rapat Tinjauan Manajemen
- `trx_rtm_attendances` - Daftar hadir RTM
- `trx_rtm_action_plans` - Action plan dari RTM

#### Analytics Module
- `mst_iku_indicators` - 8 Indikator Kinerja Utama PT
- `trx_iku_data` - Data aktual IKU per periode

## Key Features

### 1. RBAC (Role-Based Access Control)
Roles:
- `superadmin` - Full access
- `lpm_admin` - Manage SPMI processes
- `auditor` - Conduct audits
- `auditee` - Submit evidence
- `rector` - Executive view
- `dean` - Faculty oversight
- `head_of_study_program` - Prodi management

### 2. PPEPP Workflow Enforcement
Strict phase transitions:
```
Planning -> Implementation -> Evaluation -> Control -> Improvement -> Completed
```

### 3. 8 IKU (Indikator Kinerja Utama)
1. Daya Saing Lulusan
2. Penjelajahan Data
3. Karya Inovatif
4. Kolaborasi Luar Negeri
5. Publikasi Bereputasi
6. Luaran Penelitian
7. Kepuasan Pengguna
8. Kemampuan Bahasa Inggris

### 4. Audit Trail (SPBE Compliance)
- Immutable logs for all CRUD operations
- Captures: Who, What, When, Where, IP, User Agent

## Installation

### 1. Install Dependencies
```bash
composer install
npm install
```

### 2. Environment Configuration
```bash
cp .env.example .env
php artisan key:generate
```

Configure database in `.env`:
```
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=espmi
DB_USERNAME=postgres
DB_PASSWORD=secret
```

### 3. Run Migrations
```bash
php artisan migrate
```

### 4. Run Seeders
```bash
php artisan db:seed
```

This will seed:
- Roles & Permissions
- University Structure (Sample)
- 8 IKU Indicators
- Sample Instrument

## Directory Structure

```
app/
├── Modules/                    # Modular Monolith structure
│   ├── Core/
│   │   ├── Models/
│   │   │   ├── User.php
│   │   │   ├── Role.php
│   │   │   ├── Permission.php
│   │   │   ├── RefUnit.php
│   │   │   └── ActivityLog.php
│   │   └── Providers/
│   │       └── CoreModuleServiceProvider.php
│   ├── Standar/
│   │   ├── Models/
│   │   │   ├── MstInstrument.php
│   │   │   ├── MstMetric.php
│   │   │   └── MstPeriod.php
│   │   └── Providers/
│   ├── Audit/
│   │   ├── Models/
│   │   │   ├── TrxAudit.php
│   │   │   ├── TrxAuditDetail.php
│   │   │   └── TrxAuditor.php
│   │   └── Providers/
│   ├── Pelaksanaan/
│   │   ├── Models/
│   │   │   └── TrxEvidence.php
│   │   └── Providers/
│   ├── Pengendalian/
│   │   ├── Models/
│   │   │   ├── TrxFinding.php
│   │   │   └── TrxRtm.php
│   │   └── Providers/
│   └── Analytics/
│       ├── Models/
│       │   ├── MstIkuIndicator.php
│       │   └── TrxIkuData.php
│       └── Providers/
├── Models/
│   └── User.php               # Extended User model (Laravel Breeze compatible)
└── Providers/
    ├── ModuleServiceProvider.php
    └── ...

database/
├── migrations/
│   ├── core/                  # Core module migrations
│   ├── standar/               # Standar module migrations
│   ├── audit/                 # Audit module migrations
│   ├── pelaksanaan/           # Pelaksanaan module migrations
│   ├── pengendalian/          # Pengendalian module migrations
│   └── analytics/             # Analytics module migrations
└── seeders/
    ├── DatabaseSeeder.php
    ├── RolesAndPermissionsSeeder.php
    ├── UniversityStructureSeeder.php
    ├── IkuIndicatorSeeder.php
    └── SampleInstrumentSeeder.php
```

## Model Relationships

### RefUnit (Organization Structure)
- `parent()` → RefUnit (self-referential hierarchy)
- `children()` → RefUnit[]
- `users()` → User[] (via role_user pivot)

### User
- `roles()` → Role[] (via role_user pivot with unit context)
- `units()` → RefUnit[]
- `activityLogs()` → ActivityLog[]

### TrxAudit
- `period()` → MstPeriod
- `instrument()` → MstInstrument
- `unit()` → RefUnit
- `details()` → TrxAuditDetail[]
- `auditors()` → TrxAuditor[]
- `evidences()` → TrxEvidence[]
- `findings()` → TrxFinding[]

### MstMetric (Standards Hierarchy)
- `instrument()` → MstInstrument
- `parent()` → MstMetric
- `children()` → MstMetric[] (recursive)

## Next Steps

To continue building the system:

1. **Create Controllers** - Add CRUD controllers for each module
2. **Create Form Requests** - Add validation rules
3. **Create Resources** - Add API resources
4. **Create Services** - Add business logic layer
5. **Create Routes** - Define routes for each module
6. **Create Vue Components** - Build frontend with Inertia.js
7. **Add Policies** - Implement authorization
8. **Add Observers** - Handle model events
9. **Add Jobs** - Queue heavy tasks (report generation)
10. **Add Notifications** - Email notifications for assignments

## Development Guidelines

1. **Keep modules isolated** - No direct imports between modules except through Core
2. **Use repository pattern** for complex queries
3. **Use service classes** for business logic
4. **Always log critical operations** using ActivityLog
5. **Use transactions** for multi-step operations
6. **Follow PSR coding standards**

## References

- Permendiktisaintek No. 39 Tahun 2025
- SN-Dikti (Standar Nasional Pendidikan Tinggi)
- 8 IKU (Indikator Kinerja Utama PTN)
- SPBE (Sistem Pemerintahan Berbasis Elektronik)
