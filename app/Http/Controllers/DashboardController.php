<?php

namespace App\Http\Controllers;

use App\Models\Points;
use App\Models\Polylines;
use App\Models\Polygons;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->points = new Points();
     
    }

    public function index()
    {
        $data = [
            "title" => "Dashboard",
            "Total_points" => $this->points->count(),
           
        ];

        return view ('dashboard', $data);
    }
}