<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User | Office Care Kelola</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/employee-management.css') }}">
</head>
<body>

<div class="app">
    <main class="main-content">

        <section class="employee-card" style="padding:25px; max-width:650px; margin:40px auto;">

            <h2 style="margin-bottom:25px;">Edit User</h2>

            <form action="{{ route('employee-management.users.update', $user->id) }}"
                  method="POST">

                @csrf
                @method('PUT')

                <div style="display:grid; gap:15px;">

                    <div class="form-group">
                        <label>Username *</label>
                        <input type="text" name="name"
                               value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <small class="form-error">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Email *</label>
                        <input type="email" name="email"
                               value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <small class="form-error">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password"
                               placeholder="Kosongkan jika tidak diubah">
                        @error('password')
                            <small class="form-error">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Role *</label>
                        <select name="role_id" required>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}"
                                    {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

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