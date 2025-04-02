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
use App\Models\CartOrderDetail;
use App\Models\CartOrders;
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
    ) {

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


    public function checkout($type = null)
    {
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
        if ($type != null) {
            if ($type == 2) {
                $cart_id = ModelsCart::where('customer_id', $customer_id)->first();
                $carts = CartDetail::with('product', 'product_name')->where('cart_id', $cart_id->ID)->get();
            } else {
                $cart_id = CartOrders::where('customer_id', $customer_id)->first();
                $carts = CartOrderDetail::with('product', 'product_name')->where('cart_order_id', $cart_id->id)->get();
            }
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
                    'cart' => 2,
                ];
            }
            $carts = $convertedItems;
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

    public function store(StoreCartRequest $request)
    {
        $customerID = Auth::guard('customer')->id();
        $request['customer_id'] = $customerID;
        if ($request->type != null) {
            if ($request->type == 2) {
                $cart_id = ModelsCart::where('customer_id', $customerID)->first();
                $carts = CartDetail::with('product', 'product_name')->where('cart_id', $cart_id->ID)->get();
            } else {
                $cart_id = CartOrders::where('customer_id', $customerID)->first();
                $carts = CartOrderDetail::with('product', 'product_name')->where('cart_order_id', $cart_id->id)->get();
            }
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
                    'cart' => 2,
                ];
            }
            $carts = $convertedItems;
            $request->merge(['carts' => $carts]);
        }


        $system = $this->system;
        $order = $this->cartService->order($request, $system,);


        if ($order['flag']) {

            if ($request->type == 2) {
                $order['order']->update(['by_order' => 0]);
            } else {
                $order['order']->update(['by_order' => 1]);
            }

            if ($order['order']->method !== 'cod') {
                $response = $this->paymentMethod($order);
                if ($response['errorCode'] == 0) {
                    return redirect()->away($response['url']);
                }
            }
            //dd($order['order']->code);
            return redirect()->route('cart.success', ['code' => $order['order']->code])
                ->with('success', 'Đặt hàng thành công');
        }

        return redirect()->route('cart.checkout')->with('error', 'Đặt hàng không thành công. Hãy thử lại');
    }


    public function success($code)
    {
        $order = $this->orderRepository->findByCondition([
            ['code', '=', $code],
        ], false, ['products']);
        //dd($order);
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

    public function paymentMethod($order = null)
    {
        $class = $order['order']->method;
        $response = $this->{$class}->payment($order['order']);
        return $response;
    }

    private function config()
    {
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

    public function storeCart(Request $request)
    {
        $customer_id = auth()->guard('customer')->id();
        $qty = $request->input('qty_cart', 1);
        $unit_price = $request->input('price_cart', 0);
        $product_id = $request->input('id_item');
        $product_variant_id = null;

        // Kiểm tra giỏ hàng đã tồn tại chưa
        $cart = ModelsCart::where('customer_id', $customer_id)->first();

        if (!$cart) {
            $cart = ModelsCart::create(['customer_id' => $customer_id]);
            $cart->refresh();
        }

        // Đảm bảo ID được lấy đúng
        $cart_id = $cart->ID; // Kiểm tra lại ModelsCart có dùng 'ID' hay 'id' (chuẩn là 'id')

        if (!$cart_id) {
            return redirect()->back()->with('error', 'Lỗi khi tạo giỏ hàng.');
        }

        // Kiểm tra sản phẩm có trong giỏ hàng chưa
        $item = CartDetail::where('cart_id', $cart_id)
            ->where('product_id', $product_id)
            ->where('product_variant_id', $product_variant_id)
            ->first();

        if ($item) {
            $item->quantity += $qty;
            $item->save();
        } else {
            CartDetail::create([
                'cart_id' => $cart_id,
                'product_id' => $product_id,
                'product_variant_id' => $product_variant_id,
                'unit_price' => $unit_price,
                'quantity' => $qty
            ]);
        }

        return redirect()->back()->with('success', 'Thêm vào giỏ hàng thành công');
    }


    public function cart($type = null)
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

        if ($type == 2) {
            $cart = ModelsCart::where('customer_id', $customer_id)->first();

            if ($cart) {
                $carts1 = CartDetail::with(['product', 'product_name'])
                    ->where('cart_id', $cart->ID)
                    ->get();
            } else {
                $carts1 = collect(); // Trả về một collection rỗng để tránh lỗi
            }
        } else {
            $cart = CartOrders::where('customer_id', $customer_id)->first();

            if ($cart) {
                $carts1 = CartOrderDetail::with(['product', 'product_name'])
                    ->where('cart_order_id', $cart->id)
                    ->get();
            } else {
                $carts1 = collect(); // Trả về một collection rỗng để tránh lỗi
            }
        }


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
                'cart' => $type,
            ];
        }
        $carts = $convertedItems;
        $system = $this->system;
        $config = $this->config();
        return view('frontend.cart.cart', compact(
            'config',
            'seo',
            'system',
            'provinces',
            'carts',
            'cartPromotion',
            'cartCaculate',
            'type',
        ));
    }


    public function update(Request $request)
    {
        $customer_id = auth()->guard('customer')->id();
        if ($request->type == 2) {
            $cart = ModelsCart::where('customer_id', $customer_id)->first();

            $change = (int) $request->change;
            $cartItem = CartDetail::where('cart_id', $cart->ID)
                ->first();
        } else {
            $cart = CartOrders::where('customer_id', $customer_id)->first();

            $change = (int) $request->change;
            $cartItem = CartOrderDetail::where('cart_order_id', $cart->id)
                ->first();
        }
        if ($cartItem) {
            $newQty = max(1, $cartItem->quantity + $change);
            $cartItem->quantity = $newQty;
            $cartItem->save();

            return redirect()->back()->with('success', 'Cập nhật giỏ hàng thành công!');
        }

        return redirect()->back()->with('error', 'Không tìm thấy sản phẩm trong giỏ hàng!');
    }


    public function remove(Request $request)
    {
        $customer_id = auth()->guard('customer')->id();
        if ($request->type == 2) {
            $cart = ModelsCart::where('customer_id', $customer_id)->first();
            $cartItem = CartDetail::where('cart_id', $cart->ID)
                ->where('product_id', $request->product_id)
                ->first();
        } else {
            $cart = CartOrders::where('customer_id', $customer_id)->first();
            $cartItem = CartOrderDetail::where('cart_order_id', $cart->id)
                ->where('product_id', $request->product_id)
                ->first();
        }
        $cartItem->delete();
        return redirect()->back();
    }

    public function storeCartOrder(Request $request)
    {
        $customer_id = auth()->guard('customer')->id();

        if (!$customer_id) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để đặt hàng.');
        }

        $qty = $request->input('qty_cart', 1);
        $unit_price = $request->input('price_cart', 0);
        $product_id = $request->input('id_item');
        $product_variant_id = null;

        // Kiểm tra giỏ hàng đã tồn tại chưa
        $cart = CartOrders::where('customer_id', $customer_id)->first();

        if (!$cart) {
            $cart = CartOrders::create(['customer_id' => $customer_id]);
            $cart->refresh();
        }

        // Chắc chắn $cart đã có ID
        $cart_id = $cart->id;

        // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
        $item = CartOrderDetail::where('cart_order_id', $cart_id)
            ->where('product_id', $product_id)
            ->where('product_variant_id', $product_variant_id)
            ->first();

        if ($item) {
            $item->quantity += $qty;
            $item->save();
        } else {
            CartOrderDetail::create([
                'cart_order_id' => $cart_id,
                'product_id' => $product_id,
                'product_variant_id' => $product_variant_id,
                'unit_price' => $unit_price,
                'quantity' => $qty
            ]);
        }

        return redirect()->back()->with('success', 'Thêm vào giỏ hàng thành công');
    }
}
