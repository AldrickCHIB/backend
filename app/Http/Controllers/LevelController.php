<?php

namespace App\Http\Controllers;

use App\Models\Level;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LevelController extends Controller
{
    //

    public function getAll()
    {
        $level = Level::select('clave', 'nombre')->get();

        if ($level->isEmpty()) {
            $data = [
                "message" => "No hay registros para Nivel Educativo",
                "status" => 404
            ];
            return response()->json($data, 404);
        } else {
            $data = [
                "data" => $level,
                "status" => 200
            ];
            return response()->json($data, 200);
        }
    }

    public function findOne($clave)
    {
        $level = Level::select('clave', 'nombre')->where('clave', $clave)->first();
        if (!$level) {
            $data = [
                "message" => "No se encontró el nivel educativo con clave '$clave'",
                "status" => 404,
            ];
            return response()->json($data, 404);
        }

        $data = [
            "message" => "Nivel educativo encontrado",
            "data" => $level,
            "status" => 200,
        ];
        return response()->json($data, 200);
    }

    public function save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "clave" => "required|unique:level",
            "nombre" => "required"
        ]);

        if ($validator->fails()) {
            $data = [
                "message" => "Error en la validación de los datos",
                "errora" => $validator->errors(),
                "status" => 400
            ];
            return response()->json($data, 400);
        }
        $level = Level::create([
            "clave" => $request->clave,
            "nombre" => $request->nombre
        ]);

        if (!$level) {
            $data = [
                "message" => "Error al crear el Nivel Educativo",
                "status" => 400,
            ];
            return response()->json($data, 400);
        }

        $data = [
            "message"=> "Nivel Educativo creado correctamente",
            "data" => $level,
            "status" => 201
        ];
        return response()->json($data, 201);
    }



}
