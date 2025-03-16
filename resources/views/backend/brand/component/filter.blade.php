<form action="{{ route('brand.index') }}">
    <div class="filter-wrapper">
        <div class="uk-flex uk-flex-middle uk-flex-space-between">
            @include('backend.dashboard.component.perpage')
            <div class="action">
                <div class="uk-flex uk-flex-middle">
                    @include('backend.dashboard.component.filterPublish')
                    @include('backend.dashboard.component.keyword')
                    <a href="{{ route('brand.create') }}" class="btn btn-danger"><i class="fa fa-plus mr5"></i>Thêm mới thương hiệu</a>
                </div>
            </div>
        </div>
    </div>
</form>