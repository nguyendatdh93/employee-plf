<?php

namespace App\Http\Controllers;

use App\Http\Middleware\CheckIpRange;
use App\Http\Middleware\CheckResetPassword;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(CheckIpRange::class);
        $this->middleware('auth');
        $this->middleware(CheckResetPassword::class);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }
}
