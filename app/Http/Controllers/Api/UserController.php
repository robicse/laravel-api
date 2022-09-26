<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\UserCollection;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function info($id)
    {
        return new UserCollection(User::where('id', $id)->get());
    }

    public function updateName(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        $this->validate($request,[
            'name' => 'required',
            'email' => 'required|string|email|unique:users,email,'.Auth::id(),
        ]);
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->avatar_original == $user->avatar_original){

        }else{
            if($request->hasFile('avatar_original')){
                $user->avatar_original = $request->avatar_original->store('uploads/users');
            }
        }
        $user->save();
        return response()->json([
            'message' => 'Profile information has been updated successfully'
        ]);
    }

    public function userList()
    {
        return new UserCollection(User::all());
    }
}
