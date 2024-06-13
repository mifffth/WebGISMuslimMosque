<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShapefileController extends Controller
{
    public function getShapefile()
    {
        $shapefile = DB::table('shp_sleman')->get();
        $geojson = [
            'type' => 'FeatureCollection',
            'features' => [],
        ];

        foreach ($shapefile as $row) {
            $geojson['features'][] = [
                'type' => 'Feature',
                'geometry' => json_decode($row->geom), // assuming 'geom' is the geometry column
                'properties' => [
                    'name' => $row->KECAMATAN,
                    'description' => $row->KODE_KEC,
                    // add other properties as needed
                ],
            ];
        }

        return response()->json($geojson);
    }
}
