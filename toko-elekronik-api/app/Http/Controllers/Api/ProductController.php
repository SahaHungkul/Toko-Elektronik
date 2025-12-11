<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
class ProductController extends Controller
{
    public function index()
    {
        try{
            $produk = Product::all();
            return response()->json([
                'status' => true,
                'message' => 'data produk berhasil diambil',
                'data' => $produk,
            ]);
        }catch(\Exception $e){
            return response()->json([
                'status' => false,
                'message' => 'terjadi kesalahan',
                'error' => $e->getMessage(),
            ]);
        }
    }
    public function store(Request $request)
    {
        try{
            DB::beginTransaction();
            $validasi = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'required|numeric',
                'stock' => 'required|integer',
                'category_id' => 'required|exists:categories,id',
                'product_code' => 'nullable|string|max:50|unique:products,product_code',
            ]);
            $product = Product::create($validasi);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'produk berhasil disimpan',
                'data' => $product,
            ], 201);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'terjadi kesalahan',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function show($id)
    {
        try{
            $produk = Product::find($id);
            if(!$produk){
                return response()->json([
                    'status' => false,
                    'message' => 'produk tidak ditemukan',
                ],404);
            }
            return response()->json([
                'status' => true,
                'message' => 'data produk berhasil diambil',
                'data' => $produk,
            ]);
        }catch(\Exception $e){
            return response()->json([
                'status' => false,
                'message' => 'terjadi kesalahan',
                'error' => $e->getMessage(),
            ]);
        }
    }
    public function update(Request $request,$id)
    {
        try{
            DB::beginTransaction();
            $produk = Product::find($id);
            if(!$produk){
                return response()->json([
                    'status' =>false,
                    'message' => 'produk tidak ditemukan',
                ],404);
            }
            $validasi = $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'description' => 'sometimes|nullable|string',
                'price' => 'sometimes|required|numeric',
                'stock' => 'sometimes|required|integer',
                'category_id' => 'sometimes|required|exists:categories,id',
                'product_code' => 'sometimes|nullable|string|max:50|unique:products,product_code,'.$produk->id,
            ]);
            $produk->update($validasi);
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'produk berhasil diupdate',
                'data' => $produk,
            ]);
        }catch(\Exception $e){
            return response()->json([
                'status' => false,
                'message' => 'terjadi kesalahan',
                'error' => $e->getMessage(),
            ]);
        }
    }
    public function destroy($id)
    {
        try{
            $produk = Product::find($id);
            if(!$produk){
                return response()->json([
                    'status' => false,
                    'message' => 'produk tidak ditemukan',
                ],404);
            }
            $produk->delete();
            return response()->json([
                'status' => true,
                'message' => 'produk berhasil dihapus',
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

