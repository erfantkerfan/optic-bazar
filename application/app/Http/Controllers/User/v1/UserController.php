<?php

namespace App\Http\Controllers\User\v1;

use App\Order;
use App\Order_detail;
use App\Prescription;
use App\transaction;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use SoapClient;

class UserController extends Controller
{

    protected $request;

    public function __construct(Request $request)
    {
        $this->middleware('user');

        date_default_timezone_set("Asia/Tehran");
        $this->request = $request;

        require(base_path('app/jdf.php'));
    }

    /*
     *
     * set name to connection
     * transaction handel
     *
     * */


    public function index()
    {
        $where_array = array();
        $UserLogin = Auth::user();

        $request = transaction::join('users', 'transactions.user_id' , '=' , 'users.id')
            ->join('order_details', 'order_details.val' , '=', 'transactions.key' )
            ->join('orders', 'orders.id' , '=', 'order_details.order_id' )
            ->join('products', 'products.id' , '=', 'orders.product_id' )
            ->where('users.id' , $UserLogin->id)
            ->where('orders.status' , 'paid')
            ->where('transactions.type' , 'order')
            ->where($where_array)
            ->select(
                'transactions.*',
                'users.name as user_name',
                'users.mobile as user_mobile',
                'users.email as user_email'
            )
            ->orderBy('transactions.created_at', 'desc')
            ->distinct('transactions.id')
            ->paginate(20);


        $fullCart = array();
        $orderData = array();
        $labrator = [];
        $full_labrator_price = [];


        if($request){
            foreach ($request as $trans){

                $order_detail_list = [];
                $order_details = Order_detail::where('key' , 'thank_you_key')->where('val' , $trans->key)->get();
                if($order_details){
                    foreach ($order_details as $order_detail){
                        $order_detail_list[] = $order_detail['order_id'];
                    }

                    $order = Order::join('prescriptions', 'prescriptions.id' , '=', 'orders.prescription_id' )
                        ->join('products', 'products.id' , '=', 'orders.product_id' )
                        ->whereIN('orders.id' , $order_detail_list)
                        ->where('orders.status' , 'paid')
                        ->select(
                            'prescriptions.*',
                            'products.type as product_type',
                            'products.sku as product_sku',
                            'products.seller_id as seller_id',
                            'orders.created_at as created',
                            'orders.price as price',
                            'orders.lathe_id as lathe_id',
                            'orders.lathe as lathe',
                            'orders.order_key as key',
                            'orders.status_operator as status_operator',
                            'orders.id as order_id'
                        )->get();

                    if($order){
                        foreach ($order as $v){
                            $total = $v['price'];
                            $labrator_price = 0;

                            $order_service = Order_detail::where('order_id', $v['order_id'])->where('key', 'LIKE' ,'%order_service%%price%')->get();
                            if($order_service){
                                foreach ($order_service as $service) {
                                    $labrator_price = $labrator_price + $service['val'];
                                    $total = $total + $service['val'];
                                }
                            }
                            $order_service = Order_detail::where('order_id', $v['order_id'])->where('key', 'LIKE' ,'%order_service%%labrator%')->first();
                            if($order_service){
                                $labrator[$v['order_id']] = $order_service->val;
                            }

                            $fullCart[$trans->id][$v['order_id']] = $total;
                            $full_labrator_price[$trans->id][$v['order_id']] = $labrator_price;
                            $orderData[$trans->id][] = $v;
                        }
                    }

                }

            }
        }
        return view('site.dashboard.index', ['request' => $request, 'orderData' => $orderData, 'fullTotal' => $fullCart, 'full_labrator_price' => $full_labrator_price, 'labrator' => $labrator]);
    }

    public function prescriptionDelete(){
        $id = $this->request->id;

        $UserLogin = Auth::user();
        $prescriptions = Prescription::where('user_id', $UserLogin->id)->where('id', $id)->first();
        if(!$prescriptions) return back()->with('error' ,  'اطلاعات ارسال شده اشتباه است.');

        $prescriptions->delete();

        return back()->with('success' ,  'این نسخه با موفقیت حذف شد.')->withInput();
    }

    public function charge(){

        $UserLogin = Auth::user();
        return view('site.user.charge', ['UserLogin' => $UserLogin]);

    }

