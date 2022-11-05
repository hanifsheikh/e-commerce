<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MaterialController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('HasPermission:material-view')->only(['fetchMaterials', 'searchMaterials']);
        $this->middleware('HasPermission:material-create')->only('store');
        $this->middleware('HasPermission:material-update')->only('update');
        $this->middleware('HasPermission:material-delete')->only('destroy');
    }
    public function fetchMaterials()
    {
        $materials = Material::orderBy('id', 'DESC')->get();
        return response()->json(['materials' => $materials]);
    }
    public function searchMaterials(Request $request)
    {
        $materials = Material::where('material_title', 'like', '%' . $request->searchString . '%')->orWhere('slug', 'like', '%' . $request->searchString . '%')->orderBy('id', 'DESC')->get();
        return response()->json(['materials' => $materials]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'material_title' => 'required|unique:materials|max:255',
                'image' => "required|mimes:jpeg,jpg,png,gif|max:2000",
                'price' => "required|integer|min:10",
            ],
            [
                'material_title.required' => 'Material title is required',
                'material_title.unique' => 'Material title has been taken.',
                'material_title.max' => 'Material title maximum character exceeded',
                'image.mimes' => 'Unsupported Image format. (supported formats are jpeg,jpg,png,gif)',
                'image.max' => 'Material Image should not be more than 2MB',
                'price.required' => 'Material price is required.',
                'price.integer' => 'Material price is invalid.',
                'price.min' => 'Material price too low.',
            ]
        );
        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages(), 'status' => false]);
        }
        $image = $request->file('image');
        $fileNameToStore = Str::slug($request->material_title) . '-' . time() . '.webp';
        $saveImage = $this->resizeImage($image, $fileNameToStore);
        if ($saveImage) {
            Material::create([
                'material_title' => $request->material_title,
                'slug' => Str::slug($request->material_title),
                'price' => $request->price,
                'material_image' => $fileNameToStore
            ]);

            return response()->json(['status' => true, 'message' => 'Material has been created.']);
        }
        return response()->json(['status' => false, 'message' => 'Something went wrong.']);
    }
    public function resizeImage($file, $fileNameToStore)
    {
        // Resize image
        $resizedContent = Image::make($file)->orientate()->resize(720, null, function ($constraint) {
            $constraint->upsize();
            $constraint->aspectRatio();
        })->encode('webp', 75);
        // Put image to storage 
        $save = Storage::disk('public')->put('material_images/' . $fileNameToStore, $resizedContent->__toString());

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
                'material_title' => 'required|unique:materials,material_title,' . $request->id . '|max:255',
                'image' => "nullable|mimes:jpeg,jpg,png,gif|max:2000",
                'price' => "required|integer|min:10",
            ],
            [
                'material_title.required' => 'Material title is required',
                'material_title.max' => 'Material title maximum character exceeded',
                'material_title.unique' => 'Material title has been taken',
                'image.mimes' => 'Unsupported Image format. (supported formats are jpeg,jpg,png,gif)',
                'image.max' => 'Material Image should not be more than 2MB',
                'price.required' => 'Material price is required.',
                'price.integer' => 'Material price is invalid.',
                'price.min' => 'Material price too low.',
            ]
        );
        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages(), 'status' => false]);
        }
        $image = $request->file('image');
        if ($image) {
            $fileNameToStore = Str::slug($request->material_title) . '-' . time() . '.webp';
            $saveImage = $this->resizeImage($image, $fileNameToStore);
            if ($saveImage) {
                $material =  Material::where('id', $request->id)->first();
                // Delete Previous Photo 
                try {
                    unlink(storage_path('/app/public/material_images/' . $material->material_image));
                } catch (\Throwable $th) {
                }
                $material->update([
                    'material_title' => $request->material_title,
                    'slug' => Str::slug($request->material_title),
                    'price' =>  $request->price,
                    'material_image' => $fileNameToStore
                ]);

                return response()->json(['status' => true, 'message' => 'Material has been updated.']);
            }
        } else {
            Material::where('id', $request->id)->update([
                'material_title' => $request->material_title,
                'slug' => Str::slug($request->material_title),
                'price' =>  $request->price,
            ]);

            return response()->json(['status' => true, 'message' => 'Material title has been updated.']);
        }
        return response()->json(['status' => false, 'message' => 'Something went wrong!']);
    }
    public function destroy(Request $request)
    {
        $material = Material::where('id', $request->id)->first();
        if ($material) {
            try {
                unlink(storage_path('/app/public/material_images/' . $material->material_image));
            } catch (\Throwable $th) {
            }
            $material->delete();
            return response()->json(['status' => true, 'message' => 'Material has been deleted successfully!']);
        }
        return response()->json(['status' => false, 'message' => 'Material already deleted!']);
    }
}
