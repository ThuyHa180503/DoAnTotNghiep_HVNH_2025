<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\FrontendController;
use Illuminate\Http\Request;
use App\Services\CartService;
use App\Repositories\Interfaces\ProvinceRepositoryInterface  as ProvinceRepository;
use App\Repositories\Interfaces\PromotionRepositoryInterface  as PromotionRepository;
use App\Repositories\Interfaces\OrderRepositoryInterface  as OrderRepository;
use App\Http\Requests\StoreCartRequest;
use Cart;
use App\Classes\Vnpay;
use App\Classes\Momo;
use App\Classes\Paypal;
use App\Classes\Zalo;
use App\Models\Cart as ModelsCart;
use App\Models\CartDetail;
use Illuminate\Support\Facades\Auth;
use Stringable;
use Illuminate\Support\Str;


class CartController extends FrontendController
{
  
    protected $provinceRepository;
    protected $promotionRepository;
    protected $orderRepository;
    protected $cartService;
    protected $vnpay;
    protected $momo;
    protected $paypal;
    protected $zalo;

    public function __construct(
        ProvinceRepository $provinceRepository,
        PromotionRepository $promotionRepository,
        OrderRepository $orderRepository,
        CartService $cartService,
        Vnpay $vnpay,
        Momo $momo,
        Paypal $paypal,
        Zalo $zalo,
    ){
       
        $this->provinceRepository = $provinceRepository;
        $this->promotionRepository = $promotionRepository;
        $this->orderRepository = $orderRepository;
        $this->cartService = $cartService;
        $this->vnpay = $vnpay;
        $this->momo = $momo;
        $this->paypal = $paypal;
        $this->zalo = $zalo;
        parent::__construct();
    }


    public function checkout($type=null){
        
        $provinces = $this->provinceRepository->all();
        $customer_id = auth()->guard('customer')->id();
        $carts = Cart::instance('shopping')->content();
        $cartCaculate = $this->cartService->reCaculateCart();
        $cartPromotion = $this->cartService->cartPromotion($cartCaculate['cartTotal']);
        $seo = [
            'meta_title' => 'Trang thanh toán đơn hàng',
            'meta_keyword' => '',
            'meta_description' => '',
            'meta_image' => '',
            'canonical' => write_url('thanh-toan', TRUE, TRUE),
        ];
        if($type!=null ){
            $cart_id=ModelsCart::where('customer_id',$customer_id)->first();
            $carts=CartDetail::with('product','product_name')->where('cart_id',$cart_id->ID)->get();
            $convertedItems = [];
            foreach ($carts as $cart) {
                $rowId = Str::uuid()->toString();

                $convertedItems[$rowId] = (object) [
                    'rowId' => $rowId,
                    'id' => $cart->product_id,
                    'qty' => (string) $cart->quantity,
                    'name' => optional($cart->product_name)->name ?? 'Sản phẩm không tồn tại',
                    'price' => (float) $cart->unit_price,
                    'options' => (object) [], 
                    'associatedModel' => null,
                    'taxRate' => 0,
                    'priceOriginal' => (float) $cart->unit_price,
                    'image' => optional($cart->product)->image ?? '',
                    'cart'=>2,
                ];
            }
            $carts=$convertedItems;
        }
        $system = $this->system;
        $config = $this->config();
        return view('frontend.cart.index', compact(
            'config',
            'seo',
            'system',
            'provinces',
            'carts',
            'cartPromotion',
            'cartCaculate',
            'type'
        ));
        
    }

    public function store(StoreCartRequest $request){
        $customerID = Auth::guard('customer')->id();
        $request['customer_id'] = $customerID;
        //$request['product_catalogue_id'] = 16;
        
        $system = $this->system;
        $order = $this->cartService->order($request, $system);
        if($order['flag']){
            if($order['order']->method !== 'cod'){
                $response = $this->paymentMethod($order);
                if($response['errorCode'] == 0){
                    return redirect()->away($response['url']);
                }
            }
            return redirect()->route('cart.success', ['code' => $order['order']->code])->with('success','Đặt hàng thành công');
        }
        return redirect()->route('cart.checkout')->with('error','Đặt hàng không thành công. Hãy thử lại');
    }

    public function success($code){
        $order = $this->orderRepository->findByCondition([
            ['code', '=', $code],
        ], false, ['products']);
        
        $seo = [
            'meta_title' => 'Thanh toán đơn hàng thành công',
            'meta_keyword' => '',
            'meta_description' => '',
            'meta_image' => '',
            'canonical' => write_url('cart/success', TRUE, TRUE),
        ];
        $system = $this->system;
        $config = $this->config();
        return view('frontend.cart.success', compact(
            'config',
            'seo',
            'system',
            'order'
        ));
    }

