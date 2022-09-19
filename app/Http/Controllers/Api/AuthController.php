<?php /** @noinspection PhpUndefinedClassInspection */

namespace App\Http\Controllers\Api;

use App\Models\BusinessSetting;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\User;
use App\Notifications\EmailVerificationNotification;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    public function signup(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6'
        ]);
        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        // if(BusinessSetting::where('type', 'email_verification')->first()->value != 1){
        //     $user->email_verified_at = date('Y-m-d H:m:s');
        // }
        // else {
        //     $user->notify(new EmailVerificationNotification());
        // }
        $user->email_verified_at = date('Y-m-d H:m:s');
        $user->save();

        $customer = new Customer;
        $customer->user_id = $user->id;
        $customer->save();
        return response()->json([
            'message' => 'Registration Successful. Please verify and log in to your account.'
        ], 201);
    }
    public function signupViaPhone(Request $request)
    {
        $check = User::where('phone', $request->phone)->first();
        if (!empty($check)){
            return response()->json([
                'message' => 'This number already exist in our system try another one'
            ], 201);
        }
        $request->validate([
            'name' => 'required|string',
            'phone' => 'required',
            'password' => 'required|string|min:6'
        ]);
        $user = new User([
            'name' => $request->name,
            'phone' => $request->phone,
            'email_verified_at' => date('Y-m-d H:m:s'),
            'password' => bcrypt($request->password)
        ]);
        $user->save();

        $customer = new Customer;
        $customer->user_id = $user->id;
        $customer->save();
        return response()->json([
            'message' => 'Registration Successful. Please verify and log in to your account.'
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            //'email' => 'required|string|email',
            'name' => 'required|string',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);
        //$credentials = request(['email', 'password']);
        $credentials = request(['name', 'password']);
        if (!Auth::attempt($credentials))
            return response()->json(['message' => 'Unauthorized'], 401);
        $user = $request->user();
        if($user->email_verified_at == null){
            return response()->json(['message' => 'Please verify your account'], 401);
        }
        $tokenResult = $user->createToken('Personal Access Token');
        return $this->loginSuccess($tokenResult, $user);
    }
    public function mobileLogin(Request $request)
    {

        $request->validate([
            'phone' => 'required|string',
            'password' => 'required|string',
        ]);
        $credentials = request(['phone', 'password']);
        if (!Auth::attempt($credentials))
            return response()->json(['message' => 'Unauthorized'], 401);
        $user = $request->user();

//        if($user->email_verified_at == null){
//            return response()->json(['message' => 'Please verify your account'], 401);
//        }
        $tokenResult = $user->createToken('Personal Access Token');
        return $this->loginSuccess($tokenResult, $user);
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    public function socialLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email'
        ]);
        if (User::where('email', $request->email)->first() != null) {
            $user = User::where('email', $request->email)->first();
        } else {
            $user = new User([
                'name' => $request->name,
                'email' => $request->email,
                'provider_id' => $request->provider,
                'email_verified_at' => Carbon::now()
            ]);
            $user->save();
            $customer = new Customer;
            $customer->user_id = $user->id;
            $customer->save();
        }
        $tokenResult = $user->createToken('Personal Access Token');
        return $this->loginSuccess($tokenResult, $user);
    }

    protected function loginSuccess($tokenResult, $user)
    {
        $token = $tokenResult->token;
        $token->expires_at = Carbon::now()->addWeeks(100);
        $token->save();
        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString(),
            'user' => [
                'id' => $user->id,
                'type' => $user->user_type,
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => $user->avatar,
                'avatar_original' => $user->avatar_original,
                'address' => $user->address,
                'country'  => $user->country,
                'city' => $user->city,
                'postal_code' => $user->postal_code,
                'phone' => $user->phone
            ]
        ]);
    }
    public function changePass(Request $request)
    {
        $user = User::find($request->user_id);
        if (!empty($user)) {
            if (!Hash::check($request->old_password, $user->password)) {
                return response()->json([
                    'message' => 'Old password does not matched!'
                ]);
            }else{
                $user->password = Hash::make($request->new_password);
                $user->save();
                return response()->json([
                    'message' => 'Password Successfully Changed!'
                ]);
            }

        }else{
            return response()->json([
                'message' => 'User does not matched!'
            ]);
        }
    }

}
