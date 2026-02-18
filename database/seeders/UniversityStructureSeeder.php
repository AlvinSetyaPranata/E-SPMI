<?php

namespace Database\Seeders;

use App\Modules\Core\Models\RefUnit;
use Illuminate\Database\Seeder;

class UniversityStructureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create University Level
        $university = RefUnit::create([
            'code' => 'UNIV',
            'name' => 'Universitas Komprehensif Indonesia',
            'type' => RefUnit::TYPE_UNIVERSITY,
            'parent_id' => null,
            'description' => 'Perguruan Tinggi Negeri',
            'head_name' => 'Prof. Dr. Rektor',
            'head_nip' => '197001011990011001',
            'contact_email' => 'rektor@universitas.ac.id',
            'contact_phone' => '021-12345678',
            'is_active' => true,
        ]);

        // Create Faculties
        $faculties = [
            [
                'code' => 'FTIK',
                'name' => 'Fakultas Teknik dan Ilmu Komputer',
                'head_name' => 'Prof. Dr. Dekan FTIK',
                'head_nip' => '197002021991021002',
                'contact_email' => 'dekan.ftik@universitas.ac.id',
            ],
            [
                'code' => 'FE',
                'name' => 'Fakultas Ekonomi',
                'head_name' => 'Prof. Dr. Dekan FE',
                'head_nip' => '197003031992031003',
                'contact_email' => 'dekan.fe@universitas.ac.id',
            ],
            [
                'code' => 'FIP',
                'name' => 'Fakultas Ilmu Pendidikan',
                'head_name' => 'Prof. Dr. Dekan FIP',
                'head_nip' => '197004041993041004',
                'contact_email' => 'dekan.fip@universitas.ac.id',
            ],
            [
                'code' => 'FISIP',
                'name' => 'Fakultas Ilmu Sosial dan Ilmu Politik',
                'head_name' => 'Prof. Dr. Dekan FISIP',
                'head_nip' => '197005051994051005',
                'contact_email' => 'dekan.fisip@universitas.ac.id',
            ],
            [
                'code' => 'FH',
                'name' => 'Fakultas Hukum',
                'head_name' => 'Prof. Dr. Dekan FH',
                'head_nip' => '197006061995061006',
                'contact_email' => 'dekan.fh@universitas.ac.id',
            ],
        ];

        $facultyModels = [];
        foreach ($faculties as $faculty) {
            $facultyModels[] = RefUnit::create(array_merge($faculty, [
                'type' => RefUnit::TYPE_FACULTY,
                'parent_id' => $university->id,
                'description' => null,
                'contact_phone' => null,
                'is_active' => true,
            ]));
        }

        // Create Study Programs under FTIK
        $ftik = $facultyModels[0];
        $studyProgramsFTIK = [
            [
                'code' => 'TI',
                'name' => 'Teknik Informatika',
                'head_name' => 'Dr. Kaprodi TI',
                'head_nip' => '198001011990011001',
                'contact_email' => 'kaprodi.ti@universitas.ac.id',
            ],
            [
                'code' => 'SI',
                'name' => 'Sistem Informasi',
                'head_name' => 'Dr. Kaprodi SI',
                'head_nip' => '198002021990021002',
                'contact_email' => 'kaprodi.si@universitas.ac.id',
            ],
            [
                'code' => 'TK',
                'name' => 'Teknik Komputer',
                'head_name' => 'Dr. Kaprodi TK',
                'head_nip' => '198003031990031003',
                'contact_email' => 'kaprodi.tk@universitas.ac.id',
            ],
            [
                'code' => 'IF',
                'name' => 'Ilmu Komputer',
                'head_name' => 'Dr. Kaprodi IF',
                'head_nip' => '198004041990041004',
                'contact_email' => 'kaprodi.if@universitas.ac.id',
            ],
        ];

        foreach ($studyProgramsFTIK as $prodi) {
            RefUnit::create(array_merge($prodi, [
                'type' => RefUnit::TYPE_PROGRAM_STUDY,
                'parent_id' => $ftik->id,
                'description' => null,
                'contact_phone' => null,
                'is_active' => true,
            ]));
        }

        // Create Study Programs under FE
        $fe = $facultyModels[1];
        $studyProgramsFE = [
            [
                'code' => 'MN',
                'name' => 'Manajemen',
                'head_name' => 'Dr. Kaprodi MN',
                'head_nip' => '198101011990011001',
                'contact_email' => 'kaprodi.mn@universitas.ac.id',
            ],
            [
                'code' => 'AK',
                'name' => 'Akuntansi',
                'head_name' => 'Dr. Kaprodi AK',
                'head_nip' => '198102021990021002',
                'contact_email' => 'kaprodi.ak@universitas.ac.id',
            ],
            [
                'code' => 'EKO',
                'name' => 'Ekonomi Pembangunan',
                'head_name' => 'Dr. Kaprodi EKO',
                'head_nip' => '198103031990031003',
                'contact_email' => 'kaprodi.eko@universitas.ac.id',
            ],
        ];

        foreach ($studyProgramsFE as $prodi) {
            RefUnit::create(array_merge($prodi, [
                'type' => RefUnit::TYPE_PROGRAM_STUDY,
                'parent_id' => $fe->id,
                'description' => null,
                'contact_phone' => null,
                'is_active' => true,
            ]));
        }

        // Create LPM Unit
        RefUnit::create([
            'code' => 'LPM',
            'name' => 'Lembaga Penjaminan Mutu',
            'type' => RefUnit::TYPE_LPM,
            'parent_id' => $university->id,
            'description' => 'Unit pengelola SPMI',
            'head_name' => 'Prof. Dr. Kepala LPM',
            'head_nip' => '197010101990101010',
            'contact_email' => 'lpm@universitas.ac.id',
            'contact_phone' => '021-12345679',
            'is_active' => true,
        ]);

        // Create Bureaus
        $bureaus = [
            [
                'code' => 'BAK',
                'name' => 'Biro Akademik dan Kemahasiswaan',
                'head_name' => 'Kabag. BAK',
            ],
            [
                'code' => 'BUK',
                'name' => 'Biro Umum dan Keuangan',
                'head_name' => 'Kabag. BUK',
            ],
            [
                'code' => 'BPL',
                'name' => 'Biro Perencanaan dan Layanan',
                'head_name' => 'Kabag. BPL',
            ],
        ];

        foreach ($bureaus as $bureau) {
            RefUnit::create([
                'code' => $bureau['code'],
                'name' => $bureau['name'],
                'type' => RefUnit::TYPE_BUREAU,
                'parent_id' => $university->id,
                'head_name' => $bureau['head_name'],
                'head_nip' => null,
                'is_active' => true,
            ]);
        }
    }
}
