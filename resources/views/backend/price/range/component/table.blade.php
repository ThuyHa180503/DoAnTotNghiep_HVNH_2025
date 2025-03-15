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
            <th>{{ __('ID') }}</th>
            <th>{{ __('Thương hiệu') }}</th>
            <th>Dải giá</th>
            <th>{{ __('Loại giá trị') }}</th>
            <th>{{ __('Giá trị') }}</th>
            <th class="text-center" style="width:100px;">{{ __('Hành động') }}</th>
        </tr>
    </thead>
    <tbody>
        @if(isset($price_ranges) && $price_ranges->count() > 0)
            @foreach($price_ranges as $range)
                <tr id="row-{{ $range->id }}">
                    <td>
                        <input type="checkbox" value="{{ $range->id }}" class="input-checkbox checkBoxItem">
                    </td>
                    <td>{{ $range->id }}</td>
                    <td>{{ $range->brand->name ?? 'N/A' }}</td>
                    <td>
                        @if(fmod($range->range_from, 1) == 0 && fmod($range->range_to, 1) == 0)
                            {{ number_format($range->range_from, 0) }} - {{ number_format($range->range_to, 0) }}
                        @else
                            {{ number_format($range->range_from, 2) }} - {{ number_format($range->range_to, 2) }}
                        @endif
                    </td>

                    <td>
                        @if ($range->value_type == 'percentage')
                            Phần trăm (%)
                        @else
                            Giá trị cố định
                        @endif
                    </td>
                    <td class="text-right">
                        @if(fmod($range->value, 1) == 0)
                            {{ number_format($range->value, 0) }}
                        @else
                            {{ number_format($range->value, 2) }}
                        @endif
                    </td>

                    <td class="text-center">
                        <a href="{{ route('price_range.edit', [$range->id, $queryUrl ?? '']) }}" class="btn btn-success">
                            <i class="fa fa-edit"></i>
                        </a>
                        <form action="{{ route('price_range.destroy', $range->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirmDelete()">
                                <i class="fa fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="7" class="text-center">Không có dữ liệu</td>
            </tr>
        @endif
    </tbody>
</table>

{{ $price_ranges->links('pagination::bootstrap-4') }}
