<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Casts;

class CastController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cast = Casts::all();
        //response
        return response([
            "message" => "tampil cast berhasil",
            "data" => $cast
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        //validasi
        $validated = $request->validate([
            'name' => 'required|min:3',
            'age' => 'required',
            'bio' => 'required',
        ], [

            'required' => 'inputan :attribute harus diisi',
            'min' => 'inputan :attribute harus :min karakter',

        ]);
        //tambah data ke DB
        $cast = new Casts;

        $cast->name = $request->input('name');
        $cast->age = $request->input('age');
        $cast->bio = $request->input('bio');

        $cast->save();
        //response
        return response([
            "message" => "tambah cast berhasil"
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $cast = Casts::find($id);
        if (!$cast) {
            return response([
                "message" => "Data cast tidak ditemukan"
            ], 404);
        }
        return response([
            'message' => 'Detail data Cast',
            'data' => $cast
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //validasi
        $validated = $request->validate([
            'name' => 'required|min:3',
            'age' => 'required',
            'bio' => 'required',
        ], [

            'required' => 'inputan :attribute harus diisi',
            'min' => 'inputan :attribute harus :min karakter',

        ]);
        $cast = Casts::find($id);
        if (!$cast) {
            return response([
                "message" => "Data cast tidak ditemukan"
            ], 404);
        }

        $cast->name = $request->input('name');
        $cast->age = $request->input('age');
        $cast->bio = $request->input('bio');

        $cast->save();
        return response([
            "message" => "Update Cast Berhasil"
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $cast = Casts::find($id);
        if (!$cast) {
            return response([
                "message" => "Data cast tidak ditemukan"
            ], 404);
        }
        $cast->delete();
        return response([
            "message" => "Berhasil menghapus Cast"
        ], 200);
    }
}
