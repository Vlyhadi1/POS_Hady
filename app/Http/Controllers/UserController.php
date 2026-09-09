<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchRequest;
use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index(SearchRequest $request)
    {
        $search = trim((string) $request->input('search'));
        $role = strtolower(trim((string) $request->input('role')));

        $users = User::with('role')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($role !== '', function ($query) use ($role) {
                $query->whereHas('role', function ($q) use ($role) {
                    $q->whereRaw('LOWER(name) = ?', [$role]);
                });
            })
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        // Statistik harus berdasarkan seluruh data, bukan hanya halaman pagination.
        $totalUsers = User::count();
        $totalAdmins = User::whereHas('role', fn ($q) => $q->whereRaw("LOWER(name) = 'admin'"))->count();
        $totalKasir = User::whereHas('role', fn ($q) => $q->whereRaw("LOWER(name) = 'kasir'"))->count();
        $roles = Role::orderBy('name')->get();

        return view('users.index', compact(
            'users',
            'roles',
            'totalUsers',
            'totalAdmins',
            'totalKasir'
        ));
    }

    public function create()
    {
        $roles = Role::orderBy('name')->get();

        return view('users.create', compact('roles'));
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('photos', 'public');
        }

        $data['password'] = Hash::make($data['password']);

        User::create($data);

        return redirect()
            ->route('admin.users')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $roles = Role::orderBy('name')->get();

        return view('users.edit', compact('user', 'roles'));
    }

    public function update(UpdateRequest $request, User $user)
    {
        $data = $request->validated();

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->role_id = $data['role_id'];

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        if ($request->hasFile('photo')) {
            $this->deletePhoto($user->photo);
            $user->photo = $request->file('photo')->store('photos', 'public');
        }

        $user->save();

        return redirect()
            ->route('admin.users')
            ->with('success', 'Data user berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Akun yang sedang digunakan tidak dapat dihapus.');
        }

        $adminRole = Role::whereRaw("LOWER(name) = 'admin'")->first();
        if ($adminRole && $user->role_id === $adminRole->id && User::where('role_id', $adminRole->id)->count() <= 1) {
            return back()->with('error', 'Admin terakhir tidak dapat dihapus. Buat admin lain terlebih dahulu.');
        }

        $this->deletePhoto($user->photo);
        $user->delete();

        return back()->with('success', 'User berhasil dihapus.');
    }

    public function profile()
    {
        return view('profile.index', [
            'user' => auth()->user()->load('role'),
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'remove_photo' => ['nullable', 'boolean'],
            'current_password' => ['nullable', 'required_with:password', 'current_password'],
            'password' => ['nullable', 'confirmed', 'min:8'],
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan user lain.',
            'photo.image' => 'File foto tidak valid.',
            'photo.mimes' => 'Foto harus JPG, JPEG, PNG, atau WEBP.',
            'photo.max' => 'Ukuran foto maksimal 2MB.',
            'current_password.required_with' => 'Password saat ini wajib diisi jika ingin mengganti password.',
            'current_password.current_password' => 'Password saat ini tidak sesuai.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
            'password.min' => 'Password baru minimal 8 karakter.',
        ]);

        unset($data['current_password'], $data['remove_photo']);

        if ($request->hasFile('photo')) {
            $this->deletePhoto($user->photo);
            $data['photo'] = $request->file('photo')->store('photos', 'public');
        } elseif ($request->boolean('remove_photo')) {
            $this->deletePhoto($user->photo);
            $data['photo'] = null;
        }

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    private function deletePhoto(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
