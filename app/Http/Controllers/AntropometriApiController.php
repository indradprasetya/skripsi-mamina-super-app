<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AntropometriApiController extends Controller
{
    public function get($tipe, $gender)
    {
        switch ($tipe) {
            case 'bbu':
                $model = $gender === 'laki'
                    ? \App\Models\AntropometriBbULaki::class
                    : \App\Models\AntropometriBbUPerempuan::class;
                break;
            case 'tbu':
                $model = $gender === 'laki'
                    ? \App\Models\AntropometriTbULaki::class
                    : \App\Models\AntropometriTbUPerempuan::class;
                break;
            case 'bbtb':
                $model = $gender === 'laki'
                    ? \App\Models\AntropometriBbTbLaki::class
                    : \App\Models\AntropometriBbTbPerempuan::class;
                break;
            case 'imtu':
                $model = $gender === 'laki'
                    ? \App\Models\AntropometriImtULaki::class
                    : \App\Models\AntropometriImtUPerempuan::class;
                break;
            case 'lku':
                $model = $gender === 'laki'
                    ? \App\Models\AntropometriLkULaki::class
                    : \App\Models\AntropometriLkUPerempuan::class;
                break;
            default:
                return response()->json(['error' => 'Tipe tidak valid'], 400);
        }

        $data = $model::all();
        return response()->json($data);
    }
}
