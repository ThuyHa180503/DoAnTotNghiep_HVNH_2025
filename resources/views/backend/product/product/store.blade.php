@include('backend.dashboard.component.breadcrumb', ['title' => $config['seo'][$config['method']]['title']])
@include('backend.dashboard.component.formError')
@php
    $url = ($config['method'] == 'create') ? route('product.store') : route('product.update', [$product->id, $queryUrl ?? '']);
@endphp
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
                                    <label class="control-label">Chọn thương hiệu</label>
                                    <select name="product_brand_id" class="form-control setupSelect2" id="brandSelect">
                                        <option value="">-- Chọn thương hiệu --</option>
                                        @foreach($brands as $brand)
                                            <option 
                                                {{ old('product_brand_id') == $brand->id ? 'selected' : '' }} 
                                                value="{{ $brand->id }}">
                                                {{ $brand->name ?? 'Không có tên' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                        </div>
                        
                    </div>
                    <div class="row ">
                        <div class="col-lg-12">
                            <div class="form-row">
                                <label class="control-label">Chọn giải giá</label>
                                <select name="sub_brand_id" class="form-control setupSelect2" id="subBrandSelect" style="display: none;">
                                    <option value="">-- Chọn giải giá --</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-row mb15">
                        <label class="control-label text-left">
                            <input type="checkbox" name="order" value="1"
                                {{ old('default_customer') ? 'checked' : '' }}>
                            Cho phép order
                            <i class="fa fa-info-circle text-primary" data-toggle="tooltip" data-placement="right"
                            title="Chọn để đánh dấu cho phép order khi hết hàng"
                            style="background-color: white; color: red;"></i>
                        </label>
                    </div>

                </div>
                
            </div>

                @include('backend.product.product.component.aside')
            </div>
        </div>
        @include('backend.dashboard.component.button')
    </div>
</form>


<script>
$(document).ready(function () {
    let subBrands = @json($sub_brands); 

    $('#brandSelect').change(function () {
        let selectedBrand = $(this).val();
        let subBrandSelect = $('#subBrandSelect');
        subBrandSelect.empty().append('<option value="">-- Chọn giải giá --</option>');

        if (selectedBrand) {
            let filteredSubBrands = subBrands.filter(sub => sub.brand_id == selectedBrand);

            if (filteredSubBrands.length > 0) {
                filteredSubBrands.forEach(sub => {
                    subBrandSelect.append(`<option value="${sub.id}">${sub.name ?? 'Không có tên'}</option>`);
                });
                subBrandSelect.show();
            } else {
                subBrandSelect.hide();
            }
        } else {
            subBrandSelect.hide();
        }
    });
    $('#brandSelect').trigger('change');
});


</script>
