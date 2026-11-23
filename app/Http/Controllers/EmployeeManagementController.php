<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Role;

class EmployeeManagementController extends Controller
{
    private function allowedPermissions(): array
    {
        $groups = [
            'users',
            'duty_roster',
            'areas',
            'cleaning_tasks',
            'cleaning_activities',
        ];

        $actions = ['view', 'add', 'edit', 'delete'];

        $permissions = [];

        foreach ($groups as $group) {
            foreach ($actions as $action) {
                $permissions[] = $group . '.' . $action;
            }
        }

        return $permissions;
    }

    private function cleanPermissions(?array $permissions): array
    {
        $allowed = $this->allowedPermissions();
        $cleaned = [];

        foreach ($permissions ?? [] as $permission => $value) {
            if (in_array($permission, $allowed, true) && (string) $value === '1') {
                $cleaned[$permission] = true;
            }
        }

        return $cleaned;
    }

    public function index()
    {
        $users = User::with('role')->get();
        $roles = Role::all();

        return view('employee-management.index', compact('users', 'roles'));
    }

    // TAMBAH USER
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'role_id' => 'required|exists:roles,id',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role_id' => $validated['role_id'],
        ]);

        return redirect()
            ->route('employee-management.index')
            ->with('success', 'Akun pengguna baru berhasil dibuat. Data telah tersimpan di dalam sistem.');
    }

    // EDIT USER
    public function editUser(User $user)
    {
        $roles = Role::all();

        return view('employee-management.edit-user', compact('user', 'roles'));
    }

    // UPDATE USER
    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'password' => 'nullable|string|min:8',
            'role_id' => 'required|exists:roles,id',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role_id = $validated['role_id'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()
            ->route('employee-management.index')
            ->with('success', 'Data pengguna telah berhasil diperbarui. Perubahan telah disimpan dalam sistem.');
    }

    // DELETE USER
    public function destroyUser(User $user)
    {
        $user->delete();

        return redirect()
            ->route('employee-management.index')
            ->with('success', 'Akun pengguna telah berhasil dihapus. Data telah dihapus dari sistem.');
    }

    // DELETE MULTIPLE USER
    public function destroyMultipleUsers(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'required|integer|exists:users,id',
        ]);

        User::whereIn('id', $validated['ids'])->delete();

        return redirect()
            ->route('employee-management.index')
            ->with('success', 'User yang dipilih berhasil dihapus!');
    }

    // TAMBAH ROLE
    public function storeRole(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'description' => 'nullable|string',
            'status' => 'nullable|in:Active',
            'permissions' => 'nullable|array',
        ]);

        $role = new Role();

        $role->name = $validated['name'];
        $role->description = $validated['description'] ?? null;
        $role->status = $request->input('status') === 'Active'
            ? 'Active'
            : 'Inactive';

        $role->permissions = $this->cleanPermissions(
            $validated['permissions'] ?? []
        );

        $role->save();

        return redirect()
            ->route('employee-management.index')
            ->with('success', 'Role berhasil ditambahkan!');
    }

    // EDIT ROLE
    public function editRole(Role $role)
    {
        return view('employee-management.edit-role', compact('role'));
    }

    // UPDATE ROLE
    public function updateRole(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles', 'name')->ignore($role->getKey()),
            ],
            'description' => 'nullable|string',
            'status' => 'required|in:Active,Inactive',
            'permissions' => 'nullable|array',
        ]);

        $role->name = $validated['name'];
        $role->description = $validated['description'] ?? null;
        $role->status = $validated['status'];

        $role->permissions = $this->cleanPermissions(
            $validated['permissions'] ?? []
        );

        $role->save();

        return redirect()
            ->route('employee-management.index')
            ->with('success', 'Role dan permission berhasil diperbarui!');
    }

    // DELETE ROLE
    public function destroyRole(Role $role)
    {
        if ($role->users()->exists()) {
            return redirect()
                ->route('employee-management.index')
                ->with('error', 'Role tidak dapat dihapus karena masih digunakan oleh user!');
        }

        $role->delete();

        return redirect()
            ->route('employee-management.index')
            ->with('success', 'Role berhasil dihapus!');
    }

    // DELETE MULTIPLE ROLE
    public function destroyMultipleRoles(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'required|integer|exists:roles,id',
        ]);

        $roles = Role::whereIn('id', $validated['ids'])->get();

        foreach ($roles as $role) {
            if ($role->users()->exists()) {
                return redirect()
                    ->route('employee-management.index')
                    ->with('error', 'Beberapa role tidak dapat dihapus karena masih digunakan oleh user!');
            }
        }

        Role::whereIn('id', $validated['ids'])->delete();

        return redirect()
            ->route('employee-management.index')
            ->with('success', 'Role yang dipilih berhasil dihapus!');
    }
}
