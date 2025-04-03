<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="function.js"></script>
@php
$name = $product->name;
$canonical = write_url($product->canonical);
$image = image($product->image);
$price = getPrice($product);
$catName = $productCatalogue->name;
$review = getReview($product);
$description = $product->description;
$attributeCatalogue = $product->attributeCatalogue;
$gallery = json_decode($product->album);
$initialQuantity = $product->product_variants->sum('quantity'); // Số lượng ban đầu
$initialAllowOrder = $product->allow_order; // Giá trị allow_order ban đầu
@endphp
<div class="panel-body">
    <div class="uk-grid uk-grid-medium">
        <div class="uk-width-large-1-2">
            @if(!is_null($gallery))
            <div class="popup-gallery">
                <div class="swiper-container">
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-wrapper big-pic">
                        <?php foreach ($gallery as $key => $val) {  ?>
                            <div class="swiper-slide" data-swiper-autoplay="2000">
                                <a href="{{ image($val) }}" data-uk-lightbox="{group:'my-group'}" class="image img-scaledown"><img src="{{ image($val) }}" alt="<?php echo $val ?>"></a>
                            </div>
                        <?php }  ?>
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
                <div class="swiper-container-thumbs">
                    <div class="swiper-wrapper pic-list">
                        <?php foreach ($gallery as $key => $val) {  ?>
                            <div class="swiper-slide">
                                <span class="image img-scaledown"><img src="{{ image($val) }}" alt="<?php echo $val ?>"></span>
                            </div>
                        <?php }  ?>
                    </div>
                </div>
            </div>
            @endif
        </div>
        <div class="uk-width-large-1-2">
            <div class="popup-product">
                <h1 class="title product-main-title"><span>{{ $name }}</span></h1>
                <div class="rating">
                    <div class="uk-flex uk-flex-middle">
                        <div class="author">Đánh giá: </div>
                        <div class="star-rating">
                            <div class="stars" style="--star-width: 8{{ rand(1, 9) }}%"></div>
                        </div>
                    </div>
                </div>

                <div class="product-specs">
                    <div class="spec-row">Mã sản phẩm: <strong>{{ $product->code }}</strong></div>
                    <div class="spec-row">Tình Trạng: <strong id="stock-status">Còn hàng</strong></div>
                </div>

                <div class="uk-grid uk-grid-small">
                    <div class="uk-width-large-1-2">
                        <div class="a-left">
                            {!! $price['html'] !!}
                            @if($price['price'] != $price['priceSale'])
                            <div class="price-save">
                                Tiết kiệm: <strong>{{ convert_price($price['price'] - $price['priceSale'], true) }}</strong> (<span style="color:red">-{{ $price['percent'] }}%</span>)
                            </div>
                            @endif
                            @include('frontend.product.product.component.variant')
                            <div class="quantity mt10">
                                <div class="uk-flex uk-flex-middle">
                                    <div class="quantitybox uk-flex uk-flex-middle">
                                        <div class="minus quantity-button">-</div>
                                        <input type="text" name="quantity" id="quantityInput" value="1" class="quantity-text">
                                        <div class="plus quantity-button">+</div>
                                    </div>
                                </div>
                                <div class="btn-group uk-flex uk-flex-middle" style="margin-top:10px">
                                    <!-- Nội dung ban đầu sẽ được JS cập nhật -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="uk-width-large-1-2">
                        <div class="a-right">
                            <div class="mb20"><strong>Dịch vụ của chúng tôi</strong></div>
                            <div class="panel-body">
                                <div class="right-item">
                                    <div class="label">Cam kết bán hàng</div>
                                    <div class="desc">✅Chính hãng có thẻ bảo hành đầy đủ</div>
                                </div>
                                <div class="right-item">
                                    <div class="label">CHĂM SÓC KHÁCH HÀNG</div>
                                    <div class="desc">✅Tư vấn nhiệt tình, lịch sự, trung thực</div>
                                </div>
                                <div class="right-item">
                                    <div class="label">CHÍNH SÁCH GIAO HÀNG</div>
                                    <div class="desc">✅Đồng kiểm →Thử hàng →Hài lòng thanh toán</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="product-description">
                    {!! $product->languages->first()->pivot->description !!}
                </div>
            </div>
        </div>
    </div>
    <div class="uk-grid uk-grid-medium">
        <div class="uk-width-large">
            <div class="product-wrapper">
                @include('frontend.product.product.component.general')
                {{-- ẩn đánh giá --}}
                @include('frontend.product.product.component.review', ['model' => $product, 'reviewable' => 'App\Models\Product'])
            </div>
        </div>
    </div>

    
        <div class="uk-container uk-container-center">
            <div class="panel-product">
                <h2 class="heading1" style="color: #333; text-align: center;">Sản phẩm cùng danh mục</h2>
                <div class="panel-body list-product">
                    @if(count($productCatalogue->products))
                    <div class="uk-grid uk-grid-medium">
                        @foreach($productCatalogue->products as $index => $product)
                        @if($index > 7) @break @endif
                        <div class="uk-width-1-2 uk-width-small-1-2 uk-width-medium-1-3 uk-width-large-1-5 mb20">
                            @include('frontend.component.product-item', ['product' => $product])
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
   

  
        <div class="uk-container uk-container-center">
            <div class="panel-product">
                <h2 class="heading1" style="text-align: center;">Sản phẩm đã xem</h2>
                <div class="panel-body list-product">
                    @if(!is_null($cartSeen) && isset($cartSeen) )
                    <div class="uk-grid uk-grid-medium">
                        @foreach($cartSeen as $key => $val)
                        @php
                        $name = $val->name;
                        $canonical = $val->options['canonical'];
                        $image = $val->options['image'];
                        $priceSeen = number_format($val->price, 0, ',', '.');
                        @endphp
                        <div class="uk-width-1-2 uk-width-small-1-2 uk-width-medium-1-3 uk-width-large-1-5 mb20">

                            <div class="product-item product">
                                <a href="{{ $canonical }}" class="image img-scaledown img-zoomin"><img src="{{ $image }}" alt="{{ $name }}"></a>
                                <div class="info">
                                    <h3 class="title"><a href="{{ $canonical }}" title="{{ $name }}">{{ $name }}</a></h3>
                                    <div class="price">
                                        <div class="price-sale">{{ $priceSeen }}</div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
   
