<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Points extends Model
{
    use HasFactory;

    protected $table = 'table_points';

    // protected $fillable = [
    //     'name',
    //     'description',
    //     'geom',
    //     'image'
    // ];
    protected $guarded = ['id'];

    public function points() //penambahan return image, ngambil data dari db dan jadi koleksi
    {
        return $this->select(DB::raw('id, toponim, objek, image, ST_AsGeoJSON(geom) as geom, created_at, updated_at'))->get();
    }
    public function point($id) //ngambil sesuai id, bukan semua
    {
        return $this->select(DB::raw('id, toponim, objek, image, ST_AsGeoJSON(geom) as geom, created_at, updated_at'))->where('id', $id)->get();
    }
}
