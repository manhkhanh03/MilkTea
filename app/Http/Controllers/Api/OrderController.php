<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Storage;
use App\Models\DiscountCodeHasProduct;
use App\Models\DiscountCode;
use App\Models\ProductSizeFlavor;
use App\Models\Order;
use App\Models\ProductImage;

class OrderController extends Controller
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
        $product = Order::create($request->all());
        return response()->json($product, 200, ['OK']);
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

    public function handleRequest(Request $request) {
        $product = ProductSizeFlavor::where('size_id', $request->size_id)
            ->where('flavor_id', $request->flavor_id)
            ->where('product_id', $request->product_id)
            ->select('id', 'product_id', 'flavor_id', 'size_id', 'price')
            ->get();

        $current_date = date('Y-m-d H:i:s');
        $discount_codes = DiscountCodeHasProduct::join('discount_codes', 'discount_code_has_products.discount_code_id', 
            '=', 'discount_codes.id')
            ->where('discount_codes.id', $request->discount_id)
            ->select('type_discount_amount', 'discount_amount')
            ->get();

         $web_discount_codes = DiscountCode::where('start_date', '<=', $current_date)
            ->where('end_date', '>=', $current_date)
            ->select('type_discount_amount', 'discount_amount')
            ->get();

        $result = [
            'product' => $product,
            'discount' => $discount_codes,
            'discount_web' => $web_discount_codes,
        ];
        return $result;
    }

    public function handleDiscount(Request $request) {
        $product = $this->handleRequest($request);
        $discount = 0;
        $web_discount = 0;
        if (isset($product['discount'][0]) && !empty($product['discount'])) {
            if ($product['discount'][0]->type_discount_amount == '%') {
                $discount = floatval($product['product'][0]->price) * floatval($product['discount'][0]->discount_amount / 100);
            } else {
                $discount = floatval($product['discount'][0]->discount_amount);
            }
        }

        if (isset($product['discount_web'][0]) && !empty($product['discount_web'])) {
            if ($product['discount_web'][0]->type_discount_amount == '%') {
                $web_discount = floatval($product['product'][0]->price) * floatval($product['discount_web'][0]->discount_amount / 100);
            } else {
                $web_discount = floatval($product['discount_web'][0]->discount_amount);
            }
        }


        // return $product;
        return [
            'discount' => $discount, 
            'web_discount' =>$web_discount,
            'quantity' => $request->quantity,
            'delivery' => $request->delivery,
            'product_id' => $request->product_id,
            'product_size_flavor_id' => $product['product'][0]->id,
            'price' => floatval($product['product'][0]->price),
            'user_id' => $request->user_id,
        ];
    }

    public function createTokenOrder(Request $request) {
        $result = $this->handleDiscount($request);

        $secretKey = config('jwt.secret');
        $payload = array(
            'iss' => 'manhkhanh',
            'iat' => time(),
            'exp' => time() + 3600,
            'nbf' => time(),
            'sub' => $request->product_id,
            'jti' => 'your_jti',
            'product' => serialize($result),
        );

        // Storage::put('products.txt', serialize($products));

        $token = JWT::encode($payload, $secretKey, 'HS256');
        return response()->json(['status' => 'OK'], 200, ['OK'])->header('Authorization', 'Bearer ' . $token)->withCookie(Cookie::make('info_order', $token));
    }

    public function decodeTokenOrder(Request $request) {
        $token = $request->Cookie('info_order');
        try {
            $payload  = JWTAuth::setToken($token)->getPayload();
            $product = unserialize($payload->get('product'));
            return response()->json($product, 200);
        }catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
