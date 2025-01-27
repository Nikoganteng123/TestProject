<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Menampilkan semua produk.
     */
    public function index()
    {
        $products = Product::all();
        return response()->json([
            'message' => 'Successfully fetched products',
            'data' => $products
        ], 200);
    }

    /**
     * Menyimpan produk baru.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'image' => 'nullable|string' // Jika ada gambar
        ]);

        // Simpan ke database
        $product = Product::create($validated);

        return response()->json([
            'message' => 'Product successfully created',
            'data' => $product
        ], 201);
    }

    /**
     * Menampilkan detail produk berdasarkan ID.
     */
    public function show($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'message' => 'Product not found'
            ], 404);
        }

        return response()->json([
            'message' => 'Successfully fetched product',
            'data' => $product
        ], 200);
    }

    /**
     * Memperbarui produk berdasarkan ID.
     */
    public function update(Request $request, $id)
    {
        // Cari produk
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'message' => 'Product not found'
            ], 404);
        }

        // Validasi input
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'price' => 'sometimes|numeric',
            'image' => 'nullable|string'
        ]);

        // Update data
        $product->update($validated);

        return response()->json([
            'message' => 'Product successfully updated',
            'data' => $product
        ], 200);
    }

    /**
     * Menghapus produk berdasarkan ID.
     */
    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'message' => 'Product not found'
            ], 404);
        }

        // Hapus produk
        $product->delete();

        return response()->json([
            'message' => 'Product successfully deleted'
        ], 200);
    }
}
