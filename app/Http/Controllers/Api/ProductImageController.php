<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use App\Models\ProductSizeFlavor;
use App\Models\DiscountCodeHasProduct;
use App\Models\Flavor;
use App\Models\Size;

class ProductImageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $image = ProductImage::create($request->all());
        return response()->json($image, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        return response()->json(['message: ' => $request], 200);
        try {
            foreach($request as $id) {
                return response()->json(['message: ' => $id], 200);
                // $img = ProductImage::find($id);
                // $img->delete();
            }
            return response()->json(['message: ' => 'Successfully'], 200);
        }
        catch(\Exception $error) {
            return response()->json(['error: ' => $error->getMessage()], 400);
        }
    }

    public function delete(Request $request)
    {
        try {
            foreach($request->ids as $id) {
                $img = ProductImage::find($id);
                $img->delete();
            }
            return response()->json(['message: ' => 'Successfully'], 200);
        }
        catch(\Exception $error) {
            return response()->json(['error: ' => $error->getMessage()], 400);
        }
    }
}
