<?php

namespace Database\Seeders;

use App\Models\Record;
use App\Models\Child;
use Illuminate\Database\Seeder;

class RecordSeeder extends Seeder
{
    public function run()
    {
        // Get all children
        $children = Child::all();

        foreach ($children as $child) {
            // Calculate age in months
            $ageInMonths = now()->diffInMonths($child->tgl_lahir);

            // Generate records for each month from birth
            for ($i = 0; $i <= $ageInMonths; $i++) {
                $date = $child->tgl_lahir->addMonths($i);

                // Generate realistic growth data based on age
                $baseWeight = 3.0; // Starting weight in kg
                $baseHeight = 50.0; // Starting height in cm
                $baseHead = 34.0; // Starting head circumference in cm

                // Monthly growth rates
                $weightGain = min(0.7, 0.7 * (1 - ($i / 24))); // Slower weight gain after 2 years
                $heightGain = min(2.5, 2.5 * (1 - ($i / 24))); // Slower height gain after 2 years
                $headGain = min(1.0, 1.0 * (1 - ($i / 12))); // Head growth slows after 1 year

                // Calculate current measurements
                $weight = $baseWeight + ($weightGain * $i);
                $height = $baseHeight + ($heightGain * $i);
                $head = $baseHead + ($headGain * $i);

                // Add some random variation (±10%)
                $weight *= (1 + (rand(-10, 10) / 100));
                $height *= (1 + (rand(-10, 10) / 100));
                $head *= (1 + (rand(-10, 10) / 100));

                Record::create([
                    'id_anak' => $child->id_anak,
                    'berat_badan' => round($weight, 2),
                    'tinggi_badan' => round($height, 2),
                    'lingkar_kepala' => round($head, 2),
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);
            }
        }
    }
}
