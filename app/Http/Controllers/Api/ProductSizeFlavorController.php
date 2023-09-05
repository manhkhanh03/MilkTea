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

class ProductSizeFlavorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function handle_info_flavor(Request $request) {
        if ($request['request']) {
            $new_request = [
                'name' => $request['flavor'],
                'type' => $request['type']
            ];
            $fla = Flavor::create($new_request);
            return $fla['id'];
        }
        
        $flavor = Flavor::where('name', $request->flavor)->first();
        return $flavor->id;
    }

    public function handle_info_size(Request $request) { 
        $size = Size::where('name', $request->size)->first();
        return $size->id;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data_product = [
            'product_id' => $request->product_id, 
            'flavor_id' => $this->handle_info_flavor($request),
            'size_id' => $this->handle_info_size($request),
            'price' => $request->price,
        ];
        
        $product_size_flavor = ProductSizeFlavor::create($data_product);
        return response()->json($product_size_flavor, 200);
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
    public function destroy(string $id)
    {
        $psfCollection = ProductSizeFlavor::where('product_id', $id)->get();
        foreach ($psfCollection as $psf) {
            $psf->delete();
        }
        return response()->json($psf, 200);
    }
}
