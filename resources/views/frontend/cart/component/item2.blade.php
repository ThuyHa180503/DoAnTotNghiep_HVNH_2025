<div class="panel-body">
    @if (!empty($carts))
    <div class="cart-list">
        @php
        $tongTien = collect($carts)->sum(fn($cart) => ($cart->price ?? 0) * ($cart->qty ?? 0));

        @endphp
        @foreach($carts as $cart)
        <div class="cart-item">
            <div class="uk-grid uk-grid-medium">
                <div class="uk-width-small-1-1 uk-width-medium-1-5">
                    <div class="cart-item-image">
                        <span class="image img-scaledown"><img src="{{ $cart->image }}" alt=""></span>
                        <span class="cart-item-number">{{ $cart->qty }}</span>
                    </div>
                </div>
                <div class="uk-width-small-1-1 uk-width-medium-4-5">
                    <div class="cart-item-info">
                        <h3 style="max-width: 90%;" class="title"><span>{{ $cart->name }}
                                <em>{{ $cart->attribute }}</p>
                            </span></h3>
                        <div class="cart-item-action uk-flex uk-flex-middle uk-flex-space-between">
                            <div class="cart-item-qty" style="display: flex; align-items: center;">
                                <form action="" hidden></form>
                                <form action="{{ route('cart.update_1') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="type" value="{{ $type }}">
                                    <input type="hidden" name="product_id" value="{{ $cart->product_id ?? $cart->id }}">
                                    <input type="hidden" name="product_variant_id" value="{{ $cart->product_variant_id ?? '' }}">
                                    <input type="hidden" name="change" value="-1">
                                    <button type="submit" class="btn-qty2 minus">-</button>
                                </form>
                                <input type="text" class="input-qty" value="{{ $cart->qty }}" readonly>
                                <form action="{{ route('cart.update_1') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="type" value="{{ $type }}">
                                    <input type="hidden" name="product_id" value="{{ $cart->product_id ?? $cart->id }}">
                                    <input type="hidden" name="product_variant_id" value="{{ $cart->product_variant_id ?? '' }}">
                                    <input type="hidden" name="change" value="1">
                                    <button type="submit" class="btn-qty2 plus">+</button>
                                </form>
                            </div>
                            <div class="cart-item-price">
                                <span class="cart-price-sale">{{ convert_price($cart->price * $cart->qty, true) }}đ</span>
                            </div>
                            <form action="{{ route('cart.remove') }}" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="{{ $type == 2 ? 2 : 3 }}">
                                <input type="hidden" name="product_id" value="{{ $cart->id }}">
                                <button type="submit" class="cart-item-remove">✕</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="panel-foot mt30 pay">
        <div class="cart-summary mb20">
            <div class="cart-summary-item">
                <div class="uk-flex uk-flex-middle uk-flex-space-between">
                    <span class="summary-title">Giảm giá</span>
                    <div class="summary-value discount-value">-{{ convert_price($cartPromotion['discount'] ?? 0, true) }}đ</div>
                </div>
            </div>
            <div class="cart-summary-item">
                <div class="uk-flex uk-flex-middle uk-flex-space-between">
                    <span class="summary-title">Phí giao hàng</span>
                    <div class="summary-value">Miễn phí</div>
                </div>
            </div>
            <div class="cart-summary-item">
                <div class="uk-flex uk-flex-middle uk-flex-space-between">
                    <span class="summary-title bold">Tổng tiền</span>
                    <div class="summary-value cart-total">
                        {{ number_format($tongTien ?? 0, 0, ',', '.') }}đ
                    </div>
                </div>
            </div>
            <div class="buy-more">
                <a href="{{ write_url('san-pham') }}" class="btn-buymore">Chọn thêm sản phẩm khác</a>
            </div>
        </div>
    </div>
    @endif
</div>