<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\SettingsCollection;
use App\Models\AppSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    public function index()
    {
        return new SettingsCollection(AppSettings::all());
    }

    public function updateName(Request $request, $id)
    {
        //return $request->all();
        $app_settings = AppSettings::findOrFail($request->id);
        $app_settings->name = $request->name;
        $app_settings->save();
        return response()->json([
            'message' => 'App Settings information has been updated successfully',
            'data' => $app_settings
        ]);
    }
}