</div>

<!-- Phần còn lại của HTML giữ nguyên -->
<input type="hidden" class="productName" value="{{ $product->name }}">
<input type="hidden" class="attributeCatalogue" value="{{ json_encode($attributeCatalogue) }}">
<input type="hidden" class="productCanonical" value="{{ write_url($product->canonical) }}">
<input type="hidden" id="initialQuantity" value="{{ $initialQuantity }}">
<input type="hidden" id="initialAllowOrder" value="{{ $initialAllowOrder }}">
<input type="hidden" id="isAuthenticated" value="{{ auth('customer')->check() ? '1' : '0' }}">
<input type="hidden" id="loginUrl" value="{{ route('fe.auth.login') }}">
<input type="hidden" id="cartStoreUrl" value="{{ route('cart.storeCart2') }}">
<input type="hidden" id="cartOrderUrl" value="{{ route('cart.storeCartOrder') }}">
<input type="hidden" id="productId" value="{{ $product->id }}">

<script>
    (function($) {
        "use strict";
        var HT = {};
        var currentVariantId = null;
        var currentQuantity = $('#initialQuantity').val(); // Lấy quantity ban đầu từ PHP
        var allowOrder = $('#initialAllowOrder').val(); // Lấy allow_order ban đầu từ PHP

        HT.popupSwiperSlide = () => {
            document.querySelectorAll(".popup-gallery").forEach(popup => {
                var swiper = new Swiper(popup.querySelector(".swiper-container"), {
                    loop: true,
                    autoplay: {
                        delay: 2000,
                        disableOnInteraction: false
                    },
                    pagination: {
                        el: '.swiper-pagination'
                    },
                    navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev'
                    },
                    thumbs: {
                        swiper: {
                            el: popup.querySelector('.swiper-container-thumbs'),
                            slidesPerView: 3,
                            spaceBetween: 10,
                            slideToClickedSlide: true,
                        }
                    }
                });
            });
        }

        HT.changeQuantity = () => {
            $(document).on('click', '.quantity-button', function() {
                let _this = $(this);
                let quantityInput = $('.quantity-text');
                let currentQty = parseInt(quantityInput.val());
                let newQty = _this.hasClass('minus') ? currentQty - 1 : currentQty + 1;
                if (newQty < 1) newQty = 1;
                quantityInput.val(newQty);
            });
        }

        HT.selectVariantProduct = () => {
            if ($('.choose-attribute').length) {
                $(document).on('click', '.choose-attribute', function(e) {
                    e.preventDefault();
                    let _this = $(this);
                    let attribute_id = _this.attr('data-attributeid');
                    let attribute_name = _this.text();
                    _this.parents('.attribute-item').find('span').html(attribute_name);
                    _this.parents('.attribute-value').find('.choose-attribute').removeClass('active');
                    _this.addClass('active');
                    HT.handleAttribute();
                });
            }
        }

        HT.handleAttribute = () => {
            let attribute_id = [];
            let flag = true;
            $('.attribute-value .choose-attribute').each(function() {
                if ($(this).hasClass('active')) {
                    attribute_id.push($(this).attr('data-attributeid'));
                }
            });

            $('.attribute').each(function() {
                if ($(this).find('.choose-attribute.active').length === 0) {
                    flag = false;
                    return false;
                }
            });

            if (flag) {
                $.ajax({
                    url: 'ajax/product/loadVariant',
                    type: 'GET',
                    data: {
                        'attribute_id': attribute_id,
                        'product_id': $('#productId').val(),
                        'language_id': $('input[name=language_id]').val(),
                    },
                    dataType: 'json',
                    success: function(res) {
                        currentVariantId = res.variant.id;
                        currentQuantity = res.variant.quantity; // Cập nhật quantity từ variant
                        $('#variant').val(currentVariantId);
                        HT.setUpVariantPrice(res);
                        HT.setupVariantGallery(res);
                        HT.setupVariantUrl(res, attribute_id);
                        HT.updateStockStatus(); // Cập nhật trạng thái tồn kho
                    },
                });
            }
        }

        HT.setupVariantUrl = (res, attribute_id) => {
            if (res.variant && res.variant.canonical) {
                let newUrl = res.variant.canonical;
                history.pushState({
                    attribute_id: attribute_id
                }, "Page Title", newUrl);
            }
        };

        HT.setUpVariantPrice = (res) => {
            $('.popup-product .price').html(res.variantPrice.html);
            $('#price_cart').val(res.variantPrice.price);
        }

        HT.setupVariantGallery = (gallery) => {
            let album = gallery.variant.album.split(',');
            if (album[0] == 0) {
                album = JSON.parse($('input[name=product_gallery]').val());
            }
            let html = `<div class="swiper-container">
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-wrapper big-pic">`;
            album.forEach((val) => {
                html += `<div class="swiper-slide" data-swiper-autoplay="2000">
                <a href="${val}" data-uk-lightbox="{group:'my-group'}" class="image img-scaledown"><img src="${val}" alt="${val}"></a>
            </div>`;
            });
            html += `</div><div class="swiper-pagination"></div></div>
        <div class="swiper-container-thumbs"><div class="swiper-wrapper pic-list">`;
            album.forEach((val) => {
                html += `<div class="swiper-slide"><span class="image img-scaledown"><img src="${val}" alt="${val}"></span></div>`;
            });
            html += `</div></div>`;
            $('.popup-gallery').html(html);
            HT.popupSwiperSlide();
        }

        HT.updateStockStatus = () => {
            $('#stock-status').text(currentQuantity > 0 ? 'Còn hàng' : 'Hết hàng');
            let $btnGroup = $('.btn-group');
            $btnGroup.empty();

            if (currentQuantity <= 0) {
                console.log(1);
                if (allowOrder == 0) {
                    $btnGroup.append('<div class="btn-item btn-out-of-stock"><span>Hết hàng</span></div>');
                } else if (allowOrder == 1) {

                    $btnGroup.append(`
                    <div class="btn-item btn-1 addToCart">
                        <form id="addToCartForm" action="${$('#cartOrderUrl').val()}" method="POST">
                            <input type="hidden" name="_token" value="${$('meta[name="csrf-token"]').attr('content')}">
                            <input type="hidden" name="qty_cart" id="qty_cart" value="1">
                            <input type="hidden" name="price_cart" id="price_cart" value="${$('#price_cart').val()}">
                            <input type="hidden" name="id_item" id="id_item" value="${$('#productId').val()}">
                            <input type="hidden" name="variant" id="variant" value="${currentVariantId || ''}">
                            <input type="hidden" name="type" id="type" value="2">
                        </form>
                        <div class="btn-item btn-1 addToCart1" style="margin-left:10px">
                            <a href="javascript:void(0);" class="add-to-cart-order">Order hàng</a>
                        </div>
                    </div>
                `);
                }
            } else {

                $btnGroup.append(`
                    <div class="btn-item btn-1 addToCart" data-id="${$('#productId').val()}">
                        <a href="" title="">Mua Ngay</a>
                    </div>
                    <form id="addToCartForm" action="${$('#cartStoreUrl').val()}" method="POST">
                        <input type="hidden" name="_token" value="${$('meta[name="csrf-token"]').attr('content')}">
                        <input type="hidden" name="qty_cart" id="qty_cart" value="1">
                        <input type="hidden" name="price_cart" id="price_cart" value="${$('#price_cart').val()}">
                        <input type="hidden" name="id_item" id="id_item" value="${$('#productId').val()}">
                        <input type="hidden" name="variant" id="variant" value="${currentVariantId || ''}">
                        <input type="hidden" name="type" id="type" value="2">
                    </form>
                    <div class="btn-item btn-1 addToCart1" style="margin-left:10px">
                        <a href="javascript:void(0);" class="add-to-cart">Giỏ hàng</a>
                    </div>
                `);

            }
        }

        HT.submitCart = () => {
            let quantity = $('.quantity-text').val();
            $('#qty_cart').val(quantity);
            $('#variant').val(currentVariantId);
            $('#addToCartForm').submit();
        }

        HT.loadProductVariant = () => {
            let attributeCatalogue = JSON.parse($('.attributeCatalogue').val());
            if (typeof attributeCatalogue != 'undefined' && attributeCatalogue.length) {
                HT.handleAttribute();
            } else {
                HT.updateStockStatus(); // Cập nhật trạng thái ban đầu nếu không có variant
            }
        }

        $(document).ready(function() {
            HT.changeQuantity();
            HT.popupSwiperSlide();
            HT.selectVariantProduct();
            HT.loadProductVariant();

            // Sự kiện cho nút "+ Giỏ hàng"
            $(document).on('click', '.add-to-cart', function(e) {
                e.preventDefault();
                HT.submitCart();
            });

            // Sự kiện cho nút "+ Giỏ hàng Order"
            $(document).on('click', '.add-to-cart-order', function(e) {
                e.preventDefault();
                HT.submitCart();
            });
        });

    })(jQuery);
</script>
<!-- Đảm bảo có meta tag CSRF trong layout -->
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
     .heading1 {
        padding-bottom: 10px;
        color: #333;
        margin: 0 0 10px;
        text-transform: uppercase;
        color: #333;
        
    }
</style>