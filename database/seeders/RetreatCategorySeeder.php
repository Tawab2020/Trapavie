<?php

namespace Database\Seeders;

use App\Models\RetreatCategory;
use Illuminate\Database\Seeder;

class RetreatCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cats = [
            ['en' => "Specialization"],
            ['en'=>"Languages"],
            ['en'=>"Retreat types"],
            ['en'=>"Diets"], 
            ['en'=>"Activities"],
            ['en'=>"Treatments"],
        ];


        foreach ($cats as $cat){
            RetreatCategory::create(['title'=>json_encode($cat)]);
        }
    }
}
