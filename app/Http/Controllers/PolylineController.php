<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Polylines;

class PolylineController extends Controller
{
    public function __construct()
    {
        $this->polyline = new Polylines();
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $polylines = $this->polyline->polylines();

        foreach ($polylines as $p) {
            $feature[] = [
                'type' => 'Feature',
                'geometry' => json_decode($p->geom),
                'properties' => [
                    'id' => $p->id,
                    'name' => $p->name,
                    'description' => $p->description,
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

        //Validate data
        $request->validate([
            'name' => 'required',
            'geom' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:10000',
            'image.max' => 'Image must not exceed 10MB.'
        ],
        [
            'name.required' => 'Name is required',
            'geom.required' => 'Location is required'

        ]);

        //create folder images
        if (!is_dir('storage/images')) {
            mkdir('storage/images', 0777);
        };

        // upload image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_polyline.' . $image->getClientOriginalExtension();
            $image->move('storage/images', $filename);

        }
        else{
            $filename=null;
        }
        
        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'geom' => $request->geom,
            'image' => $filename
        ];


        // Create Polyline
    if(!$this->polyline->create($data)){
        return redirect()->back()->with('error', 'Failed to create polyline');
    }

        //Redirect to map
        return redirect()->back()->with('success', 'Polyline created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $polyline = $this->polyline->polyline($id);

        foreach ($polyline as $p) {
            $feature[] = [
                'type' => 'Feature',
                'geometry' => json_decode($p->geom),
                'properties' => [
                    'id' => $p->id,
                    'name' => $p->name,
                    'description' => $p->description,
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
        $polyline = $this->polyline->find($id);

        $data = [
            'title' => 'Edit Polyline',
            'polyline' => $polyline,
            'id' => $id
        ];

        return view('edit-polyline', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //Validate data
        $request->validate([
            'name' => 'required',
            'geom' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:10000',
            'image.max' => 'Image must not exceed 10MB.'
        ],
        [
            'name.required' => 'Name is required',
            'geom.required' => 'Location is required'

        ]);

        //create folder images
        if (!is_dir('storage/images')) {
            mkdir('storage/images', 0777);
        };

        // upload image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_polyline.' . $image->getClientOriginalExtension();
            $image->move('storage/images', $filename);
        
        //delete old image
        $image_old = $this->polyline->find($id)->image;
        if($image_old != null){
            unlink('storage/images/'.$image_old);
        }

        }
        else{
            $filename= $request->image_old;
        }
        
        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'geom' => $request->geom,
            'image' => $filename
        ];


        // Update Polyline
    if(!$this->polyline->find($id)->update($data)){
        return redirect()->back()->with('error', 'Failed to update polyline');
    }

        //Redirect to map
        return redirect()->back()->with('success', 'Polyline update successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // get image
        $image = $this->polyline->find($id)->image;

        //dd($image);

        // delete polyline
        if (!$this->polyline->destroy($id)) {
            return redirect()->back()->with('error', 'Failed to delete polyline');
        }

        // delete image
        if ($image !=null) {
        unlink('storage/images/' . $image);
        }  

        // redirect to map
        return redirect()->back()->with('success', 'Polyline deleted successfully');
    }

    public function table()
    {
        $polylines = $this->polyline->polylines();

        $data = [
            'title' => 'Table Polyline',
            'polylines' => $polylines
        ];

        return view('table-polyline', $data);
    }
}

