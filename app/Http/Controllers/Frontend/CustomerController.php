<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\FrontendController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Customer\EditProfileRequest;
use App\Http\Requests\Customer\RecoverCustomerPasswordRequest;
use App\Http\Requests\Customer\StoreCustomerRequest;
use App\Models\Customer;
use App\Models\CustomerCatalogue;
use App\Models\Order;
use App\Models\Province;
use App\Models\Wallet;
use App\Models\Wallet_transactions;
use App\Services\Interfaces\CustomerServiceInterface  as CustomerService;
use App\Services\Interfaces\ConstructServiceInterface  as ConstructService;
use App\Repositories\Interfaces\ConstructRepositoryInterface  as ConstructRepository;

class CustomerController extends FrontendController
{

    protected $customerService;
    protected $constructRepository;
    protected $constructService;
    protected $customer;

    public function __construct(
        CustomerService $customerService,
        ConstructRepository $constructRepository,
        ConstructService $constructService,

    ) {

        $this->customerService = $customerService;
        $this->constructService = $constructService;
        $this->constructRepository = $constructRepository;

        parent::__construct();
    }


    public function profile()
    {

        $customer = Auth::guard('customer')->user();

        $system = $this->system;
        $seo = [
            'meta_title' => 'Trang quản lý tài khoản khách hàng' . $customer['name'],
            'meta_keyword' => '',
            'meta_description' => '',
            'meta_image' => '',
            'canonical' => route('customer.profile')
        ];
        return view('frontend.auth.customer.profile', compact(
            'seo',
            'system',
            'customer',
        ));
    }

    public function updateProfile(EditProfileRequest $request)
    {
        $customerId =  Auth::guard('customer')->user()->id;
        if ($this->customerService->update($customerId, $request)) {
            return redirect()->route('customer.profile')->with('success', 'Thêm mới bản ghi thành công');
        }
        return redirect()->route('customer.profile')->with('error', 'Thêm mới bản ghi không thành công. Hãy thử lại');
    }

    public function passwordForgot()
    {

        $customer = Auth::guard('customer')->user();
        $system = $this->system;
        $seo = [
            'meta_title' => 'Trang thay đổi mật khẩu' . $customer['name'],
            'meta_keyword' => '',
            'meta_description' => '',
            'meta_image' => '',
            'canonical' => route('customer.profile')
        ];
        return view('frontend.auth.customer.password', compact(
            'seo',
            'system',
            'customer',
        ));
    }

    public function recovery(RecoverCustomerPasswordRequest $request)
    {
        $customer = Auth::guard('customer')->user();

        if (!Hash::check($request->password, $customer->password)) {
            return redirect()->back()->with('error', 'Mật khẩu hiện tại không chính xác.');
        }
        // Thay đổi mật khẩu
        $customer->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect()->route('customer.profile')->with('success', 'Mật khẩu đã được thay đổi thành công.');
    }

    public function logout()
    {
        Auth::guard('customer')->logout();
        return redirect()->route('home.index')->with('success', 'Bạn đã đăng xuất khỏi hệ thống.');
    }

    public function construction(Request $request)
    {
        $customer = Auth::guard('customer')->user();
        $condition = [
            'keyword' => $request->input('keyword'),
            'confirm' => $request->input('confirm')
        ];
        $constructs = $this->constructRepository->findConstructByCustomer($customer->id, $condition);
        $system = $this->system;
        $seo = [
            'meta_title' => 'Trang quản lý danh sách công trình của ' . $customer['name'],
            'meta_keyword' => '',
            'meta_description' => '',
            'meta_image' => '',
            'canonical' => route('customer.profile')
        ];

        return view('frontend.auth.customer.construction', compact(
            'seo',
            'system',
            'customer',
            'constructs',
        ));
    }


    public function constructionProduct($id)
    {
        $customer = Auth::guard('customer')->user();

        $construction = $this->constructRepository->findById($id, ['*'], ['products']);

        $system = $this->system;
        $seo = [
            'meta_title' => 'Chi tiết sản phẩm công trình ' . $construction->name . ' của ' . $customer['name'],
            'meta_keyword' => '',
            'meta_description' => '',
            'meta_image' => '',
            'canonical' => route('customer.profile')
        ];
        return view('frontend.auth.customer.product', compact(
            'seo',
            'system',
            'customer',
            'construction',
        ));
    }

    public function warranty(Request $request)
    {
        $customer = Auth::guard('customer')->user();

        $condition = [
            'keyword' => $request->input('keyword'),
            'confirm' => $request->input('status')
        ];

        $warranty = $this->constructRepository->warranty($customer->id, $condition);


        $system = $this->system;
        $seo = [
            'meta_title' => 'Thông tin kích hoạt bảo hành',
            'meta_keyword' => '',
            'meta_description' => '',
            'meta_image' => '',
            'canonical' => route('customer.profile')
        ];
        return view('frontend.auth.customer.warranty', compact(
            'seo',
            'system',
            'customer',
            'warranty',
        ));
    }


