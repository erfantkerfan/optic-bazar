<?php

namespace App\Http\Controllers\Admin\v1;

use App\Brand;
use App\country;
use App\Http\Controllers\ProductSmartController;
use App\Lens_detail;
use App\lensPrice;
use App\Optical_glass_detail;
use App\optical_glassPrice;
use App\Product;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManagerStatic as Image;

class ProductController extends ProductSmartController
{
    protected $request;

    public function __construct(Request $request){
        $this->middleware('admin');

        date_default_timezone_set("Asia/Tehran");
        $this->request = $request;
    }

    public function productsLens(){

        //filter set to query
        $where_array = array();

        $filter = $this->request->get('filter_sku');
        if($filter){

            $where_array[] = array('products.sku', 'LIKE', "%".$filter."%");
        }

        $filter = $this->request->get('filter_status');
        if($filter){

            $where_array[] = array('products.status', $filter);
        }

        // Get Product Query And Join Lens Detail
        $request = Product::join('lens_details', 'lens_details.product_id' , '=' , 'products.id')
            ->where('products.type', 1)
            ->where($where_array)
            ->orderBy('products.sku', 'desc')
            ->select('products.id',
                'products.sku',
                'products.image',
                'products.country',
                'products.price',
                'products.seller_id',
                'products.brand_id',
                'products.original_price',
                'products.status',
                'lens_details.structure',
                'products.created_at as created_date',
                'lens_details.curvature','lens_details.color','lens_details.number','lens_details.consumption_period','lens_details.abatement','lens_details.oxygen_supply')
            ->paginate(35);


        // Return View
        return view('admin/products/lens', ['request' => $request]);

    }

