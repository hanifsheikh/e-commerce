<?php

namespace Modules\Admin\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class HomePageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('HasPermission:homePageManager-create')->only('storeBanner');
        $this->middleware('HasPermission:homePageManager-create')->only('storeRecommended');
    }

    // Banners Starts Here 
    public function indexBanner()
    {
        $banners = DB::table('banners')->orderBy('position')->get();
        return response()->json($banners);
    }
    public function storeBanner(Request $request)
    {
        $imagesFolder = 'banners/';
        if (!file_exists(storage_path('app/public/' . $imagesFolder))) {
            mkdir(storage_path('app/public/' . $imagesFolder), 755, true);
        }
        // Store Image
        $file = $request->file('image');
        if ($file) {
            $fileNameToStore = Str::random(10) . '-' . rand(1, 10) . '-' . time() . '.' . $file->extension();
            $saveImage = Storage::disk('public')->put('banners/' . $fileNameToStore, file_get_contents($file));
            // $saveImage = $this->resizeImage($file, $fileNameToStore);
            if ($saveImage) {
                $maxPosition = DB::table('banners')->max('position');
                DB::table('banners')->insert([
                    'url' => $request->url === 'null' ? null : $request->url,
                    'image' => $fileNameToStore,
                    'position' => $maxPosition + 1,
                ]);
                return response()->json(['message' => 'Slide Created', 'status' => true]);
            }
        }
        return response()->json(['message' => 'Something Went Wrong!', 'status' => false]);
    }
    public function resizeImage($file, $fileNameToStore)
    {
        // Resize image
        $resizedContent = Image::make($file)->orientate()->resize(1024, null, function ($constraint) {
            $constraint->upsize();
            $constraint->aspectRatio();
        })->encode('webp');
        // Put image to storage 
        $save = Storage::disk('public')->put('banners/' . $fileNameToStore, $resizedContent->__toString());

        if ($save) {
            return true;
        }
        return false;
    }
    public function bannerPositionUp(Request $request)
    {
        $positionToApply = DB::table('banners')
            ->select('*')
            ->where('position', '<', $request->position)
            ->orderBy('position', 'DESC')
            ->take(1)->get();

        DB::table('banners')
            ->where('id', $request->id)
            ->update([
                'position' => $positionToApply[0]->position
            ]);

        DB::table('banners')->where('id', $positionToApply[0]->id)->update([
            'position' => $request->position
        ]);

        return response()->json(['message' => 'Slide position updated!', 'status' => true]);
    }
    public function bannerPositionDown(Request $request)
    {
        $positionToApply = DB::table('banners')
            ->select('*')
            ->where('position', '>', $request->position)
            ->orderBy('position', 'ASC')
            ->take(1)->get();

        DB::table('banners')
            ->where('id', $request->id)
            ->update([
                'position' => $positionToApply[0]->position
            ]);

        DB::table('banners')->where('id', $positionToApply[0]->id)->update([
            'position' => $request->position
        ]);

        return response()->json(['message' => 'Slide position updated!', 'status' => true]);
    }
    public function bannerDestroy(Request $request)
    {
        $banner = DB::table('banners')->where('id', $request->id)->first();
        try {
            unlink(storage_path('/app/public/banners/' . $banner->image));
        } catch (\Throwable $th) {
        }
        DB::table('banners')->where('id', $request->id)->delete();
        return response()->json(['message' => 'Slide Deleted!', 'status' => true]);
    }

    // Banners Ends Here 


    // Recommended Products Starts Here

    public function indexRecommendedProducts()
    {
        $recommended_products = json_decode(DB::table('home_page_caches')->first()->recommended_products);
        return response()->json($recommended_products);
    }
    public function storeRecommended(Request $request)
    {
        $recommended_products = json_decode(DB::table('home_page_caches')->first()->recommended_products);
        $updatedData = [];

        $imagesFolder = 'recommended_product_images/';
        if (!file_exists(storage_path('app/public/' . $imagesFolder))) {
            mkdir(storage_path('app/public/' . $imagesFolder), 755, true);
        }
        // Store Image
        $file = $request->file('image');
        if ($file) {
            $fileNameToStore = Str::random(10) . '-' . rand(1, 10) . '-' . time() . '.' . $file->extension();
            $saveImage = Storage::disk('public')->put('recommended_product_images/' . $fileNameToStore, file_get_contents($file));
            // $saveImage = $this->resizeImage($file, $fileNameToStore);
            if ($saveImage) {
                foreach ($recommended_products as $recommended_product) {
                    if ($recommended_product->id == $request->id) {
                        try {
                            unlink(storage_path('/app/public/recommended_product_images/' . $recommended_product->image));
                        } catch (Exception $e) {
                        }
                        $recommended_product->image = $fileNameToStore;
                        $recommended_product->link = $request->url;
                    }
                    array_push($updatedData, $recommended_product);
                };
                DB::table('home_page_caches')->update([
                    'recommended_products' => json_encode($updatedData)
                ]);
                return response()->json(['message' => 'Recommended product for position <b> ' . $request->id . ' </b> has been updated!', 'status' => true]);
            }
        } else {
            foreach ($recommended_products as $recommended_product) {
                if ($recommended_product->id == $request->id) {
                    $recommended_product->link = $request->url;
                }
                array_push($updatedData, $recommended_product);
            };
            DB::table('home_page_caches')->update([
                'recommended_products' => json_encode($updatedData)
            ]);
            return response()->json(['message' => 'Recommended product for position <b> ' . $request->id . ' </b> has been updated!', 'status' => true]);
        }
        return response()->json(['message' => 'Something Went Wrong!', 'status' => false]);
    }

    // Recommended Products Ends Here 
}
