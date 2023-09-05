<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\ProductImage;
use App\Models\DiscountCodeHasProduct;
use App\Models\DiscountCode;
use App\Models\ProductSizeFlavor;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Firebase\JWT\JWT;

class CartController extends Controller
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
        $product = ProductSizeFlavor::where('product_id', $request->product_id)
            ->where('size_id', $request->size_id)
            ->where('flavor_id', $request->flavor_id)
            ->first();
        
        if(empty($product)) {
            return response()->json(['message' => 'Fail'], 200, ['OK']);
        } else {
            $new_product = new Request();
            $new_product->merge(['product_size_flavor_id' => $product->id]);
            $new_product->merge(['customer_id' => $request->user_id]);
            $new_product->merge(['quantity' => $request->quantity]);
            $new_product->merge(['total' => $product->price * $request->quantity]);
            $new_product = Cart::create($new_product->all());
            return response()->json($new_product, 200, ['OK']);
        }
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
       
    }

    public function show_cart(Request $request) {
        $cart = Cart::join('product_size_flavors', 'product_size_flavors.id', '=', 'cart.product_size_flavor_id')
            // ->join('product_images', 'product_images.product_id', '=', 'product_size_flavors.product_id')
            ->join('products', 'products.id', '=', 'product_size_flavors.product_id')
            ->join('sizes', 'sizes.id', '=', 'product_size_flavors.size_id')
            ->join('flavors', 'flavors.id', '=', 'product_size_flavors.flavor_id')
            ->where('customer_id', $request->customer_id)
            ->groupBy('products.id', 'product_size_flavors.id', 'cart.id', 'products.name', 'total', 'price', 'size_id', 'flavor_id', 'cart.quantity'  )
            ->select('products.id', 'product_size_flavors.id as product_size_flavor_id', 'cart.id as cart_id', 'products.name as product_name', 'total', 'price', 'sizes.name as size_name', 'flavors.name as flavor_name', 'cart.quantity' )
            ->get();
            
        $cart = json_decode($cart, true);
        $current_date = date('Y-m-d H:i:s');
        foreach ($cart as &$product) {
            $images = ProductImage::where('product_id', $product['id'])->first();
            $discount_codes = DiscountCodeHasProduct::join('discount_codes', 'discount_code_has_products.discount_code_id', 
            '=', 'discount_codes.id')
            ->where('product_id', $product['id'])
            ->where('start_date', '<=', $current_date)
            ->where('end_date', '>=', $current_date)
            ->select('type_discount_amount', 'discount_amount')
            ->get();

            $web_discount_codes = DiscountCode::where('applies_to_all_products', 1)
                ->where('start_date', '<=', $current_date)
                ->where('end_date', '>=', $current_date)
                ->select('type_discount_amount', 'discount_amount')
                ->get();

            $product['discount_code'] = $discount_codes;
            $product['web_discount_code'] = $web_discount_codes;
            $product['url'] = $images['url'];
        }

        return $cart;
        // return response()->json($cart, 200, ['OK']);
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
        $product = Cart::find($id);
        $product->delete();
        return response()->json($product, 200, ['OK']);
    }

    public function handleRequest(Request $request, $data, $key) {
        $product = ProductSizeFlavor::find($data['product_size_flavor_id'][$key]);

        $current_date = date('Y-m-d H:i:s');
        $discount_codes = DiscountCodeHasProduct::join('discount_codes', 'discount_code_has_products.discount_code_id', 
            '=', 'discount_codes.id')
            ->where('start_date', '<=', $current_date)
            ->where('end_date', '>=', $current_date)
            ->where('discount_code_has_products.product_id', $data['product_id'][$key])
            ->select('type_discount_amount', 'discount_amount')
            ->get();

         $web_discount_codes = DiscountCode::where('applies_to_all_products', 1)
            ->where('start_date', '<=', $current_date)
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

    public function handleDiscount(Request $request, $data, $key) {
        $product = $this->handleRequest($request, $data, $key);
        $discount = 0;
        $web_discount = 0;
        // return $product;
        if (isset($product['discount'][0])) {
            if ($product['discount'][0]->type_discount_amount == '%') {
                $discount = floatval($product['product']->price) * floatval($product['discount'][0]->discount_amount / 100);
            } else {
                $discount = floatval($product['discount'][0]->discount_amount);
            }
        }

        if (isset($product['discount_web'][0])) {
            if ($product['discount_web'][0]->type_discount_amount == '%') {
                $web_discount = floatval($product['product']->price) * floatval($product['discount_web'][0]->discount_amount / 100);
            } else {
                $web_discount = floatval($product['discount_web'][0]->discount_amount);
            }
        }

        return [
            'discount' => $discount, 
            'web_discount' =>$web_discount,
            'quantity' => $data['quantity'][$key],
            'delivery' => $request->delivery,
            'product_id' => $data['product_id'][$key],
            'product_size_flavor_id' => $data['product_size_flavor_id'][$key],
            'price' => floatval($data['price'][$key]),
            'user_id' => $request->user_id,
            'cart_id' => $data['cart_id'][$key],
        ];
    }

      // Cart
    public function createTokenCart(Request $request) {
        $data = $request->only([
            'product_size_flavor_id',
            'product_id',
            'price',
            'quantity',
            'total',
            'cart_id',
        ]);

        $result = [];

        foreach($data['product_id'] as $key => $product) {
            array_push($result,  $this->handleDiscount($request, $data, $key));
        }

        try {
            $secretKey = config('jwt.secret');
            $payload = array(
                'iss' => 'manhkhanh',
                'iat' => time(),
                'exp' => time() + 3600,
                'nbf' => time(),
                'sub' => $request->user_id,
                'jti' => 'your_jti',
                'products' => serialize($result),
            );

            // Storage::put('products.txt', serialize($products));

            $token = JWT::encode($payload, $secretKey, 'HS256');
            return response()->json(['status' => 'OK'], 200, ['OK'])->header('Authorization', 'Bearer ' . $token)
                ->withCookie(Cookie::make('info_order', $token));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function decodeTokenCart(Request $request) {
        $token = $request->Cookie('info_order');
        try {
            $payload  = JWTAuth::setToken($token)->getPayload();
            $products = unserialize($payload->get('products'));
            return response()->json($products, 200);
        }catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
