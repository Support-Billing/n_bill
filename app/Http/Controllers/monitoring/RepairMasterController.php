<?php

namespace App\Http\Controllers\monitoring;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Services\MyService;
use App\Models\monitoring\RepairCdr;
use DB;

class RepairMasterController extends Controller
{
    
    private $_page_title = 'Repair and chek data Master ';
    private $_url_data = 'repairchekmaster';
    private $_access_menu;
    private $_myService;
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->_myService = app(MyService::class);
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $this->_access_menu = $this->_myService->parser_access_menu($this->_url_data);
            return $next($request);
        });
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        echo 'ready to check';
    }
}
