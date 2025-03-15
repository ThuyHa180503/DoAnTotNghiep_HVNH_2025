@include('backend.dashboard.component.breadcrumb', ['title' => 'Chỉnh sửa dải giá'])
@include('backend.dashboard.component.formError')

<form action="{{ route('price_range.update', $price_range->id) }}" method="post" class="box">
    @csrf
    @method('PUT')
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row"> 
            <div>
                <div class="ibox w">
                    <div class="ibox-title">
                        <h5>Thông tin dải giá</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label class="control-label">Chọn thương hiệu</label>
                                    <select name="brand_id" class="form-control setupSelect2">
                                        <option value="">-- Chọn thương hiệu --</option>
                                        @foreach($brands as $brand)
                                            <option 
                                                {{ (old('brand_id', $price_range->brand_id) == $brand->id) ? 'selected' : '' }} 
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
                                    <label class="control-label">Loại giá trị</label>
                                    <select name="value_type" class="form-control">
                                        <option value="percentage" {{ old('value_type', $price_range->value_type) == 'percentage' ? 'selected' : '' }}>Phần trăm (%)</option>
                                        <option value="fixed" {{ old('value_type', $price_range->value_type) == 'fixed' ? 'selected' : '' }}>Giá trị cố định</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label class="control-label">Giá trị</label>
                                    <input type="number" step="0.01" name="value" class="form-control" 
                                        value="{{ old('value', $price_range->value) }}" placeholder="Nhập giá trị dải giá">
                                </div>
                            </div>
                        </div>

                        <!-- Cập nhật range_from và range_to -->
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label class="control-label">Dải giá từ</label>
                                    <input type="number" step="0.01" name="range_from" class="form-control" 
                                        value="{{ old('range_from', $price_range->range_from) }}" placeholder="Nhập giá trị bắt đầu">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label class="control-label">Dải giá đến</label>
                                    <input type="number" step="0.01" name="range_to" class="form-control" 
                                        value="{{ old('range_to', $price_range->range_to) }}" placeholder="Nhập giá trị kết thúc">
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
