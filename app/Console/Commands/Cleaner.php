<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class Cleaner extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleaning outdated images.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        return $this->runCleaner();
    }
    private function runCleaner()
    {
        $path = 'product_images';
        $files = Storage::disk('public')->allFiles($path);
        $fileNames = [];
        foreach ($files as $file) {
            array_push($fileNames,  explode('/', $file)[1]);
        }
        $fileNames =  array_filter($fileNames, function ($fileName) {
            if ($fileName == '.gitignore' || $fileName == 'thumbnails' || $fileName == 'no_image.png') {
                return false;
            }
            return true;
        });
        $db_product_images = DB::table('product_images')->select('image_url')->pluck('image_url')->toArray();
        $db_order_product_imagesArrays =  DB::table('order_products')->select('images')->pluck('images')->toArray();

        $db_order_product_images = [];
        foreach ($db_order_product_imagesArrays as $db_order_product_imagesArray) {
            array_push($db_order_product_images, json_decode($db_order_product_imagesArray));
        }
        $db_order_product_images = array_unique(array_merge(...$db_order_product_images));
        $db_images = array_unique(array_merge($db_order_product_images, $db_product_images));
        $filesToDelete = [];
        foreach ($fileNames as $fileName) {
            if (!in_array($fileName, $db_images)) {
                array_push($filesToDelete, $fileName);
            }
        }
        if (count($filesToDelete)) {
            foreach ($filesToDelete as $file_to_delete) {
                Storage::disk('public')->delete('/product_images/' . $file_to_delete);
            }
        }

        // Clean Thumbnails 
        $path = storage_path('/app/public/product_images/thumbnails');
        $files = File::allFiles($path);
        $fileNames = [];
        foreach ($files as $file) {
            array_push($fileNames, pathinfo($file)['basename']);
        }
        $fileNames =  array_filter($fileNames, function ($fileName) {
            if ($fileName == '.gitignore' || $fileName == 'no_image.png') {
                return false;
            }
            return true;
        });
        $db_product_images = DB::table('product_images')->select('thumbnail')->pluck('thumbnail')->toArray();
        $db_images = array_unique($db_product_images);
        $filesToDelete = [];
        foreach ($fileNames as $fileName) {
            if (!in_array($fileName, $db_images)) {
                array_push($filesToDelete, $fileName);
            }
        }
        if (count($filesToDelete)) {
            foreach ($filesToDelete as $file_to_delete) {
                Storage::disk('public')->delete('/product_images/thumbnails/' . $file_to_delete);
            }
        }
    }
}
