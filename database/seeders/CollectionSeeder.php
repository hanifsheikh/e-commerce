<?php

namespace Database\Seeders;

use App\Models\Collection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CollectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('collections')->truncate();
        // \App\Models\Collection::factory(10)->create();
        $collections = ['Autumn Collection', 'Winter Collection', 'Mid Season Sale', 'Spring Collection', 'Valentine Collection'];
        $collections_images = ['1.jpg', '2.jpg', '3.jpg', '4.webp', '5.jpg'];
        for ($i = 0; $i < count($collections); $i++) {
            Collection::create([
                'collection_title' => $collections[$i],
                'slug' => Str::slug($collections[$i]),
                'collection_image' => $collections_images[$i]
            ]);
        }
    }
}
