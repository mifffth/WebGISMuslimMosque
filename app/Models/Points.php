<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Points extends Model
{
    use HasFactory;

    protected $table = 'table_points';

    protected $fillable = [
        'toponim',
        'objek',
        'geom',
        'image'
    ];

    public function points()
    {
        return $this->select(DB::raw('id, toponim, objek, image, ST_AsGeoJSON(geom) as geom, created_at, updated_at'))->get();
    }

    public function point($id)
    {
        return $this->select(DB::raw('id, toponim, objek, image, ST_AsGeoJSON(geom) as geom, created_at, updated_at'))->where('id', $id)->get();
    }
}
