<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function history()
    {
        return view('history');
    }

    public function contact()
    {
        return view('contact');
    }
}
