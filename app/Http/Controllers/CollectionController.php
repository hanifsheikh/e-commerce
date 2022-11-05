<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CollectionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('HasPermission:collection-view')->only(['fetchCollections', 'searchCollections', 'fetchCollectionProducts']);
        $this->middleware('HasPermission:collection-create')->only('store');
        $this->middleware('HasPermission:collection-update')->only('update', 'removeProduct', 'addProduct', 'searchProduct');
        $this->middleware('HasPermission:collection-delete')->only('destroy');
    }
    public function fetchCollections()
    {
        $collections = Collection::orderBy('id', 'DESC')->get();
        return response()->json(['collections' => $collections]);
    }
    public function fetchCollectionProducts(Request $request)
    {
        $collectionProducts = [];
        $collectionProductIDs = DB::table('collection_products')->where('collection_id', $request->id)->pluck('product_id');
        if (count($collectionProductIDs)) {
            $collectionProducts = Product::whereIn('id', $collectionProductIDs)
                ->with(['seller', 'image' => function ($query) {
                    return $query->where('position', 1)->orderBy('id', 'DESC');
                }])
                ->get();
        }
        return response()->json(['collectionProducts' => $collectionProducts]);
    }
    public function searchCollections(Request $request)
    {
        $collections = Collection::where('collection_title', 'like', '%' . $request->searchString . '%')->orWhere('slug', 'like', '%' . $request->searchString . '%')->orderBy('id', 'DESC')->get();
        return response()->json(['collections' => $collections]);
    }

    public function store(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'collection_title' => 'required|unique:collections|max:255',
                'image' => "required|mimes:jpeg,jpg,png,gif|max:2000",
            ],
            [
                'collection_title.required' => 'Collection title is required',
                'collection_title.unique' => 'Collection title has been taken.',
                'collection_title.max' => 'Collection title maximum character exceeded',
                'image.mimes' => 'Unsupported Image format. (supported formats are jpeg,jpg,png,gif)',
                'image.max' => 'Collection Image should not be more than 2MB',
            ]
        );
        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages(), 'status' => false]);
        }
        $image = $request->file('image');
        $fileNameToStore = Str::slug($request->collection_title) . '-' . time() . '.webp';
        $saveImage = $this->resizeImage($image, $fileNameToStore);
        if ($saveImage) {
            Collection::create([
                'collection_title' => $request->collection_title,
                'slug' => Str::slug($request->collection_title),
                'collection_image' => $fileNameToStore
            ]);
            $this->refreshCollectionCache();
            return response()->json(['status' => true, 'message' => 'Collection has been created.']);
        }
        return response()->json(['status' => false, 'message' => 'Something went wrong.']);
    }
    public function searchProduct(Request $request)
    {
        $collectionProductIDs = DB::table('collection_products')->where('collection_id', $request->collection_id)->pluck('product_id');
        $products = Product::where('product_title', 'like', '%' . $request->searchString . '%')->whereNotIn('id', $collectionProductIDs)->with(['seller', 'image' => function ($query) {
            return $query->where('position', 1)->orderBy('id', 'DESC');
        }])->paginate(20);
        return response()->json($products);
    }
    public function resizeImage($file, $fileNameToStore)
    {
        // Resize image
        $resizedContent = Image::make($file)->orientate()->resize(720, null, function ($constraint) {
            $constraint->upsize();
            $constraint->aspectRatio();
        })->encode('webp', 75);
        // Put image to storage 
        $save = Storage::disk('public')->put('collection_images/' . $fileNameToStore, $resizedContent->__toString());

        if ($save) {
            return true;
        }
        return false;
    }
    public function update(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'collection_title' => 'required|unique:collections,collection_title,' . $request->id . '|max:255',
                'image' => "nullable|mimes:jpeg,jpg,png,gif|max:2000",
            ],
            [
                'collection_title.required' => 'Collection title is required',
                'collection_title.max' => 'Collection title maximum character exceeded',
                'collection_title.unique' => 'Collection title has been taken',
                'image.mimes' => 'Unsupported Image format. (supported formats are jpeg,jpg,png,gif)',
                'image.max' => 'Collection Image should not be more than 2MB',
            ]
        );
        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages(), 'status' => false]);
        }
        $image = $request->file('image');
        if ($image) {
            $fileNameToStore = Str::slug($request->collection_title) . '-' . time() . '.webp';
            $saveImage = $this->resizeImage($image, $fileNameToStore);
            if ($saveImage) {
                $collection =  Collection::where('id', $request->id)->first();
                // Delete Previous Photo 
                try {
                    unlink(storage_path('/app/public/collection_images/' . $collection->collection_image));
                } catch (\Throwable $th) {
                }
                $collection->update([
                    'collection_title' => $request->collection_title,
                    'slug' => Str::slug($request->collection_title),
                    'collection_image' => $fileNameToStore
                ]);
                $this->refreshCollectionCache();
                return response()->json(['status' => true, 'message' => 'Collection has been updated.']);
            }
        } else {
            Collection::where('id', $request->id)->update([
                'collection_title' => $request->collection_title,
                'slug' => Str::slug($request->collection_title)
            ]);
            $this->refreshCollectionCache();
            return response()->json(['status' => true, 'message' => 'Collection title has been updated.']);
        }
        return response()->json(['status' => false, 'message' => 'Something went wrong!']);
    }
    public function destroy(Request $request)
    {
        $collection = Collection::where('id', $request->id)->first();
        if ($collection) {
            DB::table('collection_products')->where('collection_id', $collection->id)->delete();
            try {
                unlink(storage_path('/app/public/collection_images/' . $collection->collection_image));
            } catch (\Throwable $th) {
            }
            $collection->delete();
            $this->refreshCollectionCache();
            return response()->json(['status' => true, 'message' => 'Collection has been deleted successfully!']);
        }
        return response()->json(['status' => false, 'message' => 'Collection already deleted!']);
    }
    public function refreshCollectionCache()
    {
        $collections = DB::table('collections')->orderBy('id', 'DESC')->get()->take(10);
        DB::table('home_page_caches')->update([
            'collections' => json_encode($collections)
        ]);
    }
    public function removeProduct(Request $request)
    {
        $collection = Collection::where('id', $request->collection_id)->first();
        if ($collection) {
            $collection_products =  DB::table('collection_products')->where('product_id', $request->product_id)
                ->where('collection_id', $request->collection_id)->get();
            if (count($collection_products)) {
                DB::table('collection_products')->where('product_id', $request->product_id)
                    ->where('collection_id', $request->collection_id)->delete();
                return response()->json(['status' => true, 'message' => 'Product has been deleted from collection successfully!']);
            } else {
                return response()->json(['status' => false, 'message' => 'Product is not available in this collection!']);
            }
        }
        return response()->json(['status' => false, 'message' => 'Collection is not available!']);
    }
    public function addProduct(Request $request)
    {
        $collection = Collection::where('id', $request->collection_id)->first();
        if ($collection) {
            $collection_product =  DB::table('collection_products')->where('product_id', $request->product_id)
                ->where('collection_id', $request->collection_id)->get();
            if (count($collection_product)) {
                return response()->json(['status' => false, 'message' => 'Product is already exist in this collection!']);
            } else {
                DB::table('collection_products')->insert([
                    'collection_id' => $request->collection_id,
                    'product_id' => $request->product_id
                ]);
                return response()->json(['status' => true, 'message' => 'Product has been added to collection!']);
            }
        }
        return response()->json(['status' => false, 'message' => 'Collection is not available!']);
    }
}
