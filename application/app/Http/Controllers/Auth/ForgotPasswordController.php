<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\SendSMS;
use App\User;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    //use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        date_default_timezone_set("Asia/Tehran");
        $this->middleware('guest');
    }

    public function showLinkRequestForm()
    {
        return view('auth.passwords.mobile');
    }

    public function sendResetLinkEmail(Request $request)
    {

        $validate = $this->validate($request, ['mobile' => 'required|numeric|regex:/(0)[0-9]{10}/']);


        $user = User::where('mobile', $validate['mobile'])->first();
        if(!$user) return back()->with('error' ,  'موبایل وارد شده صحیح نمی باشد.');

        $expiredTime = Carbon::parse($user->conferm_time)->addMinute(1);
        $newtime = date('Y-m-d H:i:s');

        if(strtotime($expiredTime) > strtotime($newtime)){
            return back()->with('error' , 'در حال حاضر امکان ارسال مجدد نمی باشد لطفا دقایقی دیگر امتحان کنید.');
        }

        $conferm = rand(111111, 999999);

        $user->password = Hash::make($conferm);
        $user->conferm_time = date('Y-m-d H:i:s');
        $user->save();
        SendSMS::sendConferm($validate['mobile'], $conferm, 'ForgotPassword');

        return redirect('login')->with('success' ,  'رمز عبور جدید برای شما پیامک شد. لطفا بعد از ورود رمز عبور خود را از حساب من تغییر دهید.');
    }
}
