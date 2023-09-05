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
use App\Models\Product;
use App\Models\ProductView;
use App\Models\TransactionHistory;
use App\Models\ShippingTracking;
use App\Models\ShopView;
use App\Models\Flavor;
use DateTime;
use Carbon\Carbon;
use DB;

class VendorController extends Controller
{
    public function show_orders_vendor(Request $request, $shop_id) {
        $type = str_replace("_", " ", $request->type);
        $startDate = (new DateTime($request->startDate))->format('Y-m-d') . ' 00:00:00';
        $endDate = (new DateTime($request->endDate))->format('Y-m-d') . ' 23:59:00';
        $type_search = $request->type_search;
        $data_search = $request->data_search;

        $products = Order::join('product_size_flavors', 'orders.product_size_flavor_id', '=', 'product_size_flavors.id')
            ->join('products', 'product_size_flavors.product_id', '=', 'products.id')
            ->join('shipping_tracking', 'orders.id', '=', 'shipping_tracking.order_id')
            ->join('flavors', 'product_size_flavors.flavor_id', '=', 'flavors.id')
            ->join('users', 'orders.customer_id', '=', 'users.id')
            ->join('sizes', 'product_size_flavors.size_id', '=', 'sizes.id')
            ->whereBetween('order_date', [$startDate, $endDate])
            ->when(isset($request->type_search), function ($query) use ($type_search, $data_search) {
                if ($type_search == 'customer name')
                    return $query->where('users.user_name', $data_search);
                else if ($type_search == 'order id')
                    return $query->where('orders.id', $data_search);
                else if ($type_search == 'product')
                    return $query->where('products.name', 'like', '%' . $data_search . '%');
                if ($data_search != '') {
                    return $query->where('users.user_name', $data_search)
                        ->orWhere('orders.id', $data_search)
                        ->orWhere('products.name', 'like', '%' .$data_search . '%');
                }
            })
            ->when(isset($request->type), function ($query) use ($type){
                if ($type == 'cancel')
                    return $query->whereIn('shipping_tracking.status', ['Cancelled', 'Cancellation request']);
                return $query->where('shipping_tracking.status', $type);
            })
            ->where('products.shop_id', $shop_id)
            ->select('products.name', 'orders.quantity', 'shipping_tracking.status',
                'order_date', 'shipping_tracking.id as table_shipping_tracking_id', 'shipping_tracking.id as shipping_tracking_id',
                'flavors.name as flavors_name', 'size', 'flavors.type as flavor_type', 'products.id as product_id', 'orders.id as order_id')
            ->get();

        $products = json_decode($products, true);
        foreach ($products as $index => &$product) {
            $image = ProductImage::where('product_id', $product['product_id'])
                ->first();
            if (empty($image)) {
                unset($products[$index]);
                continue;
            }
            $product['image'] = $image->url;
        }

        return $products;
    }

    public function show_products_vendor(Request $request, string $shop_id) {
        $type = str_replace("_", " ", $request->type);
        $type_search = $request->type_search;
        $data_search = $request->data_search;

        $products = Product::join('product_size_flavors', 'products.id', '=', 'product_size_flavors.product_id')
            ->join('flavors', 'product_size_flavors.flavor_id', '=', 'flavors.id')
            ->when(isset($request->type) , function ($query) use ($type) {
                if ($type != 'sold out')
                    return $query->where('products.status', $type);
                return $query->where('quantity', 0);
            })
            ->when(isset($request->type_search), function($query) use ($type_search, $data_search) {
                if ($type_search == 'product id')
                    return $query->where('products.id', $data_search);
                else if ($type_search == 'product')
                    return $query->where('products.name', 'like', '%' . $data_search . '%');
                if ($data_search != '') {
                    return $query->where('products.id', $data_search)
                        ->orWhere('products.name', 'like', '%' . $data_search . '%');
                }
            })
            ->where('products.status', '<>', 'deleted')
            ->where('products.shop_id', $shop_id)
            ->groupBy('products.name', 'quantity', 'status', 'flavors.type', 'products.id')
            ->select('products.name', 'quantity', 'status', 'flavors.type as flavor_type', 'products.id as product_id', 'shop_id')
            ->get();
        // return $products;
        $products = json_decode($products, true);
        foreach ($products as $index => &$product) {
            $image = ProductImage::where('product_id', $product['product_id'])
                ->first();
            if (empty($image)) {
                unset($products[$index]);
                continue;
            }
            $prices = ProductSizeFlavor::where('product_id', $product['product_id'])
                ->select('price')
                ->orderBy('price', 'asc')
                ->get();
            $product['image'] = $image->url;
            $prices = json_decode($prices);
            $product['prices'] = [];
            foreach($prices as $price) {
                array_push($product['prices'], $price->price);
            }
        }

        return $products;
    }