    public function productEditLens(){

        // Get Product ID
        $id = $this->request->product;

        //check user validate
        $request = Product::where('id' , $id)->orderBy('id', 'desc')->first();
        if(!$request) return back()->with('error' ,  'اطلاعات ارسال شده اشتباه است.');

        if($request->type == 1){
            $details = Lens_detail::where('product_id' , $request->id)->first();
            $prices = lensPrice::where('product_id' , $request->id)->get();
        }else{
            return back()->with('error' ,  'اطلاعات ارسال شده اشتباه است.');
        }

        $bonakdars = User::where('role' , 'bonakdar')->orderBy('id', 'desc')->get();
        $brands = Brand::orderBy('name', 'desc')->get();

        $group = $this->LensProductGroup();
        $diagonals = $this->LensProductDiagonals();
        $curvatures = $this->LensProductCurvatures();
        $colors = $this->LensProductColor();
        $numbers = $this->LensProductNumber();
        $consumption_period = $this->LensProductConsumptionPeriod();
        $structures = $this->LensProductStructures();
        $astigmatisms = $this->LensProductAstigmatisms();
        $axis = $this->SearchAxis();

        $country = country::orderBy('title', 'ASC')->get();

        return view('admin/products/edit_lens', ['request' => $request , 'detail' => $details, 'axises' => $axis , 'prices' => $prices , 'bonakdars' => $bonakdars , 'brands' => $brands ,
            'country' => $country, 'group' => $group, 'diagonals' => $diagonals, 'astigmatisms' => $astigmatisms, 'structures' => $structures, 'curvatures' => $curvatures, 'colors' => $colors, 'numbers' => $numbers, 'consumption_period' => $consumption_period]);
    }
    public function ActionProductEditLens(){

        // Get Product ID
        $id = $this->request->product;

        //check user validate
        $request = Product::where('id' , $id)->orderBy('id', 'desc')->first();
        if(!$request) return back()->with('error' ,  'اطلاعات ارسال شده اشتباه است.');


        // Validation Data
        $ValidData = $this->validate($this->request,[
            'sku' => 'required|string',
            'country' => 'required|string|max:255',
            'consumption_period' => 'required|string|max:255',
            'brand' => 'required|numeric',
            'number' => 'required|numeric',
            'curvature' => 'required',
            'structure' => 'required',
            'color' => 'required',
            'color_description' => 'required',
            'thickness' => 'required',
            'abatement' => 'required',
            'status' => 'required',
            'oxygen_supply' => 'required',
            'seller' => 'numeric',
        ]);

        $sph = $this->request->get('sph');
        $cyl = $this->request->get('cyl');
        $diagonal = $this->request->get('diagonal');
        $inventory = $this->request->get('inventory');
        $price = $this->request->get('price');
        $discount_price = $this->request->get('discount_price');
        $purchase_price = $this->request->get('purchase_price');

        if($sph) {
            foreach ($sph as $key => $sp) {

                if(
                    $purchase_price[$key] ||
                    $discount_price[$key] ||
                    $price[$key] ||
                    $inventory[$key]
                ){

                    $group_name = "Sph = [ " . $sp . " ]";
                    $group_name = (isset($cyl[$key])) ? $group_name . " cyl = [ " . $cyl[$key] . " ]" : $group_name;

                    if(!$purchase_price[$key] || !is_numeric($purchase_price[$key])){
                        return back()->with('error' ,  'خطا ، لطفا قیمت خرید گروه '.$group_name.' را وارد کنید.')->withInput();
                    }

                    if(!$price[$key] || !is_numeric($price[$key])){
                        return back()->with('error' ,  'خطا ، لطفا قیمت فروش گروه '.$group_name.' را وارد کنید.')->withInput();
                    }


                    if(($price[$key] <= $purchase_price[$key]) || ($discount_price[$key] && ($discount_price[$key] <= $purchase_price[$key]))){
                        return back()->with('error' ,  'خطا ،  قیمت خرید از قیمت فروش در گروه '.$group_name.' بیشتر است.')->withInput();
                    }

                }

            }
        }

        if($request->sku != $ValidData['sku']){
            $ValidData_sku = $this->validate($this->request,[
                'sku' => 'required|string|unique:products',
            ]);
        }

        if($this->request->get('image')){
            if($this->request->get('image') != $request->image){
                $ValidData_image = $this->validate($this->request,[
                    'image' => 'required',
                ]);

                $image = $this->request->get('image');
                if($image){
                    $slim = str_replace(chr(92), '', $image);
                    $slim = json_decode($slim);
                    $file = $slim->output->image;
                    $filename = str_random(15) . '.jpg';
                    Image::make($file)->save($this->fileFinalPath('/'). $filename);
                    $image = url('uploads/'.$filename);
                }

                $request->image = $image;
            }
        }


        $request->sku = $ValidData['sku'];
        $request->status = $ValidData['status'];
        $request->seller_id = $ValidData['seller'];
        $request->brand_id = $ValidData['brand'];
        $request->country = $ValidData['country'];
        if($this->request->get('gallery')) $request->gallery = json_encode($this->request->get('gallery'));
        $request->description = $this->request->get('content');

        $sortPrice = collect($price)->sort()->values()->all();
        $minprice = 0;
        foreach ($sortPrice as $sop){
            if(!$minprice) $minprice = $sop;
        }

        $sortDiscountPrice = collect($discount_price)->sort()->values()->all();
        $minDiscountPrice = 0;
        foreach ($sortDiscountPrice as $sop){
            if(!$minDiscountPrice) $minDiscountPrice = $sop;
        }

        $request->original_price = $minprice;
        $request->sale_price = ($minprice - $minDiscountPrice);

        if($minprice > $minDiscountPrice){
            $minprice = $minDiscountPrice;
        }

        $request->price = $minprice;


        if($request->save()){

            $request_detail = Lens_detail::where('product_id' , $id)->first();


            $request_detail->curvature = $ValidData['curvature'];
            $request_detail->structure = $ValidData['structure'];
            $request_detail->consumption_period = $ValidData['consumption_period'];
            $request_detail->color = $ValidData['color'];
            $request_detail->color_description = $ValidData['color_description'];
            $request_detail->number = $ValidData['number'];
            $request_detail->astigmatism = 0;
            $request_detail->thickness = $ValidData['thickness'];
            $request_detail->abatement = $ValidData['abatement'];
            $request_detail->oxygen_supply = $ValidData['oxygen_supply'];

            $request_detail->axises = json_encode($this->request->get('axis'));

            $request_detail->save();


            if($sph){
                foreach ($sph as $key => $sp){


                    if(isset($cyl[$key])) {
                        $requestPrice = lensPrice::where('product_id', $request->id)->where('sph', $sp)->where('cyl', $cyl[$key])->orderBy('id', 'desc')->first();
                    }else{
                        $requestPrice = lensPrice::where('product_id', $request->id)->where('sph', $sp)->where('cyl', '')->orderBy('id', 'desc')->first();
                    }


                    if(!$requestPrice){

                        $newPrice = new lensPrice();

                        $newPrice->product_id = $request->id;
                        $newPrice->sph = $sp;

                        if(isset($cyl[$key])){
                            $newPrice->cyl = $cyl[$key];
                        }

                        if($diagonal[$key]){
                            $newPrice->diagonal = $diagonal[$key];
                        }else{
                            $newPrice->diagonal = '0';
                        }

                        $newPrice->inventory = $inventory[$key];
                        $newPrice->original_price = $price[$key];

                        if($discount_price[$key]){
                            $newPrice->discount_price = $discount_price[$key];
                            $newPrice->price = $discount_price[$key];
                        }else{
                            $newPrice->price = $price[$key];
                        }

                        $newPrice->sale_price = $purchase_price[$key];

                        $newPrice->save();

                    }else{


                        if($diagonal[$key]){
                            $requestPrice->diagonal = $diagonal[$key];
                        }else{
                            $requestPrice->diagonal = '0';
                        }

                        $requestPrice->inventory = $inventory[$key];
                        $requestPrice->original_price = $price[$key];

                        if($discount_price[$key]){
                            $requestPrice->discount_price = $discount_price[$key];
                            $requestPrice->price = $discount_price[$key];
                        }else{
                            $requestPrice->discount_price = 0;
                            $requestPrice->price = $price[$key];
                        }

                        $requestPrice->sale_price = $purchase_price[$key];

                        $requestPrice->save();

                    }

                }
            }



            return redirect('cp-manager/products/lens')->with('success' ,  'اطلاعات بروز رسانی شد.')->withInput();

        }else{
            return redirect('cp-manager/products/lens')->with('error' ,  'خطا در ذخیره سازی اطلاعات.');
        }


    }

