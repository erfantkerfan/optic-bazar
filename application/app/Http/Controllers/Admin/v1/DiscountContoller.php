<?php

namespace App\Http\Controllers\Admin\v1;

use App\coupon;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DiscountContoller extends Controller
{

    protected $request;

    public function __construct(Request $request){
        $this->middleware('admin');

        date_default_timezone_set("Asia/Tehran");
        $this->request = $request;

        require(base_path('app/jdf.php'));
    }


    public function coupons(){
        $request = coupon::orderBy('created_at', 'desc')->paginate(35);

        return view('admin/coupons/index', ['request' => $request]);

    }

    public function couponAdd(){
        return view('admin/coupons/add');
    }
    public function ActionCouponAdd(){

        // Validation Data
        $ValidData = $this->validate($this->request,[
            'coupon_key' => 'required|string|max:100|unique:coupons',
            'discount_type' => 'required',
            'coupon_amount' => 'required|numeric',
            'expiry_date' => 'required',
            'minimum_amount' => 'numeric|nullable',
            'maximum_amount' => 'numeric|nullable',
        ]);

        if($ValidData['discount_type'] == 'percent'){
            $ValidData_price = $this->validate($this->request,[
                'coupon_amount' => 'required|numeric|max:100',
            ]);
        }

        $start_date = explode('/', $this->request->get('start_date'));
        if(isset($start_date[1])){
            $start_date = jalali_to_gregorian($start_date[0],$start_date[1],$start_date[2],'/');
        }else{
            $start_date = Carbon::now()->subDays(1);
        }

        $expiry_date = explode('/', $this->request->get('expiry_date'));
        if(isset($expiry_date[1])){
            $expiry_date = jalali_to_gregorian($expiry_date[0],$expiry_date[1],$expiry_date[2],'/');
        }else{
            $expiry_date = '';
        }


        $newCoupon = new coupon;
        $newCoupon->coupon_key = $ValidData['coupon_key'];
        $newCoupon->discount_type = $ValidData['discount_type'];
        $newCoupon->coupon_amount = $ValidData['coupon_amount'];
        $newCoupon->start_date = $start_date;
        $newCoupon->expiry_date = $expiry_date;
        $newCoupon->minimum_amount = ($ValidData['minimum_amount']) ? $ValidData['minimum_amount'] : 0;
        $newCoupon->maximum_amount = ($ValidData['maximum_amount']) ? $ValidData['maximum_amount'] : 0;
        $newCoupon->exclude_sale = ($this->request->get('exclude_sale')) ? 1 : 0;
        $newCoupon->disposable = ($this->request->get('disposable')) ? 1 : 0;
        $newCoupon->product_categories = $this->request->get('product_categories');

        $newCoupon->product_ids = json_encode($this->request->get('product_ids'));
        $newCoupon->exclude_product_ids = json_encode($this->request->get('exclude_product_ids'));

        //$newCoupon->customer_phone = ($ValidData['customer_phone']) ? $ValidData['customer_phone'] : 0;
        $newCoupon->description = $this->request->get('description');
        $newCoupon->save();

        return redirect('cp-manager/coupons')->with('success' ,  'اطلاعات با موفقیت ثبت شد.')->withInput();

    }

    public function couponEdit(){

        //get user id
        $id = $this->request->id;

        //check user validate
        $request = coupon::where('id' , $id)->orderBy('id', 'desc')->first();
        if(!$request) return redirect('cp-manager/coupons')->with('error' ,  'اطلاعات ارسال شده اشتباه است.');

        return view('admin/coupons/edit', ['request' => $request]);
    }
    public function ActionCouponEdit(){

        //get user id
        $id = $this->request->id;

        //check user validate
        $request = coupon::where('id' , $id)->orderBy('id', 'desc')->first();
        if(!$request) return redirect('cp-manager/coupons')->with('error' ,  'اطلاعات ارسال شده اشتباه است.');

        // Validation Data
        $ValidData = $this->validate($this->request,[
            'coupon_key' => 'required|string|max:100',
            'discount_type' => 'required',
            'coupon_amount' => 'required|numeric',
            'expiry_date' => 'required',
            'minimum_amount' => 'numeric|nullable',
            'maximum_amount' => 'numeric|nullable',
        ]);

        if($ValidData['discount_type'] == 'percent'){
            $ValidData_price = $this->validate($this->request,[
                'coupon_amount' => 'required|numeric|max:100',
            ]);
        }

        if($ValidData['coupon_key'] != $request->coupon_key){
            $ValidData_coupon_key = $this->validate($this->request,[
                'coupon_key' => 'required|string|max:100|unique:coupons',
            ]);
        }

        $start_date = explode('/', $this->request->get('start_date'));
        if(isset($start_date[1])){
            $start_date = jalali_to_gregorian($start_date[0],$start_date[1],$start_date[2],'/');
        }else{
            $start_date = Carbon::now()->subDays(1);
        }

        $expiry_date = explode('/', $this->request->get('expiry_date'));
        if(isset($expiry_date[1])){
            $expiry_date = jalali_to_gregorian($expiry_date[0],$expiry_date[1],$expiry_date[2],'/');
        }else{
            $expiry_date = '';
        }

        $request->coupon_key = $ValidData['coupon_key'];
        $request->discount_type = $ValidData['discount_type'];
        $request->coupon_amount = $ValidData['coupon_amount'];
        $request->start_date = $start_date;
        $request->expiry_date = $expiry_date;
        $request->minimum_amount = ($ValidData['minimum_amount']) ? $ValidData['minimum_amount'] : 0;
        $request->maximum_amount = ($ValidData['maximum_amount']) ? $ValidData['maximum_amount'] : 0;
        $request->exclude_sale = ($this->request->get('exclude_sale')) ? 1 : 0;
        $request->disposable = ($this->request->get('disposable')) ? 1 : 0;
        $request->product_categories = $this->request->get('product_categories');

        $request->product_ids = json_encode($this->request->get('product_ids'));
        $request->exclude_product_ids = json_encode($this->request->get('exclude_product_ids'));

        //$request->customer_phone = ($ValidData['customer_phone']) ? $ValidData['customer_phone'] : 0;
        $request->description = $this->request->get('description');
        $request->save();


        return redirect('cp-manager/coupons')->with('success' ,  'اطلاعات این کوپن بروز رسانی شد.')->withInput();

    }

    public function couponDelete(){

        //get user id
        $id = $this->request->id;

        //check user validate
        $request = coupon::where('id' , $id)->orderBy('id', 'desc')->first();
        if(!$request) return redirect('cp-manager/coupons')->with('error' ,  'اطلاعات ارسال شده اشتباه است.');

        $request->delete();

        return back()->with('success' ,  'این کوپن با موفقیت حذف شد.')->withInput();
    }


}
