@include('backend.dashboard.component.breadcrumb', ['title' => 'Thêm mới nhóm giá'])
@include('backend.dashboard.component.formError')

<form action="{{ route('price_group.store') }}" method="post" class="box">
    @csrf
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row"> 
            <div>
                <div class="ibox w">
                    <div class="ibox-title">
                        <h5>Thông tin nhóm giá</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label class="control-label">Tên nhóm giá</label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Nhập tên nhóm giá">
                                </div>
                            </div>
                        </div>

                        <div class="row mb15">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label class="control-label">Chọn danh mục</label>
                                    <select name="product_catalogue_id" class="form-control setupSelect2">
                                        <option value="">-- Chọn danh mục --</option>
                                        @foreach($categorys as $category)
                                            <option 
                                                {{ old('product_catalogue_id') == $category->id ? 'selected' : '' }} 
                                                value="{{ $category->id }}">
                                                {{ optional($category->product_catalogue_language->first())->name ?? 'Không có tên' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label class="control-label">Chọn thương hiệu</label>
                                    <select name="brand_id" class="form-control setupSelect2">
                                        <option value="">-- Chọn thương hiệu --</option>
                                        @foreach($brands as $brand)
                                            <option 
                                                {{ old('brand_id') == $brand->id ? 'selected' : '' }} 
                                                value="{{ $brand->id }}">
                                                {{ optional($brand->product_catalogue_language->first())->name ?? 'Không có tên' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label class="control-label">Giảm giá (%)</label>
                                    <input type="number" name="discount" class="form-control" value="{{ old('discount') }}" placeholder="Nhập giảm giá">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label class="control-label">Phí vận chuyển</label>
                                    <input type="number" name="shipping" class="form-control" value="{{ old('shipping') }}" placeholder="Nhập phí vận chuyển">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label class="control-label">Tỷ giá hối đoái</label>
                                    <input type="number" step="0.01" name="exchange_rate" class="form-control" value="{{ old('exchange_rate') }}" placeholder="Nhập tỷ giá hối đoái">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        @include('backend.dashboard.component.button')
    </div>
</form>
