
@include('backend.dashboard.component.breadcrumb', ['title' => 'Quản lý dải giá'])
<div class="row mt20">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>Quản lý dải giá</h5>
                @include('backend.dashboard.component.toolbox', ['model' => 'attributeCatalogue'])
            </div>
            <div class="ibox-content">
                @include('backend.price.range.component.filter')
                @include('backend.price.range.component.table')
            </div>
        </div>
    </div>
</div>

