
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Role | Office Care Kelola</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/employee-management.css') }}">

    <style>
        .permission-section {
            margin-top: 25px;
        }

        .permission-section h3 {
            margin-bottom: 15px;
        }

        .permission-group {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 12px;
        }

        .permission-group h4 {
            margin: 0 0 12px;
            font-size: 15px;
        }

        .permission-options {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
        }

        .permission-options label {
            display: flex;
            align-items: center;
            gap: 7px;
            font-size: 13px;
            cursor: pointer;
        }

        .permission-options input {
            width: 16px;
            height: 16px;
            cursor: pointer;
        }

        .permission-note {
            color: #777;
            font-size: 12px;
            margin-top: 8px;
        }

        @media (max-width: 500px) {
            .permission-options {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>

<body>

@php
    $permissionGroups = [
        'Dashboard' => [
            'dashboard.view' => 'View',
        ],
        'User List' => [
            'users.view' => 'View',
            'users.add' => 'Add',
            'users.edit' => 'Edit',
            'users.delete' => 'Delete',
        ],
        'Role List' => [
            'roles.view' => 'View',
            'roles.add' => 'Add',
            'roles.edit' => 'Edit',
            'roles.delete' => 'Delete',
        ],
        'Duty Roster' => [
            'duty_roster.view' => 'View',
            'duty_roster.add' => 'Add',
            'duty_roster.edit' => 'Edit',
            'duty_roster.delete' => 'Delete',
        ],
        'Area Management' => [
            'areas.view' => 'View',
            'areas.add' => 'Add',
            'areas.edit' => 'Edit',
            'areas.delete' => 'Delete',
        ],
        'Cleaning Task Management' => [
            'cleaning_tasks.view' => 'View',
            'cleaning_tasks.add' => 'Add',
            'cleaning_tasks.edit' => 'Edit',
            'cleaning_tasks.delete' => 'Delete',
        ],
        'Cleaning Activities' => [
            'cleaning_activities.view' => 'View',
            'cleaning_activities.add' => 'Add',
            'cleaning_activities.edit' => 'Edit',
            'cleaning_activities.delete' => 'Delete',
        ],
        'Announcement' => [
            'announcements.view' => 'View',
            'announcements.add' => 'Add',
            'announcements.edit' => 'Edit',
            'announcements.delete' => 'Delete',
        ],
    ];
@endphp

<div class="app">
    <main class="main-content">

        <section class="employee-card"
                 style="padding:25px; max-width:750px; margin:40px auto;">

            <h2 style="margin-bottom:25px;">Edit Role</h2>

            <form action="{{ route('employee-management.roles.update', $role->id) }}"
                  method="POST">

                @csrf
                @method('PUT')

                <div style="display:grid; gap:15px;">

                    <div class="form-group">
                        <label>Role Name *</label>
                        <input type="text"
                               name="name"
                               value="{{ old('name', $role->name) }}"
                               required>

                        @error('name')
                            <small class="form-error">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Description</label>
                        <input type="text"
                               name="description"
                               value="{{ old('description', $role->description) }}">
                    </div>

                    <div class="form-group">
                        <label>Status *</label>
                        <select name="status" required>
                            <option value="Active"
                                {{ old('status', $role->status) == 'Active' ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="Inactive"
                                {{ old('status', $role->status) == 'Inactive' ? 'selected' : '' }}>
                                Inactive
                            </option>
                        </select>
                    </div>

                </div>

                <div class="permission-section">

                    <h3>Menu Permissions</h3>
                    <p style="font-size:13px; color:#777; margin-bottom:15px;">
                        Atur hak akses yang diberikan kepada role ini.
                    </p>

                    @foreach ($permissionGroups as $groupName => $permissions)
                        <div class="permission-group">

                            <h4>{{ $groupName }}</h4>

                            <div class="permission-options">

                                @foreach ($permissions as $permissionKey => $permissionLabel)

                                    <label>
                                        <input
                                            type="checkbox"
                                            name="permissions[{{ $permissionKey }}]"
                                            value="1"
                                            {{ old('permissions.' . $permissionKey, data_get($role->permissions, $permissionKey, false)) ? 'checked' : '' }}
                                        >

                                        {{ $permissionLabel }}
                                    </label>

                                @endforeach

                            </div>

                        </div>
                    @endforeach

                    @error('permissions')
                        <small class="form-error">{{ $message }}</small>
                    @enderror

                    <p class="permission-note">
                        Catatan: Admin tetap memiliki akses penuh ke seluruh menu.
                    </p>

                </div>

                <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:25px;">

                    <a href="{{ route('employee-management.index') }}"
                       class="cancel-button"
                       style="text-decoration:none;">
                        Cancel
                    </a>

                    <button type="submit" class="save-button">
                        Update
                    </button>

                </div>

            </form>
        </section>

    </main>
</div>

</body>
</html>
