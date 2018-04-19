<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function showUserManagerment()
    {
        

        return view('admins.user_managerment');
    }
}
