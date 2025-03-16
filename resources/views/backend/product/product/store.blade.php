@include('backend.dashboard.component.breadcrumb', ['title' => $config['seo'][$config['method']]['title']])
@include('backend.dashboard.component.formError')
@php
    $url = ($config['method'] == 'create') ? route('product.store') : route('product.update', [$product->id, $queryUrl ?? '']);
@endphp
<form action="{{ $url }}" method="post" class="box">
    @csrf
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-9">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>{{ __('messages.tableHeading') }}</h5>
                    </div>
                    <div class="ibox-content">
                        @include('backend.dashboard.component.content', ['model' => ($product) ?? null])
                    </div>
                </div>
               @include('backend.dashboard.component.album', ['model' => ($product) ?? null])
               @include('backend.product.product.component.variant')
               @include('backend.dashboard.component.seo', ['model' => ($product) ?? null])
            </div>
            <div class="col-lg-3">
            <div class="ibox w">
                <div class="ibox-title">
                    <h5>CHỌN THƯƠNG HIỆU</h5>
                </div>
                <div class="ibox-content">
                    <div class="row mb15">
                        <div class="col-lg-12">
                            <div class="form-row">
                                <select name="product_brand_id" class="form-control setupSelect2">
                                    @foreach($product_brands as $brand)
                                        <option value="{{ $brand->id }}" 
                                            {{ old('product_brand_id', isset($brand->id) ? $brand->id : '') == $brand->id ? 'selected' : '' }}>
                                            {{ $brand->brand_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

                @include('backend.product.product.component.aside')
            </div>
        </div>
        @include('backend.dashboard.component.button')
    </div>
</form>
