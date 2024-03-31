<?php

namespace App\Http\Controllers;

use App\Models\Product;

use Illuminate\View\View;

use Illuminate\Http\RedirectResponse;

use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::latest()->paginate(5);

        return view('products.index', compact('products'));
    }

    public function create(): View
    {
        return view('products.create');
    }

    public function store(Request $request): RedirectResponse
    {
        // buat validasi form
        $this->validate($request, [
            'image' => 'required|image|mimes:jpeg,jpg,png,webp,avif|max:2048',
            'nama_produk' => 'required|min:5',
            'harga' => 'required|min:5',
            'deskripsi' => 'required|min:5'
        ]);

        //upload image
        $image = $request->file('image');
        $image->storeAs('public/products', $image->hashName());

        //create post
        Product::create([
            'image'     => $image->hashName(),
            'nama_produk'     => $request->nama_produk,
            'harga'   => $request->harga,
            'deskripsi'   => $request->deskripsi
        ]);

        return redirect()->route('products.index')->with(['success' => 'Data Berhasil Disimpan!']);
    }

    public function show(string $id): View
    {
        $product = Product::findOrFail($id);

        return view('products.show', compact('product'));
    }

    public function edit(string $id): View
    {
        //get post by ID
        $product = Product::findOrFail($id);

        //render view with post
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        //validate form
        $this->validate($request, [
            'image'     => 'image|mimes:jpeg,jpg,png|max:2048',
            'nama_produk'     => 'required|min:5',
            'harga'     => 'required|min:5',
            'deskripsi'   => 'required|min:5'
        ]);

        //get post by ID
        $product = Product::findOrFail($id);

        //check if image is uploaded
        if ($request->hasFile('image')) {

            //upload new image
            $image = $request->file('image');
            $image->storeAs('public/products', $image->hashName());

            //delete old image
            Storage::delete('public/products/'.$product->image);

            //update post with new image
            $product->update([
                'image'     => $image->hashName(),
                'nama_produk'     => $request->nama_produk,
                'harga'     => $request->harga,
                'deskripsi'   => $request->deskripsi
            ]);

        } else {

            //update post without image
            $product->update([
                'nama_produk'     => $request->nama_produk,
                'harga'     => $request->harga,
                'deskripsi'   => $request->deskripsi
            ]);
        }

        //redirect to index
        return redirect()->route('products.index')->with(['success' => 'Data Berhasil Diubah!']);
    }

    public function destroy($id): RedirectResponse
    {
        $product = Product::findOrFail($id);

        // hapus gambar
        Storage::delete('public/products/'. $product->image);

        $product->delete();

        return redirect()->route('products.index')->with(['success' => 'Data Berhasil Dihapus!']);
    }

    public function home(): View
    {
        //get posts
        $products = Product::latest()->paginate(8);

        //render view with posts
        return view('products.home', compact('products'));
    }
}
