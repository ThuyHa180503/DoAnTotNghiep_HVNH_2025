@include('backend.dashboard.component.breadcrumb', ['title' => 'Chỉnh sửa nhóm giá'])
@include('backend.dashboard.component.formError')

<form action="{{ route('price_group.update', $price_group->id) }}" method="post" class="box">
    @csrf
    @method('PUT')
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
                                    <input type="text" name="name" class="form-control" value="{{ old('name') ?? $price_group->name }}" placeholder="Nhập tên nhóm giá">

                                </div>
                            </div>
                        </div>

                        <div class="row mb15">
    <div class="col-lg-12">
        <div class="form-row">
            <label class="control-label">Danh mục</label>
<input type="text" name="catalogue_name" readonly class="form-control" value="{{ $price_group->catalogue_name }}">


        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="form-row">
            <label class="control-label">Thương hiệu</label>
            <input type="text" class="form-control" value="{{ $price_group->brand_name }}" readonly>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="form-row">
            <label class="control-label">Dải giá</label>
            <input type="text" class="form-control" value="{{ $price_group->sub_brand_name }}" readonly>
        </div>
    </div>
</div>


                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label class="control-label">Giảm giá (%)</label>
                                    <input type="number" name="discount" class="form-control" value="{{ old('discount', $price_group->discount) }}" placeholder="Nhập giảm giá">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label class="control-label">Phí vận chuyển</label>
                                    <input type="number" name="shipping" class="form-control" value="{{ old('shipping', $price_group->shipping) }}" placeholder="Nhập phí vận chuyển">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label class="control-label">Tỷ giá hối đoái</label>
                                    <input type="number" step="0.01" name="exchange_rate" class="form-control" value="{{ old('exchange_rate', $price_group->exchange_rate) }}" placeholder="Nhập tỷ giá hối đoái">
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
