<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use App\Models\Flavor;
use App\Models\Size;
use App\Models\Shop;
use App\Models\ProductSizeFlavor;
use App\Models\DiscountCodeHasProduct;
use App\Models\DiscountCode;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::join('product_size_flavors', 'products.id', '=', 'product_size_flavors.product_id')
            ->select('products.id', 'sales', 'products.name', 'shop_id')
            ->groupBy('products.id', 'sales', 'products.name', 'shop_id')
            ->get();

        $products = json_decode($products, true);

        foreach ($products as &$value) {
            $prices = ProductSizeFlavor::where('product_id', $value['id'])
                ->select('price')
                ->get();
            $images = ProductImage::where('product_id', $value['id'])
                ->select('url')
                ->get();

            $sold = ProductSizeFlavor::join('orders', 'product_size_flavors.id', '=', 'orders.product_size_flavor_id')
                ->where('product_id', $value['id'])
                ->selectRaw('count(product_id) as total')
                ->get();

            $user = User::where('id', '=', $value['shop_id'])->first();
            $value['user'] = $user;
            $value['prices'] = $prices;
            $value['images'] = $images;
            $value['sold'] = $sold;
        }
        return response()->json($products, 200, ['OK']);
    }

    public function totalProduct() {
        $total = Product::where('status', 'confirmed')->selectRaw('count(id)')
            ->get();
        return $total;
    }

    public function handleGetProduct(Request $request) {
        $limit = $request->per_page;
        $offset = ($request->page - 1) * $request->per_page;
        $products = Product::join('product_size_flavors', 'products.id', '=', 'product_size_flavors.product_id')
            ->where('products.status', 'confirmed')
            ->select('products.id', 'products.name', 'shop_id')
            ->groupBy('products.id', 'products.name', 'shop_id')
            ->offset($offset)->limit($limit)
            ->get();
        $products = json_decode($products, true);

        foreach ($products as $index => &$value) {
            $prices = ProductSizeFlavor::where('product_id', $value['id'])
                ->select('price')
                ->orderBy('price', 'asc')
                ->get();

            $sold = ProductSizeFlavor::join('orders', 'product_size_flavors.id', '=', 'orders.product_size_flavor_id')
                ->where('product_id', $value['id'])
                ->selectRaw('count(product_id) as total')
                ->get();

            $image = ProductImage::where('product_id', $value['id'])
                ->first('url');
            if (empty($image)) {
                unset($products[$index]);
                continue;
            }

            $shop = Shop::find($value['shop_id']);
            $value['shop'] = $shop;
            $value['prices'] = $prices;
            $value['image'] = $image['url'];
            $value['sold'] = $sold;
        }
        return response()->json([$products, $this->totalProduct(), $request->page], 200, ['OK']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $product = Product::create($request->all());
        return response()->json($product, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::where('products.id', '=', $id)->first();
        $sizes = ProductSizeFlavor::join('sizes', 'product_size_flavors.size_id', '=', 'sizes.id')
            ->where('product_id', '=', $id)
            ->select('product_id', 'size_id', 'price', 'name', 'size')
            ->orderBy('price', 'desc')
            ->get();
        $user = User::where('id', '=', $product->shop_id)->first();
        $flavors = ProductSizeFlavor::join('flavors', 'product_size_flavors.flavor_id', '=', 'flavors.id')
            ->where('product_id', '=', $id)
            ->groupBy('product_id', 'flavor_id', 'flavors.name', 'type')
            ->select('product_id', 'flavor_id', 'flavors.name', 'type')
            ->get();

        $images = ProductImage::where('product_id', $id)
            ->select('url')
            ->get();

        $sold = ProductSizeFlavor::join('orders', 'product_size_flavors.id', '=', 'orders.product_size_flavor_id')
            ->where('product_id', $id)
            ->selectRaw('count(product_id) as total')
            ->get();

        $current_date = date('Y-m-d H:i:s');
        $discount_codes = DiscountCodeHasProduct::join('discount_codes', 'discount_code_has_products.discount_code_id',
            '=', 'discount_codes.id')
            ->where('product_id', $id)
            ->where('start_date', '<=', $current_date)
            ->where('end_date', '>=', $current_date)
            ->select('type_discount_amount', 'discount_amount', 'discount_codes.id')
            ->get();

        $web_discount_codes = DiscountCode::where('start_date', '<=', $current_date)
            ->where('end_date', '>=', $current_date)
            ->select('type_discount_amount', 'discount_amount')
            ->get();

        // A collection includes a key and a value.
        $collection = collect(['sizes' => $sizes]);
        $collection->put('user', $user);
        $collection->put('flavors', $flavors);
        $collection->put('images', $images);
        $collection->put('sold', $sold);
        $collection->put('shop_discount_codes', $discount_codes);
        $collection->put('web_discount_codes', $web_discount_codes);
        $result = $collection->merge($product);

        return response()->json($result, 200, ['OK']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::find($id);
        $product->update($request->all());
        return response()->json($product, 200);
    }

    public function update_all_info(Request $request, string $id) {
        $product = "......";
    }

    public function show_all_info(Request $request) {
        $product = Product::where('id', $request->product)
            ->where('shop_id', $request->shop_id)
            ->select('id as product_id', 'quantity as product_quantity', 'name as product_name')
            ->first();
        if (!empty($product)) {
            $psf = ProductSizeFlavor::join('flavors', 'product_size_flavors.flavor_id', '=', 'flavors.id')
                ->join('sizes', 'product_size_flavors.size_id', '=', 'sizes.id')
                ->where('product_id', $request->product)
                ->select('size_id', 'flavor_id', 'sizes.name as size_name', 'price',
                    'sizes.size', 'flavors.name as flavor_name', 'flavors.type')
                ->get();

            $psf = json_decode($psf, true);

            $images = ProductImage::where('product_id', $request->product)->select('url', 'id')->get();
            $product['images'] = $images;
            $product['psf'] = $psf;
            return $product;
        }
        return response()->json(['error' => 'Forbidden'], 403);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
