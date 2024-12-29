<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reviews;

class ReviewController extends Controller
{
    public function storeupdate(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'critic' => 'required',
            'rate' => 'required|integer',
            'movie_id' => 'required|exists:movie,id'
        ], [
            'required' => 'inputan :attribute harus diisi tidak boleh kosong',
            'integer' => 'inputan :attribute harus berupa angka',
            'exist' => 'inputan :attribute tidak ditemukan di tabel movies'
        ]);
        $review = Reviews::updateOrCreate(
            ['user_id' => $user->id],
            [
                'critic' => $request->input('critic'),
                'rate' => $request->input('rate'),
                'movie_id' => $request->input('movie_id')
            ]
        );
        return response([
            "message" => 'Review berhasil dibuat/diupdate'
        ], 201);
    }
}