    public function finance(Request $request) {
        $startDate = (new DateTime($request->startDate))->format('Y-m-d') . ' 00:00:00';
        $endDate = (new DateTime($request->endDate))->format('Y-m-d') . ' 23:59:00';

        $detail = Order::join('product_size_flavors', 'orders.product_size_flavor_id', '=', 'product_size_flavors.id')
            ->join('products', 'product_size_flavors.product_id', '=', 'products.id')
            ->join('shipping_tracking', 'orders.id', '=', 'shipping_tracking.order_id')
            ->whereBetween('orders.order_date', [$startDate, $endDate])
            ->where('shipping_tracking.status', 'Delivered')
            ->orWhere('orders.payment_status', 'Paid')
            ->where('shop_id', $request->shop_id)
            ->selectRaw('products.id as product_id, order_date, orders.quantity, payment_method, total, products.name as product_name, products.shop_id')
            ->get();

        // $shop_id = $request->shop_id;
        // $detail = Order::with(['productSizeFlavor.products' => function ($q) {
        //         $q->select(['id', 'name', 'shop_id', 'quantity', 'status']);
        //     }, 'shippingTracking' => function ($q) {
        //         $q->select(['id', 'order_id', 'status', 'delivery_person_id']);
        //     }, 'productSizeFlavor.products.productImage' => function ($q) {
        //         $q->first([]);
        //     }])
        //     ->whereHas('productSizeFlavor.products' ,function ($query) use($shop_id) {
        //         $query->where('shop_id', $shop_id);
        //     })
        //     ->where(function ($q) use($startDate, $endDate) {
        //         $q->whereBetween('order_date', [$startDate, $endDate]);
        //     })
        //     ->where(function ($query) {
        //         $query->orWhere('payment_status', 'Paid');
        //     })
        //     ->orWhereHas('shippingTracking', function ($query) {
        //         $query->where('status', 'Await delivery');
        //     })
        //     ->selectRaw('order_date, orders.quantity, orders.payment_method, payment_status, total, product_size_flavor_id, orders.id')
        //     ->get();
        // return $detail;

        $overview = Order::join('product_size_flavors', 'orders.product_size_flavor_id', '=', 'product_size_flavors.id')
            ->join('products', 'product_size_flavors.product_id', '=', 'products.id')
            ->join('shipping_tracking', 'orders.id', '=', 'shipping_tracking.order_id')
            ->where('shipping_tracking.status', 'Delivered')
            ->orWhere('orders.payment_status', 'Paid')
            ->where('shop_id', $request->shop_id)
            ->selectRaw('total - (total * 0.05) as total_overview, order_date, payment_status, shipping_tracking.status, orders.id  ')
            ->get();

        $total = 0; $mouth = 0; $week = 0;

        $currentMonth = now()->month;
        $currentWeek = now()->weekOfYear;

        foreach($overview as $item) {
            $total += $item->total_overview;
            $orderDateTime = new \DateTime($item->order_date);
            $weekOrder = $orderDateTime->format('W');
            $monthOrder = $orderDateTime->format('n');

            if ($currentMonth == $monthOrder)
                $mouth += $item->total_overview;
            if ($currentWeek == $weekOrder)
                $week += $item->total_overview;
        }

        foreach($detail as $index => $item) {
            $productImage = ProductImage::where('product_id', $item->product_id)->first(['url']);
            if (empty($productImage)) {
                unset($detail[$index]);
                continue;
            }
            $item->url = $productImage->url;
        }

        $data = collect([
            'overview' => collect([
                'total' => $total,
                'mouth' => $mouth,
                'week' => $week,
            ]),
            'detail' => $detail,
        ]);
        return $data;
    }

