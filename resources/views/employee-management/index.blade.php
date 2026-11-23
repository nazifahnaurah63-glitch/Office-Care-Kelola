<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Management | Office Care Kelola</title>
    <link rel="stylesheet" href="{{ asset('css/partials/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/partials/topbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/employee-management.css') }}">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

@php
    $currentRole = auth()->user()->role;

    $can = fn ($permission) =>
        $currentRole && $currentRole->hasPermission($permission);
@endphp
@if (session('success'))
    <div class="flash-notification flash-success" id="flashNotification">
        <span class="flash-icon">✓</span>
        <span>{{ session('success') }}</span>
    </div>
@endif

@if (session('error'))
    <div class="flash-notification flash-error" id="flashNotification">
        <span class="flash-icon">!</span>
        <span>{{ session('error') }}</span>
    </div>
@endif

@if ($errors->any())
    <div class="flash-notification flash-error" id="flashNotification">
        <span class="flash-icon">!</span>
        <div>
            <strong>Terjadi kesalahan!</strong>
            <ul>
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

        <section class="page-heading">
            <div class="heading-icon">♙</div>
            <div>
                <h1>Employee Management</h1>
                <p>Kelola data karyawan kantor</p>
            </div>
        </section>

        {{-- USER LIST --}}
        @if ($can('users.view'))
        <section class="employee-card data-card" data-table-container>

            <div class="employee-card-heading">
                <h2>User List</h2>

                <div class="table-controls">
                    <button type="button" class="add-button" onclick="openUserModal()">
                        + Add
                    </button>

                    <button type="button" class="delete-selected" hidden>
                        <i class="fas fa-trash"></i> Delete
                    </button>

                    <select class="page-length" aria-label="Jumlah data">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
            </div>

            <div class="employee-table-wrap">
                <table class="employee-table feature-table">
                    <thead>
                        <tr>
                            <th><input type="checkbox" class="select-all"></th>
                            <th>Username</th>
                            <th>Gmail</th>
                            <th>Roles</th>
                            <th>Action</th>
                        </tr>

                        <tr class="search-row">
                            <th></th>
                            <th><input type="text" placeholder="Search Username"></th>
                            <th><input type="text" placeholder="Search Gmail"></th>
                            <th><input type="text" placeholder="Search Roles"></th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td>
                                    <input type="checkbox" class="row-checkbox"
                                           value="{{ $user->id }}">
                                </td>

                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>

                                <td>
                                    <span class="role-badge {{ strtolower($user->role?->name ?? '') === 'admin' ? 'badge-admin' : 'badge-user' }}">
                                        {{ $user->role?->name ?? 'Belum ada role' }}
                                    </span>
                                </td>

                                <td>
                                    <div class="action-buttons">

    @if ($can('users.edit'))
    <button type="button"
        class="edit-action"
        title="Edit"
        data-user-id="{{ $user->id }}"
        data-user-name="{{ $user->name }}"
        data-user-email="{{ $user->email }}"
        data-user-role="{{ $user->role_id }}"
        onclick="openEditUserModal(this)">
        <i class="fas fa-pen"></i>
    </button>
@endif

@if ($can('users.delete'))
    <form action="{{ route('employee-management.users.destroy', $user->id) }}"
          method="POST"
          class="delete-form">
        @csrf
        @method('DELETE')

        <button type="submit" class="delete-action" title="Hapus">
            <i class="fas fa-trash"></i>
        </button>
    </form>
@endif

</div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">Belum ada data user.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="table-footer">
                <span class="table-info">Showing 0 to 0 of 0 entries</span>
                <div class="pagination"></div>
            </div>
        </section>
@endif
        {{-- ROLES LIST --}}
        @if ($can('roles.view'))
        <section class="employee-card data-card" data-table-container>

            <div class="employee-card-heading">
                <h2>Roles List</h2>

               <div class="table-controls">

    @if ($can('roles.add'))
        <button type="button" class="add-button" onclick="openRoleModal()">
    + Add
</button>
    @endif

    @if ($can('roles.delete'))
        <button type="button" class="delete-selected" hidden>
            <i class="fas fa-trash"></i> Delete
        </button>
    @endif

    <select class="page-length" aria-label="Jumlah data">
        <option value="10">10</option>
        <option value="25">25</option>
        <option value="50">50</option>
        <option value="100">100</option>
    </select>

