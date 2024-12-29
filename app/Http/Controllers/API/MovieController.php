<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Movie;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $movie = Movie::get();
        return response([
            "message" => "movie berhasil ditampilkan",
            "data" => $movie
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'poster' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'title' => 'required',
            'summary' => 'required',
            'genre_id' => 'required|exists:genres,id',
            'year' => 'required|numeric'
        ], [
            'required' => 'inputan :attribute harus diisi',
            'max' => 'inputan :attribute harus :max karakter',
            'mimes' => 'inputan :attribute harus format jpeg,png,jpg,gif',
            'poster' => 'inputan :attribute harus format gambar',
            'exist' => 'inputan :attribute tidak ditemukan di table genres',
        ]);
        $uploadedFileUrl = cloudinary()->upload($request->file('poster')->getRealPath(), [
            'folder' => 'poster'
        ])->getSecurePath();

        $movie = new Movie;

        $movie->title = $request->input('title');
        $movie->summary = $request->input('summary');
        $movie->genre_id = $request->input('genre_id');
        $movie->year = $request->input('year');
        $movie->poster = $uploadedFileUrl;

        $movie->save();

        return response([
            "message" => "movie berhasil ditambahkan"
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $movie = Movie::with('listMovie')->find($id);
        if (!$movie) {
            return response([
                "message" => "Data movie tidak ditemukan"
            ], 404);
        }
        return response([
            'message' => 'Detail data Movie',
            'data' => $movie
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'poster' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'title' => 'required',
            'summary' => 'required',
            'genre_id' => 'required|exists:genres,id',
            'year' => 'required'
        ], [
            'required' => 'inputan :attribute harus diisi',
            'max' => 'inputan :attribute harus :max karakter',
            'mimes' => 'inputan :attribute harus format jpeg,png,jpg,gif',
            'poster' => 'inputan :attribute harus format gambar',
            'exist' => 'inputan :attribute tidak ditemukan di table genres',
        ]);

        $movie = Movie::find($id);

        if ($request->hasFile('poster')) {
            $uploadedFileUrl = cloudinary()->upload($request->file('poster')->getRealPath(), [
                'folder' => 'poster',
            ])->getSecurePath();
            $movie->poster = $uploadedFileUrl;
        }

        if (!$movie) {
            return response([
                "message" => "Data movie tidak ditemukan"
            ], 404);
        }

        $movie->title = $request->input('title');
        $movie->summary = $request->input('summary');
        $movie->genre_id = $request->input('genre_id');
        $movie->year = $request->input('year');


        $movie->save();

        return response([
            "message" => "movie berhasil diupdate"
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $movie = Movie::find($id);
        if (!$movie) {
            return response([
                "message" => "Data movie tidak ditemukan"
            ], 404);
        }
        $movie->delete();

        return response([
            "message" => "movie berhasil dihapus"
        ], 200);
    }
}
