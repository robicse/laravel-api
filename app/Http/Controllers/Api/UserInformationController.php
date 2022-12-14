<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\UserCollection;
use App\UserInformation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserInformationController extends Controller
{
    public function store(Request $request)
    {
        //return $request->all();
        $this->validate($request,[
            'user_id' => 'required',
        ]);
        $user_information = new UserInformation();
        $user_information->user_id = $request->user_id;
        $user_information->location_name = $request->location_name;
        $user_information->latitude = $request->latitude;
        $user_information->longitude = $request->longitude;
        $user_information->temperature = $request->temperature;
        $user_information->weather = $request->weather;
        $user_information->weather_status = $request->weather_status;
        $user_information->capacitor = $request->capacitor;
        $user_information->total_strike = $request->total_strike;
        $user_information->save();
        return response()->json([
            'message' => 'User information has been updated successfully',
            'data' => UserInformation::all()
        ]);
    }

    public function information($id)
    {
        return UserInformation::where('user_id', $id)->get();
    }

    public function updateUserInfo(Request $request, $id)
    {
        // $this->validate($request,[
        //     'user_id' => 'required',
        // ]);

        $user_information = UserInformation::findOrFail($id);
        $user_information->user_id = $request->user_id ? $request->user_id : $user_information->user_id;
        $user_information->location_name = $request->location_name ? $request->location_name : $user_information->location_name;
        $user_information->latitude = $request->latitude ? $request->latitude : $user_information->latitude;
        $user_information->longitude = $request->longitude ? $request->longitude : $user_information->longitude;
        $user_information->temperature = $request->temperature ? $request->temperature : $user_information->temperature;
        $user_information->weather = $request->weather ? $request->weather : $user_information->weather;
        $user_information->weather_status = $request->weather_status ? $request->weather_status : $user_information->weather_status;
        $user_information->capacitor = $request->capacitor ? $request->capacitor : $user_information->capacitor;
        $user_information->total_strike = $request->total_strike ? $request->total_strike : $user_information->total_strike;
        $user_information->save();

        return response()->json([
            'message' => 'User information has been updated successfully'
        ]);
    }

    public function deleteUserInfo($id)
    {
        $user_information = UserInformation::findOrFail($id);
        $user_information->delete();

        return response()->json([
            'message' => 'User information has been deleted successfully'
        ]);
    }

    public function userInformationList()
    {
        return UserInformation::all();
    }
}
