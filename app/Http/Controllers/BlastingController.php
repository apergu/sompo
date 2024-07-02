<?php

namespace App\Http\Controllers;

use Exception;

use App\Models\Blasting;
use Illuminate\Http\Request;

class BlastingController extends Controller
{
    //
    public function index()
    {
        try {
            $blasting = Blasting::where([
                'SendNow' => 'Y'
            ])->get();

            if (count($blasting) > 0) {
                return response()->json($blasting);
            }

            return response()->json($blasting);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
