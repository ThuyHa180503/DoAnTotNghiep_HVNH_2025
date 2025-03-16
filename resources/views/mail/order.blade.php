<!-- <!DOCTYPE html>
<html>
    <head>
        <style>
            .cart-success{
                padding: 30px 10px;
            }
            @media (min-width: 1220px){
                .cart-success{
                    width:800px;
                    margin:0 auto;
                }
            }
            .cart-success .cart-heading{
                text-align: center;
                margin-bottom:30px;
            }
            .cart-success .cart-heading > span{
                text-transform: uppercase;
                font-weight: 700;
            }
            .discover-text > *{
                display: inline-block;
                padding:10px 25px;
                background: #7A95A2;
                border-radius: 16px;
                cursor:pointer;
                color:#fff;
            }
            .discover-text{
                text-align: center;
            }
            .checkout-box{
                border:1px solid #000;
                padding:15px 20px;
                border-radius: 16px;
            }
            .cart-success .panel-body{
                margin-bottom:40px;
            }
            .checkout-box-head{
                margin-bottom:30px;
            }

            .checkout-box-head .order-title{
                border:1px solid #000;
                border-radius: 16px;
                padding:10px 15px;
                font-weight: 700;
                font-size:16px;
            }
            .checkout-box-head .order-date{
                display: flex;
                align-items: center;
                font-size:16px;
                font-weight: bold;
                text-align: center;
            }
            .cart-success .table{
                width:100%;
                border-spacing: 0;
                background: #d9d9d9;
                border-collapse: inherit;
            }
            .cart-success .table thead>tr th{
                color:#fff;
                background: #7A95A2;
                font-weight: 500;
                font-size:14px;
                vertical-align: middle;
                border-bottom: 2px solid #dee2e6;
                text-align: center;
                border:none;
                padding:12px 15px;
            }
            .cart-success tbody tr td{
                padding:12px 15px;
                vertical-align: middle;
                font-size: 14px;
                color:#000;
                border-bottom:1px solid #ccc;
            }
            .cart-success tfoot{
                background: #fff;
            }
            .cart-success tfoot tr td{
                padding:8px;
            }

            .cart-success .table td:last-child{
                text-align: right;
            }
            .cart-success .table tbody tr:nth-child(2n) td {
                background-color: #d9d9d9;
            }
            .total_payment{
                font-weight: bold;
                font-size:24px;
            }
            .panel-foot .checkout-box div{
                margin-bottom:15px;
                font-weight: 500;
            }
            .uk-text-left{
                text-align: left;
            }
            .uk-text-right{
                text-align: right;
            }
            .uk-text-center{
                text-align: center;
            }
        </style>
    </head>
    <body>
        <div class="cart-success">
            <div class="panel-body">
                <h2 class="cart-heading"><span>Thông tin đơn hàng</span></h2>
                <div class="checkout-box">
                    <div class="checkout-box-head">
                        <div class="uk-grid uk-grid-medium uk-flex uk-flex-middle">
                            <div class="uk-width-large-1-3"></div>
                            <div class="uk-width-large-1-3">
                                <div class="order-title uk-text-center">ĐƠN HÀNG #{{ $data['order']->code }}</div>
                            </div>
                            <div class="uk-width-large-1-3">
                                <div class="order-date">{{ convertDateTime($data['order']->created_at); }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="checkout-box-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="uk-text-left">Tên sản phẩm</th>
                                    <th class="uk-text-center">Số lượng</th>
                                    <th class="uk-text-center">Giá niêm yết</th>
                                    <th class="uk-text-right">Giá bán</th>
                                    <th class="uk-text-right">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($data['carts']['carts'] as $da)
                            <tr>
                                <td class="uk-text-left">{{ $da['name'] }}</td>
                                <td class="uk-text-center">{{ $da['qty'] }}</td>
                                <td class="uk-text-right">{{ $da['price'] }}đ</td>
                                <td class="uk-text-right">{{ $da['price'] }}đ</td>
                                <td class="uk-text-right"><strong>{{$da['subtotal'] }}đ</strong></td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4">Mã giảm giá</td>
                                <td><strong>{{-- $data['order']->promotion['code'] --}}</strong></td>
                            </tr>
                            <tr>
                                <td colspan="4">Tổng giá trị sản phẩm</td>
                                <td><strong>{{-- data['carts']['total_price'] --}}0đ</strong></td>
                            </tr>
                            <tr>
                                <td colspan="4">Tổng giá trị khuyến mãi</td>
                                <td><strong>{{-- convert_price($data['order']->promotion['discount'], true) --}}0đ</strong></td>
                            </tr>
                            <tr>
                                <td colspan="4">Phí giao hàng</td>
                                <td><strong>0đ</strong></td>
                            </tr>
                           
                            <tr class="total_payment">
                                <td colspan="4"><span>Tổng thanh toán</span></td>
                                <td>{{$data['carts']['total_price'] }}</td>
                            </tr>
                        </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <div class="panel-foot">
                <h2 class="cart-heading"><span>Thông tin nhận hàng</span></h2>
                <div class="checkout-box">
                    <div>Tên người nhận: {{ $data['order']->fullname }}<span></span></div>
                    <div>Email: {{ $data['order']->email }}<span></span></div>
                    <div>Địa chỉ: {{ $data['order']->address }}<span></span></div>
                    <div>Số điện thoại: {{ $data['order']->phone }}<span></span></div>
                    <div>Hình thức thanh toán: {{ array_column(__('payment.method'), 'title', 'name')[$data['order']->method] ?? '-' }}<span></span></div>
                </div>
            </div>
        </div>
    </body>
</html> -->
<!DOCTYPE html>
<html lang="vi">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Thông tin đơn hàng</title>
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap');
            
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
                font-family: 'Roboto', sans-serif;
            }
            
            body {
                background-color: #f5f5f5;
                color: #333;
                padding: 20px;
            }
            
            .cart-success {
                max-width: 900px;
                margin: 20px auto;
                background: #fff;
                border-radius: 20px;
                box-shadow: 0 5px 15px rgba(0,0,0,0.1);
                padding: 30px;
            }
            
            .cart-heading {
                text-align: center;
                margin-bottom: 30px;
                color: #333;
                position: relative;
                padding-bottom: 15px;
            }
            
            .cart-heading:after {
                content: "";
                position: absolute;
                bottom: 0;
                left: 50%;
                transform: translateX(-50%);
                width: 80px;
                height: 3px;
                background: #7A95A2;
                border-radius: 3px;
            }
            
            .cart-heading > span {
                text-transform: uppercase;
                font-weight: 700;
                font-size: 22px;
                letter-spacing: 1px;
            }
            
            .checkout-box {
                border: 1px solid #e0e0e0;
                padding: 25px;
                border-radius: 16px;
                margin-bottom: 40px;
                box-shadow: 0 3px 8px rgba(0,0,0,0.05);
                background: #fff;
            }
            
            .order-title {
                background: #7A95A2;
                color: white;
                border-radius: 12px;
                padding: 12px 20px;
                font-weight: 700;
                font-size: 18px;
                text-align: center;
                margin-bottom: 15px;
                letter-spacing: 1px;
                box-shadow: 0 3px 6px rgba(0,0,0,0.1);
            }
            
            .order-date {
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 16px;
                font-weight: 500;
                color: #555;
                margin-bottom: 30px;
            }
            
            .table {
                width: 100%;
                border-spacing: 0;
                border-collapse: separate;
                border-radius: 12px;
                overflow: hidden;
                box-shadow: 0 2px 8px rgba(0,0,0,0.08);
                margin-bottom: 20px;
            }
            
            .table thead > tr th {
                color: #fff;
                background: #7A95A2;
                font-weight: 600;
                font-size: 15px;
                padding: 16px;
                text-align: center;
                border: none;
            }
            
            .table tbody tr td {
                padding: 14px 16px;
                vertical-align: middle;
                font-size: 15px;
                color: #333;
                border-bottom: 1px solid #eee;
                background-color: #f9f9f9;
            }
            
            .table tbody tr:nth-child(2n) td {
                background-color: #f0f0f0;
            }
            
            .table tbody tr:last-child td {
                border-bottom: none;
            }
            
            .table td:last-child {
                text-align: right;
                font-weight: 500;
            }
            
            .table tfoot {
                background: #fff;
            }
            
            .table tfoot tr td {
                padding: 14px 16px;
                border-top: 1px solid #eee;
                color: #555;
                font-weight: 500;
            }
            
            .table tfoot tr.total_payment {
                font-weight: bold;
                font-size: 20px;
                background-color: #f5f5f5;
            }
            
            .table tfoot tr.total_payment td {
                padding: 18px 16px;
                color: #333;
            }
            
            .customer-info-item {
                margin-bottom: 15px;
                font-size: 16px;
                display: flex;
            }
            
            .customer-info-item strong {
                width: 180px;
                font-weight: 600;
                color: #555;
            }
            
            .customer-info-item span {
                color: #333;
                flex: 1;
            }
            
            .payment-method {
                background: #f5f5f5;
                padding: 10px 15px;
                border-radius: 10px;
                margin-top: 15px;
                font-weight: 600;
                border-left: 4px solid #7A95A2;
            }
            
            .uk-text-left {
                text-align: left;
            }
            
            .uk-text-right {
                text-align: right;
            }
            
            .uk-text-center {
                text-align: center;
            }
            
            @media (max-width: 768px) {
                .cart-success {
                    padding: 20px 15px;
                }
                
                .checkout-box {
                    padding: 15px;
                }
                
                .table thead > tr th {
                    font-size: 13px;
                    padding: 12px 8px;
                }
                
                .table tbody tr td {
                    font-size: 13px;
                    padding: 12px 8px;
                }
                
                .customer-info-item {
                    flex-direction: column;
                }
                
                .customer-info-item strong {
                    width: 100%;
                    margin-bottom: 5px;
                }
            }
        </style>
    </head>
    <body>
        <div class="cart-success">
            <div class="panel-body">
                <h2 class="cart-heading"><span>Thông tin đơn hàng</span></h2>
                <div class="checkout-box">
                    <div class="order-title">ĐƠN HÀNG #{{ $data['order']->code }}</div>
                    <div class="order-date">Ngày đặt: {{ convertDateTime($data['order']->created_at); }}</div>
                    
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="uk-text-left">Tên sản phẩm</th>
                                <th class="uk-text-center">Số lượng</th>
                                <th class="uk-text-center">Giá niêm yết</th>
                                <th class="uk-text-right">Giá bán</th>
                                <th class="uk-text-right">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($data['carts']['carts'] as $da)
                            <tr>
                                <td class="uk-text-left">{{ $da['name'] }}</td>
                                <td class="uk-text-center">{{ $da['qty'] }}</td>
                                <td class="uk-text-right">{{ $da['price'] }}đ</td>
                                <td class="uk-text-right">{{ $da['price'] }}đ</td>
                                <td class="uk-text-right">{{ $da['subtotal'] }}đ</td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4">Mã giảm giá</td>
                                <td>{{-- $data['order']->promotion['code'] --}}</td>
                            </tr>
                            <tr>
                                <td colspan="4">Tổng giá trị sản phẩm</td>
                                <td>{{-- data['carts']['total_price'] --}}0đ</td>
                            </tr>
                            <tr>
                                <td colspan="4">Tổng giá trị khuyến mãi</td>
                                <td>{{-- convert_price($data['order']->promotion['discount'], true) --}}0đ</td>
                            </tr>
                            <tr>
                                <td colspan="4">Phí giao hàng</td>
                                <td>0đ</td>
                            </tr>
                           
                            <tr class="total_payment">
                                <td colspan="4"><span>Tổng thanh toán</span></td>
                                <td>{{ $data['carts']['total_price'] }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="panel-foot">
                <h2 class="cart-heading"><span>Thông tin nhận hàng</span></h2>
                <div class="checkout-box">
                    <div class="customer-info-item">
                        <strong>Tên người nhận:</strong>
                        <span>{{ $data['order']->fullname }}</span>
                    </div>
                    <div class="customer-info-item">
                        <strong>Email:</strong>
                        <span>{{ $data['order']->email }}</span>
                    </div>
                    <div class="customer-info-item">
                        <strong>Địa chỉ:</strong>
                        <span>{{ $data['order']->address }}</span>
                    </div>
                    <div class="customer-info-item">
                        <strong>Số điện thoại:</strong>
                        <span>{{ $data['order']->phone }}</span>
                    </div>
                    <div class="payment-method">
                        Hình thức thanh toán: {{ array_column(__('payment.method'), 'title', 'name')[$data['order']->method] ?? '-' }}
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>