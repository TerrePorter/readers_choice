<?php

namespace Modules\Campaigns\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Campaigns\Models\Categories;
use Modules\Campaigns\Models\CategoryTypes;

class CampaignsDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $this->call([]);

        // crete campaign types
        $types = [
            'bizser' => 'Business and Services',
            'dinent' => 'Dining and Entertainment'
        ];
        foreach ($types as $index => $item) {
                CategoryTypes::firstOrCreate([
                                                 'name'  => $index,
                                                 'title' => $item
                                             ]);
        }

        // create some text categories for each type
        $types = CategoryTypes::all();
        foreach ($types as $index => $type) {
            for ($i=0; $i <= 10; $i++) {
                $words = fake()->words(rand(1, 2));
                $name = implode(" ", $words);
                $tag = strtolower(implode("_", $words));

                Categories::create([
                                       'name' => $name,
                                       'tag'  => $tag,
                                       'type' => $type->id
                                   ]);
            }
        }
    }
}
