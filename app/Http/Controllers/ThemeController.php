<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ThemeController extends Controller
{
    public function index(Request $request)
    {
       return view('theme-dashboard');
    }

    public function main(Request $request)
    {
        return view('main');
    }
}
