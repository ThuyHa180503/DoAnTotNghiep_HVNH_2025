@include('backend.dashboard.component.breadcrumb', ['title' => 'Thêm mới nhóm giá'])
@include('backend.dashboard.component.formError')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

                        <div class="row">
    <div class="col-lg-12">
        <div class="form-row">
            <label class="control-label">Chọn giải giá</label>
            <select name="sub_brand_id" class="form-control setupSelect2" id="subBrandSelect" style="display: none;">
                <option value="">-- Chọn giải giá --</option>
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
<!-- 
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách thương hiệu và danh mục</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .brand-container {
            max-width: 600px;
        }
        .brand {
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            padding: 8px;
            background: #f1f1f1;
            margin-top: 5px;
            display: flex;
            align-items: center;
            border-radius: 5px;
        }
        .toggle-btn {
            margin-right: 10px;
            cursor: pointer;
            font-size: 16px;
        }
        .category-list {
            display: none;
            padding-left: 20px;
        }
        .category {
            padding: 5px;
            display: flex;
            align-items: center;
        }
        .discount-input {
            margin-left: auto;
            padding: 3px;
            width: 70px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }
        .price-dropdown {
            margin-left: auto;
        }
    </style>
</head>
<body>

    <h2>Tạo nhóm giá</h2>

    <label for="group-name">Tên nhóm giá:</label>
    <input type="text" id="group-name" placeholder="Nhập tên nhóm giá" style="width: 100%; padding: 5px; margin-bottom: 10px;">

    <div class="brand-container">

        <!-- Thương hiệu MLB -->
        <div class="brand" data-brand="MLB">
            <span class="toggle-btn">[+]</span> MLB
            <select class="price-dropdown">
                <option value="">Chọn giá</option>
                <option value="100k">100k</option>
                <option value="200k">200k</option>
            </select>
        </div>
        <div class="category-list" id="MLB">
            <div class="category">Quần áo <input type="text" class="discount-input" placeholder="Giảm giá %"></div>
            <div class="category">Mũ <input type="text" class="discount-input" placeholder="Giảm giá %"></div>
        </div>

        <!-- Thương hiệu Nike -->
        <div class="brand" data-brand="Nike">
            <span class="toggle-btn">[+]</span> Nike
            <select class="price-dropdown">
                <option value="">Chọn giá</option>
                <option value="150k">150k</option>
                <option value="250k">250k</option>
            </select>
        </div>
        <div class="category-list" id="Nike">
            <div class="category">Giày <input type="text" class="discount-input" placeholder="Giảm giá %"></div>
            <div class="category">Quần áo thể thao <input type="text" class="discount-input" placeholder="Giảm giá %"></div>
        </div>

        <!-- Thương hiệu Adidas -->
        <div class="brand" data-brand="Adidas">
            <span class="toggle-btn">[+]</span> Adidas
            <select class="price-dropdown">
                <option value="">Chọn giá</option>
                <option value="180k">180k</option>
                <option value="280k">280k</option>
            </select>
        </div>
        <div class="category-list" id="Adidas">
            <div class="category">Áo khoác <input type="text" class="discount-input" placeholder="Giảm giá %"></div>
            <div class="category">Giày thể thao <input type="text" class="discount-input" placeholder="Giảm giá %"></div>
        </div>

    </div>

    <script>
        $(document).ready(function () {
            $(".brand").click(function () {
                let brandName = $(this).data("brand");
                let categoryList = $("#" + brandName);
                let toggleBtn = $(this).find(".toggle-btn");

                categoryList.slideToggle();
                toggleBtn.text(categoryList.is(":visible") ? "[-]" : "[+]");
            });
        });
    </script>

</body>
</html> -->
