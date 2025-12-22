<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('programStudi');

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('program_studi_id')) {
            $query->where('program_studi_id', $request->program_studi_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate(20);
        $programStudis = ProgramStudi::all();

        return view('admin.users.index', compact('users', 'programStudis'));
    }

    public function create()
    {
        $programStudis = ProgramStudi::all();
        return view('admin.users.create', compact('programStudis'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'role' => 'required|in:admin,dosen,mahasiswa',
            'program_studi_id' => 'nullable|exists:program_studi,id',
            'nim' => 'nullable|string|unique:users,nim',
            'nip' => 'nullable|string|unique:users,nip',
            'nidn' => 'nullable|string',
            'no_telp' => 'nullable|string',
            'alamat' => 'nullable|string',
            'gelar_depan' => 'nullable|string',
            'gelar_belakang' => 'nullable|string',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        
        User::create($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan');
    }

    public function edit(User $user)
    {
        $programStudis = ProgramStudi::all();
        return view('admin.users.edit', compact('user', 'programStudis'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,dosen,mahasiswa',
            'program_studi_id' => 'nullable|exists:program_studi,id',
            'nim' => 'nullable|string|unique:users,nim,' . $user->id,
            'nip' => 'nullable|string|unique:users,nip,' . $user->id,
            'nidn' => 'nullable|string',
            'no_telp' => 'nullable|string',
            'alamat' => 'nullable|string',
            'gelar_depan' => 'nullable|string',
            'gelar_belakang' => 'nullable|string',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil diupdate');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dihapus');
    }

    public function toggleStatus(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
        return back()->with('success', 'Status user berhasil diubah');
    }

    public function resetPassword(Request $request, User $user)
    {
        $newPassword = $request->input('password', 'password123');
        $user->update(['password' => Hash::make($newPassword)]);
        
        return back()->with('success', 'Password berhasil direset');
    }
}
