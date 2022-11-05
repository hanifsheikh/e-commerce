<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\CategoryCache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        // $this->middleware('HasPermission:category-view')->only(['fetchCategories']);
        $this->middleware('HasPermission:category-create')->only('store');
        $this->middleware('HasPermission:category-update')->only('update');
        $this->middleware('HasPermission:category-delete')->only('destroy');
    }

    public function fetchCategories()
    {
        // Fetching data from Category Cache Table for performance. 
        $categories = CategoryCache::all();
        return response()->json($categories);
    }

    public function store(Request $request)
    {
        // return response()->json($request->all());
        $imagesFolder = 'category_images/';
        $thumbnailFolder = 'category_images/thumbnails/';
        if (!file_exists(storage_path('app/public/' . $imagesFolder))) {
            mkdir(storage_path('app/public/' . $imagesFolder), 755, true);
        }
        if (!file_exists(storage_path('app/public/' . $thumbnailFolder))) {
            mkdir(storage_path('app/public/' . $thumbnailFolder), 755, true);
        }
        // Store Image
        $file = $request->file('image');
        $icon = $request->file('icon');
        if ($file || $icon) {
            if ($icon && $file) {
                $fileNameToStore = $request->category_name . '-' . time() . '.png';
                $saveImage = $this->resizeImage($file, $fileNameToStore);
                $thumbnailToStore = $request->category_name . '-' . time() . '-icon.png';
                $saveThumbnail = $this->resizeThumbnail($icon, $thumbnailToStore);
                if ($saveImage && $saveThumbnail) {
                    $category = Category::where('category_name', '=', $request->category_name)->get();
                    if (!count($category)) {
                        //Creating the Category Entry
                        $category = Category::create([
                            'category_name' => $request->category_name,
                            'category_url' => ($request->parent_id != 'undefined' && $request->parent_id) ? $request->parent_url . '/' . Str::slug($request->category_name) : Str::slug($request->category_name),
                            "parent_id" => ($request->parent_id != 'undefined' && $request->parent_id) ? $request->parent_id : null,
                            "category_image" => $fileNameToStore,
                            "category_thumbnail" => $thumbnailToStore,
                        ]);
                        $this->refreshCache();
                        return response()->json(["message" => "Category <b>" .  $category->category_name . "</b> has been created!", 'status' => true]);
                    }
                }
            } else if ($icon) {
                $thumbnailToStore = $request->category_name . '-' . time() . '-icon.png';
                $saveThumbnail = $this->resizeThumbnail($icon, $thumbnailToStore);
                if ($saveThumbnail) {
                    $category = Category::where('category_name', '=', $request->category_name)->get();
                    if (!count($category)) {
                        //Creating the Category Entry
                        $category = Category::create([
                            'category_name' => $request->category_name,
                            'category_url' => ($request->parent_id != 'undefined' && $request->parent_id) ? $request->parent_url . '/' . Str::slug($request->category_name) : Str::slug($request->category_name),
                            "parent_id" => ($request->parent_id != 'undefined' && $request->parent_id) ? $request->parent_id : null,
                            "category_image" => 'no_image.png',
                            "category_thumbnail" => $thumbnailToStore,
                        ]);
                        $this->refreshCache();
                        return response()->json(["message" => "Category <b>" .  $category->category_name . "</b> has been created!", 'status' => true]);
                    }
                }
            } else {
                $fileNameToStore = $request->category_name . '-' . time() . '.png';
                $saveImage = $this->resizeImage($file, $fileNameToStore);
                if ($saveImage) {
                    $category = Category::where('category_name', '=', $request->category_name)->get();
                    if (!count($category)) {
                        //Creating the Category Entry
                        $category = Category::create([
                            'category_name' => $request->category_name,
                            'category_url' => ($request->parent_id != 'undefined' && $request->parent_id) ? $request->parent_url . '/' . Str::slug($request->category_name) : Str::slug($request->category_name),
                            "parent_id" => ($request->parent_id != 'undefined' && $request->parent_id) ? $request->parent_id : null,
                            "category_image" => $fileNameToStore,
                            "category_thumbnail" => 'no_image.png',
                        ]);
                        $this->refreshCache();
                        return response()->json(["message" => "Category <b>" .  $category->category_name . "</b> has been created!", 'status' => true]);
                    }
                }
            }
        } else {
            $category = Category::where('category_name', '=', $request->category_name)->get();
            if (!count($category)) {
                //Creating the Category Entry
                $category = Category::create([
                    'category_name' => $request->category_name,
                    'category_url' => ($request->parent_id != 'undefined' && $request->parent_id) ? $request->parent_url . '/' . Str::slug($request->category_name) : Str::slug($request->category_name),
                    "parent_id" => ($request->parent_id != 'undefined' && $request->parent_id) ? $request->parent_id : null,
                    "category_image" => "no_image.png",
                    "category_thumbnail" => "no_image.png",
                ]);
                $this->refreshCache();
                return response()->json(["message" => "Category <b>" .  $category->category_name . "</b> has been created!", "status" => true]);
            }
        }
        return response()->json(["message" => "Category <b>" . $request->category_name . "</b> already exist.", "status" => false]);
    }
    public function resizeImage($file, $fileNameToStore)
    {
        // Resize image
        $resizedContent = Image::make($file)->orientate()->resize(1024, null, function ($constraint) {
            $constraint->upsize();
            $constraint->aspectRatio();
        })->encode('png');
        // Put image to storage 
        $save = Storage::disk('public')->put('category_images/' . $fileNameToStore, $resizedContent->__toString());

        if ($save) {
            return true;
        }
        return false;
    }
    public function resizeThumbnail($file, $thumbnailToStore)
    {
        // Resize thumbnail
        $resizedContent = Image::make($file)->orientate()->resize(128, null, function ($constraint) {
            $constraint->upsize();
            $constraint->aspectRatio();
        })->encode('png');
        // Put thumbnail to storage 
        $save = Storage::disk('public')->put('category_images/thumbnails/' . $thumbnailToStore, $resizedContent->__toString());

        if ($save) {
            return true;
        }
        return false;
    }
    public function update(Request $request)
    {
        $category = Category::where('category_name', '=', $request->category_name)->where('id', '!=', $request->id)->get();
        if (!count($category)) {

            $category = Category::find($request->id)->update([
                'category_name' => $request->category_name,
                'parent_id' => $request->parent_id
            ]);
            $this->refreshCache();
            return response()->json(["message" => "Category <b>" . $request->category_name  . "</b> has been updated!", "status" => true]);
        }
        return response()->json(["message" => "Category <b>" . $request->category_name . "</b> already exist!", "status" => false]);
    }

    public function refreshCache()
    {
        // Fetch All categories with their childrens
        $categories = Category::with(['childrens' => function ($query) {
            return $query->with(['childrens' => function ($q) {
                return $q->with('parent')->get();
            }, 'parent'])->get();
        }])->where('parent_id', null)->get();

        // Parent Array 
        $parents = [];

        // Loop through each categories and put in the $parents[] Array by key => value pair.
        foreach ($categories as $category) {
            $parents[$category->category_name] = [
                'id' => $category->id,
                'category_url' => $category->category_url,
                'category_image' => $category->category_image,
                'category_thumbnail' => $category->category_thumbnail,
                'childrens' => $category->childrens,
            ];
        }
        //Sort Parents Array by Key.
        ksort($parents);

        // Put the Array elements into 'category_caches' table this will load every time user requests. 
        DB::table('category_caches')->truncate();
        foreach ($parents as $parent => $childrens) {
            CategoryCache::create([
                'category_name' => $parent,
                'id' => $childrens['id'],
                'category_url' => $childrens['category_url'],
                'category_image' => $childrens['category_image'],
                'category_thumbnail' => $childrens['category_thumbnail'],
                'childrens' => $childrens['childrens']
            ]);
        }
    }
    public function destroy(Request $request)
    {
        $category = Category::find($request->id);
        $product = DB::table('products')->where('category_id', $request->id)->first();

        if ($category) {
            $category_name = $category->category_name;
            if ($category->haveChild) {
                return response()->json(['message' => "Category <b>" . $category_name . "</b> have childrens.", "status" => false]);
            } elseif ($product) {
                return response()->json(['message' => "Category <b>" . $category_name . "</b> have products.", "status" => false]);
            }
            $category->delete();
            if ($category->category_image && $category->category_image != 'no_image.png') {
                try {
                    unlink(storage_path('/app/public/category_images/' . $category->category_image));
                } catch (\Throwable $th) {
                }
            }
            if ($category->category_thumbnail && $category->category_thumbnail != 'no_image.png') {
                try {
                    unlink(storage_path('/app/public/category_images/thumbnails/' . $category->category_thumbnail));
                } catch (\Throwable $th) {
                }
            }
            $this->refreshCache();
            return response()->json(['message' => "Category <b>" . $category_name . "</b> has been deleted.", "status" => true]);
        }
        return response()->json(['message' =>  "Already deleted.", "status" => false]);
    }
}
