<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function landing()
    {
        $title = 'Landing Page'; // Adjust this as needed
        return view('landing', compact('title'));
    }
}
