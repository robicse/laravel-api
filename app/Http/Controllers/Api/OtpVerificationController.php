<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VerificationCode;
use App\Helpers\UserInfo;

class OtpVerificationController extends Controller
{
    public function OtpSend(Request $request)
    {
        //return 'ok';
        $verification = VerificationCode::where('phone',$request->phone)->first();
        if (!empty($verification)){
            $verification->delete();
        }
        $verCode = new VerificationCode();
        $verCode->phone = $request->phone;
        $verCode->code = mt_rand(1111,9999);
        $verCode->status = 0;
        $verCode->save();
        $text = $verCode->code." is your One-Time Password (OTP) for Demo API. Enjoy and purchase with Demo API.";
//        echo $text;exit();
        UserInfo::smsAPI("88".$verCode->phone,$text);
        return response()->json([
            'message' => 'OTP successfully sent to user'
        ], 201);
    }

    public function OtpCheck(Request $request)
    {
        //return 'ok';
        $verification = VerificationCode::where('phone',$request->phone)->where('status',0)->where('code',$request->code)->first();
        if (!empty($verification)){
            $verification->status = 1;
            $verification->save();
            return response()->json([
                'message' => 'OTP Checked successfully'
            ], 201);
        }else{
            return response()->json([
                'message' => 'OTP Code does not match!!'
            ], 201);
        }

    }
}
