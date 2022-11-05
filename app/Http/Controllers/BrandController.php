<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('HasPermission:brand-view')->only(['fetchBrands', 'searchBrands']);
        $this->middleware('HasPermission:brand-create')->only('store');
        $this->middleware('HasPermission:brand-update')->only('update');
        $this->middleware('HasPermission:brand-delete')->only('destroy');
    }

    public function fetchBrands()
    {
        $brands = DB::table('brands')->orderBy('id', 'DESC')->get();
        return response()->json($brands);
    }
    public function searchBrands(Request $request)
    {
        $brands = DB::table('brands')->where('brand_name', 'like', '%' . $request->searchString . '%')->orderBy('id', 'DESC')->get();
        return response()->json($brands);
    }
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'brand_name' => 'required|unique:brands,brand_name,' . $request->id . '|max:100',
                'image' => "required|mimes:jpeg,jpg,png,gif|max:2000",
            ],
            [
                'brand_name.required' => 'Brand name is required',
                'brand_name.unique' => 'Brand name has been taken.',
                'brand.max' => 'Brand name maximum character exceeded',
                'image.mimes' => 'Unsupported Image format. (supported formats are jpeg,jpg,png,gif)',
                'image.max' => 'Brand Logo should not be more than 2MB',
                'image.required' => 'Brand Logo is required',
            ]
        );
        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages(), 'status' => false]);
        }

        $imagesFolder = 'brand_images/';
        if (!file_exists(storage_path('app/public/' . $imagesFolder))) {
            mkdir(storage_path('app/public/' . $imagesFolder), 755, true);
        }
        // Store Image
        $file = $request->file('image');
        if ($file) {
            $fileNameToStore = Str::slug($request->brand_name) . '-' . time() . '.webp';
            $saveImage = $this->resizeImage($file, $fileNameToStore);
            if ($saveImage) {
                Brand::create([
                    'brand_name' => $request->brand_name,
                    'slug' => Str::slug($request->brand_name),
                    "brand_logo" => $fileNameToStore,
                ]);
                $this->refreshBrandCache();
                return response()->json(['status' => true, 'message' => 'Brand has been created successfully!']);
            }
        }
        return response()->json(['status' => false, 'message' => 'Something went wrong!']);
    }
    public function resizeImage($file, $fileNameToStore)
    {
        // Resize image
        $resizedContent = Image::make($file)->orientate()->resize(256, null, function ($constraint) {
            $constraint->upsize();
            $constraint->aspectRatio();
        })->encode('webp');
        // Put image to storage 
        $save = Storage::disk('public')->put('brand_images/' . $fileNameToStore, $resizedContent->__toString());

        if ($save) {
            return true;
        }
        return false;
    }
    public function destroy(Request $request)
    {
        $brand = Brand::where('id', $request->id)->first();
        if ($brand) {
            $product_exists = Product::where('brand_id', $brand->id)->first();
            if ($product_exists) {
                return response()->json(['status' => false, 'message' =>  'Brand ' . $brand->brand_name . ' has Products!']);
            }
            if ($brand->brand_logo && $brand->brand_logo != 'no_image.png') {
                try {
                    unlink(storage_path('/app/public/brand_images/' . $brand->brand_logo));
                } catch (\Throwable $th) {
                }
            }
            $brand->delete();
            $this->refreshBrandCache();
            return response()->json(['status' => true, 'message' => 'Brand has been deleted successfully!']);
        }
        return response()->json(['status' => false, 'message' => 'Brand already deleted!']);
    }
    public function update(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'brand_name' => 'required|unique:brands,brand_name,' . $request->id . '|max:100',
                'image' => "nullable|mimes:jpeg,jpg,png,gif|max:2000",
            ],
            [
                'brand_name.required' => 'Brand name is required',
                'brand_name.unique' => 'Brand name has been taken.',
                'brand.max' => 'Brand name maximum character exceeded',
                'image.mimes' => 'Unsupported Image format. (supported formats are jpeg,jpg,png,gif)',
                'image.max' => 'Logo should not be more than 2MB',
            ]
        );
        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages(), 'status' => false]);
        }
        $file = $request->file('image');
        if ($file) {
            $fileNameToStore = Str::slug($request->brand_name) . '-' . time() . '.webp';
            $saveImage = $this->resizeImage($file, $fileNameToStore);
            if ($saveImage) {
                $brand = Brand::where('id', $request->id)->first();
                if ($brand->brand_logo && $brand->brand_logo != 'no_image.png') {
                    try {
                        unlink(storage_path('/app/public/brand_images/' . $brand->brand_logo));
                    } catch (\Throwable $th) {
                    }
                }
                $brand->update([
                    'brand_name' => $request->brand_name,
                    'slug' => Str::slug($request->brand_name),
                    "brand_logo" => $fileNameToStore,
                ]);

                $this->refreshBrandCache();
                return response()->json(['status' => true, 'message' => 'Brand has been updated successfully!']);
            }
        } else {
            Brand::where('id', $request->id)->update([
                'brand_name' => $request->brand_name,
                'slug' => Str::slug($request->brand_name)
            ]);
            $this->refreshBrandCache();
            return response()->json(['status' => true, 'message' => 'Brand has been updated successfully!']);
        }
        return response()->json(['status' => false, 'message' => ['error' => ['Something went wrong!']]]);
    }
    public function refreshBrandCache()
    {
        $brands = DB::table('brands')->orderBy('id', 'DESC')->get()->take(24);
        DB::table('home_page_caches')->update([
            'brands' => json_encode($brands)
        ]);
    }
}
