<?php

namespace App\Http\Controllers;

use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;

class ProductImageController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('HasPermission:product-create')->only(['storeImage']);
        $this->middleware('HasPermission:product-update')->only(['storeVariantImage']);
        $this->middleware('HasPermission:product-create')->only(['resizeImage']);
    }
    public function storeImage(Request $request)
    {
        try {

            $imagesFolder = 'product_images/';
            $texturesFolder = 'product_variant_textures/';
            $thumbnailsFolder = 'product_images/thumbnails';

            if (!file_exists(storage_path('app/public/' . $imagesFolder))) {
                mkdir(storage_path('app/public/' . $imagesFolder), 755, true);
            }
            if (!file_exists(storage_path('app/public/' . $texturesFolder))) {
                mkdir(storage_path('app/public/' . $texturesFolder), 755, true);
            }
            if (!file_exists(storage_path('app/public/' . $thumbnailsFolder))) {
                mkdir(storage_path('app/public/' . $thumbnailsFolder), 755, true);
            }

            // Store texture
            $file = $request->file('texture');

            if ($file) {
                $fileNameToStore =
                    strtolower($this->sanitizeString($request->product_title)) .
                    '_' .
                    $request->sku .
                    '_' .
                    '.webp';
                $saveTexture = $this->resizeTexture($file, $fileNameToStore);
                if ($saveTexture) {
                    ProductVariant::where('sku', $request->sku)->update([
                        'texture' => $fileNameToStore
                    ]);
                }
            }

            //Store Images
            if ($request->imageArrayLength) {
                // Get file from request through loop
                for ($i = 1; $i <=  $request->imageArrayLength; $i++) {
                    $file = $request->file('image' . $i);
                    // Create unique file name
                    $fileNameToStore =
                        strtolower($this->sanitizeString($request->product_title)) .
                        '_' .
                        $request->sku .
                        '_' .
                        $i .
                        '_1500' .
                        '.webp';
                    $thumbnailName =
                        strtolower($this->sanitizeString($request->product_title)) .
                        '_' .
                        $request->sku .
                        '_' .
                        $i .
                        '_320' .
                        '.webp';
                    // Refer image to method resizeImage
                    $save = $this->resizeImage($file, $fileNameToStore);
                    $saveThumb = $this->resizeProductThumbImage($file, $thumbnailName);
                    if ($save && $saveThumb) {
                        ProductImage::create([
                            'product_id' => $request->product_id,
                            'product_variant_id' => $request->product_variant_id,
                            'image_url' => $fileNameToStore,
                            'thumbnail' => $thumbnailName,
                            'position' => $i,
                        ]);
                    }
                }
            } else {
                ProductImage::create([
                    'product_id' => $request->product_id,
                    'product_variant_id' => $request->product_variant_id,
                    'image_url' => 'no_image.png',
                    'thumbnail' => 'no_image.png',
                    'position' => 1,
                ]);
            }

            return response()->json(['status' => true]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false]);
        }
    }
    public function storeVariantImage(Request $request)
    {

        $texturesFolder = 'product_variant_textures/';

        if (!file_exists(storage_path('app/public/' . $texturesFolder))) {
            mkdir(storage_path('app/public/' . $texturesFolder), 755, true);
        }

        // Store texture
        $file = $request->file('texture');

        if ($file) {
            $fileNameToStore =
                strtolower($this->sanitizeString($request->product_title)) .
                '_' .
                $request->sku .
                '_' .
                '.webp';
            $saveTexture = $this->resizeTexture($file, $fileNameToStore);
            if ($saveTexture) {
                ProductVariant::where('sku', $request->sku)->update([
                    'texture' => $fileNameToStore
                ]);
            }
        }
        return;
    }

    public function resizeImage($file, $fileNameToStore)
    {
        // Resize image
        $resizedContent = Image::make($file)->orientate()->resize(1500, null, function ($constraint) {
            $constraint->upsize();
            $constraint->aspectRatio();
        })->encode('webp', 100);
        // Put image to storage
        // $save = $resizedContent->save(storage_path('app/public/product_images/' . $fileNameToStore));
        $save = Storage::disk('public')->put('product_images/' . $fileNameToStore, $resizedContent->__toString());

        if ($save) {
            return true;
        }
        return false;
    }
    public function resizeProductThumbImage($file, $thumbnailName)
    {
        // Resize image
        $resizedContent = Image::make($file)->orientate()->resize(320, null, function ($constraint) {
            $constraint->upsize();
            $constraint->aspectRatio();
        })->encode('webp', 90);
        // Put image to storage
        // $save = $resizedContent->save(storage_path('app/public/product_images/' . $fileNameToStore));
        $save = Storage::disk('public')->put('product_images/thumbnails/' . $thumbnailName, $resizedContent->__toString());
        if ($save) {
            return true;
        }
        return false;
    }

    public function resizeTexture($file, $fileNameToStore)
    {
        // Resize image
        $resizedContent = Image::make($file)->orientate()->resize(120, null, function ($constraint) {
            $constraint->upsize();
            $constraint->aspectRatio();
        })->encode('webp', 75);
        // Put image to storage
        // $save = $resizedContent->save(storage_path('app/public/product_images/' . $fileNameToStore));
        $save = Storage::disk('public')->put('product_variant_textures/' . $fileNameToStore, $resizedContent->__toString());

        if ($save) {
            return true;
        }
        return false;
    }
    public function sanitizeString($string)
    {
        $sanitized_string =  preg_replace('/\s+/', ' ', $string);
        $sanitized_string =  preg_replace('/[^a-zA-Z0-9\']/', '_', $sanitized_string);
        $sanitized_string = str_replace("'", '', $sanitized_string);
        return $sanitized_string;
    }
}
