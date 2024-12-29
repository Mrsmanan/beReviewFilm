<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Genres;

class GenreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $genre = Genres::all();
        //response
        return response([
            "message" => "tampil genre berhasil",
            "data" => $genre
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //validasi
        $validated = $request->validate([
            'name' => 'required'
        ], [

            'required' => 'inputan :attribute harus diisi',

        ]);
        //tambah data ke DB
        $genre = new Genres;

        $genre->name = $request->input('name');
        //$genre->age = $request->input('age');
        //$genre->bio = $request->input('bio');

        $genre->save();
        //response
        return response([
            "message" => "tambah genre berhasil"
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $genre = Genres::find($id);
        if (!$genre) {
            return response([
                "message" => "Data genre tidak ditemukan"
            ], 404);
        }
        return response([
            'message' => 'Detail data genre',
            'data' => $genre
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //validasi
        $validated = $request->validate([
            'name' => 'required'
        ], [

            'required' => 'inputan :attribute harus diisi',

        ]);
        $genre = Genres::find($id);
        if (!$genre) {
            return response([
                "message" => "Data genre tidak ditemukan"
            ], 404);
        }

        $genre->name = $request->input('name');

        $genre->save();
        //response
        return response([
            "message" => "update     genre berhasil"
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $genre = Genres::find($id);
        if (!$genre) {
            return response([
                "message" => "Data genre tidak ditemukan"
            ], 404);
        }
        $genre->delete();
        return response([
            "message" => "delete genre berhasil"
        ], 200);
    }
}