    public function productAddLens(){

        $bonakdars = User::where('role' , 'bonakdar')->orderBy('id', 'desc')->get();
        $brands = Brand::orderBy('name', 'desc')->get();

        $group = $this->LensProductGroup();
        $diagonals = $this->LensProductDiagonals();
        $curvatures = $this->LensProductCurvatures();
        $colors = $this->LensProductColor();
        $numbers = $this->LensProductNumber();
        $consumption_period = $this->LensProductConsumptionPeriod();
        $structures = $this->LensProductStructures();
        $astigmatisms = $this->LensProductAstigmatisms();
        $axis = $this->SearchAxis();


        //return $group;
        $country = country::orderBy('title', 'ASC')->get();

        return view('admin/products/add_lens', ['bonakdars' => $bonakdars , 'brands' => $brands, 'axises' => $axis ,
            'country' => $country, 'group' => $group, 'diagonals' => $diagonals, 'astigmatisms' => $astigmatisms, 'structures' => $structures, 'curvatures' => $curvatures, 'colors' => $colors, 'numbers' => $numbers, 'consumption_period' => $consumption_period]);

    }
    public function ActionProductAddLens(){


        // Validation Data
        $ValidData = $this->validate($this->request,[
            'sku' => 'required|string|unique:products',
            'country' => 'required|string|max:255',
            'consumption_period' => 'required|string|max:255',
            'brand' => 'required|numeric',
            'number' => 'required|numeric',
            'curvature' => 'required',
            'structure' => 'required',
            'color' => 'required',
            'color_description' => 'required',
            'thickness' => 'required',
            'abatement' => 'required',
            'oxygen_supply' => 'required',
            'seller' => 'numeric',
        ]);

        $sph = $this->request->get('sph');
        $cyl = $this->request->get('cyl');
        $diagonal = $this->request->get('diagonal');
        $inventory = $this->request->get('inventory');
        $price = $this->request->get('price');
        $discount_price = $this->request->get('discount_price');
        $purchase_price = $this->request->get('purchase_price');

        if($sph) {
            foreach ($sph as $key => $sp) {

                if(
                    $purchase_price[$key] ||
                    $discount_price[$key] ||
                    $price[$key] ||
                    $inventory[$key]
                ){

                    $group_name = "Sph = [ " . $sp . " ]";
                    $group_name = (isset($cyl[$key])) ? $group_name . " cyl = [ " . $cyl[$key] . " ]" : $group_name;

                    if(!$purchase_price[$key] || !is_numeric($purchase_price[$key])){
                        return back()->with('error' ,  'خطا ، لطفا قیمت خرید گروه '.$group_name.' را وارد کنید.')->withInput();
                    }

                    if(!$price[$key] || !is_numeric($price[$key])){
                        return back()->with('error' ,  'خطا ، لطفا قیمت فروش گروه '.$group_name.' را وارد کنید.')->withInput();
                    }


                    if(($price[$key] <= $purchase_price[$key]) || ($discount_price[$key] && ($discount_price[$key] <= $purchase_price[$key]))){
                        return back()->with('error' ,  'خطا ،  قیمت خرید از قیمت فروش در گروه '.$group_name.' بیشتر است.')->withInput();
                    }

                }

            }
        }

        $image_url = '';
        if($this->request->get('image')){
            $ValidData_image = $this->validate($this->request,[
                'image' => 'required',
            ]);

            $image = $this->request->get('image');
            if($image){
                $slim = str_replace(chr(92), '', $image);
                $slim = json_decode($slim);
                $file = $slim->output->image;
                $filename = str_random(15) . '.jpg';
                Image::make($file)->save($this->fileFinalPath('/'). $filename);
                $image = url('uploads/'.$filename);
            }

            $image_url = $image;
        }


        $sortPrice = collect($price)->sort()->values()->all();
        $minprice = 0;
        foreach ($sortPrice as $sop){
            if(!$minprice) $minprice = $sop;
        }

        $sortDiscountPrice = collect($discount_price)->sort()->values()->all();
        $minDiscountPrice = 0;
        foreach ($sortDiscountPrice as $sop){
            if(!$minDiscountPrice) $minDiscountPrice = $sop;
        }



        $newProduct = new Product;

        $newProduct->original_price = $minprice;
        $newProduct->sale_price = ($minprice - $minDiscountPrice);

        if($minprice > $minDiscountPrice){
            $minprice = $minDiscountPrice;
        }


        $newProduct->type = 1;
        $newProduct->status = 'active';
        $newProduct->sku = $ValidData['sku'];
        $newProduct->seller_id = $ValidData['seller'];
        $newProduct->brand_id = $ValidData['brand'];
        $newProduct->country = $ValidData['country'];
        $newProduct->price = $minprice;
        $newProduct->image = $image_url;
        $newProduct->gallery = json_encode($this->request->get('gallery'));
        $newProduct->description = $this->request->get('content');

        /*if($this->request->get('discount_price')){
            $newProduct->discount_price = $this->request->get('discount_price');
            $newProduct->price = $this->request->get('discount_price');
        }else{
            $newProduct->price = $ValidData['price'];
        }*/



        if($newProduct->save()){


            $newDetail = new Lens_detail;

            $newDetail->product_id = $newProduct->id;
            $newDetail->curvature = $ValidData['curvature'];
            $newDetail->structure = $ValidData['structure'];
            $newDetail->consumption_period = $ValidData['consumption_period'];
            $newDetail->color = $ValidData['color'];
            $newDetail->color_description = $ValidData['color_description'];
            $newDetail->number = $ValidData['number'];
            $newDetail->astigmatism = 0;
            $newDetail->thickness = $ValidData['thickness'];
            $newDetail->abatement = $ValidData['abatement'];
            $newDetail->oxygen_supply = $ValidData['oxygen_supply'];


            $newDetail->axises = json_encode($this->request->get('axis'));

            $newDetail->save();

            if($sph){
                foreach ($sph as $key => $sp){

                    $newPrice = new lensPrice();



                    $newPrice->product_id = $newProduct->id;
                    $newPrice->sph = $sp;

                    if(isset($cyl[$key])){
                        $newPrice->cyl = $cyl[$key];
                    }

                    if($diagonal[$key]){
                        $newPrice->diagonal = $diagonal[$key];
                    }else{
                        $newPrice->diagonal = '0';
                    }

                    $newPrice->inventory = $inventory[$key];
                    $newPrice->original_price = $price[$key];

                    if($discount_price[$key]){
                        $newPrice->discount_price = $discount_price[$key];
                        $newPrice->price = $discount_price[$key];
                    }else{
                        $newPrice->price = $price[$key];
                    }

                    $newPrice->sale_price = $purchase_price[$key];

                    $newPrice->save();

                }
            }


            return redirect('cp-manager/products/lens')->with('success' ,  'اطلاعات بروز رسانی شد.')->withInput();

        }else{
            return redirect('cp-manager/products/lens')->with('error' ,  'خطا در ذخیره سازی اطلاعات.');
        }


    }

