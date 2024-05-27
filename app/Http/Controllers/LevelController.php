<?php

namespace App\Http\Controllers;

use App\Models\Level;
use Illuminate\Http\Request;

class LevelController extends Controller
{
    //

    public function index()
    {
        $level = Level::all();
        $data = [
            "data" => $level,
            "status" => 200
        ];

        return response()->json($data, 200);



    }
}
