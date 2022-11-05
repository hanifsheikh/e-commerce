<?php

namespace Database\Seeders;

use App\Models\CategoryCache;
use App\Models\Product;
use Faker\Generator;
use Illuminate\Container\Container;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * The current Faker instance.
     *
     * @var \Faker\Generator
     */
    protected $faker;

    /**
     * Create a new seeder instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->faker = $this->withFaker();
    }

    /**
     * Get a new Faker instance.
     *
     * @return \Faker\Generator
     */
    protected function withFaker()
    {
        return Container::getInstance()->make(Generator::class);
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('products')->truncate();

        $categories = CategoryCache::all();
        $units = [
            "pcs",
            "gm",
            "kg",
            "m",
            "cm",
            "dm",
            "mm",
        ];
        foreach ($categories as $main_parent_category) {
            if (count($main_parent_category['childrens'])) {
                foreach ($main_parent_category['childrens'] as $second_level_category) {
                    if (count($second_level_category['childrens'])) {
                        foreach ($second_level_category['childrens'] as $third_level_category) {
                            $product_count = rand(1, 2);
                            for ($i = 0; $i < $product_count; $i++) {
                                Product::create([
                                    "product_title" => $this->faker->sentence($nbWords = 6, $variableNbWords = true),
                                    'category_id' => $third_level_category['id'],
                                    'category_parent_id' => $main_parent_category['id'],
                                    'category_second_level_id' => $second_level_category['id'],
                                    'brand_id' => rand(1, 21),
                                    'unit' => $units[rand(0, count($units) - 1)],
                                    'seller_id' => rand(1, 11),
                                    'ratings' => rand(1, 5),
                                    'total_sales' => rand(1, 100),
                                ]);
                            }
                        }
                    } else {
                        $product_count = rand(1, 2);
                        for ($i = 0; $i < $product_count; $i++) {
                            Product::create([
                                "product_title" => $this->faker->sentence($nbWords = 6, $variableNbWords = true),
                                'category_id' => $second_level_category['id'],
                                'category_parent_id' => $main_parent_category['id'],
                                'category_second_level_id' => $second_level_category['id'],
                                'brand_id' => rand(1, 21),
                                'unit' => $units[rand(0, count($units) - 1)],
                                'seller_id' => rand(1, 11),
                                'ratings' => rand(1, 5),
                                'total_sales' => rand(1, 100),
                            ]);
                        }
                    }
                }
            } else {
                $product_count = rand(1, 2);
                for ($i = 0; $i < $product_count; $i++) {
                    Product::create([
                        "product_title" => $this->faker->sentence($nbWords = 6, $variableNbWords = true),
                        'category_id' => $main_parent_category['id'],
                        'category_parent_id' => $main_parent_category['id'],
                        'category_second_level_id' => $main_parent_category['id'],
                        'brand_id' => rand(1, 21),
                        'unit' => $units[rand(0, count($units) - 1)],
                        'seller_id' => rand(1, 11),
                        'ratings' => rand(1, 5),
                        'total_sales' => rand(1, 100),
                    ]);
                }
            }
        }
    }
}
