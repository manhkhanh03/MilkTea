<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\ProductImage;
use App\Models\DiscountCodeHasProduct;
use App\Models\DiscountCode;
use App\Models\Flavor;

class FlavorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

    }

    public function get(Request $request) {
        $flavors = Flavor::where('type', $request->type)->get();
        return $flavors;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $flavor = Flavor::create($request->all());
        return response()->json($flavor, 200);
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
        //
    }
}
