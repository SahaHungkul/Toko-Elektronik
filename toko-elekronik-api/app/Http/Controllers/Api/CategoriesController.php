<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoriesResource;
use Illuminate\Http\Request;
use App\Models\Categories;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $categories = Categories::all();
            return response()->json([
                'status' => true,
                'message' => 'data kategori berhasil diambil',
                'data' => CategoriesResource::collection($categories),
            ]);
        }catch(\Exception $e){
            return response()->json([
                'status' => false,
                'message' =>'terjadi kesalahan',
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            $validasi = $request->validate([
                'name' => 'required|string|max:255',
            ]);
            $kategori = Categories::create($validasi);
            return response()->json([
                'status' => true,
                'message' => 'kategori berhasil ditambahkan',
                'data' => new CategoriesResource($kategori),
            ]);
        }catch(\Exception $e)
        {
            return response()->json([
                'status' => false,
                'message' => 'terjadi kesalahan',
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try{
            $kategori = Categories::find($id);
            if(!$kategori){
                return response()->json([
                    'status' => false,
                    'message' => 'kategori tidak ditemukan',
                ],404);
            }
            return response()->json([
                'status' => true,
                'message' => 'data kategori berhasil diambil',
                'data' => new CategoriesResource($kategori),
            ]);
        }catch(\Exception $e){
            return response()->json([
                'status' => false,
                'message' => 'terjadi kesalahan',
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try{
            $kategori = Categories::find($id);
            if(!$kategori){
                return response()->json([
                    'status' => false,
                    'message' => 'kategori tidak ditemukan',
                ],404);
            }
            $update = $request->validate([
                'name' => 'required|string|max:255'
            ]);
            $kategori->update($update);
            return response()->json([
                'status' => true,
                'message' => 'kategori berhasil di perbaharui',
                'data' => new CategoriesResource($kategori),
            ]);
        }catch(\Exception $e){
            return response()->json([
                'status' => false,
                'message' => 'terjadi kesalahan',
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            $kategori = Categories::find($id);
            if(!$kategori){
                return response()->json([
                    'status' => false,
                    'message' => 'kategori tidak ditemukan',
                ],404);
            }
            $kategori->delete();
            return response()->json([
                'status' => true,
                'message' =>'kategori berhasil dihapus',
            ]);
        }catch(\Exception $e){
            return response()->json([
                'status' => false,
                'message' => 'terjadi kesalahan',
                'error' => $e->getMessage(),
            ]);
        }
    }
}
