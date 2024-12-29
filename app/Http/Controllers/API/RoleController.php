<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Roles;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware(['auth:api', 'Admin'])->except(['index', 'show']);
    }
    public function index()
    {
        $role = Roles::all();
        //Response
        return response([
            "message" => "Tampil Role Berhasil",
            "data" => $role
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required|min:2',
        ], [
            $messages = [
                'name' => 'Inputan :attribute harus diisi',
                'min' => 'Inputan :attribute harus :min Karakter',
            ]
        ]);

        //tambah data
        $role = new Roles;

        $role->name = $request->input('name');

        $role->save();

        //Response
        return response([
            "message" => "Tambah Role Berhasil"
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $role = Roles::find($id);
        if (!$role) {
            //Response
            return response([
                "message" => "Data Role Tidak ditemukan",
                "data" => $role
            ], 404);
        }
        return response([
            "message" => "Detail Data Role",
            "data" => $role
        ], 200);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validate = $request->validate([
            'name' => 'required|min:2',
        ], [
            $messages = [
                'name' => 'Inputan :attribute harus diisi',
                'min' => 'Inputan :attribute harus :min Karakter',
            ]
        ]);

        $role = Roles::find($id);
        if (!$role) {
            //Response
            return response([
                "message" => "Data Role Tidak ditemukan",
                "data" => $role
            ], 404);
        }

        $role->name = $request->input('name');

        $role->save();
        return response([
            "message" => "Update Role Berhasil"
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = Roles::find($id);
        if (!$role) {
            //Response
            return response([
                "message" => "Data Role Tidak ditemukan",
                "data" => $role
            ], 404);
        }
        $role->delete();
        return response([
            "message" => "Delete data Role Berhasil"
        ], 200);
    }
}
