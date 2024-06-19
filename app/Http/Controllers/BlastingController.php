<?php

namespace App\Http\Controllers;

use App\Models\Blasting;
use Illuminate\Http\Request;

class BlastingController extends Controller
{
    //
    public function index()
    {
        $blasting = Blasting::all();
        return response()->json($blasting);
    }
}
