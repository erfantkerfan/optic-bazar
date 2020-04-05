<?php

namespace App\Http\Controllers\Amel\v1;

use App\Order;
use App\Order_detail;
use App\Prescription;
use App\Product;
use App\transaction;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AmelController extends Controller
{

    protected $request;

    public function __construct(Request $request){
        $this->middleware('amel');

        date_default_timezone_set("Asia/Tehran");
        $this->request = $request;

        require(base_path('app/jdf.php'));
    }

    public function orders(){
        $where_array = array();
        $userLogin = Auth::user();

        //filter set to query

        $filter_start_date = $this->request->get('filter_start_date');
        if($filter_start_date){

            $date = explode('/', $filter_start_date);
            $date = jalali_to_gregorian($date[0], $date[1], $date[2], '-');

            $where_array[] = array('transactions.created_at', '>=', $date);
        }

        $filter_end_date = $this->request->get('filter_end_date');
        if($filter_end_date){

            $date = explode('/', $filter_end_date);
            $date = jalali_to_gregorian($date[0], $date[1], $date[2], '-');

            $where_array[] = array('transactions.created_at', '<=', $date);
        }

        $filter_code = $this->request->get('filter_code');
        if($filter_code){

            $code = explode('-', $filter_code);
            if(isset($code[1]) && !empty($code[1])){
                $where_array[] = array('transactions.id', $code[1]);
            }else{
                $where_array[] = array('transactions.id', 0);
            }
        }



        //get data query
        $request = transaction::join('users', 'transactions.user_id' , '=' , 'users.id')
            ->join('order_details', 'order_details.val' , '=', 'transactions.key' )
            ->join('orders', 'orders.id' , '=', 'order_details.order_id' )
            ->where('orders.status' , 'paid')
            ->where('orders.lathe_id' , $userLogin->id)
            ->where('transactions.type' , 'order')
            ->where('transactions.status' , '!=' ,  'cancel')
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
                        ->where('orders.lathe_id' , $userLogin->id)
                        ->select(
                            'prescriptions.*',
                            'products.type as product_type',
                            'products.sku as product_sku',
                            'products.seller_id as seller',
                            'orders.created_at as created',
                            'orders.price as price',
                            'orders.lathe_id as lathe_id',
                            'orders.order_key as key',
                            'orders.id as order_id'
                        )->get();

                    if($order){
                        foreach ($order as $v){
                            $total = $v['price'];

                            $order_service = Order_detail::where('order_id', $v['order_id'])->where('key', 'LIKE' ,'%order_service%%price%')->get();
                            if($order_service){
                                foreach ($order_service as $service) {
                                    $total = $total + $service['val'];
                                }
                            }
                            $order_service = Order_detail::where('order_id', $v['order_id'])->where('key', 'LIKE' ,'%order_service%%labrator%')->first();
                            if($order_service){
                                $labrator[$v['order_id']] = $order_service->val;
                            }

                            $fullCart[$trans->id][$v['order_id']] = $total;
                            $orderData[$trans->id][] = $v;
                        }
                    }

                }

            }
        }

        //view data
        return view('amel/orders/index', ['request' => $request, 'orderData' => $orderData, 'fullTotal' => $fullCart, 'labrator' => $labrator]);
    }

    public function orderShow(){
        $where_array = array();
        $userLogin = Auth::user();
        $key = $this->request->key;

        //get data query
        $order_detail_list = [];
        $fullCart = [];
        $UserLogin = [];
        $labrator = [];
        $order_details = Order_detail::where('key' , 'thank_you_key')->where('val' , $key)->get();
        if($order_details){
            foreach ($order_details as $order_detail){
                $order_detail_list[] = $order_detail['order_id'];
            }

            $order = Order::join('prescriptions', 'prescriptions.id' , '=', 'orders.prescription_id' )
                ->join('products', 'products.id' , '=', 'orders.product_id' )
                ->whereIN('orders.id' , $order_detail_list)
                ->where('orders.status' , 'paid')
                ->where('orders.lathe_id' , $userLogin->id)
                ->select(
                    'prescriptions.*',
                    'products.type as product_type',
                    'products.sku as product_sku',
                    'products.seller_id as seller',
                    'products.brand_id as brand_id',
                    'orders.created_at as created',
                    'orders.price as price',
                    'orders.lathe_id as lathe_id',
                    'orders.order_key as key',
                    'orders.id as order_id',
                    'orders.user_id'
                )->get();

            if($order){
                foreach ($order as $v){
                    $total = $v['price'];

                    $order_service = Order_detail::where('order_id', $v['order_id'])->where('key', 'LIKE' ,'%order_service%%price%')->get();
                    if($order_service){
                        foreach ($order_service as $service) {
                            $total = $total + $service['val'];
                        }
                    }
                    $order_service = Order_detail::where('order_id', $v['order_id'])->where('key', 'LIKE' ,'%order_service%%labrator%')->first();
                    if($order_service){
                        $labrator[$v['order_id']] = $order_service->val;
                    }


                    $fullCart[$v['order_id']] = $total;
                }

                $UserLogin = User::where('id', $order[0]['user_id'])->first();

            }

        }

        $trans = transaction::where('key', $key)->first();

        if($order){
            //view data
            return view('amel/orders/print', ['UserLogin' => $UserLogin, 'orderData' => $order, 'fullTotal' => $fullCart, 'labrator' => $labrator, 'trans' => $trans]);
        }else{
            return back();
        }
    }

    public function bill(){
        $userLogin = Auth::user();
        $where_array = array();

        //filter set to query

        $filter_start_date = $this->request->get('filter_start_date');
        if($filter_start_date){

            $date = explode('/', $filter_start_date);
            $date = jalali_to_gregorian($date[0], $date[1], $date[2], '-');

            $where_array[] = array('orders.created_at', '>=', $date);
        }

        $filter_end_date = $this->request->get('filter_end_date');
        if($filter_end_date){

            $date = explode('/', $filter_end_date);
            $date = jalali_to_gregorian($date[0], $date[1], $date[2], '-');

            $where_array[] = array('orders.created_at', '<=', $date);
        }

        $filter_checkout = $this->request->get('filter_checkout');
        if($filter_checkout){
            $where_array[] = array('orders.checkout_amel', $filter_checkout);
        }

        $filter_code = $this->request->get('filter_code');
        if($filter_code){

            $code = explode('-', $filter_code);
            if(isset($code[1]) && !empty($code[1])){

                $where_array[] = array('transactions.id', $code[1]);

                if(isset($code[2]) && !empty($code[2])) {
                    $where_array[] = array('orders.id', $code[2]);
                }

            }
        }


        //get data query

        $fullCart = [];
        $order = Order::join('products', 'products.id' , '=', 'orders.product_id' )
            ->join('order_details', 'order_details.order_id', '=' , 'orders.id')
            ->join('transactions', 'transactions.key', '=' , 'order_details.val')
            ->where('order_details.key' , 'thank_you_key')
            ->where('orders.status' , 'paid')
            ->where('orders.lathe_id' , $userLogin->id)
            ->where('orders.status_operator' , '>=' , 2)
            ->where($where_array)
            ->select(
                'products.type as product_type',
                'products.sku as product_sku',
                'products.seller_id as seller',
                'orders.created_at as created',
                'orders.paid_amount_amel as price',
                'orders.lathe_id as lathe_id',
                'orders.order_key as key',
                'orders.id as order_id',
                'transactions.id as transactions_id',
                'transactions.created_at as transactions_created_at',
                'transactions.payment_method as transactions_payment_method',
                'transactions.description as description'
            )->paginate(20);

        if($order){
            foreach ($order as $v){
                $total = $v['price'];

                $order_service = Order_detail::where('order_id', $v['order_id'])->where('key', 'LIKE' ,'%order_service%%price%')->get();
                if($order_service){
                    foreach ($order_service as $service) {
                        $total = $total + $service['val'];
                    }
                }

                $fullCart[$v['order_id']] = $total;
            }
        }


        //view data
        return view('amel/bill/index', ['request' => $order, 'fullTotal' => $fullCart]);

    }

}
