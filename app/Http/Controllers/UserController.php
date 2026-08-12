<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchRequest;
use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage; // <--- PENTING: Tambahkan ini

class UserController extends Controller
{
    public function index(SearchRequest $request)
    {
        $keyword = $request->input('search');

        if ($keyword) {
            $users = User::whereRaw("MATCH(name, email) AGAINST(? IN BOOLEAN MODE)", [$keyword])
                ->paginate(10)
                ->withQueryString();
        } else {
            $users = User::latest()->paginate(10)->withQueryString();
        }

        return view('users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    public function store(StoreRequest $request)
    {
        $dataReq = $request->validated();

        // 1. TAMBAHKAN PENANGANAN FOTO DI SINI
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('photos', 'public');
        }

        User::create([
            'name'     => $dataReq['name'],
            'email'    => $dataReq['email'],
            'password' => Hash::make($dataReq['password']),
            'role_id'  => $dataReq['role_id'],
            'photo'    => $photoPath, // <--- Simpan path foto ke database
        ]);

        return redirect()->route('admin.users')->with('success', 'User berhasil dibuat');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(UpdateRequest $request, User $user)
    {
        $dataReq = $request->validated();

        $user->name    = $dataReq['name'];
        $user->email   = $dataReq['email'];
        $user->role_id = $dataReq['role_id'];

        if (!empty($dataReq['password'])) {
            $user->password = Hash::make($dataReq['password']);
        }

        // 2. TAMBAHKAN PENANGANAN UPDATE FOTO DI SINI
        if ($request->hasFile('photo')) {
            // Hapus foto lama dari storage jika ada
            if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }

            // Simpan foto baru
            $user->photo = $request->file('photo')->store('photos', 'public');
        }

        $user->save();

        return redirect()->route('admin.users.edit', $user->id)->with('success', 'User updated');
    }

    public function destroy(User $user)
    {
        // Hapus foto dari storage saat user dihapus
        if ($user->photo && Storage::disk('public')->exists($user->photo)) {
            Storage::disk('public')->delete($user->photo);
        }

        $user->delete();
        return back()->with('success', 'User deleted');
    }
}