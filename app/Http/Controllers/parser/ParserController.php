<?php

namespace App\Http\Controllers\parser;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use DB;

class ParserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Mengambil data dari body permintaan POST
        $data = $request->input('data');
        $valuesArray = str_getcsv($data);
        $JmlKol = count($valuesArray);

        // Kirim respons kembali ke klien
        return response()->json(
            [
                'message' => true,
                'data' => 'aaaaa',
                'JmlKol' => $JmlKol
            ]
        );
    }



}