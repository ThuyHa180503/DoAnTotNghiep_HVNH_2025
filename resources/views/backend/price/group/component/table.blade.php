@php
    $query= base64_encode(http_build_query(request()->query()));
    $queryUrl = rtrim($query,'=');
@endphp
<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th style="width:50px;">
                <input type="checkbox" id="checkAll" class="input-checkbox">
            </th>
            <th>{{ __('ID') }}</th>
            <th>{{ __('Tên nhóm giá') }}</th>
            <th>{{ __('Thương hiệu') }}</th>
            <th>{{ __('Danh mục') }}</th>
            <th>{{ __('Chiết khấu (%)') }}</th>
            <th>{{ __('Phí vận chuyển') }}</th>
            <th>{{ __('Tỷ giá') }}</th>
            <th class="text-center" style="width:100px;">{{ __('Hành động') }}</th>
        </tr>
    </thead>
    <tbody>
        @if(isset($price_groups) && $price_groups->count() > 0)
        @foreach($price_groups as $group)
<tr id="row-{{ $group->id }}">
    <td>
        <input type="checkbox" value="{{ $group->id }}" class="input-checkbox checkBoxItem">
    </td>
    <td>{{ $group->id }}</td>
    <td>{{ $group->name }}</td>
    <td>{{ $group->brand->name ?? 'N/A' }}</td>
    <td>{{ $group->catalogue->name ?? 'N/A' }}</td>
    <td class="text-right">{{ number_format($group->discount, 2) }}%</td>
    <td class="text-right">{{ number_format($group->shipping, 2) }}</td>
    <td class="text-right">{{ number_format($group->exchange_rate, 4) }}</td>
    <td class="text-center">
        <a href="{{ route('price_group.edit', [$group->id, $queryUrl ?? '']) }}" class="btn btn-success">
            <i class="fa fa-edit"></i>
        </a>
        <form action="{{ route('price_group.destroy', $group->id) }}" method="POST" style="display:inline;">
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
                <td colspan="9" class="text-center">Không có dữ liệu</td>
            </tr>
        @endif
    </tbody>
</table>
{{ $price_groups->links('pagination::bootstrap-4') }}

