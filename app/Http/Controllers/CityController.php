<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CityController extends Controller
{
    //
    public function ajaxGetByCountry(Request $request)
    {
        $cities = City::where('country', $request->country)
            ->where(function($query) use ($request) {
                $query->where('city', 'LIKE', '%' . $request->search . '%')
                      ->orWhere("admin_name", "LIKE", "%" . $request->search . "%");
            })
            ->get()
            ->map(function ($item) {
                $item->text = $item->city . ', ' . $item->admin_name;
                return $item;
            });

        return response()->json($cities);
    }
}
