<?php

namespace Database\Seeders;

use App\Models\About;
use Illuminate\Database\Seeder;

class AboutSeeder extends Seeder
{
    /**
     * Seed the about table with the initial row (id = 1).
     */
    public function run(): void
    {
        About::firstOrCreate(
            ['id' => 1],
            [
                'heading' => 'About',
                'body' => 'Add your bio here.',
                'avatar' => null,
                'links' => null,
            ]
        );
    }
}