    public function paymentMethod($order = null){
        $class = $order['order']->method;
        $response = $this->{$class}->payment($order['order']);
        return $response;
    }

    private function config(){
        return [
            'language' => $this->language,
            'css' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'
            ],
            'js' => [
                'backend/library/location.js',
                'frontend/core/library/cart.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
            ]
        ];
    }
  
    public function storeCart(Request $request) {

        
        $customer_id = auth()->guard('customer')->id();
        $qty = $request->input('qty_cart', 1);
        $unit_price = $request->input('price_cart', 0);
        $product_id = $request->input('id_item');
        $product_variant_id = null; 
        $cart = ModelsCart::firstOrCreate(
            ['customer_id' => $customer_id], 
            [] 
        );
    if($cart){
        $item = CartDetail::where('cart_id', $cart->ID)
        ->where('product_id', $product_id)
        ->where('product_variant_id', $product_variant_id)
        ->first();
    if ($item) {
    $item->quantity += $qty;
    $item->save();
    } 
    else {
        CartDetail::create([
            'cart_id' => $cart->ID,
            'product_id' => $product_id,
            'product_variant_id' => $product_variant_id,
            'unit_price' => $unit_price,
            'quantity' => $qty
        ]);
    }

    return redirect()->back(); 
    }
        
    }


    public function cart()
{
    $provinces = $this->provinceRepository->all();
    $customer_id = auth()->guard('customer')->id();
    
    if (!$customer_id) {
        return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để xem giỏ hàng.');
    }

    $cartCaculate = $this->cartService->reCaculateCart();
    $cartPromotion = $this->cartService->cartPromotion($cartCaculate['cartTotal']);

    $seo = [
        'meta_title' => 'Trang thanh toán đơn hàng',
        'meta_keyword' => '',
        'meta_description' => '',
        'meta_image' => '',
        'canonical' => write_url('thanh-toan', TRUE, TRUE),
    ];

    $cart = ModelsCart::where('customer_id', $customer_id)->first();
    
    if (!$cart) {
        return redirect()->route('home')->with('error', 'Giỏ hàng trống.');
    }

    $carts1 = CartDetail::with(['product', 'product_name'])
        ->where('cart_id', $cart->ID)
        ->get();

    $convertedItems = [];

    foreach ($carts1 as $cartItem) {
        $rowId = Str::uuid()->toString();
        $convertedItems[$rowId] = (object) [
            'rowId' => $rowId,
            'id' => $cartItem->product_id,
            'qty' => (string) $cartItem->quantity,
            'name' => optional($cartItem->product_name)->name ?? 'Sản phẩm không tồn tại',
            'price' => (float) $cartItem->unit_price,
            'options' => (object) [], 
            'associatedModel' => null,
            'taxRate' => 0,
            'priceOriginal' => (float) $cartItem->unit_price,
            'image' => optional($cartItem->product)->image ?? '',
            'cart' => 2,
        ];
    }
    $carts=$convertedItems;
    $system = $this->system;
    $config = $this->config();

    return view('frontend.cart.cart', compact(
        'config',
        'seo',
        'system',
        'provinces',
        'carts', 
        'cartPromotion',
        'cartCaculate'
    ));
}


    public function update(Request $request)
{
    $customer_id = auth()->guard('customer')->id();
    $cart = ModelsCart::where('customer_id', $customer_id)->first(); 
  

    if (!$cart) {
        return redirect()->back()->with('error', 'Không tìm thấy giỏ hàng!');
    }
    
    $change = (int) $request->change; 
    $cartItem = CartDetail::where('cart_id', $cart->ID)
                          ->first();
    if ($cartItem) {
        $newQty = max(1, $cartItem->quantity + $change);
        $cartItem->quantity = $newQty;
        $cartItem->save();

        return redirect()->back()->with('success', 'Cập nhật giỏ hàng thành công!');
    }

    return redirect()->back()->with('error', 'Không tìm thấy sản phẩm trong giỏ hàng!');
}

    

    public function remove(Request $request){
    $customer_id = auth()->guard('customer')->id();
    $cart = ModelsCart::where('customer_id', $customer_id)->first(); 
    $cartItem = CartDetail::where('cart_id', $cart->ID)
    ->where('product_id', $request->product_id) // Xác định sản phẩm cần xóa
    ->first();
    $cartItem->delete();
return redirect()->back();
    }
}