    public function active(Request $request)
    {
        $response = $this->constructService->activeWarranty($request, 'active');
        return response()->json($response);
    }

    public function order()
    {
        $customer = Auth::guard('customer')->user();
        $customerID = Auth::guard('customer')->id();
        $orders = Order::where('customer_id', $customerID)->get();
        $system = $this->system;

        $seo = [
            'meta_title' => 'Trang quản lý tài khoản khách hàng' . $customer['name'],
            'meta_keyword' => '',
            'meta_description' => '',
            'meta_image' => '',
            'canonical' => route('customer.profile')
        ];
        return view('frontend.auth.customer.order', compact(
            'seo',
            'system',
            'customer',
            'orders'
        ));
    }

    public function createCustomer(Request $request)
    {

        $customer = Auth::guard('customer')->user();
        $code = Auth::guard('customer')->user()->code;
        $system = $this->system;
        $provinces=Province::all();

        $seo = [
            'meta_title' => 'Trang quản lý tài khoản khách hàng' . $customer['name'],
            'meta_keyword' => '',
            'meta_description' => '',
            'meta_image' => '',
            'canonical' => route('customer.profile')
        ];
        return view('frontend.auth.customer.create_customer', compact(
            'seo',
            'system',
            'customer',
            'code',
            'provinces',
        ));
    }
    public function store(StoreCustomerRequest $request)
{
    $customer = new Customer();
    $customer->email = $request->email;
    $customer->name = $request->name;
    $customer->publish=1;
    $customer->referral_by = $request->referral_by ?? null; 
    $customer->code = time();
    $customer->customer_catalogue_id = 1;
    $customer->password = bcrypt($request->password);
    $customer->phone = $request->phone;
    $customer->address = $request->address;
    $customer->birthday = $request->birthday;

    $customer->save();

    return redirect()->back()->with('success', 'Cộng tác viên đã được thêm mới thành công.');
}


    public function cancelOrder($id)
    {
        $customerID = Auth::guard('customer')->id();
        $order = Order::where('id', $id)
            ->where('customer_id', $customerID)
            ->where('confirm', 'pending')
            ->first();
        if ($order) {
            $order->update(['confirm' => 'cancel']);
            return redirect()->back()->with('success', 'Đơn hàng đã được huỷ');
        }

        return redirect()->back()->with('error', 'Không thể huỷ đơn hàng');
    }
    public function wallet()
    {
        $customer = Auth::guard('customer')->user();
        $customerID = Auth::guard('customer')->id();

        $wallet = Wallet::where('customer_id', $customerID)->first();

        if (!$wallet) {
            $wallet = Wallet::create([
                'customer_id' => $customerID,
                'bank_account_name' => '',
                'bank_account_number' => '',
                'bank_name' => ''
            ]);
        }
        $transactions = Wallet_transactions::where('wallet_id', $wallet->id)
            ->orderBy('created_at', 'desc')
            ->get();
        $balance = 0;
        foreach ($transactions as $transaction) {
            if (in_array($transaction->type, ['commission', 'deposit'])) {
                $balance += $transaction->amount;
            } elseif (in_array($transaction->type, ['deduction', 'withdrawal'])) {
                $balance -= $transaction->amount;
            }
        }

        $system = $this->system;

        $seo = [
            'meta_title' => 'Trang quản lý tài khoản khách hàng ' . $customer->name,
            'meta_keyword' => '',
            'meta_description' => '',
            'meta_image' => '',
            'canonical' => route('customer.profile')
        ];

        return view('frontend.auth.customer.wallet', compact(
            'seo',
            'system',
            'customer',
            'wallet',
            'transactions',
            'balance'
        ));
    }

    public function updateBankAccount(Request $request)
    {
        $request->validate([
            'bank_account_name' => 'required|string|max:255',
            'bank_account_number' => 'required|numeric',
            'bank_name' => 'required|string|max:255',
        ]);

        $customerID = Auth::guard('customer')->id();
        $wallet = Wallet::firstOrCreate(
            ['customer_id' => $customerID],
            ['bank_account_name' => '', 'bank_account_number' => '', 'bank_name' => '']
        );

        $wallet->update([
            'bank_account_name' => $request->bank_account_name,
            'bank_account_number' => $request->bank_account_number,
            'bank_name' => $request->bank_name,
        ]);

        return redirect()->back()->with('success', 'Cập nhật tài khoản ngân hàng thành công!');
    }
}