</div>
            </div>

            <div class="employee-table-wrap">
                <table class="employee-table feature-table">
                    <thead>
                        <tr>
                            <th>
    @if ($can('roles.delete'))
    <input type="checkbox" class="select-all">
@endif
</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>

                        <tr class="search-row">
                            <th></th>
                            <th><input type="text" placeholder="Search Name"></th>
                            <th><input type="text" placeholder="Search Description"></th>
                            <th><input type="text" placeholder="Search Status"></th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($roles as $role)
                            <tr>
                                <td>
    @if ($can('roles.delete'))
        <input type="checkbox"
               class="row-checkbox"
               value="{{ $role->id }}">
    @endif
</td>

                                <td>{{ $role->name }}</td>
                                <td>{{ $role->description ?? '-' }}</td>

                                <td>
                                    <span class="status-badge">
                                        {{ $role->status ?? 'Active' }}
                                    </span>
                                </td>

                                <td>
    <div class="action-buttons">

        @if ($can('roles.edit'))
    <button type="button"
        class="edit-action"
        title="Edit"
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
            <form action="{{ route('employee-management.roles.destroy', $role->id) }}"
                  method="POST"
                  class="delete-form">
                @csrf
                @method('DELETE')

                <button type="submit" class="delete-action" title="Hapus">
                    <i class="fas fa-trash"></i>
                </button>
            </form>
        @endif

    </div>
</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">Belum ada data role.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="table-footer">
                <span class="table-info">Showing 0 to 0 of 0 entries</span>
                <div class="pagination"></div>
            </div>
        </section>

        <footer>
            <span>
                © Copyright 2026, PT Kelola Bisnis Indonesia.
                All Rights Reserved.
            </span>

            <div>
                <a href="#">Whatsapp</a>
                <a href="#">Email</a>
                <a href="#">Kebijakan Privasi</a>
                <a href="#">Legal Cookie</a>
            </div>
        </footer>

    </main>
</div>


<div id="roleModal" class="role-modal-overlay">

    <div class="role-modal">

       <div class="role-modal-header">
    <span>Add Role</span>
    <button type="button"
            class="modal-close"
            onclick="closeRoleModal()">
        &times;
    </button>
</div>

        <form action="{{ route('employee-management.roles.store') }}" method="POST">
    @csrf
    <input type="hidden" name="_form_type" value="role">

            <div class="role-modal-body">

                <div class="role-form-row">
    <div class="role-form-group">
        <label>Name</label>
        <input type="text" name="name"
               placeholder="Enter role name" required>
    </div>

    <div class="role-form-group">
        <label>Description</label>
        <textarea name="description"
                  placeholder="Enter description"></textarea>
    </div>
</div>

                <div class="role-form-group">
                    <label>Status</label>

                    <div class="role-status-row">
                        <label class="role-switch">
                            <input type="checkbox" name="status" value="Active" checked>
                            <span class="role-slider"></span>
                        </label>

                        <span class="status-text">Active</span>
                    </div>
                </div>

                <hr style="border:0; border-top:1px solid #eee; margin:22px 0;">

                

<h3 class="permission-title">Role Permission</h3>

<div class="select-all-row">
    <label>
        <input type="checkbox" id="selectAllPermissions">
        Select All
    </label>
</div>

<div class="permission-section">
    <div class="permission-section-title">
        User Access Management
    </div>

    @php
        $permissionGroups = [
            'Employee Management' => 'users',
            'Duty Roster' => 'duty_roster',
            'Area Management' => 'areas',
            'Cleaning Task Management' => 'cleaning_tasks',
            'Cleaning Activities' => 'cleaning_activities',
        ];

        $permissionActions = [
            'Read' => 'view',
            'Created' => 'add',
            'Update' => 'edit',
            'Delete' => 'delete',
        ];
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
                        <td>
                            <input
                                type="checkbox"
                                class="permission-checkbox"
                                name="permissions[{{ $prefix }}.{{ $action }}]"
                                value="1">
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
</div>

            <div class="role-modal-footer">
                <button type="button"
                        class="role-cancel"
                        onclick="closeRoleModal()">
                    Cancel
                </button>

                <button type="submit" class="role-save">
                    Save
                </button>
            </div>

        </form>
    </div>
