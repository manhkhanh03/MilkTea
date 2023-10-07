<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DiscountCode;
use App\Models\DiscountCodeHasProduct;

class DiscountCodeController extends Controller
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
        //
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

    public function getByShopId(string $shopId, $status = '') {
        $discount = DiscountCode::where('shop_id', $shopId)
            ->when($status != 'all', function ($q) use ($status) {
                return $q->where('status', $status);
            })
            ->get();
        return $discount;
    }

    public function search(Request $request) {    
        $status = $request->status;    
        $discount = DiscountCode::when(true, function($q) use($request) {
                if ($request->has('code'))
                    return $q->where('code', 'LIKE', '%' . $request->code . '%');
                else return $q->find($request->id);
            })
            ->when($status != 'all', function ($q) use ($status) {
                return $q->where('status', $status);
            })
            ->get();

        return $discount;
    }
}
