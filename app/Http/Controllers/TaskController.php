<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Jumlah data yang di tampilkan per pagination
        $max_data = 5;

        if (request('search')) {
            // Menampilkan pencarian data
            $data = Task::where('task', 'like', '%' . request('search') . '%')->paginate($max_data)->withQueryString();
        } else {
            // Menampilkan data
            $data = Task::orderBy('id', 'desc')->paginate($max_data);
        }

        return view("task.app", compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validasi form
        $request->validate([
            'task' => 'required|min:5'
        ]);

        // Data yang akan di simpan
        $data = [
            'task' => $request->input('task')
        ];

        // Simpan Data
        Task::create($data);

        // redirect ke halaman task dan tampilkan pesan berhasil simpan data
        return redirect()->route('task')->with('succes', 'The new task had succesfully');
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'task' => 'required|min:5'
        ]);

        // data yang akan di ubah
            $data = [
            'task' => $request->input('task'),
            'is_done' => $request->input('is_done')
            ];

            // Ubah data berdasarkan id
            Task::where('id')->route('task')->with('succes', 'The task had succesfully updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Hapus data berdasarkan id
        Task::where('id', $id)->delete();

        // redirect ke halaman task dan tampilkan pesan berhasil hapus data
        return redirect()->route('task')->with('succes', 'The task had succesfully deleted');
    }
}
