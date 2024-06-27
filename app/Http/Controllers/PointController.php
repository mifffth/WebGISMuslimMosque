<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\points;

class pointController extends Controller
{
    public function __construct()
    {
        $this->point = new points();
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $points = $this->point->points();

        foreach ($points as $p) {
            $feature[] = [
                'type' => 'Feature',
                'geometry' => json_decode($p->geom),
                'properties' => [
                    'id' => $p->id,
                    'toponim' => $p->toponim,
                    'objek' => $p->objek,
                    'address' => $p->address,
                    'image' => $p->image,
                    'created_at' => $p->created_at,
                    'updated_at' => $p->updated_at
                ]
            ];
        }

        return response()->json([
            'type' => 'FeatureCollection',
            'features' => $feature,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        // Validate data
        $request->validate([
            'toponim' => 'required',
            'geom' => 'required',
            'image' => 'mimes:png,jpg,jpeg,gif,tiff|max:10000' //10mb
        ],
        [
            'toponim.required' => 'Nama lokasi harus diisi',
            'geom.required' => 'Titik lokasi harus diisi',
            'image.mimes' => 'Foto harus memiliki format : png,jpg,jpeg,gif,tiff',
            'image.max' => 'Foto tidak boleh melebihi 10 mb'
        ]);

        // Create folder images
        if (!is_dir('storage/images')) {
            mkdir('storage/images', 0777);
        };

        // Upload image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_point.' . $image->getClientOriginalExtension();
            $image->move('storage/images', $filename);
        } else {
            $filename = null;
        }
        $data = [
            'toponim' => $request->toponim,
            'objek' => $request->objek,
            'address' => $request->address,
            'geom' => $request->geom,
            'image' => $filename
        ];

        // Create point
        if (!$this->point->create($data)) {
            return redirect()->back()->with('Error', 'Gagal membuat titik lokasi');
        }

        // Redirect to map
        return redirect()->back()->with('Sukses', 'Titik lokasi berhasil dibuat');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $point = $this->point->point($id);

        foreach ($point as $p) {
            $feature[] = [
                'type' => 'Feature',
                'geometry' => json_decode($p->geom),
                'properties' => [
                    'id' => $p->id,
                    'toponim' => $p->toponim,
                    'objek' => $p->objek,
                    'address' => $p->address,
                    'image' => $p->image,
                    'created_at' => $p->created_at,
                    'updated_at' => $p->updated_at
                ]
            ];
        }

        return response()->json([
            'type' => 'FeatureCollection',
            'features' => $feature,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $point = $this->point->find($id);

        $data = [
            'title' => 'Edit point',
            'point' => $point,
            'id' => $id,
        ];

        return view('edit-point', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate data
        $request->validate([
            'toponim' => 'required',
            'geom' => 'required',
            'image' => 'mimes:png,jpg,jpeg,gif,tiff|max:10000' //10mb
        ],
        [
            'toponim.required' => 'Nama lokasi harus diisi',
            'geom.required' => 'Titik lokasi harus diisi',
            'image.mimes' => 'Foto harus memiliki format : png,jpg,jpeg,gif,tiff',
            'image.max' => 'Foto tidak boleh melebihi 10 mb'
        ]);

        // Create folder images
        if (!is_dir('storage/images')) {
            mkdir('storage/images', 0777);
        };

        // Upload image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_point.' . $image->getClientOriginalExtension();
            $image->move('storage/images', $filename);

            // Delete old image
            $image_old = !$this->point->find($id)->image;
            if ($image_old != null) {
                unlink('storage/images/' . $image_old);
            }
        } else {
            $filename = $request->image_old;
        }
        $data = [
            'toponim' => $request->toponim,
            'objek' => $request->objek,
            'address' => $request->address,
            'geom' => $request->geom,
            'image' => $filename
        ];

        // Update point
        if (!$this->point->find($id)->update($data)) {
            return redirect()->back()->with('Error', 'Gagal menyimpan data');
        }

        // Redirect to map
        return redirect()->back()->with('Sukses', 'Data berhasil tersimpan');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Get point
        $image = $this->point->find($id)->image;

        // Delete image
        if ($image != null) {
            unlink('storage/images/' . $image);
        }
        // Delete point
        if (!$this->point->destroy($id)) {
            return redirect()->back()->with('Error', 'Data gagal dihapus');
        }
        // Redirect to map
        return redirect()->back()->with('Sukses', 'Data berhasil dihapus');
    }

    public function table()
    {
        $points = $this->point->points();

        $data = [
            'title' => 'Table Point',
            'points' => $points
        ];

        return view('table-point', $data);
    }
}