    public function productsOpticalGlass(){

        //filter set to query
        $where_array = array();

        $filter = $this->request->get('filter_sku');
        if($filter){

            $where_array[] = array('products.sku', 'LIKE', "%".$filter."%");
        }

        $filter = $this->request->get('filter_status');
        if($filter){

            $where_array[] = array('products.status', $filter);
        }

        // Get Product Query And Join Lens Detail
        $request = Product::join('optical_glass_details', 'optical_glass_details.product_id' , '=' , 'products.id')
            ->where('products.type', 2)
            ->where($where_array)
            ->orderBy('products.sku', 'desc')
            ->select(

                'products.sku',
                'products.image',
                'products.country',
                'products.price',
                'products.seller_id',
                'products.original_price',
                'products.brand_id',
                'products.status',

                'optical_glass_details.*')
            ->paginate(35);

        // Return View
        return view('admin/products/optical_glass', ['request' => $request]);

    }

    public function productEditOpticalGlass(){

        // Get Product ID
        $id = $this->request->product;

        //check user validate
        $request = Product::where('id' , $id)->orderBy('id', 'desc')->first();
        if(!$request) return back()->with('error' ,  'اطلاعات ارسال شده اشتباه است.');

        if($request->type == 2){
            $details = Optical_glass_detail::where('product_id' , $request->id)->first();
            $prices = optical_glassPrice::where('product_id' , $request->id)->get();
        }else{
            return back()->with('error' ,  'اطلاعات ارسال شده اشتباه است.');
        }

        $bonakdars = User::where('role' , 'bonakdar')->orderBy('id', 'desc')->get();
        $brands = Brand::orderBy('name', 'desc')->get();

        $group = $this->OpticalGlassProductGroup();
        $adds = $this->SearchAddOptic();
        $refractive_indexs = $this->OpticalGlassProductLightBreakdown();
        $anti_reflex_colors = $this->OpticalGlassProductAntiReflexColors();
        $types = $this->OpticalGlassProductTypes();
        $properties = $this->OpticalGlassProductProperties();
        $sizes = $this->OpticalGlassProductSize();
        $blocks = $this->OpticalGlassProductBlocks();
        $bloc_trolls = $this->OpticalGlassProductBlocTrolls();
        $photocrophys = $this->OpticalGlassProductPhotocrophys();
        $photo_colors = $this->OpticalGlassProductPhoto_colors();
        $polycarbonates = $this->OpticalGlassProductPolycarbonates();
        $polybreaks = $this->OpticalGlassProductPolybreaks();
        $color_whites = $this->OpticalGlassProductColorWhites();
        $colored_scores = $this->OpticalGlassProductColored_scores();
        $waterings = $this->OpticalGlassProductWaterings();
        $structures = $this->OpticalGlassProductStructures();
        $yu_vies = $this->OpticalGlassProductYuVies();

        $country = country::orderBy('title', 'ASC')->get();


        return view('admin/products/edit_optical_glass', ['request' => $request , 'detail' => $details , 'prices' => $prices , 'bonakdars' => $bonakdars , 'brands' => $brands ,
            'country' => $country, 'group' => $group, 'sizes' => $sizes, 'blocks' => $blocks, 'bloc_trolls' => $bloc_trolls,  'types' => $types, 'properties' => $properties,
            'color_whites' => $color_whites, 'polybreaks' => $polybreaks, 'polycarbonates' => $polycarbonates, 'photo_colors' => $photo_colors,
            'yu_vies' => $yu_vies, 'structures' => $structures, 'waterings' => $waterings, 'colored_scores' => $colored_scores, 'photocrophys' => $photocrophys,
            'refractive_indexs' => $refractive_indexs, 'anti_reflex_colors' => $anti_reflex_colors, 'adds' => $adds]);

    }
    public function ActionProductEditOpticalGlass(){

        // Get Product ID
        $id = $this->request->product;

        //check user validate
        $request = Product::where('id' , $id)->orderBy('id', 'desc')->first();
        if(!$request) return back()->with('error' ,  'اطلاعات ارسال شده اشتباه است.');


        // Validation Data
        $ValidData = $this->validate($this->request,[
            'sku' => 'required|string',
            'country' => 'required|string|max:255',
            'brand' => 'required|numeric',
            'seller' => 'numeric',
            'type' => 'required',
            'refractive_index' => 'required',
            'yu_vie' => 'required',
            'status' => 'required',
        ]);


        if($ValidData['type'] == "Stock تک دید"){
            $ValidDataStock = $this->validate($this->request,[
                'anti_reflex_color' => 'required',
                'block' => 'required',
                'bloc_troll' => 'required',
                'photocrophy' => 'required',
                'photo_color' => 'required',
                'polycarbonate' => 'required',
                'poly_break' => 'required',
                'color_white' => 'required',
                'colored_score' => 'required',
                'watering' => 'required',
                'structure' => 'required',
            ]);
        }else{
            $ValidDataRx = $this->validate($this->request,[
                'property' => 'required',
            ]);
        }


        $sph = $this->request->get('sph');
        $size = $this->request->get('size');
        $add = $this->request->get('add');
        $inventory = $this->request->get('inventory');
        $price = $this->request->get('price');
        $discount_price = $this->request->get('discount_price');
        $purchase_price = $this->request->get('purchase_price');

        if($sph) {
            foreach ($sph as $key => $sp) {

                if(
                    $purchase_price[$key] ||
                    $discount_price[$key] ||
                    $price[$key] ||
                    $inventory[$key]
                ){

                    $group_name = $sp;

                    if(!$purchase_price[$key] || !is_numeric($purchase_price[$key])){
                        return back()->with('error' ,  'خطا ، لطفا قیمت خرید گروه '.$group_name.' را وارد کنید.')->withInput();
                    }

                    if(!$price[$key] || !is_numeric($price[$key])){
                        return back()->with('error' ,  'خطا ، لطفا قیمت فروش گروه '.$group_name.' را وارد کنید.')->withInput();
                    }


                    if(($price[$key] <= $purchase_price[$key]) || ($discount_price[$key] && ($discount_price[$key] <= $purchase_price[$key]))){
                        return back()->with('error' ,  'خطا ،  قیمت خرید از قیمت فروش در گروه '.$group_name.' بیشتر است.')->withInput();
                    }

                }

            }
        }

        if($request->sku != $ValidData['sku']){
            $ValidData_sku = $this->validate($this->request,[
                'sku' => 'required|string|unique:products',
            ]);
        }

        if($this->request->get('image')){
            if($this->request->get('image') != $request->image){
                $ValidData_image = $this->validate($this->request,[
                    'image' => 'required',
                ]);

                $image = $this->request->get('image');
                if($image){
                    $slim = str_replace(chr(92), '', $image);
                    $slim = json_decode($slim);
                    $file = $slim->output->image;
                    $filename = str_random(15) . '.jpg';
                    Image::make($file)->save($this->fileFinalPath('/'). $filename);
                    $image = url('uploads/'.$filename);
                }

                $request->image = $image;
            }
        }



        $sortPrice = collect($price)->sort()->values()->all();
        $minprice = 0;
        foreach ($sortPrice as $sop){
            if(!$minprice) $minprice = $sop;
        }

        $sortDiscountPrice = collect($discount_price)->sort()->values()->all();
        $minDiscountPrice = 0;
        foreach ($sortDiscountPrice as $sop){
            if(!$minDiscountPrice) $minDiscountPrice = $sop;
        }

        $request->original_price = $minprice;
        $request->sale_price = ($minprice - $minDiscountPrice);

        if($minprice > $minDiscountPrice){
            $minprice = $minDiscountPrice;
        }

        $request->sku = $ValidData['sku'];
        $request->status = $ValidData['status'];
        $request->seller_id = $ValidData['seller'];
        $request->brand_id = $ValidData['brand'];
        $request->country = $ValidData['country'];
        if($this->request->get('gallery')) $request->gallery = json_encode($this->request->get('gallery'));
        $request->description = $this->request->get('content');

        $request->price = $minprice;


        if($request->save()){

            $request_detail = Optical_glass_detail::where('product_id' , $id)->first();


            $request_detail->type = $this->request->get('type');
            $request_detail->property = $this->request->get('property');
            $request_detail->refractive_index = $this->request->get('refractive_index');
            $request_detail->anti_reflex_color = $this->request->get('anti_reflex_color');
            $request_detail->block = $this->request->get('block');
            $request_detail->bloc_troll = $this->request->get('bloc_troll');
            $request_detail->photocrophy = $this->request->get('photocrophy');
            $request_detail->photo_color = $this->request->get('photo_color');
            $request_detail->polycarbonate = $this->request->get('polycarbonate');
            $request_detail->poly_break = $this->request->get('poly_break');
            $request_detail->color_white = $this->request->get('color_white');
            $request_detail->colored_score = $this->request->get('colored_score');
            $request_detail->watering = $this->request->get('watering');
            $request_detail->structure = $this->request->get('structure');
            $request_detail->yu_vie = $this->request->get('yu_vie');

            $request_detail->add = json_encode($this->request->get('add'));

            $request_detail->save();



            if($sph){
                foreach ($sph as $key => $sp){


                    $requestPrice = optical_glassPrice::where('product_id', $request->id)->where('sph', $sp)->orderBy('id', 'desc')->first();


                    if(!$requestPrice){

                        $newPrice = new optical_glassPrice();

                        $newPrice->product_id = $request->id;
                        $newPrice->sph = $sp;

                        /*if($add[$key] != 'بدون محدودیت'){
                            $newPrice->add = $add[$key];
                        }else{
                            $newPrice->add = '0';
                        }*/

                        if($size[$key]){
                            $newPrice->size = $size[$key];
                        }else{
                            $newPrice->size = '0';
                        }

                        $newPrice->inventory = $inventory[$key];
                        $newPrice->original_price = $price[$key];

                        if($discount_price[$key]){
                            $newPrice->discount_price = $discount_price[$key];
                            $newPrice->price = $discount_price[$key];
                        }else{
                            $newPrice->price = $price[$key];
                        }
                        $newPrice->sale_price = $purchase_price[$key];

                        $newPrice->save();

                    }else{

                        $requestPrice->sph = $sp;

                        /*if($add[$key] != 'بدون محدودیت'){
                            $requestPrice->add = $add[$key];
                        }else{
                            $requestPrice->add = '0';
                        }*/

                        if($size[$key]){
                            $requestPrice->size = $size[$key];
                        }else{
                            $requestPrice->size = '0';
                        }

                        $requestPrice->inventory = $inventory[$key];
                        $requestPrice->original_price = $price[$key];


                        if($discount_price[$key]){
                            $requestPrice->discount_price = $discount_price[$key];
                            $requestPrice->price = $discount_price[$key];
                        }else{
                            $requestPrice->discount_price = 0;
                            $requestPrice->price = $price[$key];
                        }

                        $requestPrice->sale_price = $purchase_price[$key];

                        $requestPrice->save();

                    }

                }
            }




            return redirect('cp-manager/products/optical-glass')->with('success' ,  'اطلاعات بروز رسانی شد.')->withInput();

        }else{
            return redirect('cp-manager/products/optical-glass')->with('error' ,  'خطا در ذخیره سازی اطلاعات.');
        }


    }

