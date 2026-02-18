<?php

namespace Database\Seeders;

use App\Modules\Analytics\Models\MstIkuIndicator;
use Illuminate\Database\Seeder;

class IkuIndicatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $indicators = [
            [
                'code' => MstIkuIndicator::IKU_1,
                'name' => 'Daya Saing Lulusan',
                'description' => 'Persentase lulusan yang bekerja, berwirausaha, atau melanjutkan studi dalam waktu 6 bulan setelah lulus',
                'formula' => '(Jumlah lulusan yang bekerja/wirausaha/studi / Total lulusan) x 100%',
                'measurement_unit' => '%',
                'target_national' => 80.00,
                'target_per_year' => json_encode(['2024' => 75, '2025' => 78, '2026' => 80]),
                'data_source' => MstIkuIndicator::SOURCE_SIAKAD,
                'is_active' => true,
                'order_no' => 1,
            ],
            [
                'code' => MstIkuIndicator::IKU_2,
                'name' => 'Penjelajahan Data',
                'description' => 'Persentase dosen yang melakukan penelitian dengan pendekatan big data/data science',
                'formula' => '(Jumlah dosen peneliti data / Total dosen) x 100%',
                'measurement_unit' => '%',
                'target_national' => 25.00,
                'target_per_year' => json_encode(['2024' => 20, '2025' => 22, '2026' => 25]),
                'data_source' => MstIkuIndicator::SOURCE_SISTER,
                'is_active' => true,
                'order_no' => 2,
            ],
            [
                'code' => MstIkuIndicator::IKU_3,
                'name' => 'Karya Inovatif',
                'description' => 'Persentase dosen yang menghasilkan karya inovatif (paten, hak cipta, desain industri)',
                'formula' => '(Jumlah dosen dengan karya inovatif / Total dosen) x 100%',
                'measurement_unit' => '%',
                'target_national' => 15.00,
                'target_per_year' => json_encode(['2024' => 10, '2025' => 12, '2026' => 15]),
                'data_source' => MstIkuIndicator::SOURCE_SISTER,
                'is_active' => true,
                'order_no' => 3,
            ],
            [
                'code' => MstIkuIndicator::IKU_4,
                'name' => 'Kolaborasi Luar Negeri',
                'description' => 'Persentase dosen yang melakukan kerjasama penelitian dengan mitra luar negeri',
                'formula' => '(Jumlah dosen dengan kerjasama internasional / Total dosen) x 100%',
                'measurement_unit' => '%',
                'target_national' => 20.00,
                'target_per_year' => json_encode(['2024' => 15, '2025' => 17, '2026' => 20]),
                'data_source' => MstIkuIndicator::SOURCE_MANUAL,
                'is_active' => true,
                'order_no' => 4,
            ],
            [
                'code' => MstIkuIndicator::IKU_5,
                'name' => 'Publikasi Bereputasi',
                'description' => 'Persentase dosen yang mempublikasikan artikel di jurnal bereputasi (Q1-Q2)',
                'formula' => '(Jumlah dosen dengan publikasi bereputasi / Total dosen) x 100%',
                'measurement_unit' => '%',
                'target_national' => 25.00,
                'target_per_year' => json_encode(['2024' => 18, '2025' => 21, '2026' => 25]),
                'data_source' => MstIkuIndicator::SOURCE_SISTER,
                'is_active' => true,
                'order_no' => 5,
            ],
            [
                'code' => MstIkuIndicator::IKU_6,
                'name' => 'Luaran Penelitian',
                'description' => 'Persentase penelitian yang menghasilkan luaran yang dimanfaatkan oleh masyarakat/industri',
                'formula' => '(Jumlah penelitian dengan luaran dimanfaatkan / Total penelitian) x 100%',
                'measurement_unit' => '%',
                'target_national' => 60.00,
                'target_per_year' => json_encode(['2024' => 50, '2025' => 55, '2026' => 60]),
                'data_source' => MstIkuIndicator::SOURCE_SISTER,
                'is_active' => true,
                'order_no' => 6,
            ],
            [
                'code' => MstIkuIndicator::IKU_7,
                'name' => 'Kepuasan Pengguna',
                'description' => 'Persentase kepuasan pengguna lulusan (employer satisfaction)',
                'formula' => '(Total skor kepuasan / Jumlah responden) x 100%',
                'measurement_unit' => '%',
                'target_national' => 85.00,
                'target_per_year' => json_encode(['2024' => 80, '2025' => 82, '2026' => 85]),
                'data_source' => MstIkuIndicator::SOURCE_MANUAL,
                'is_active' => true,
                'order_no' => 7,
            ],
            [
                'code' => MstIkuIndicator::IKU_8,
                'name' => 'Kemampuan Bahasa Inggris',
                'description' => 'Persentase lulusan dengan kemampuan bahasa Inggris minimal B2 (CEFR)',
                'formula' => '(Jumlah lulusan dengan sertifikat B2+ / Total lulusan) x 100%',
                'measurement_unit' => '%',
                'target_national' => 40.00,
                'target_per_year' => json_encode(['2024' => 30, '2025' => 35, '2026' => 40]),
                'data_source' => MstIkuIndicator::SOURCE_SIAKAD,
                'is_active' => true,
                'order_no' => 8,
            ],
        ];

        foreach ($indicators as $indicator) {
            MstIkuIndicator::create($indicator);
        }
    }
}