</div>

@endif

{{-- MODAL EDIT ROLE --}}
<div id="editRoleModal" class="role-modal-overlay">

    <div class="role-modal">

        <div class="role-modal-header">
    <span>Add Role</span>
    <button type="button"
            class="role-modal-close"
            onclick="closeRoleModal()">
        &times;
    </button>
</div>

        <form id="editRoleForm" method="POST">
            @csrf
            @method('PUT')

            <input type="hidden" name="_form_type" value="role_edit">

            <div class="role-modal-body">

                <div class="role-form-row">
                    <div class="role-form-group">
                        <label>Name</label>
                        <input type="text"
                            id="editRoleName"
                            name="name"
                            placeholder="Enter role name"
                            required>
                    </div>

                    <div class="role-form-group">
                        <label>Description</label>
                        <textarea
                            id="editRoleDescription"
                            name="description"
                            placeholder="Enter description"></textarea>
                    </div>
                </div>

                <div class="role-form-group">
                    <label>Status</label>

                    <div class="role-status-row">
                        <label class="role-switch">
                            <input type="hidden" name="status" value="Inactive">

                            <input type="checkbox"
                                id="editRoleStatus"
                                name="status"
                                value="Active">

                            <span class="role-slider"></span>
                        </label>

                        <span class="status-text" id="editRoleStatusText">
                            Inactive
                        </span>
                    </div>
                </div>

                <hr style="border:0; border-top:1px solid #eee; margin:22px 0;">

                <h3 class="permission-title">Role Permission</h3>

                <div class="select-all-row">
                    <label>
                        <input type="checkbox" id="editSelectAllPermissions">
                        Select All
                    </label>
                </div>

                <div class="permission-section">
                    <div class="permission-section-title">
                        User Access Management
                    </div>

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

                                $editPermissionActions = [
                                    'view',
                                    'add',
                                    'edit',
                                    'delete',
                                ];
                            @endphp

                            @foreach ($editPermissionGroups as $group => $prefix)
                                <tr>
                                    <td>{{ $group }}</td>

                                    @foreach ($editPermissionActions as $action)
                                        <td>
                                            <input
                                                type="checkbox"
                                                class="edit-permission-checkbox"
                                                name="permissions[{{ $prefix }}.{{ $action }}]"
                                                value="1"
                                                data-permission="{{ $prefix }}.{{ $action }}">
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>

            <div class="role-modal-footer">
                <button type="button"
                    class="role-cancel"
                    onclick="closeEditRoleModal()">
                    Cancel
                </button>

                <button type="submit" class="role-save">
                    Save
                </button>
            </div>

        </form>
    </div>
</div>

