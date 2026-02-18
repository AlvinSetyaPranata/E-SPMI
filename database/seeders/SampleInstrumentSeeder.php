<?php

namespace Database\Seeders;

use App\Modules\Core\Models\User;
use App\Modules\Standar\Models\MstInstrument;
use App\Modules\Standar\Models\MstMetric;
use Illuminate\Database\Seeder;

class SampleInstrumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get first user as creator (should be created by Laravel Breeze)
        $creator = User::first();
        
        if (!$creator) {
            $creator = User::create([
                'name' => 'Administrator',
                'email' => 'admin@universitas.ac.id',
                'password' => bcrypt('password'),
                'nip' => '197001011990011001',
                'is_active' => true,
            ]);
        }

        // Create Instrument
        $instrument = MstInstrument::create([
            'code' => 'AMI-2025',
            'name' => 'Instrumen Audit Mutu Internal 2025',
            'description' => 'Instrumen standar untuk audit mutu internal perguruan tinggi sesuai dengan Permendiktisaintek No. 39 Tahun 2025',
            'type' => MstInstrument::TYPE_INTERNAL,
            'reference_regulation' => 'Permendiktisaintek No. 39 Tahun 2025',
            'status' => MstInstrument::STATUS_ACTIVE,
            'effective_date' => '2025-01-01',
            'expired_date' => null,
            'created_by' => $creator->id,
        ]);

        // Create Standards based on PPEPP
        $standards = [
            [
                'code' => 'PS-1',
                'name' => 'Penetapan (P) - Standar Penetapan',
                'description' => 'Proses penetapan standar, kebijakan, dan target mutu',
                'type' => MstMetric::TYPE_STANDARD,
                'order_no' => 1,
                'weight' => 20,
                'children' => [
                    [
                        'code' => 'PS-1.1',
                        'name' => 'Penyusunan Standar Mutu',
                        'type' => MstMetric::TYPE_INDICATOR,
                        'order_no' => 1,
                        'weight' => 50,
                        'data_type' => MstMetric::DATA_TYPE_CHOICE,
                        'data_options' => json_encode(['1' => 'Belum ada', '2' => 'Draft', '3' => 'Disahkan', '4' => 'Diterapkan']),
                        'target_value' => 4,
                    ],
                    [
                        'code' => 'PS-1.2',
                        'name' => 'Penetapan Kebijakan Mutu',
                        'type' => MstMetric::TYPE_INDICATOR,
                        'order_no' => 2,
                        'weight' => 50,
                        'data_type' => MstMetric::DATA_TYPE_CHOICE,
                        'data_options' => json_encode(['1' => 'Belum ada', '2' => 'Draft', '3' => 'Disahkan', '4' => 'Diterapkan']),
                        'target_value' => 4,
                    ],
                ],
            ],
            [
                'code' => 'PS-2',
                'name' => 'Pelaksanaan (P) - Standar Pelaksanaan',
                'description' => 'Implementasi standar dalam kegiatan akademik dan non-akademik',
                'type' => MstMetric::TYPE_STANDARD,
                'order_no' => 2,
                'weight' => 30,
                'children' => [
                    [
                        'code' => 'PS-2.1',
                        'name' => 'Ketersediaan Dokumen Proses',
                        'type' => MstMetric::TYPE_INDICATOR,
                        'order_no' => 1,
                        'weight' => 40,
                        'data_type' => MstMetric::DATA_TYPE_PERCENTAGE,
                        'target_value' => 100,
                    ],
                    [
                        'code' => 'PS-2.2',
                        'name' => 'Implementasi SOP',
                        'type' => MstMetric::TYPE_INDICATOR,
                        'order_no' => 2,
                        'weight' => 60,
                        'data_type' => MstMetric::DATA_TYPE_PERCENTAGE,
                        'target_value' => 85,
                    ],
                ],
            ],
            [
                'code' => 'PS-3',
                'name' => 'Evaluasi (E) - Standar Evaluasi',
                'description' => 'Proses monitoring dan evaluasi terhadap pelaksanaan standar',
                'type' => MstMetric::TYPE_STANDARD,
                'order_no' => 3,
                'weight' => 30,
                'children' => [
                    [
                        'code' => 'PS-3.1',
                        'name' => 'Keterlaksanaan Audit Internal',
                        'type' => MstMetric::TYPE_INDICATOR,
                        'order_no' => 1,
                        'weight' => 100,
                        'data_type' => MstMetric::DATA_TYPE_BOOLEAN,
                        'target_value' => 1,
                    ],
                ],
            ],
            [
                'code' => 'PS-4',
                'name' => 'Pengendalian (P) - Standar Pengendalian',
                'description' => 'Tindak lanjut terhadap temuan audit',
                'type' => MstMetric::TYPE_STANDARD,
                'order_no' => 4,
                'weight' => 10,
                'children' => [
                    [
                        'code' => 'PS-4.1',
                        'name' => 'Penyelesaian Temuan Audit',
                        'type' => MstMetric::TYPE_INDICATOR,
                        'order_no' => 1,
                        'weight' => 100,
                        'data_type' => MstMetric::DATA_TYPE_PERCENTAGE,
                        'target_value' => 100,
                    ],
                ],
            ],
            [
                'code' => 'PS-5',
                'name' => 'Peningkatan (P) - Standar Peningkatan',
                'description' => 'Proses perbaikan berkelanjutan',
                'type' => MstMetric::TYPE_STANDARD,
                'order_no' => 5,
                'weight' => 10,
                'children' => [
                    [
                        'code' => 'PS-5.1',
                        'name' => 'Keterlaksanaan RTM',
                        'type' => MstMetric::TYPE_INDICATOR,
                        'order_no' => 1,
                        'weight' => 50,
                        'data_type' => MstMetric::DATA_TYPE_BOOLEAN,
                        'target_value' => 1,
                    ],
                    [
                        'code' => 'PS-5.2',
                        'name' => 'Realisasi Action Plan',
                        'type' => MstMetric::TYPE_INDICATOR,
                        'order_no' => 2,
                        'weight' => 50,
                        'data_type' => MstMetric::DATA_TYPE_PERCENTAGE,
                        'target_value' => 90,
                    ],
                ],
            ],
        ];

        foreach ($standards as $standard) {
            $this->createMetric($instrument->id, $standard, null);
        }
    }

    /**
     * Recursively create metrics
     */
    private function createMetric($instrumentId, $data, $parentId)
    {
        $children = $data['children'] ?? [];
        unset($data['children']);

        $metric = MstMetric::create(array_merge($data, [
            'instrument_id' => $instrumentId,
            'parent_id' => $parentId,
        ]));

        foreach ($children as $child) {
            $this->createMetric($instrumentId, $child, $metric->id);
        }

        return $metric;
    }
}
