<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    //index
    public function index(Request $request)
    {
        $products = Product::with('category')->when($request->status, function ($query) use ($request) {
            $query->where('status', "%{$request->status}%");
        })->orderBy('favorite', 'desc')->get();
        return response()->json(['status' => 'success', 'data' => $products, 200]);
    }

    //store
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required',
            'name' => 'required',
            'price' => 'required',
            'image' => 'required',
            'criteria' => 'required',
            'favorite' => 'required',
            'status' => 'required',
            'stock' => 'required',
        ]);
        $product = new Product;

        $product->category_id = $request->category_id;
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;

        $product->criteria = $request->criteria;
        $product->status = $request->status;
        $product->favorite = $request->favorite;
        $product->stock = $request->stock;
        $product->save();
        //upload image
        if ($request->file('image')) {
            $image = $request->file('image');
            $image->storeAs('public/products', $product->id . '.png');
            $product->image =  $product->id . '.png';
            $product->save();
        }


        return response()->json(['status' => 'success', 'data' => $product], 200);
    }
    //show
    public function show($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['status' => 'error', 'message' => 'product not found'], 400);
        }
        return response()->json(['status' => 'success', 'data' => $product], 200);
    }

    //update
    public function update(Request $request, $id)
    {
        // Temukan produk berdasarkan ID
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['status' => 'error', 'message' => 'Product not found'], 400);
        }
        // dd($product);
        // dd($request->all());
        // Validasi input
        $request->validate([
            'category_id' => 'required',
            'name' => 'required',
            'price' => 'required',

            'criteria' => 'required',
            'favorite' => 'required',
            'status' => 'required',
            'stock' => 'required',
        ]);


        // Update properti produk
        $product->category_id = $request->category_id;
        $product->name = $request->name;
        $product->price = $request->price;
        $product->description = $request->description;
        $product->criteria = $request->criteria;
        $product->status = $request->status;
        $product->favorite = $request->favorite;
        $product->stock = $request->stock;
        $product->save();

        // Cek jika ada file gambar yang diunggah
        if ($request->file('image')) {
            $image = $request->file('image');
            $image->storeAs('public/products', $product->id . '.png');
            $product->image =  $product->id . '.png';
            $product->save();
        }

        return response()->json(['status' => 'success', 'data' => $product], 200);
    }
    //destroy
    public function destroy($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['status' => 'error', 'message' => 'Product not found'], 200);
        }
        $product->delete();
        return response()->json(['status' => 'success', 'message' => 'Product deleted'], 400);
    }
}
