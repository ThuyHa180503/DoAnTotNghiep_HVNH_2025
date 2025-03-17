@php
    $query = base64_encode(http_build_query(request()->query()));
    $queryUrl = rtrim($query, '=');
@endphp

<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th style="width:50px;">
                <input type="checkbox" id="checkAll" class="input-checkbox">
            </th>
            <th>{{ __('Thương hiệu & Dải giá') }}</th>
            <th>Dải giá</th>
            <th>{{ __('Loại giá trị') }}</th>
            <th>{{ __('Giá trị') }}</th>
            <th class="text-center" style="width:100px;">{{ __('Hành động') }}</th>
        </tr>
    </thead>
    <tbody>
        @if(isset($price_ranges) && $price_ranges->count() > 0)
            @php
                $groupedBrands = $price_ranges->groupBy('brand_name'); // Nhóm theo thương hiệu
            @endphp

            @foreach($groupedBrands as $brand_name => $brandRanges)
                {{-- Hiển thị thương hiệu --}}
                <tr>
                    <td><input type="checkbox" class="input-checkbox checkBoxItem" disabled></td>
                    <td colspan="5"><strong>|--- {{ $brand_name ?? 'N/A' }}</strong></td>
                </tr>

                @php
                    $groupedSubs = $brandRanges->groupBy('sub_name'); // Nhóm theo dải giá (sub->name)
                @endphp

                @foreach($groupedSubs as $sub_name => $priceRanges)
                    {{-- Hiển thị dải giá --}}
                    <tr>
                        <td><input type="checkbox" class="input-checkbox checkBoxItem" disabled></td>
                        <td colspan="5">|---|--- {{ $sub_name }}</td>
                    </tr>

                    @foreach($priceRanges as $priceRange)
                        {{-- Hiển thị cấp con (price_ranges->name) --}}
                        <tr id="row-{{ $priceRange->id }}">
                            <td>
                                <input type="checkbox" value="{{ $priceRange->id }}" class="input-checkbox checkBoxItem">
                            </td>
                            <td>|---|---|--- {{ $priceRange->name }}</td>
                            <td>
                                @if(fmod($priceRange->range_from, 1) == 0 && fmod($priceRange->range_to, 1) == 0)
                                    {{ number_format($priceRange->range_from, 0) }} - {{ number_format($priceRange->range_to, 0) }}
                                @else
                                    {{ number_format($priceRange->range_from, 2) }} - {{ number_format($priceRange->range_to, 2) }}
                                @endif
                            </td>
                            <td>
                                @if ($priceRange->value_type == 'percentage')
                                    Phần trăm (%)
                                @else
                                    Giá trị cố định
                                @endif
                            </td>
                            <td class="text-right">
                                @if(fmod($priceRange->value, 1) == 0)
                                    {{ number_format($priceRange->value, 0) }}
                                @else
                                    {{ number_format($priceRange->value, 2) }}
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('price_range.edit', [$priceRange->price_range_id, $queryUrl ?? '']) }}" class="btn btn-success">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <form action="{{ route('price_range.destroy', $priceRange->price_range_id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                @endforeach
            @endforeach
        @else
            <tr>
                <td colspan="6" class="text-center">Không có dữ liệu</td>
            </tr>
        @endif
    </tbody>
</table>
