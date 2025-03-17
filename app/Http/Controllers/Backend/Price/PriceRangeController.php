<?php

namespace App\Http\Controllers\Backend\Price;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Interfaces\AttributeCatalogueServiceInterface  as AttributeCatalogueService;
use App\Repositories\Interfaces\AttributeCatalogueRepositoryInterface  as AttributeCatalogueRepository;
use App\Classes\Nestedsetbie;
use App\Models\Language;
use App\Models\Price_group;
use App\Models\Price_range;
use App\Models\ProductBrand;
use App\Models\ProductCatalogue;
use App\Models\Sub_brand;
use Illuminate\Support\Facades\DB;

class PriceRangeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $attributeCatalogueService;
    protected $attributeCatalogueRepository;
    protected $nestedset;
    protected $language;
    protected $attributeCatalogue;

    public function __construct(
        AttributeCatalogueService $attributeCatalogueService,
        AttributeCatalogueRepository $attributeCatalogueRepository,
        
        AttributeCatalogueRepository $attributeCatalogue,
    ){
        $this->middleware(function($request, $next){
            $locale = app()->getLocale();
            $language = Language::where('canonical', $locale)->first();
            $this->language = $language->id;
            $this->initialize();
            return $next($request);
        });


        $this->attributeCatalogueService = $attributeCatalogueService;
        $this->attributeCatalogueRepository = $attributeCatalogueRepository;;
        $this->attributeCatalogue = $attributeCatalogue;
    }

    private function initialize(){
        $this->nestedset = new Nestedsetbie([
            'table' => 'attribute_catalogues',
            'foreignkey' => 'attribute_catalogue_id',
            'language_id' =>  $this->language,
        ]);
    } 
 
    public function index(Request $request){
        $perPage = $request->perpage ?? 20; 
        $price_ranges = Price_range::select(
            'price_ranges.id as price_range_id', 
            'sub_brands.id as sub_brand_id',    
            'price_ranges.*', 
            'product_brand_language.name as brand_name',  
            'sub_brands.name as sub_name' 
        )
        ->leftJoin('sub_brands', 'price_ranges.sub_brand_id', '=', 'sub_brands.id') 
        ->leftJoin('product_brand_language', 'sub_brands.brand_id', '=', 'product_brand_language.product_brand_id') 
        ->orderBy('product_brand_language.name') 
        ->paginate($perPage);
    
        $config = [
            'js' => [
                'backend/js/plugins/switchery/switchery.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'
            ],
            'model' => 'Price_range',
        ];
        $config['seo'] = __('messages.attributeCatalogue');
        $template = 'backend.price.range.index';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'price_ranges'
        ));
    }

    public function create(){
        $config = [
            'js' => [
                'backend/js/plugins/switchery/switchery.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'
            ],
            'model' => 'Price_group',
        ];
        $config['seo'] = __('messages.attributeCatalogue');
        $config['method'] = 'create';
        $dropdown  = $this->nestedset->Dropdown();
        $brands = ProductBrand::select(
            'product_brands.*', 
            'product_brand_language.*'
        )
        ->leftJoin('product_brand_language', 'product_brands.id', '=', 'product_brand_language.product_brand_id')
        ->get();
        
        //dd($categorys);
        $template = 'backend.price.range.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'dropdown',
            'config',
            'brands'
        ));
    }
public function store(Request $request)
{
    $request->validate([
        'brand_id' => 'required',
        'range_name' => 'required|string|max:255',
        'ranges_data' => 'required|json',
    ]);

    $ranges = json_decode($request->ranges_data, true);

    try {
        DB::beginTransaction(); // Bắt đầu transaction

        // 1️⃣ Lưu sub_brand trước
        $subBrand = Sub_brand::create([
            'brand_id' => $request->brand_id,
            'name' => $request->range_name ?? 'Không xác định',
        ]);
        
        $level = 1; 

        foreach ($ranges as $range) {
            Price_range::create([
                'sub_brand_id' => $subBrand->id, 
                'name' => 'Mức ' . $level,
                'range_from' => $range['from'],
                'range_to' => $range['to'],
                'value_type' => $range['valueType'],
                'value' => $range['value'],
            ]);
            $level++;
        }

        DB::commit(); 

        return redirect()->route('price_range.index')->with('success', 'Thêm mới bản ghi thành công');
    } catch (\Exception $e) {
        DB::rollBack();
        dd($e);
        return back()->with('error', 'Lỗi khi thêm dữ liệu: ' . $e->getMessage());
    }
}


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $config = [
            'js' => [
                'backend/js/plugins/switchery/switchery.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'
            ],
            'model' => 'Price_group',
        ];
        $config['seo'] = __('messages.attributeCatalogue');
        $config['method'] = 'create';
        $dropdown  = $this->nestedset->Dropdown();
        $price_range = Price_range::findOrfail($id);
        //dd($price_range);
        //dd($categorys);
        $template = 'backend.price.range.edit';
        return view('backend.dashboard.layout', compact(
            'template',
            'dropdown',
            'config',
            'price_range'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data=$request->except('send');
        if(Price_range::findOrfail($id)->update($data)){
            return redirect()->route('price_range.index')->with('success','Sửa bản ghi thành công');
        }
        return back()->with('error','Sửa bản ghi thất bại');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if(Price_range::findOrfail($id)->delete()){
            return redirect()->route('price_range.index')->with('success','Xoá bản ghi thành công');
        }
        return back()->with('error','Xoá bản ghi thất bại');
    }
}