    public function account_balance(Request $request) {
        $type = $request->type;
        $startDate = (new DateTime($request->startDate))->format('Y-m-d') . ' 00:00:00';
        $endDate = (new DateTime($request->endDate))->format('Y-m-d') . ' 23:59:00';

        $transaction = TransactionHistory::where('executor_id', $request->executor_id)
            ->orWhere('recipient_id', $request->executor_id)
            ->when(isset($request->type), function($query) use($type) {
                return $query->where('type', $type);
            })
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select('order_id', 'amount', 'status', 'type', 'description', 'created_at')
            ->get();
        return $transaction;
    }

    public function sales_analysis(Request $request, $shop_id) {
        $startDate = (new DateTime($request->startDate))->format('Y-m-d') . ' 00:00:00';
        $endDate = (new DateTime($request->endDate))->format('Y-m-d') . ' 23:59:00';
        $beforeDate = (new DateTime($request->beforeDate))->format('Y-m-d') . ' 00:00:00';
        $typeQuery = $request->typeQuery;

        $query = Order::join('product_size_flavors', 'orders.product_size_flavor_id', '=', 'product_size_flavors.id')
            ->join('products', 'product_size_flavors.product_id', '=', 'products.id')
            ->join('shipping_tracking', 'orders.id', '=', 'shipping_tracking.order_id');

        $visit = ProductView::whereHas('products', function ($q) use($shop_id) {
                $q->where('shop_id', $shop_id);
            })
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select('created_at')
            ->orderBy('created_at')
            ->get();

        $visit_before = ProductView::whereHas('products', function ($q) use($shop_id) {
                $q->where('shop_id', $shop_id);
            })
            ->whereBetween('created_at', [$beforeDate, $startDate])
            ->count();

        $revenue = (clone $query)
            ->where(function ($query) use($typeQuery) {
                if ($typeQuery == 'order paid') {
                    $query->where('shipping_tracking.status', 'Delivered')
                        ->orWhere('orders.payment_status', 'Paid');
                }else if ($typeQuery == 'orders confirmed') {
                    $query->where('shipping_tracking.status', '<>', 'Waiting confirmation');
                }

            })
            ->where('shop_id', $shop_id)
            ->whereBetween('orders.order_date', [$startDate, $endDate])
            ->selectRaw('total - (total * 0.05) as total, order_date')
            ->orderBy('order_date')
            ->get();

        $revenue_before = (clone $query)
            ->whereBetween('orders.order_date', [$beforeDate, $startDate])
            ->where(function ($query) use($typeQuery) {
                if ($typeQuery == 'order paid') {
                    $query->where('shipping_tracking.status', 'Delivered')
                        ->orWhere('orders.payment_status', 'Paid');
                }else if ($typeQuery == 'orders confirmed') {
                    $query->where('shipping_tracking.status', '<>', 'Waiting confirmation');
                }

            })
            ->where('shop_id', $shop_id)
            ->selectRaw('sum(total - (total * 0.05)) as total')
            ->get();

        $order = (clone $query)
            ->where(function ($query) use($typeQuery) {
                if ($typeQuery == 'order paid') {
                    $query->where('shipping_tracking.status', 'Delivered')
                        ->orWhere('orders.payment_status', 'Paid');
                }else if ($typeQuery == 'orders confirmed') {
                    $query->where('shipping_tracking.status', '<>', 'Waiting confirmation');
                }
            })
            ->where('shop_id', $shop_id)
            ->whereBetween('orders.order_date', [$startDate, $endDate])
            ->select('order_date')
            ->orderBy('order_date')
            ->get();

        $order_before = (clone $query)
            ->where(function ($query) use($typeQuery) {
                if ($typeQuery == 'order paid') {
                    $query->where('shipping_tracking.status', 'Delivered')
                        ->orWhere('orders.payment_status', 'Paid');
                }else if ($typeQuery == 'orders confirmed') {
                    $query->where('shipping_tracking.status', '<>', 'Waiting confirmation');
                }
            })
            ->whereBetween('orders.order_date', [$beforeDate, $startDate])
            ->where('shop_id', $shop_id)
            ->count();

        $shopView = ShopView::where('shop_id', $shop_id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select('created_at')
            ->orderBy('created_at')
            ->get();

        $shopViewBefore = ShopView::where('shop_id', $shop_id)
            ->whereBetween('created_at', [$beforeDate, $startDate])
            ->count();

        $data = [
            'order' => $order,
            'order_before' => $order_before,
            'revenue' => $revenue,
            'revenue_before' => $revenue_before,
            'visit' => $visit,
            'visit_before' => $visit_before,
            'page_view' => $shopView,
            'page_view_before' => $shopViewBefore,
        ];

        return $data;
    }

    public function ranking(Request $request, $shop_id) {
        $dashboardTypeSelect = $request->dashboardTypeSelect;
        $startDate = (new DateTime($request->startDate))->format('Y-m-d') . ' 00:00:00';
        $endDate = (new DateTime($request->endDate))->format('Y-m-d') . ' 23:59:00';

        $productRank = Product::join('product_size_flavors', 'products.id', '=', 'product_size_flavors.product_id')
            ->join('orders', 'orders.product_size_flavor_id', '=', 'product_size_flavors.id')
            ->leftJoin('product_views', 'product_views.product_id', '=', 'products.id')
            ->whereBetween('orders.order_date', [$startDate, $endDate])
            ->where('shop_id', $shop_id)
            ->when(function($query) use($dashboardTypeSelect) {
                if ($dashboardTypeSelect == 'by revenue')
                    $query->groupBy('products.id', 'products.name', 'product_size_flavors.price')
                        ->select('products.id', 'products.name', DB::raw('sum(product_size_flavors.price) as total'))
                        ->orderBy('total', 'desc');
                else if ($dashboardTypeSelect == 'by product')
                    $query->groupBy( 'products.name', 'orders.product_size_flavor_id')
                        ->select('products.id', 'products.name', DB::raw('count(orders.product_size_flavor_id) as total'))
                        ->orderBy('total', 'desc');
                else if ($dashboardTypeSelect == 'by views')
                    $query->groupBy('products.id', 'products.name', 'product_views.product_id')
                        ->select('products.id', 'products.name', DB::raw('count(product_views.product_id) as total'))
                        ->orderBy('total', 'desc');
                else if ($dashboardTypeSelect == 'by conversion rate')
                    $query->groupBy('products.id', 'products.name', 'product_views.product_id', 'product_size_flavors.price')
                        ->select('products.id', 'products.name', DB::raw('sum(product_size_flavors.price) / count(product_views.product_id) as total'))
                        ->orderBy('total', 'desc');
            })
            ->select()
            ->get();

        $productRank = json_decode($productRank);
        foreach($productRank as $key => &$product) {
            if ($key >= 5)
                unset($productRank[$key]);
            $image = ProductImage::where('product_id', $product->id)->first('url');
            if (empty($image))
                continue;
            $product->url = $image->url;
        }

        $productCategoryRank = Product::join('product_size_flavors', 'products.id', '=', 'product_size_flavors.product_id')
            ->join('flavors', 'flavors.id', '=', 'product_size_flavors.flavor_id')
            ->join('orders', 'orders.product_size_flavor_id', '=', 'product_size_flavors.id')
            ->whereBetween('orders.order_date', [$startDate, $endDate])
            ->where('shop_id', $shop_id)
            ->groupBy('type')
            ->select('type as name_type', DB::raw('count(orders.product_size_flavor_id) as total'))
            ->orderBy('total', 'desc')
            ->get();

        return [
            'productRank' => $productRank,
            'productCategoryRank' => $productCategoryRank
        ];
    }

    public function totalDataSalesAnalysis(Request $request, $shop_id) {
        $overview = $this->sales_analysis($request, $shop_id);
        $rank = $this->ranking($request, $shop_id);

        return [
            'overview' => $overview,
            'ranking' => $rank,
        ];
    }

    public function get(Request $request, $type) {
        $flavors = Flavor::where('type', $type)->get();
        return $flavors;
    }
}