{{-- MODAL ADD USER --}}
<div id="userModal" class="user-modal">
    <div class="user-modal-content">

        <div class="modal-heading">
            <h2>Add User</h2>
            <button type="button" class="modal-close" onclick="closeUserModal()">
                &times;
            </button>
        </div>

        <form id="addUserForm"
              action="{{ route('employee-management.users.store') }}"
              method="POST"
              class="user-form">
              <input type="hidden" name="_form_type" value="user">
            @csrf

            <div class="user-form-grid">

                <div class="form-group">
                    <label for="name">Username <span class="required">*</span></label>
                    <input type="text" id="name" name="name"
                           value="{{ old('name') }}" placeholder="Name" required>
                    @error('name')
                        <small class="form-error">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="role_id">Role <span class="required">*</span></label>
                    <select id="role_id" name="role_id" required>
                        <option value="">Pilih Role</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}"
                                {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('role_id')
                        <small class="form-error">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email <span class="required">*</span></label>
                    <input type="email" id="email" name="email"
                           value="{{ old('email') }}" placeholder="Email" required>
                    @error('email')
                        <small class="form-error">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Password <span class="required">*</span></label>
                    <input type="password" id="password" name="password"
                           placeholder="Password" required>
                    @error('password')
                        <small class="form-error">{{ $message }}</small>
                    @enderror
                </div>

            </div>
        </form>

        <div class="modal-actions">
            <button type="button" class="cancel-button" onclick="closeUserModal()">
                Cancel
            </button>

            <button type="submit" form="addUserForm" class="save-button">
                Save
            </button>
        </div>
    </div>
</div>
{{-- MODAL EDIT USER --}}
<div id="editUserModal" class="user-modal">
    <div class="user-modal-content">

        <div class="modal-heading">
            <h2>Edit User</h2>

            <button type="button" class="modal-close"
                onclick="closeEditUserModal()">
                &times;
            </button>
        </div>

        <form id="editUserForm"
              method="POST"
              class="user-form">

            @csrf
            @method('PUT')

            <input type="hidden" name="_form_type" value="edit">
            <input type="hidden" name="_edit_url" id="edit_update_url">

            <div class="user-form-grid">

                <div class="form-group">
                    <label for="edit_name">
                        Username <span class="required">*</span>
                    </label>

                    <input type="text"
                        id="edit_name"
                        name="name"
                        placeholder="Name"
                        required>
                </div>

                <div class="form-group">
                    <label for="edit_role_id">
                        Role <span class="required">*</span>
                    </label>

                    <select id="edit_role_id" name="role_id" required>
                        <option value="">Pilih Role</option>

                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}">
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="edit_email">
                        Email <span class="required">*</span>
                    </label>

                    <input type="email"
                        id="edit_email"
                        name="email"
                        placeholder="Email"
                        required>
                </div>

                <div class="form-group">
                    <label for="edit_password">Password</label>

                    <input type="password"
                        id="edit_password"
                        name="password"
                        placeholder="Kosongkan jika tidak diubah">
                </div>

            </div>
        </form>

        <div class="modal-actions">
            <button type="button"
                class="cancel-button"
                onclick="closeEditUserModal()">
                Cancel
            </button>

            <button type="submit"
                form="editUserForm"
                class="save-button">
                Save
            </button>
        </div>

    </div>
</div>
{{-- FORM DELETE MULTIPLE USERS --}}
<form id="deleteMultipleUsers"
      action="{{ route('employee-management.users.destroyMultiple') }}"
      method="POST"
      style="display:none;">
    @csrf
    @method('DELETE')
</form>

{{-- FORM DELETE MULTIPLE ROLES --}}
<form id="deleteMultipleRoles"
      action="{{ route('employee-management.roles.destroyMultiple') }}"
      method="POST"
      style="display:none;">
    @csrf
    @method('DELETE')
</form>
<!-- DELETE CONFIRMATION MODAL -->
<div id="deleteConfirmModal" class="delete-modal-overlay">
    <div class="delete-modal">
        <div class="delete-modal-header">
            <span>⚠️ Hapus Data?</span>
            <button type="button" class="delete-modal-close"
                onclick="closeDeleteModal()">×</button>
        </div>

        <div class="delete-modal-body">
            <p id="deleteConfirmMessage">
                Apakah Anda yakin ingin menghapus data ini?
                Data yang telah dihapus tidak dapat dikembalikan.
            </p>
        </div>

        <div class="delete-modal-footer">
            <button type="button" class="cancel-delete"
                onclick="closeDeleteModal()">Cancel</button>

            <button type="button" class="confirm-delete"
                onclick="confirmDelete()">Ya, Lanjutkan</button>
        </div>
    </div>
</div>
<script>


// =====================================
// MODAL ADD USER
// =====================================

function openUserModal() {
    document.getElementById('userModal').style.display = 'flex';
}

function closeUserModal() {
    document.getElementById('userModal').style.display = 'none';
}
// =====================================
// MODAL EDIT USER
// =====================================

function openEditUserModal(button) {

    const modal = document.getElementById('editUserModal');
    const form = document.getElementById('editUserForm');

    form.action = button.dataset.updateUrl;

    document.getElementById('edit_update_url').value =
        button.dataset.updateUrl;

    document.getElementById('edit_name').value =
        button.dataset.userName;

    document.getElementById('edit_email').value =
        button.dataset.userEmail;

    document.getElementById('edit_role_id').value =
        button.dataset.roleId;

    document.getElementById('edit_password').value = '';

    modal.style.display = 'flex';
}

