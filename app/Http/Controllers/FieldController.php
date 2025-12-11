<?php

namespace App\Http\Controllers;

use App\Models\Field; // Import Model Field
//use Illuminate\Http\Request;

class FieldController extends Controller
{
    public function index()
    {
        try {
            $fields = Field::all(); 
            return view('fields.index', compact('fields'));
        } catch (\Throwable $th) {
            // Tampilkan error secara eksplisit di layar
            dd("ERROR FATAL DI FIELD CONTROLLER:", $th->getMessage());
        }
    }
}