    public function productAddOpticalGlass(){

        $bonakdars = User::where('role' , 'bonakdar')->orderBy('id', 'desc')->get();
        $brands = Brand::orderBy('name', 'desc')->get();

        $group = $this->OpticalGlassProductGroup();
        $adds = $this->SearchAddOptic();
        $sizes = $this->OpticalGlassProductSize();
        $refractive_indexs = $this->OpticalGlassProductLightBreakdown();
        $anti_reflex_colors = $this->OpticalGlassProductAntiReflexColors();
        $types = $this->OpticalGlassProductTypes();
        $properties = $this->OpticalGlassProductProperties();
        $blocks = $this->OpticalGlassProductBlocks();
        $bloc_trolls = $this->OpticalGlassProductBlocTrolls();
        $photocrophys = $this->OpticalGlassProductPhotocrophys();
        $photo_colors = $this->OpticalGlassProductPhoto_colors();
        $polycarbonates = $this->OpticalGlassProductPolycarbonates();
        $polybreaks = $this->OpticalGlassProductPolybreaks();
        $color_whites = $this->OpticalGlassProductColorWhites();
        $colored_scores = $this->OpticalGlassProductColored_scores();
        $waterings = $this->OpticalGlassProductWaterings();
        $structures = $this->OpticalGlassProductStructures();
        $yu_vies = $this->OpticalGlassProductYuVies();
        $axis_optic = $this->SearchAxisOptic();

        $country = country::orderBy('title', 'ASC')->get();

        return view('admin/products/add_optical_glass', ['bonakdars' => $bonakdars , 'brands' => $brands ,
            'country' => $country, 'adds' => $adds,  'group' => $group, 'sizes' => $sizes, 'blocks' => $blocks, 'bloc_trolls' => $bloc_trolls,  'types' => $types, 'properties' => $properties,
            'color_whites' => $color_whites, 'polybreaks' => $polybreaks, 'polycarbonates' => $polycarbonates, 'photo_colors' => $photo_colors,
            'yu_vies' => $yu_vies, 'structures' => $structures, 'waterings' => $waterings, 'colored_scores' => $colored_scores, 'photocrophys' => $photocrophys,
            'refractive_indexs' => $refractive_indexs, 'anti_reflex_colors' => $anti_reflex_colors, 'axises' => $axis_optic]);

    }
    public function ActionProductAddOpticalGlass(){

        // Validation Data
        $ValidData = $this->validate($this->request,[
            'sku' => 'required|string|unique:products',
            'country' => 'required|string|max:255',
            'brand' => 'required|numeric',
            'seller' => 'numeric',
            'type' => 'required',
            'refractive_index' => 'required',
            'yu_vie' => 'required',
        ]);

        if($ValidData['type'] == "Stock تک دید"){
            $ValidDataStock = $this->validate($this->request,[
                'anti_reflex_color' => 'required',
                'block' => 'required',
                'bloc_troll' => 'required',
                'photocrophy' => 'required',
                'photo_color' => 'required',
                'polycarbonate' => 'required',
                'poly_break' => 'required',
                'color_white' => 'required',
                'colored_score' => 'required',
                'watering' => 'required',
                'structure' => 'required',
            ]);
        }else{
            $ValidDataRx = $this->validate($this->request,[
                'property' => 'required',
            ]);
        }

        $sph = $this->request->get('sph');
        $size = $this->request->get('size');
        $add = $this->request->get('add');
        $inventory = $this->request->get('inventory');
        $price = $this->request->get('price');
        $discount_price = $this->request->get('discount_price');
        $purchase_price = $this->request->get('purchase_price');

        if($sph) {
            foreach ($sph as $key => $sp) {

                if(
                    $purchase_price[$key] ||
                    $discount_price[$key] ||
                    $price[$key] ||
                    $inventory[$key]
                ){

                    $group_name = $sp;

                    if(!$purchase_price[$key] || !is_numeric($purchase_price[$key])){
                        return back()->with('error' ,  'خطا ، لطفا قیمت خرید گروه '.$group_name.' را وارد کنید.')->withInput();
                    }

                    if(!$price[$key] || !is_numeric($price[$key])){
                        return back()->with('error' ,  'خطا ، لطفا قیمت فروش گروه '.$group_name.' را وارد کنید.')->withInput();
                    }


                    if(($price[$key] <= $purchase_price[$key]) || ($discount_price[$key] && ($discount_price[$key] <= $purchase_price[$key]))){
                        return back()->with('error' ,  'خطا ،  قیمت خرید از قیمت فروش در گروه '.$group_name.' بیشتر است.')->withInput();
                    }

                }

            }
        }

        $image_url = '';
        if($this->request->get('image')){
            $ValidData_image = $this->validate($this->request,[
                'image' => 'required',
            ]);

            $image = $this->request->get('image');
            if($image){
                $slim = str_replace(chr(92), '', $image);
                $slim = json_decode($slim);
                $file = $slim->output->image;
                $filename = str_random(15) . '.jpg';
                Image::make($file)->save($this->fileFinalPath('/'). $filename);
                $image = url('uploads/'.$filename);
            }

            $image_url = $image;
        }

        $newProduct = new Product;

        $sortPrice = collect($price)->sort()->values()->all();
        $minprice = 0;
        foreach ($sortPrice as $sop){
            if(!$minprice) $minprice = $sop;
        }

        $sortDiscountPrice = collect($discount_price)->sort()->values()->all();
        $minDiscountPrice = 0;
        foreach ($sortDiscountPrice as $sop){
            if(!$minDiscountPrice) $minDiscountPrice = $sop;
        }

        $newProduct->original_price = $minprice;
        $newProduct->sale_price = ($minprice - $minDiscountPrice);

        if($minprice > $minDiscountPrice){
            $minprice = $minDiscountPrice;
        }


        $newProduct->type = 2;
        $newProduct->status = 'active';
        $newProduct->sku = $ValidData['sku'];
        $newProduct->seller_id = $ValidData['seller'];
        $newProduct->brand_id = $ValidData['brand'];
        $newProduct->country = $ValidData['country'];
        $newProduct->image = $image_url;
        $newProduct->gallery = json_encode($this->request->get('gallery'));
        $newProduct->description = $this->request->get('content');

        $newProduct->price = $minprice;



        if($newProduct->save()){

            $newDetail = new Optical_glass_detail;

            $newDetail->product_id = $newProduct->id;
            $newDetail->type = $this->request->get('type');
            $newDetail->property = $this->request->get('property');
            $newDetail->refractive_index = $this->request->get('refractive_index');
            $newDetail->anti_reflex_color = $this->request->get('anti_reflex_color');
            $newDetail->block = $this->request->get('block');
            $newDetail->bloc_troll = $this->request->get('bloc_troll');
            $newDetail->photocrophy = $this->request->get('photocrophy');
            $newDetail->photo_color = $this->request->get('photo_color');
            $newDetail->polycarbonate = $this->request->get('polycarbonate');
            $newDetail->poly_break = $this->request->get('poly_break');
            $newDetail->color_white = $this->request->get('color_white');
            $newDetail->colored_score = $this->request->get('colored_score');
            $newDetail->watering = $this->request->get('watering');
            $newDetail->structure = $this->request->get('structure');
            $newDetail->yu_vie = $this->request->get('yu_vie');

            $newDetail->add = json_encode($this->request->get('add'));

            /*$newDetail->axis =  $this->request->get('axis');*/


            $newDetail->save();



            if($sph){
                foreach ($sph as $key => $sp){

                    $newPrice = new optical_glassPrice();

                    $newPrice->product_id = $newProduct->id;
                    $newPrice->sph = $sp;

                    if($size[$key]){
                        $newPrice->size = $size[$key];
                    }else{
                        $newPrice->size = '0';
                    }

                    /*if($add[$key] != 'بدون محدودیت'){
                        $newPrice->add = $add[$key];
                    }else{
                        $newPrice->add = '0';
                    }*/

                    $newPrice->add = '0';

                    $newPrice->inventory = $inventory[$key];
                    $newPrice->original_price = $price[$key];

                    if($discount_price[$key]){
                        $newPrice->discount_price = $discount_price[$key];
                        $newPrice->price = $discount_price[$key];
                    }else{
                        $newPrice->price = $price[$key];
                    }

                    $newPrice->sale_price = $purchase_price[$key];

                    $newPrice->save();

                }
            }


            return redirect('cp-manager/products/optical-glass')->with('success' ,  'اطلاعات بروز رسانی شد.')->withInput();

        }else{
            return redirect('cp-manager/products/optical-glass')->with('error' ,  'خطا در ذخیره سازی اطلاعات.');
        }


    }

    public function productDelete(){

        //get user id
        $id = $this->request->product;

        //check user validate
        $request = Product::where('id' , $id)->orderBy('id', 'desc')->first();
        if(!$request) return back()->with('error' ,  'اطلاعات ارسال شده اشتباه است.');

        if($request->typr == 1){

            $request_lens = Lens_detail::where('product_id' , $id)->orderBy('id', 'desc')->first();
            $request_lens->delete();

        }
        elseif($request->typr == 2){

            $request_optical_glass = Optical_glass_detail::where('product_id' , $id)->orderBy('id', 'desc')->first();
            $request_optical_glass->delete();

        }

        $request->delete();

        return back()->with('success' ,  'این مححصول با موفقیت حذف شد.')->withInput();
    }


}
