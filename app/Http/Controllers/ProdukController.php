<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $produk = Produk::all();
        return view('produk.index', compact('produk'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('produk.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|max:255',
            'jenis' => 'required|max:50',
            'harga_jual' => 'required|numeric:',
            'harga_beli' => 'required|numeric',
            'foto' => 'nullable|image|mimes:jpg,png,svg|max:2048',
        ],
        [
            'nama.required' => 'Nama wajib di isi',
            'nama.max' => 'Nama maksimal 255 karakter',
            'jenis.required' => 'jenis wajib di isi',
            'jenis.max' => 'jenis maksimal 45 karakter',
            'foto.max' => 'foto maksimal 2 mb',
            'foto.mimes' => 'File ekstensi hanya bisa jpg,png,svg',
            'foto.image' => 'File harus berbentuk image',
        ]
    );

    

        // Jika file foto ada yang terupload
        if (!empty($request->foto)) {
            // Maka proses berikut yang akan di jalankan 
            $fileName = 'foto-' . uniqid() . '.' . $request->foto->extension();
            // Setelah tau foto nya sudah masuk maka tempatkan di publik
            $request->foto->move(public_path('image'), $fileName);
        } else {
            $fileName = 'nophoto.jpg';
        }

        // Tambah data produk
        Produk::create([
            'nama' => $request->nama,
            'jenis' => $request->jenis,
            'harga_jual' => $request->harga_jual,
            'harga_beli' => $request->harga_beli,
            'deskripsi' => $request->deskripsi,
            'foto' => $fileName,
        ]);

        return redirect()->route('index.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('produk.edit', compact('id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama' => 'required|max:255',
            'jenis' => 'required|max:45',
            'harga_jual' => 'required|numeric',
            'harga_beli' => 'required|numeric',
            'foto' => 'nullable|image|mimes:jpg,png,svg|max:2048',
        ],
        [
            'nama.required' => 'Nama wajib di isi',
            'nama.max' => 'Nama maksimal 255 karakter',
            'jenis.required' => 'jenis wajib di isi',
            'jenis.max' => 'jenis maksimal 45 karakter',
            'foto.max' => 'foto maksimal 2 mb',
            'foto.mimes' => 'File ekstensi hanya bisa jpg,png,svg',
            'foto.image' => 'File harus berbentuk image',
        ]
    );

    // Temukan produk berdasarkan ID
    $produk = Produk::findOrFail($id);

    // Cek jika ada file foto baru yang di unggah
    if ($request->hasFile('foto')) {
        // Hapus foto lama jika ada
        if ($produk->foto && file_exists(public_path('image/' . $produk->foto))) {
            unlink(public_path('image/' . $produk->foto));
        }

        // Ganti foto dengan yang baru
        $fileName = 'foto-' . $id . '.' . $request->foto->extension();
        $request->foto->move(public_path('image'), $fileName);
    }else {
        $fileName = $produk->foto;
    }
        
    // Update data produk
    $produk->update([
        'nama' => $request->nama,
        'jenis' => $request->jenis,
        'harga_jual' => $request->harga_jual,
        'harga_beli' => $request->harga_beli,
        'foto' => $fileName
    ]);

    return redirect()->route('index.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $produk = Produk::findOrFail($id);
        $produk->delete();
        return redirect()->route('index.index')
        ->with('succes', 'Data berhasil di hapus');
    }
}
