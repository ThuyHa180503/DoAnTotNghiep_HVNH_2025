@include('backend.dashboard.component.breadcrumb', ['title' => 'Thêm mới dải giá'])
@include('backend.dashboard.component.formError')

<form action="{{ route('price_range.store') }}" method="post" class="box">
    @csrf
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row"> 
            <div class="col-lg-12">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Thông tin dải giá</h5>
                    </div>
                    <div class="ibox-content">
                        
                        <!-- Tên Dải Giá -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="control-label">Tên dải giá</label>
                                    <input type="text" name="range_name" class="form-control" value="{{ old('range_name') }}" placeholder="Nhập tên dải giá">
                                </div>
                            </div>
                        </div>

                        <!-- Chọn Thương Hiệu -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
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

                        <!-- Nhập khoảng giá -->
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">Dải giá từ</label>
                                    <input type="number" step="0.01" id="range_from" class="form-control" placeholder="Nhập giá trị bắt đầu">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">Dải giá đến</label>
                                    <input type="number" step="0.01" id="range_to" class="form-control" placeholder="Nhập giá trị kết thúc">
                                </div>
                            </div>
                        </div>

                        <!-- Loại Giá Trị -->
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">Loại giá trị</label>
                                    <select name="value_type" class="form-control">
                                        <option value="percentage" {{ old('value_type') == 'percentage' ? 'selected' : '' }}>Phần trăm (%)</option>
                                        <option value="fixed" {{ old('value_type') == 'fixed' ? 'selected' : '' }}>Giá trị cố định</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">Giá trị</label>
                                    <input type="number" step="0.01" name="value" class="form-control" value="{{ old('value') }}" placeholder="Nhập giá trị dải giá">
                                </div>
                            </div>
                        </div>

                        <!-- Nút Thêm và Danh Sách Dải Giá -->
                        <button type="button" class="btn btn-primary mt-3" onclick="validateRange()">Thêm khoảng giá</button>

                        <table class="table table-bordered mt-3">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Dải giá từ</th>
                                    <th>Dải giá đến</th>
                                    <th>Loại giá trị</th>
                                    <th>Giá trị</th>
                                </tr>
                            </thead>
                            <tbody id="ranges_list"></tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
        @include('backend.dashboard.component.button')
    </div>
</form>

<script>
    let ranges = []; // Lưu danh sách các khoảng giá

    function validateRange() {
        let from = parseFloat(document.getElementById("range_from").value);
        let to = parseFloat(document.getElementById("range_to").value);
        let valueType = document.querySelector('select[name="value_type"]').value;
        let value = parseFloat(document.querySelector('input[name="value"]').value);

        if (isNaN(from) || isNaN(to) || from >= to) {
            alert("Khoảng giá không hợp lệ! Giá trị bắt đầu phải nhỏ hơn giá trị kết thúc.");
            return;
        }

        if (isNaN(value)) {
            alert("Vui lòng nhập giá trị hợp lệ.");
            return;
        }

        // Kiểm tra khoảng giá có bị chồng lấn không
        for (let range of ranges) {
            if (!(to <= range.from || from >= range.to)) {
                alert(`Khoảng giá bị chồng lấn với: ${range.from} - ${range.to}`);
                return;
            }
        }

        // Nếu hợp lệ, thêm vào danh sách
        ranges.push({ from, to, valueType, value });

        renderRanges();

        // Reset input
        document.getElementById("range_from").value = "";
        document.getElementById("range_to").value = "";
        document.querySelector('input[name="value"]').value = "";
    }

    function removeRange(index) {
        ranges.splice(index, 1);
        renderRanges();
    }

    function renderRanges() {
        let tableBody = document.getElementById("ranges_list");
        tableBody.innerHTML = "";
        ranges.forEach((range, index) => {
            tableBody.innerHTML += `
                <tr>
                    <td>${index + 1}</td>
                    <td>${range.from}</td>
                    <td>${range.to}</td>
                    <td>${range.valueType === 'percentage' ? 'Phần trăm (%)' : 'Giá trị cố định'}</td>
                    <td>${range.value}</td>
                    <td><button type="button" class="btn btn-danger btn-sm" onclick="removeRange(${index})">Xóa</button></td>
                </tr>
            `;
        });
    }
</script>

