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
use App\Models\ProductCatalogue;
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
        $price_ranges = Price_range::with('brand')
        ->orderBy(function($query) {
            $query->select('name')
                  ->from('product_catalogue_language')
                  ->whereColumn('product_catalogue_language.product_catalogue_id', 'price_range.brand_id')
                  ->limit(1);
        })
        ->paginate(5);
    
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

        $brands=ProductCatalogue::with('product_catalogue_language')->where('parent_id',22)->get();
        //dd($categorys);
        $template = 'backend.price.range.store';
        return view('backend.dashboard.layout', compact(
            'template',
            'dropdown',
            'config',
            'brands'
        ));
    }
    /**
     * Display the specified resource.
     */
    public function store(Request $request)
     {
       //dd($request->all());
        $data=$request->except('send');
        if(Price_range::create($data)){
            return redirect()->route('price_range.index')->with('success','Thêm mới bản ghi thành công');
        }
        return back()->with('error','Thêm mới bản ghi thất bại');
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
        $brands=ProductCatalogue::with('product_catalogue_language')->where('parent_id',22)->get();
        //dd($categorys);
        $template = 'backend.price.range.edit';
        return view('backend.dashboard.layout', compact(
            'template',
            'dropdown',
            'config',
            'brands',
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
            return redirect()->route('price_group.index')->with('success','Xoá bản ghi thành công');
        }
        return back()->with('error','Xoá bản ghi thất bại');
    }
}
