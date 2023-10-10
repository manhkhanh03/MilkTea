<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\ShippingController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\ProductViewController;
use App\Http\Controllers\Api\VendorController;
use App\Http\Controllers\Api\FlavorController;
use App\Http\Controllers\Api\ChatbotController;
use App\Http\Controllers\Api\DiscountCodeController;
use App\Models\DiscountCode;
use Illuminate\Support\Facades\Auth;
use DateTime;
use Carbon\Carbon;
use App\Models\Product;
use App\Models\ProductView;
use Illuminate\Support\Facades\Gate;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    protected $shop_id;

    private function shopInfo() {
        if(Auth::check() && Gate::allows('is-vendor') || Gate::allows('is-admin'))
            return Auth::user()->shop->id;
        return null;
    }

    public function show_web(Request $request, $address) {
        $url = $request->getSchemeAndHttpHost();
        return view($address)->with('url_web', $url);
    }

    public function show_login(Request $request) {
        $url = $request->getSchemeAndHttpHost();
        if (Auth::check()) {
            return redirect('/home')->with('url_web', $url);
        }
        return view('login')->with('url_web', $url);
    }

    public function show_web_order(Request $request) {  
        $url = $request->getSchemeAndHttpHost();
        $shipping = new ShippingController();
        $request['customer'] = Auth::user()->id;
        $request['status'] = str_replace('_', ' ', $request->status);
        $all_shipping = json_decode(json_encode($shipping->show_status_shipping_by_customer_id($request)), true);
        return view('waiting_confirmation')->with([
            'url_web' => $url,
            'shipping' => $all_shipping,
            'type' => $request['status'],
        ]);
    }

    public function show_profile(Request $request, $address) {
        $url = $request->getSchemeAndHttpHost();
        // $shipping = new ShippingController();
        // $request['customer'] = Auth::user()->id;
        // $all_shipping = json_decode(json_encode($shipping->show_status_shipping_by_customer_id($request)), true);
        return view($address)->with([
            'url_web' => $url,
            'type' => $address,
            'type_child' => $request->type,
        ]);
    }

    public function show_web_shipping_information(Request $request, $address) {
        $url = $request->getSchemeAndHttpHost();
        $shipping = new ShippingController();
        $request['customer'] = Auth::user()->id;
        $location = json_decode(json_encode($shipping->show_shipping_info_by_customer_id($request)), true);

        // return $location;
        return view($address)->with([
            'url_web' => $url,
            'type' => $address,
            'type_child' => $request->type,
            'location' => $location,
            'role' => 'customer',
            'extend' => 'frame_user',
            'body' => 'body-products',
        ]);
    }

    public function show_order_detail(Request $request) {
        $url = $request->getSchemeAndHttpHost();
        $shipping = new ShippingController();
        $request['vendor'] = Auth::user()->id;
        $location = json_decode(json_encode($shipping->show_shipping_info_by_customer_id($request)), true);

        // return $location;
        return view('detail_shipping')->with([
            'url_web' => $url,
            'location' => $location,
            'type' => 'order',
            'type_child' => $request->type,
            'role' => 'vendor',
            'extend' => 'vendor_frame',
            'body' => 'body-vendor',
        ]);
    }

    public function show_pagination(Request $request) {
        $products = new ProductController();
        if(!$request->page) {
            $request['page'] = 1;
            $request['per_page'] = 12;
        }
        $result = json_decode(json_encode($products->handleGetProduct($request)), true);

        $url = $request->getSchemeAndHttpHost();

        // return $result['original'];

        return view('menu')->with('products', $result['original'])->with('url_web', $url);
    }

    public function show_product($title, Request $request) {
        $product = new ProductController();
        $response = $product->show($request->product);
        $data = $response->getData();

        $products = new ProductController();
        $request['page'] = mt_rand(1, 5);
        $request['per_page'] = 4;

        $result = json_decode(json_encode($products->handleGetProduct($request)), true);
        $url = $request->getSchemeAndHttpHost();
        // return ['data' => $data, 'products' => $result['original'][0]];
        return view('product')->with('product', $data)->with('url_web', $url)->with('products', $result['original'][0]);
        // return view('product')->with('product', $product->show($request->product, true))->with('url_web', $url);
        // return $data;
    }

    public function show_checkout(Request $request) {
        // ................
        $url = $request->getSchemeAndHttpHost();
        return view('checkout')->with('url_web', $url)->with('web', $request->web);
    }

    public function show_update(Request $request, $address) {
        $url = $request->getSchemeAndHttpHost();
        // $noti = new NotificationController();
        // $notifications = json_decode(json_encode($noti->show_update_order($request)), true);
        // return $notifications;
        return view($address)->with('url_web', $url);
    }

    public function show_vendor(Request $request) {
        $url = $request->getSchemeAndHttpHost();
        $product_view = new ProductViewController();
        $data = $product_view->show_data_home($request, $this->shopInfo());

        // return $data;
        return view('home_vendor')->with([
            'url_web' => $url,
            'type' => '',
            'data' => $data,
        ]);
    }

    public function show_order(Request $request) {
        $url = $request->getSchemeAndHttpHost();
        $startDate = date('Y-m-d');
        $endDate = date('Y-m-d');
        $request['startDate'] = $startDate;
        $request['endDate'] = $endDate;
        $vendor = new VendorController();
        $products = $vendor->show_orders_vendor($request, $this->shopInfo());

        if(!$request->type)
            $request['type'] = 'all';
        return view('order_vendor')->with([
            'url_web' => $url,
            'type' => 'order',
            'type_child' => $request->type,
            'products' => $products,
        ]);
    }

    public function show_product_vendor(Request $request) {
        $url = $request->getSchemeAndHttpHost();
        $vendor = new VendorController();
        $products = $vendor->show_products_vendor($request, $this->shopInfo());

        if(!$request->type)
            $request['type'] = 'all_product';
        return view('product_vendor')->with([
            'url_web' => $url,
            'type' => 'product',
            'type_child' => $request->type,
            'products' => $products,
        ]);
    }

    public function show_add_product(Request $request) {
        $url = $request->getSchemeAndHttpHost();
        $product_detail = '';
        if ($request->product) {
            $product = new ProductController();
            $request['shop_id'] = $this->shopInfo();
            $product_detail = $product->show_all_info($request);
            $product_check = json_decode($product_detail, true);
            if (empty($product_check)) {
                return $this->show_product_vendor($request);
            }
            $request['type'] = $product_detail['psf'][0]['type'];
        }

        $flavor = new FlavorController();
        $flavors = $flavor->get($request);

        return view('add_product')->with([
            'url_web' => $url,
            'flavors' => $flavors,
            'product_edit' => $product_detail,
        ]);
    }

    public function show_finance_income(Request $request) {
        $url = $request->getSchemeAndHttpHost();
        $vendor = new VendorController();
        $startDate = date('Y-m-01');
        $endDate = date('Y-m-d');
        $request['startDate'] = $startDate;
        $request['endDate'] = $endDate;
        $request['shop_id'] = $this->shopInfo();
        $finance = $vendor->finance($request);

        return view('income')->with([
            'url_web' => $url,
            'type' => 'finance',
            'type_child' => 'income',
            'finance' => $finance,
            'startDate' => $request['startDate'],
        ]);
    }

    public function show_finance_balance(Request $request) {
        $url = $request->getSchemeAndHttpHost();
        $vendor = new VendorController();
        $startDate = date('Y-m-01');
        $endDate = date('Y-m-d');
        $request['startDate'] = $startDate;
        $request['endDate'] = $endDate;

        $request['executor_id'] = $this->shopInfo();
        $finance = $vendor->account_balance($request);
        return view('account_balance')->with([
            'url_web' => $url,
            'type' => 'finance',
            'type_child' => 'account_balance',
            'finance' => $finance,
            'startDate' => $request['startDate'],
            'transaction' => $request->type,
        ]);
    }

    public function show_sales_analysis(Request $request) {
        $url = $request->getSchemeAndHttpHost();
        $vendor = new VendorController();
        $startDate = date('Y-m-01');
        $endDate = date('Y-m-t');
        $beforeDate = date('Y-m-d', strtotime('-1 month', strtotime($startDate)));
        $request['startDate'] = $startDate;
        $request['endDate'] = $endDate;
        $request['beforeDate'] = $beforeDate;
        $request['typeQuery'] = 'order confirmed';
        $request['dashboardTypeSelect'] = 'by revenue';

        $salesAnalysis = $vendor->totalDataSalesAnalysis($request, $this->shopInfo());
        return view('sales_analysis')->with([
            'url_web' => $url,
            'salesAnalysis' => $salesAnalysis,
            'typeTime' => 'd',
            'endDate' => $endDate . '23:59:00',
            'shop_id' => $this->shopInfo(),
        ]);
    }

    public function show_cart(Request $request) {
        $url = $request->getSchemeAndHttpHost();
        $request['customer_id'] = Auth::user()->id;
        $cart = new CartController();
        $products = $cart->show_cart($request);
        // return $products;
        return view('cart')->with('url_web', $url)->with('products', $products);
    }

    public function chatbot(Request $request) {
        $url = $request->getSchemeAndHttpHost();
        $chatbot = new ChatbotController();
        $message = $chatbot->show($this->shopInfo(), $request->type);

        $chatbotID = $chatbot->getChatbotID($this->shopInfo());

        return view('chatbot')->with([
            'url_web'=> $url,
            'type' => 'chatbot',
            'type_child' => $request->type,
            'chatbot' => $message,
            'type_message' => $request->type,
            'chatbotId' => $chatbotID['id'],
        ]);
    }
    
    public function discountCode(Request $request) {
        $url = $request->getSchemeAndHttpHost();
        $discount = new DiscountCodeController();
        $status = $request->status;
        if(!isset($request->status))
            $status = 'all';

        $discounts = $discount->getByShopId($this->shopInfo(), $status);

        return view('discount_code')->with([
            'url_web'=> $url,
            'type' => 'discount_code',
            'discounts' => $discounts,
            'status' => $status,
        ]);
    }
    
    public function addDiscountCode(Request $request) {
        $url = $request->getSchemeAndHttpHost();
        $usecase = str_replace("-", ' ', $request->usecase);
        
        return view('add_discount_code')->with([
            'url_web' => $url,
            'usecase' => $usecase,
        ]);
    }

    public function show_api(Request $request) {
        $discount = new DiscountCodeController();
        // $discountCode = $discount->getByShopId($this->shopInfo());

        // return $discountCode;
    }
}