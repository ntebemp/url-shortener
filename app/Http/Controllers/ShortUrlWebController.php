<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShortUrlWebController extends Controller
{
    public function index()
    {
        return view('home');
    }
}
