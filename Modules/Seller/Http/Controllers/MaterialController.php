<?php

namespace Modules\Seller\Http\Controllers;

use App\Models\Material;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:seller');
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
}