    public function actionCharge(){

        // Validation Data
        $ValidData = $this->validate($this->request,[
            'price' => 'required|numeric|min:100',
            'type' => 'required'
        ]);

        $UserLogin = Auth::user();
        $sku = str_random(20);

        $newTransaction = new transaction;
        $newTransaction->user_id = $UserLogin->id;
        $newTransaction->price = $ValidData['price'];
        $newTransaction->type = 'charge';
        $newTransaction->key = $sku;
        $newTransaction->payment_method = 'online';
        $newTransaction->tracking_code = '0';
        $newTransaction->status = 'pending';
        $newTransaction->posting_status = '';
        $newTransaction->save();

        return redirect('user/charge/pay/' . $sku);

    }

    public function chargePay(){
        $key = $this->request->key;
        $UserLogin = Auth::user();

        if(!$key){
            return back()->with('error' , 'لطفا اطلاعات را وارد کنید.');
        }

        $request = transaction::where('type' , 'charge')->where('key' , $key)->where('status' , 'pending')->where('user_id' , $UserLogin->id)->orderBy('id', 'desc')->first();
        if(!$request) return back()->with('error' , 'اطلاعات ارسال شده اشتباه است.');



        $price 			= $request->price * 10; 		// Price Rial
        $ResNum 		= $request->id; 			// Invoice Number
        $MerchantCode 	= "50026119";
        $RedirectURL 	= url('user/charge/invoice/' . $key);

        return "<form id='samanpeyment' action='https://sep.shaparak.ir/payment.aspx' method='post'>
            <input type='hidden' name='Amount' value='{$price}' />
            <input type='hidden' name='ResNum' value='{$ResNum}'>
            <input type='hidden' name='RedirectURL' value='{$RedirectURL}'/>
            <input type='hidden' name='MID' value='{$MerchantCode}'/>
            </form><script>document.forms['samanpeyment'].submit()</script>";

    }

    public function chargeInvoice(){

        $key = $this->request->key;
        $State = $this->request->get('State');
        $RefNum = $this->request->get('RefNum');
        $UserLogin = Auth::user();

        if(!$key){
            return back()->with('error' , 'لطفا اطلاعات را وارد کنید.');
        }

        $request = transaction::where('type' , 'charge')->where('key' , $key)->where('status' , 'pending')->where('user_id' , $UserLogin->id)->orderBy('id', 'desc')->first();
        if(!$request) return back()->with('error' , 'اطلاعات ارسال شده اشتباه است.');


        $MerchantCode = "50026119";

        if(isset($State) && $State == "OK") {

            $soapclient = new soapclient('https://verify.sep.ir/Payments/ReferencePayment.asmx?WSDL');
            $res 		= $soapclient->VerifyTransaction($RefNum, $MerchantCode);

            if( $res <= 0 )
            {
                return redirect('user/charge')->with('error' ,  'تراکنش ناموفق.' . $res);
            } else {

                $UserLogin->credit = $request->price + $UserLogin->credit;
                $UserLogin->save();

                $request->status = 'paid';
                $request->save();


                return redirect('user/charge/status/success/' . $key);
            }
        } else {
            return redirect('user/charge')->with('error' ,  'تراکنش لغو شد.');
        }

    }

    public function chargeStatus(){
        $status = $this->request->status;
        $key = $this->request->key;
        $UserLogin = Auth::user();

        if(!$key){
            return back()->with('error' , 'لطفا اطلاعات را وارد کنید.');
        }

        $request = transaction::where('type' , 'charge')->where('key' , $key)->where('user_id' , $UserLogin->id)->orderBy('id', 'desc')->first();
        if(!$request) return back()->with('error' , 'اطلاعات ارسال شده اشتباه است.');

        switch ($status){
            case "success" :
                if($request->status != 'paid') return redirect('user/charge/status/cancel/' . $key);

                return view('site.user.charge_success', ['UserLogin' => $UserLogin, 'request' => $request]);
                break;
            case "cancel" :
                if($request->status == 'paid') return redirect('user/charge/status/success/' . $key);

                return view('site.user.charge_cancel', ['UserLogin' => $UserLogin, 'request' => $request]);
                break;
            default :
                return redirect('user/charge');
                break;
        }

    }

}
