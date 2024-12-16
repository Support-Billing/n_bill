<?php

namespace App\Http\Controllers\report;


use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class ReportController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $_id_role = Auth::user()->id_role;
            $this->parser_access_menu($_id_role);

            return $next($request);
        });
    }
}