function closeEditUserModal() {
    document.getElementById('editUserModal').style.display = 'none';
}

// =====================================
// MODAL KONFIRMASI DELETE
// =====================================

let pendingDeleteForm = null;

function openDeleteModal(form, message) {
    pendingDeleteForm = form;

    document.getElementById('deleteConfirmMessage').textContent = message;
    document.getElementById('deleteConfirmModal').style.display = 'flex';
}

function closeDeleteModal() {
    document.getElementById('deleteConfirmModal').style.display = 'none';
    pendingDeleteForm = null;
}

function confirmDelete() {
    if (pendingDeleteForm) {
        const form = pendingDeleteForm;

        closeDeleteModal();

        // Kirim form tanpa memicu konfirmasi ulang
        HTMLFormElement.prototype.submit.call(form);
    }
}


// =====================================
// TABLE FEATURES
// =====================================

document.addEventListener('DOMContentLoaded', function () {

    // MODAL EDIT USER
    const editUserModal = document.getElementById('editUserModal');

    if (editUserModal) {
        editUserModal.addEventListener('click', function (event) {
            if (event.target === this) {
                closeEditUserModal();
            }
        });
    }

    // MODAL ADD USER
    const userModal = document.getElementById('userModal');

    if (userModal) {
        userModal.addEventListener('click', function (event) {
            if (event.target === this) {
                closeUserModal();
            }
        });
    }

    // MODAL DELETE
    const deleteModal = document.getElementById('deleteConfirmModal');

    if (deleteModal) {
        deleteModal.addEventListener('click', function (event) {
            if (event.target === this) {
                closeDeleteModal();
            }
        });
    }

    // LANJUTKAN KODE TABLE FEATURES KAMU DI BAWAH SINI
    // Hilangkan confirm bawaan browser dan gunakan modal sendiri
    document.querySelectorAll('.delete-form').forEach(function (form) {

        form.removeAttribute('onsubmit');

        form.addEventListener('submit', function (event) {
            event.preventDefault();

            const isUser = form.action.includes('/users/');

            const message = isUser
                ? 'Apakah Anda yakin ingin menghapus akun pengguna ini? Data yang telah dihapus akan dikeluarkan dari sistem dan tidak dapat dikembalikan.'
                : 'Apakah Anda yakin ingin menghapus role ini? Data yang telah dihapus tidak dapat dikembalikan.';

            openDeleteModal(form, message);
        });
    });

    // Buka modal Add User jika validasi gagal
    @if ($errors->any())
    @if (old('_form_type') === 'role')
        openRoleModal();
    @elseif (old('_form_type') === 'user')
        openUserModal();
    @endif
@endif

    // NOTIFICATION AUTO HIDE
    const notification = document.getElementById('flashNotification');

    if (notification) {
        setTimeout(function () {
            notification.style.transition = 'opacity 0.5s ease';
            notification.style.opacity = '0';

            setTimeout(function () {
                notification.remove();
            }, 500);
        }, 4000);
    }

    // =====================================
    // TABLE FEATURE
    // =====================================

    document.querySelectorAll('[data-table-container]').forEach(function (container) {

        const table = container.querySelector('.feature-table');
        const tbody = table.querySelector('tbody');

        const selectAll = container.querySelector('.select-all');
        const deleteButton = container.querySelector('.delete-selected');

        const pageLength = container.querySelector('.page-length');
        const info = container.querySelector('.table-info');
        const pagination = container.querySelector('.pagination');

        const searchInputs = container.querySelectorAll('.search-row input');

        const originalRows = Array.from(tbody.querySelectorAll('tr'))
            .filter(row => row.querySelector('.row-checkbox'));

        let filteredRows = [...originalRows];
        let currentPage = 1;

        function updateDeleteButton() {
            const checked = tbody.querySelectorAll('.row-checkbox:checked');

            if (deleteButton) {
                deleteButton.hidden = checked.length === 0;
            }
        }

        function filterData() {
            filteredRows = originalRows.filter(function (row) {
                const cells = row.querySelectorAll('td');

                return Array.from(searchInputs).every(function (input, index) {
                    const keyword = input.value.toLowerCase().trim();
                    const cell = cells[index + 1];

                    if (!cell) return true;

                    return cell.textContent.toLowerCase().includes(keyword);
                });
            });

            currentPage = 1;
            renderTable();
        }

        function renderTable() {
            const perPage = parseInt(pageLength.value);
            const total = filteredRows.length;
            const totalPages = Math.ceil(total / perPage);

            if (currentPage > totalPages && totalPages > 0) {
                currentPage = totalPages;
            }

            const start = (currentPage - 1) * perPage;
            const end = Math.min(start + perPage, total);
            const visibleRows = filteredRows.slice(start, end);

            originalRows.forEach(function (row) {
                row.style.display = 'none';
            });

            visibleRows.forEach(function (row) {
                row.style.display = '';
            });

            info.textContent = total === 0
                ? 'Showing 0 to 0 of 0 entries'
                : `Showing ${start + 1} to ${end} of ${total} entries`;

            if (selectAll) {
                const visibleCheckboxes = visibleRows
                    .map(row => row.querySelector('.row-checkbox'))
                    .filter(Boolean);

                selectAll.checked =
                    visibleCheckboxes.length > 0 &&
                    visibleCheckboxes.every(cb => cb.checked);

                selectAll.indeterminate =
                    visibleCheckboxes.some(cb => cb.checked) &&
                    !selectAll.checked;
            }

            pagination.innerHTML = '';

            function createPageButton(label, page, disabled, active) {
                const button = document.createElement('button');

                button.type = 'button';
                button.textContent = label;
                button.disabled = disabled;

                if (active) {
                    button.classList.add('active');
                }

                button.addEventListener('click', function () {
                    currentPage = page;
                    renderTable();
                });

                pagination.appendChild(button);
            }

            createPageButton('«', 1, currentPage === 1 || totalPages === 0, false);

            createPageButton(
                '‹',
                Math.max(1, currentPage - 1),
                currentPage === 1 || totalPages === 0,
                false
            );

            for (let i = 1; i <= totalPages; i++) {
                createPageButton(i, i, false, currentPage === i);
            }

            createPageButton(
                '›',
                Math.min(totalPages, currentPage + 1),
                currentPage === totalPages || totalPages === 0,
                false
            );

            createPageButton(
                '»',
                totalPages,
                currentPage === totalPages || totalPages === 0,
                false
            );

            updateDeleteButton();
        }

        // SEARCH
        searchInputs.forEach(function (input) {
            input.addEventListener('input', filterData);
        });

        // PAGE LENGTH
        pageLength.addEventListener('change', function () {
            currentPage = 1;
            renderTable();
        });

        // SELECT ALL
        if (selectAll) {
            selectAll.addEventListener('change', function () {
                const perPage = parseInt(pageLength.value);
                const start = (currentPage - 1) * perPage;

                const visibleRows = filteredRows.slice(start, start + perPage);

                visibleRows.forEach(function (row) {
                    const checkbox = row.querySelector('.row-checkbox');

                    if (checkbox) {
                        checkbox.checked = selectAll.checked;
                    }
                });

                updateDeleteButton();
                renderTable();
            });
        }

        // CHECKBOX EACH ROW
        tbody.addEventListener('change', function (event) {
            if (event.target.classList.contains('row-checkbox')) {
                updateDeleteButton();
                renderTable();
            }
        });

        // DELETE SELECTED
        if (deleteButton) {
            deleteButton.addEventListener('click', function () {

                const checked = tbody.querySelectorAll('.row-checkbox:checked');

                if (checked.length === 0) return;

                const title = container.querySelector('h2').textContent.trim();
                const isUserTable = title === 'User List';

                const form = document.getElementById(
                    isUserTable
                        ? 'deleteMultipleUsers'
                        : 'deleteMultipleRoles'
                );

                const message = isUserTable
                    ? `Apakah Anda yakin ingin menghapus ${checked.length} akun pengguna ini? Data yang telah dihapus akan dikeluarkan dari sistem dan tidak dapat dikembalikan.`
                    : `Apakah Anda yakin ingin menghapus ${checked.length} role yang dipilih? Data yang telah dihapus tidak dapat dikembalikan.`;

                // Bersihkan ID pilihan sebelumnya
                form.querySelectorAll('input[name="ids[]"]').forEach(function (input) {
                    input.remove();
                });

                // Masukkan ID yang dipilih
                checked.forEach(function (checkbox) {
                    const input = document.createElement('input');

                    input.type = 'hidden';
                    input.name = 'ids[]';
                    input.value = checkbox.value;

                    form.appendChild(input);
                });

                // Tampilkan modal konfirmasi custom
                openDeleteModal(form, message);
            });
        }

        // INITIAL TABLE
        renderTable();

    });

});

