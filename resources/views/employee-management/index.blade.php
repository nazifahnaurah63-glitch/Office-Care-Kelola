<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Management | Office Care Kelola</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/design-system.css') }}">
    <link rel="stylesheet" href="{{ asset('css/employee-management.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

@php
    $currentRole = auth()->user()->role;
    $can = fn ($permission) => $currentRole && $currentRole->hasPermission($permission);
@endphp

{{-- Notifikasi Flash Sukses/Error --}}
@if (session('success'))
    <div class="kelola-flash kelola-flash-success" id="flashNotification">
        <span class="kelola-flash-icon">✓</span>
        <span>{{ session('success') }}</span>
    </div>
@endif

@if (session('error'))
    <div class="kelola-flash kelola-flash-error" id="flashNotification">
        <span class="kelola-flash-icon">!</span>
        <span>{{ session('error') }}</span>
    </div>
@endif

@if ($errors->any())
    <div class="kelola-flash kelola-flash-error" id="flashNotification">
        <span class="kelola-flash-icon">!</span>
        <div>
            <strong>Terjadi kesalahan!</strong>
            <ul style="margin: 5px 0 0; padding-left: 18px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

<div class="app">
    @include('partials.sidebar')

    <main class="main-content">
        @include('partials.topbar')

        <section class="kelola-page-heading">
            <div class="kelola-heading-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="10" cy="7" r="4"/>
                    <path d="M3 21v-2a7 7 0 0 1 14 0v2H3"/>
                    <path d="M19 8v6M16 11h6"/>
                </svg>
            </div>
            <div class="kelola-heading-text">
                <h1 class="kelola-page-title">Employee Management</h1>
                <p class="kelola-page-description">Kelola data karyawan kantor</p>
            </div>
        </section>

        {{-- Tabel User List --}}
        @if ($can('users.view'))
        <section class="kelola-table-card" data-table-container>
            <div class="kelola-table-header">
                <h2>User List</h2>
                <div class="kelola-table-controls">
                    <button type="button" class="kelola-btn kelola-btn-primary" onclick="openUserModal()">+ Tambah User</button>
                    <button type="button" class="kelola-btn kelola-btn-primary delete-selected" hidden><i class="fas fa-trash"></i> Delete</button>
                    <select class="kelola-page-length page-length" aria-label="Jumlah data">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
            </div>

            <div class="kelola-table-wrap">
                <table class="kelola-table feature-table">
                    <thead>
                        <tr>
                            <th><input type="checkbox" class="select-all"></th>
                            <th>Username</th>
                            <th>Gmail</th>
                            <th>Roles</th>
                            <th>Action</th>
                        </tr>
                        <tr class="kelola-search-row search-row">
                            <th></th>
                            <th><input type="text" placeholder="Cari Username"></th>
                            <th><input type="text" placeholder="Cari Gmail"></th>
                            <th><input type="text" placeholder="Cari Roles"></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td><input type="checkbox" class="row-checkbox" value="{{ $user->id }}"></td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="kelola-badge kelola-badge-danger">
                                        {{ $user->role?->name ?? 'Belum ada role' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="kelola-table-actions">
                                        @if ($can('users.edit'))
                                        <button type="button" class="kelola-action-icon" title="Edit"
                                            data-user-id="{{ $user->id }}"
                                            data-user-name="{{ $user->name }}"
                                            data-user-email="{{ $user->email }}"
                                            data-user-role="{{ $user->role_id }}"
                                            data-update-url="{{ route('employee-management.users.update', $user->id) }}"
                                            onclick="openEditUserModal(this)">
                                            <i class="fas fa-pen"></i>
                                        </button>
                                        @endif

                                        @if ($can('users.delete'))
                                        <form action="{{ route('employee-management.users.destroy', $user->id) }}" method="POST" class="delete-form" style="display:inline-flex; margin:0;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="kelola-action-icon" title="Hapus"><i class="fas fa-trash"></i></button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5">Belum ada data user.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="kelola-table-footer">
                <span class="kelola-table-info table-info">Showing 0 to 0 of 0 entries</span>
                <div class="kelola-pagination pagination"></div>
            </div>
        </section>
        @endif

        {{-- Tabel Roles List --}}
        @if ($can('roles.view'))
        <section class="kelola-table-card" data-table-container>
            <div class="kelola-table-header">
                <h2>Roles List</h2>
                <div class="kelola-table-controls">
                    @if ($can('roles.add'))
                    <button type="button" class="kelola-btn kelola-btn-primary" onclick="openRoleModal()">+ Tambah Role</button>
                    @endif
                    @if ($can('roles.delete'))
                    <button type="button" class="kelola-btn kelola-btn-primary delete-selected" hidden><i class="fas fa-trash"></i> Delete</button>
                    @endif
                    <select class="kelola-page-length page-length" aria-label="Jumlah data">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
            </div>

            <div class="kelola-table-wrap">
                <table class="kelola-table feature-table">
                    <thead>
                        <tr>
                            <th>@if ($can('roles.delete'))<input type="checkbox" class="select-all">@endif</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        <tr class="kelola-search-row search-row">
                            <th></th>
                            <th><input type="text" placeholder="Cari Name"></th>
                            <th><input type="text" placeholder="Cari Description"></th>
                            <th><input type="text" placeholder="Cari Status"></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($roles as $role)
                            <tr>
                                <td>@if ($can('roles.delete'))<input type="checkbox" class="row-checkbox" value="{{ $role->id }}">@endif</td>
                                <td>{{ $role->name }}</td>
                                <td>{{ $role->description ?? '-' }}</td>
                                <td>
                                    @php
                                        $status = $role->status ?? 'Active';
                                        $isActive = strtolower($status) === 'active';
                                    @endphp
                                    <span class="kelola-badge {{ $isActive ? 'kelola-badge-success' : 'kelola-badge-danger' }}">
                                        {{ $status }}
                                    </span>
                                </td>
                                <td>
                                    <div class="kelola-table-actions">
                                        @if ($can('roles.edit'))
                                        <button type="button" class="kelola-action-icon" title="Edit"
                                            data-role-id="{{ $role->id }}"
                                            data-role-name="{{ $role->name }}"
                                            data-role-description="{{ $role->description }}"
                                            data-role-status="{{ $role->status }}"
                                            data-role-permissions="{{ json_encode($role->permissions ?? []) }}"
                                            data-update-url="{{ route('employee-management.roles.update', $role->id) }}"
                                            onclick="openEditRoleModal(this)">
                                            <i class="fas fa-pen"></i>
                                        </button>
                                        @endif

                                        @if ($can('roles.delete'))
                                        <form action="{{ route('employee-management.roles.destroy', $role->id) }}" method="POST" class="delete-form" style="display:inline-flex; margin:0;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="kelola-action-icon" title="Hapus"><i class="fas fa-trash"></i></button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5">Belum ada data role.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="kelola-table-footer">
                <span class="kelola-table-info table-info">Showing 0 to 0 of 0 entries</span>
                <div class="kelola-pagination pagination"></div>
            </div>
        </section>
        @endif

        @include('partials.footer')
    </main>
</div>

{{-- Modal Add Role --}}
<div id="roleModal" class="kelola-modal-overlay">
    <div class="kelola-modal" style="max-width: 650px;">
        <div class="kelola-modal-header">
            <span>Add Role</span>
            <button type="button" class="kelola-modal-close" onclick="closeRoleModal()">&times;</button>
        </div>
        <form action="{{ route('employee-management.roles.store') }}" method="POST">
            @csrf
            <input type="hidden" name="_form_type" value="role">
            <div class="kelola-modal-body">
                <div class="kelola-form-grid">
                    <div class="kelola-form-group">
                        <label class="kelola-label">Name</label>
                        <input type="text" name="name" class="kelola-input" placeholder="Enter role name" required>
                    </div>
                    <div class="kelola-form-group">
                        <label class="kelola-label">Description</label>
                        <textarea name="description" class="kelola-textarea" style="min-height: 40px;" placeholder="Enter description"></textarea>
                    </div>
                </div>
                <div class="kelola-form-group">
                    <label class="kelola-label">Status</label>
                    <div class="role-status-row">
                        <label class="role-switch">
                            <input type="checkbox" name="status" value="Active" checked>
                            <span class="role-slider"></span>
                        </label>
                        <span class="status-text active-status">Active</span>
                    </div>
                </div>
                <hr style="border:0; border-top:1px solid #eee; margin:20px 0;">
                <h3 class="permission-title">Role Permission</h3>
                <div class="select-all-row">
                    <label><input type="checkbox" id="selectAllPermissions"> Select All</label>
                </div>
                <div class="permission-section">
                    @php
                        $permissionGroups = [
                            'Employee Management' => 'users',
                            'Duty Roster' => 'duty_roster',
                            'Area Management' => 'areas',
                            'Cleaning Task Management' => 'cleaning_tasks',
                            'Cleaning Activities' => 'cleaning_activities',
                        ];
                        $permissionActions = ['Read' => 'view', 'Created' => 'add', 'Update' => 'edit', 'Delete' => 'delete'];
                    @endphp
                    <table class="permission-table">
                        <thead>
                            <tr>
                                <th>Menu</th>
                                @foreach ($permissionActions as $label => $action)
                                    <th>{{ $label }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($permissionGroups as $group => $prefix)
                                <tr>
                                    <td>{{ $group }}</td>
                                    @foreach ($permissionActions as $action)
                                        <td><input type="checkbox" class="permission-checkbox" name="permissions[{{ $prefix }}.{{ $action }}]" value="1"></td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="kelola-modal-footer">
                <button type="button" class="kelola-btn kelola-btn-cancel" onclick="closeRoleModal()">Batal</button>
                <button type="submit" class="kelola-btn kelola-btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Edit Role --}}
<div id="editRoleModal" class="kelola-modal-overlay">
    <div class="kelola-modal" style="max-width: 650px;">
        <div class="kelola-modal-header">
            <span>Edit Role</span>
            <button type="button" class="kelola-modal-close" onclick="closeEditRoleModal()">&times;</button>
        </div>
        <form id="editRoleForm" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="_form_type" value="role_edit">
            <div class="kelola-modal-body">
                <div class="kelola-form-grid">
                    <div class="kelola-form-group">
                        <label class="kelola-label">Name</label>
                        <input type="text" id="editRoleName" name="name" class="kelola-input" placeholder="Enter role name" required>
                    </div>
                    <div class="kelola-form-group">
                        <label class="kelola-label">Description</label>
                        <textarea id="editRoleDescription" name="description" class="kelola-textarea" style="min-height: 40px;" placeholder="Enter description"></textarea>
                    </div>
                </div>
                <div class="kelola-form-group">
                    <label class="kelola-label">Status</label>
                    <div class="role-status-row">
                        <label class="role-switch">
                            <input type="hidden" name="status" value="Inactive">
                            <input type="checkbox" id="editRoleStatus" name="status" value="Active">
                            <span class="role-slider"></span>
                        </label>
                        <span class="status-text" id="editRoleStatusText">Inactive</span>
                    </div>
                </div>
                <hr style="border:0; border-top:1px solid #eee; margin:20px 0;">
                <h3 class="permission-title">Role Permission</h3>
                <div class="select-all-row">
                    <label><input type="checkbox" id="editSelectAllPermissions"> Select All</label>
                </div>
                <div class="permission-section">
                    <table class="permission-table">
                        <thead>
                            <tr>
                                <th>Menu</th>
                                <th>Read</th>
                                <th>Created</th>
                                <th>Update</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $editPermissionGroups = [
                                    'Employee Management' => 'users',
                                    'Duty Roster' => 'duty_roster',
                                    'Area Management' => 'areas',
                                    'Cleaning Task Management' => 'cleaning_tasks',
                                    'Cleaning Activities' => 'cleaning_activities',
                                ];
                                $editPermissionActions = ['view', 'add', 'edit', 'delete'];
                            @endphp
                            @foreach ($editPermissionGroups as $group => $prefix)
                                <tr>
                                    <td>{{ $group }}</td>
                                    @foreach ($editPermissionActions as $action)
                                        <td><input type="checkbox" class="edit-permission-checkbox" name="permissions[{{ $prefix }}.{{ $action }}]" value="1" data-permission="{{ $prefix }}.{{ $action }}"></td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="kelola-modal-footer">
                <button type="button" class="kelola-btn kelola-btn-cancel" onclick="closeEditRoleModal()">Batal</button>
                <button type="submit" class="kelola-btn kelola-btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Add User --}}
<div id="userModal" class="kelola-modal-overlay">
    <div class="kelola-modal">
        <div class="kelola-modal-header">
            <h2>Add User</h2>
            <button type="button" class="kelola-modal-close" onclick="closeUserModal()">&times;</button>
        </div>
        <form id="addUserForm" action="{{ route('employee-management.users.store') }}" method="POST">
            @csrf
            <input type="hidden" name="_form_type" value="user">
            <div class="kelola-modal-body">
                <div class="kelola-form-grid">
                    <div class="kelola-form-group">
                        <label for="name" class="kelola-label">Username <span class="kelola-required">*</span></label>
                        <input type="text" id="name" name="name" class="kelola-input" value="{{ old('name') }}" placeholder="Name" required>
                        @error('name')<small style="color:#bd001d; font-size:11px;">{{ $message }}</small>@enderror
                    </div>
                    <div class="kelola-form-group">
                        <label for="role_id" class="kelola-label">Role <span class="kelola-required">*</span></label>
                        <select id="role_id" name="role_id" class="kelola-select" required>
                            <option value="">Pilih Role</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                            @endforeach
                        </select>
                        @error('role_id')<small style="color:#bd001d; font-size:11px;">{{ $message }}</small>@enderror
                    </div>
                    <div class="kelola-form-group">
                        <label for="email" class="kelola-label">Email <span class="kelola-required">*</span></label>
                        <input type="email" id="email" name="email" class="kelola-input" value="{{ old('email') }}" placeholder="Email" required>
                        @error('email')<small style="color:#bd001d; font-size:11px;">{{ $message }}</small>@enderror
                    </div>
                    <div class="kelola-form-group">
                        <label for="password" class="kelola-label">Password <span class="kelola-required">*</span></label>
                        <input type="password" id="password" name="password" class="kelola-input" placeholder="Password" required>
                        @error('password')<small style="color:#bd001d; font-size:11px;">{{ $message }}</small>@enderror
                    </div>
                </div>
            </div>
            <div class="kelola-modal-footer">
                <button type="button" class="kelola-btn kelola-btn-cancel" onclick="closeUserModal()">Batal</button>
                <button type="submit" class="kelola-btn kelola-btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Edit User --}}
<div id="editUserModal" class="kelola-modal-overlay">
    <div class="kelola-modal">
        <div class="kelola-modal-header">
            <h2>Edit User</h2>
            <button type="button" class="kelola-modal-close" onclick="closeEditUserModal()">&times;</button>
        </div>
        <form id="editUserForm" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="_form_type" value="edit">
            <input type="hidden" name="_edit_url" id="edit_update_url">
            <div class="kelola-modal-body">
                <div class="kelola-form-grid">
                    <div class="kelola-form-group">
                        <label for="edit_name" class="kelola-label">Username <span class="kelola-required">*</span></label>
                        <input type="text" id="edit_name" name="name" class="kelola-input" placeholder="Name" required>
                    </div>
                    <div class="kelola-form-group">
                        <label for="edit_role_id" class="kelola-label">Role <span class="kelola-required">*</span></label>
                        <select id="edit_role_id" name="role_id" class="kelola-select" required>
                            <option value="">Pilih Role</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="kelola-form-group">
                        <label for="edit_email" class="kelola-label">Email <span class="kelola-required">*</span></label>
                        <input type="email" id="edit_email" name="email" class="kelola-input" placeholder="Email" required>
                    </div>
                    <div class="kelola-form-group">
                        <label for="edit_password" class="kelola-label">Password</label>
                        <input type="password" id="edit_password" name="password" class="kelola-input" placeholder="Kosongkan jika tidak diubah">
                    </div>
                </div>
            </div>
            <div class="kelola-modal-footer">
                <button type="button" class="kelola-btn kelola-btn-cancel" onclick="closeEditUserModal()">Batal</button>
                <button type="submit" class="kelola-btn kelola-btn-primary">Save</button>
            </div>
        </form>
    </div>
</div>

{{-- Form Delete Multiple --}}
<form id="deleteMultipleUsers" action="{{ route('employee-management.users.destroyMultiple') }}" method="POST" style="display:none;">
    @csrf @method('DELETE')
</form>
<form id="deleteMultipleRoles" action="{{ route('employee-management.roles.destroyMultiple') }}" method="POST" style="display:none;">
    @csrf @method('DELETE')
</form>

{{-- Modal Konfirmasi Delete --}}
<div id="deleteConfirmModal" class="kelola-modal-overlay">
    <div class="kelola-modal kelola-modal-sm">
        <div class="kelola-modal-header">
            <span>⚠️ Hapus Data?</span>
            <button type="button" class="kelola-modal-close" onclick="closeDeleteModal()">×</button>
        </div>
        <div class="kelola-modal-body" style="text-align: center;">
            <p id="deleteConfirmMessage" style="margin:0; font-size:15px; line-height:1.5; color:#222;">Apakah Anda yakin ingin menghapus data ini?</p>
        </div>
        <div class="kelola-modal-footer">
            <button type="button" class="kelola-btn kelola-btn-cancel" onclick="closeDeleteModal()">Batal</button>
            <button type="button" class="kelola-btn kelola-btn-primary" onclick="confirmDelete()">Ya, Lanjutkan</button>
        </div>
    </div>
</div>

<script src="{{ asset('js/employee.js') }}" defer></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        @if ($errors->any())
            @if (old('_form_type') === 'role')
                if (typeof openRoleModal === 'function') openRoleModal();
            @elseif (old('_form_type') === 'user')
                if (typeof openUserModal === 'function') openUserModal();
            @endif
        @endif
    });
</script>

</body>
</html>