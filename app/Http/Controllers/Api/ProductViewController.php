<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ShippingTracking;
use App\Models\ProductImage;
use App\Models\Product;
use App\Models\ProductSizeFlavor;
use App\Models\Size;
use App\Models\Order;
use App\Models\Flavor;
use App\Models\Location;
use App\Models\ProductView;

class ProductViewController extends Controller
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
        $ip = $request->ip();
        $request['ip_address'] = $ip;
        $product_view = ProductView::create($request->all());
        return response()->json($product_view, 200);
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

    public function task_list(Request $request, string $shop_id) {
        $sold_out = Product::where('quantity', 0)
            ->where('shop_id', $shop_id)
            ->count();

        $base_query = ShippingTracking::join('orders', 'shipping_tracking.order_id', '=', 'orders.id')
            ->join('product_size_flavors', 'orders.product_size_flavor_id', '=', 'product_size_flavors.id')
            ->join('products', 'product_size_flavors.product_id', '=', 'products.id')
            ->where('shop_id', $shop_id);

        $wc = (clone $base_query)->where('shipping_tracking.status' , 'Waiting confirmation')->count();
        $ap = (clone $base_query)->where('shipping_tracking.status' , 'Awaiting pickup')->count();
        $ad = (clone $base_query)->where('shipping_tracking.status', 'Await delivery')->count();
        $sd = (clone $base_query)->where('shipping_tracking.status' , 'Delivered')->count();
        $resolved = (clone $base_query)->where('shipping_tracking.status' , 'In delivery')->count();
        $cxl = (clone $base_query)->where('shipping_tracking.status' , 'Cancellation request')->count();


        $locked = Product::where('shop_id', $shop_id)
            ->where('status', 'violation')
            ->count();

        $data = collect([
            'sold_out' => $sold_out,
            'waiting_confirmation' => $wc,
            'awaiting_pickup' => $ap,
            'await_delivery' => $ad,
            'complete' => $sd,
            'cancel' => $cxl,
            'resolved' => $resolved,
            'locked' => $locked
        ]);
        return $data;
    }

    public function sales_analysis(Request $request, string $shop_id) {
        $currentDate = date('Y-m-d');
        $yesterday = date('Y-m-d', strtotime('-1 day'));

        $visit = ProductView::where('user_id', $shop_id)
            ->whereDate('created_at', $currentDate)
            ->groupBy('product_id', 'user_id', 'ip_address')
            ->count();

        $visit_yesterday = ProductView::where('user_id', $shop_id)
            ->whereDate('created_at', $yesterday)
            ->groupBy('product_id', 'user_id', 'ip_address')
            ->count();
        
        $view = ProductView::where('user_id', $shop_id)
            ->whereDate('created_at', $currentDate)
            ->count();

        $view_yesterday = ProductView::where('user_id', $shop_id)
            ->whereDate('created_at', $yesterday)
            ->count();
        
        $order = Order::join('shipping_tracking', 'orders.id', '=', 'shipping_tracking.order_id')
            ->where('status', '<>', 'Waiting confirmation')
            ->whereDate('orders.created_at', $currentDate)
            ->count();

        $order_yesterday = Order::join('shipping_tracking', 'orders.id', '=', 'shipping_tracking.order_id')
            ->where('status', '<>', 'Waiting confirmation')
            ->whereDate('orders.created_at', $yesterday)
            ->count();

        $total_paids = Order::join('product_size_flavors', 'orders.product_size_flavor_id', '=', 'product_size_flavors.id')
            ->join('products', 'product_size_flavors.product_id', '=', 'products.id')
            ->where('payment_status', 'Paid')
            ->where('shop_id', $shop_id)
            ->whereDate('orders.created_at', $currentDate)
            ->orderBy('order_date', 'asc')
            ->select('price', 'order_date', 'orders.quantity')
            ->get();

        $total_shipping_trackings = Order::join('product_size_flavors', 'orders.product_size_flavor_id', '=', 'product_size_flavors.id')
            ->join('products', 'product_size_flavors.product_id', '=', 'products.id')
            ->join('shipping_tracking', 'orders.id', '=', 'shipping_tracking.order_id')
            ->where('shipping_tracking.status', 'Delivered')
            ->where('shop_id', $shop_id)
            ->whereDate('orders.created_at', $currentDate)
            ->orderBy('order_date', 'asc')
            ->select('price', 'order_date', 'orders.quantity')
            ->get();

        function handleData($array, &$revenue, &$data_set) {
            foreach($array as $total) {
                $timestamp = strtotime($total['order_date']);
                $current = date("H", $timestamp);
                $current = $current & -2;
                
                if ($data_set->has($current)) {
                    $item = $data_set[$current];

                    $sumTotal = $total['price'] - ($total['price'] * 0.05);

                    $item['price'] += $sumTotal;
                    $item['quantity'] += $total['quantity'];

                    $data_set[$current] = $item;
                    $revenue += $sumTotal;
                }
            }
        }
        
        $total_paids = json_decode($total_paids, true);
        $total_shipping_trackings = json_decode($total_shipping_trackings, true);
        $revenue = 0;
        $data_set = collect([
            '0' => ['price' => 0, 'quantity' => 0],
            '2' => ['price' => 0, 'quantity' => 0],
            '4' => ['price' => 0, 'quantity' => 0],
            '6' => ['price' => 0, 'quantity' => 0],
            '8' => ['price' => 0, 'quantity' => 0],
            '10' => ['price' => 0, 'quantity' => 0],
            '12' => ['price' => 0, 'quantity' => 0],
            '14' => ['price' => 0, 'quantity' => 0],
            '16' => ['price' => 0, 'quantity' => 0],
            '18' => ['price' => 0, 'quantity' => 0],
            '20' => ['price' => 0, 'quantity' => 0],
            '22' => ['price' => 0, 'quantity' => 0],
            '24' => ['price' => 0, 'quantity' => 0],
        ]);

        handleData($total_shipping_trackings, $revenue, $data_set);      
        handleData($total_paids, $revenue, $data_set);     
        
        $data = collect([
            'visit' => $visit,
            'visit_yesterday' => $visit_yesterday != 0 ? $visit == 0 ? 0 : (($visit - $visit_yesterday) / $visit_yesterday) * 100 : ($visit == 0 ? 0 : 100),
            'view' => $view,
            'view_yesterday' => $view_yesterday != 0 ? $view == 0 ? 0 : (($view - $view_yesterday) / $view_yesterday) * 100 : ($view == 0 ? 0 : 100),
            'order' => $order,
            'order_yesterday' => $order_yesterday != 0 ? $order == 0 ? 0 : (($order - $order_yesterday) / $order_yesterday) * 100 : ($order == 0 ? 0 : 100),
            'conversion_rate' => $visit != 0 ? $order == 0 ? 0 : ($order / $visit) * 100 : ($order == 0 ? 0 : 100),
            'conversion_rate_yesterday' => $visit_yesterday != 0 ? ($order / $visit_yesterday) * 100 : ($order == 0 ? 0 : 100),
            'data_set' => $data_set,
            'revenue' => $revenue,
        ]);
        return $data;
    }

    public function show_data_home(Request $request, string $shop_id) {
        $task_list = $this->task_list($request, $shop_id);

        $sales_analysis = $this->sales_analysis($request, $shop_id);

        $data = collect([
            'task_list' => $task_list,
            'sales_analysis' => $sales_analysis,
        ]);
        return $data;
    }
}