</script>
<script>
    // MODAL ADD ROLE
    function openRoleModal() {
        document.getElementById('roleModal').style.display = 'flex';
    }
// MODAL EDIT ROLE
function openEditRoleModal(button) {
    const modal = document.getElementById('editRoleModal');
    const form = document.getElementById('editRoleForm');

    form.action = button.dataset.updateUrl;

    document.getElementById('editRoleName').value =
        button.dataset.roleName;

    document.getElementById('editRoleDescription').value =
        button.dataset.roleDescription || '';

    const status = button.dataset.roleStatus === 'Active';

    document.getElementById('editRoleStatus').checked = status;

    document.getElementById('editRoleStatusText').textContent =
        status ? 'Active' : 'Inactive';

    const permissions = JSON.parse(
        button.dataset.rolePermissions || '{}'
    );

    const checkboxes = document.querySelectorAll(
        '.edit-permission-checkbox'
    );

    checkboxes.forEach(checkbox => {
        checkbox.checked = !!permissions[checkbox.dataset.permission];
    });

    updateEditSelectAll();

    modal.style.display = 'flex';
}

function closeEditRoleModal() {
    document.getElementById('editRoleModal').style.display = 'none';
}

// STATUS EDIT ROLE
const editRoleStatus = document.getElementById('editRoleStatus');

