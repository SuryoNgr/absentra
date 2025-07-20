<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SupervisorController extends Controller
{
    public function index()
    {
        $supervisors = User::where('role', 'supervisor')->get();
        return view('admin.supervisor.index', compact('supervisors'));
    }

    public function create()
    {
        return view('admin.supervisor.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'role'     => 'supervisor',
            'password' => Hash::make('password123'), // Default password
        ]);

        return redirect()->route('admin.supervisor.index')->with('success', 'Supervisor berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $supervisor = User::where('role', 'supervisor')->findOrFail($id);
        return view('admin.supervisor.edit', compact('supervisor'));
    }

    public function update(Request $request, $id)
    {
        $supervisor = User::where('role', 'supervisor')->findOrFail($id);

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $supervisor->id,
        ]);

        $supervisor->update([
            'name'  => $request->name,
            'email' => $request->email,
        ]);

        return redirect()->route('admin.supervisor.index')->with('success', 'Supervisor berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $supervisor = User::where('role', 'supervisor')->findOrFail($id);
        $supervisor->delete();

        return redirect()->route('admin.supervisor.index')->with('success', 'Supervisor berhasil dihapus.');
    }
}