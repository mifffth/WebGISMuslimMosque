<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Points;

class PointController extends Controller
{
    public function __construct()
    {
        $this->point = new Points();
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
                    'name' => $p->toponim,
                    'description' => $p->objek,
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
    // Implementation for creating a new resource goes here (if needed)

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate data
        $request->validate(
            [
                'toponim' => 'required',
                'objek' => 'required',
                'geom' => 'required',
                'image' => 'image|mimes:jpeg,png,jpg,gif|max:10000',
            ],
            [
                'toponim.required' => 'Name is required',
                'objek.required' => 'Description is required',
                'geom.required' => 'Location is required'
            ]
        );

        // Create folder for images if it doesn't exist
        if (!is_dir('storage/images')) {
            mkdir('storage/images', 0777);
        }

        // Upload new image if provided
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_point.' . $image->getClientOriginalExtension();
            $image->move('storage/images', $filename);
        } else {
            $filename = null;
        }

        // Prepare data for insertion
        $data = [
            'toponim' => $request->toponim,
            'objek' => $request->objek,
            'geom' => $request->geom,
            'image' => $filename
        ];

        // Create Point
        if (!$this->point->create($data)) {
            return redirect()->back()->with('error', 'Failed to create point');
        }

        // Redirect to map
        return redirect()->back()->with('success', 'Point created successfully');
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
                    'name' => $p->toponim,
                    'description' => $p->objek,
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
            'title' => 'Edit Point',
            'point' => $point,
            'id' => $id
        ];

        return view('edit-point', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate data
        $request->validate(
            [
                'toponim' => 'required',
                'objek' => 'required',
                'geom' => 'required',
                'image' => 'image|mimes:jpeg,png,jpg,gif|max:10000',
            ],
            [
                'toponim.required' => 'Name is required',
                'objek.required' => 'Description is required',
                'geom.required' => 'Location is required'
            ]
        );

        // Create folder for images if it doesn't exist
        if (!is_dir('storage/images')) {
            mkdir('storage/images', 0777);
        }

        // Upload new image if provided
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_point.' . $image->getClientOriginalExtension();
            $image->move('storage/images', $filename);

            // Delete old image if a new image is uploaded
            $image_old = $this->point->find($id)->image;
            if ($image_old != null) {
                unlink('storage/images/' . $image_old);
            }
        } else {
            // Retain the old image if a new image is not uploaded
            $filename = $request->image_old;
        }

        // Prepare data for update
        $data = [
            'toponim' => $request->toponim,
            'objek' => $request->objek,
            'geom' => $request->geom,
            'image' => $filename
        ];

        // Update Point
        if (!$this->point->find($id)->update($data)) {
            return redirect()->back()->with('error', 'Failed to update point');
        }

        // Redirect to map
        return redirect()->back()->with('success', 'Point updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // get image
        $image = $this->point->find($id)->image;

        // delete point
        if (!$this->point->destroy($id)) {
            return redirect()->back()->with('error', 'Failed to delete point');
        }

        // delete image
        if ($image != null) {
            unlink('storage/images/' . $image);
        }

        // redirect to map
        return redirect()->back()->with('success', 'Point deleted successfully');
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