if (editRoleStatus) {
    editRoleStatus.addEventListener('change', function () {
        document.getElementById('editRoleStatusText').textContent =
            this.checked ? 'Active' : 'Inactive';
    });
}

// SELECT ALL EDIT ROLE
function updateEditSelectAll() {
    const checkboxes = document.querySelectorAll(
        '.edit-permission-checkbox'
    );

    const selectAll = document.getElementById('editSelectAllPermissions');

    selectAll.checked =
        checkboxes.length > 0 &&
        [...checkboxes].every(item => item.checked);
}

const editSelectAll = document.getElementById('editSelectAllPermissions');

if (editSelectAll) {
    editSelectAll.addEventListener('change', function () {
        document.querySelectorAll('.edit-permission-checkbox')
            .forEach(checkbox => {
                checkbox.checked = this.checked;
            });
    });
}

document.querySelectorAll('.edit-permission-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', updateEditSelectAll);
});

// KLIK AREA LUAR MODAL EDIT
const editRoleModal = document.getElementById('editRoleModal');

if (editRoleModal) {
    editRoleModal.addEventListener('click', function (event) {
        if (event.target === this) {
            closeEditRoleModal();
        }
    });
}
    function closeRoleModal() {
        document.getElementById('roleModal').style.display = 'none';
    }

    // STATUS SWITCH ACTIVE / INACTIVE
    document.querySelectorAll('.role-switch input').forEach(input => {
        input.addEventListener('change', function () {
            const text = this.closest('.role-status-row')
                .querySelector('.status-text');

            text.textContent = this.checked ? 'Active' : 'Inactive';
        });
    });

    // TUTUP MODAL KETIKA KLIK AREA LUAR
    document.getElementById('roleModal').addEventListener('click', function (event) {
        if (event.target === this) {
            closeRoleModal();
        }
    });
    // SELECT ALL ROLE PERMISSIONS
const selectAllPermissions = document.getElementById('selectAllPermissions');

if (selectAllPermissions) {
    const permissionCheckboxes =
        document.querySelectorAll('.permission-checkbox');

    selectAllPermissions.addEventListener('change', function () {
        permissionCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });

    permissionCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            selectAllPermissions.checked =
                [...permissionCheckboxes].every(item => item.checked);
        });
    });
}
</script>
</body>
</html>