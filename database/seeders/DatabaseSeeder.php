<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            // 1. Roles and Permissions (foundation for RBAC)
            RolesAndPermissionsSeeder::class,
            
            // 2. University Structure (Units, Faculties, Study Programs)
            UniversityStructureSeeder::class,
            
            // 3. 8 IKU Indicators
            IkuIndicatorSeeder::class,
            
            // 4. Sample Instrument (for testing)
            SampleInstrumentSeeder::class,
        ]);
    }
}
