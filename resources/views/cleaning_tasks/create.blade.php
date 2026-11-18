
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Tugas - Office Care Kelola</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            background: #f7f8fc;
            color: #292d3e;
            padding: 35px 20px;
        }

        .container {
            max-width: 700px;
            margin: 0 auto;
        }

        .header {
            margin-bottom: 25px;
        }

        .header h1 {
            font-size: 27px;
            margin-bottom: 8px;
        }

        .header p {
            color: #85899a;
            font-size: 14px;
        }

        .form-card {
            background: white;
            padding: 28px;
            border-radius: 14px;
            box-shadow: 0 4px 20px rgba(30, 35, 60, 0.05);
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        input, select {
            width: 100%;
            padding: 12px;
            border: 1px solid #dedfe7;
            border-radius: 8px;
            font-size: 14px;
            outline: none;
            background: white;
        }

        input:focus, select:focus {
            border-color: #ed174c;
        }

        .error {
            color: #d81243;
            font-size: 13px;
            margin-top: 6px;
        }

        .actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 28px;
        }

        .btn {
            display: inline-block;
            padding: 12px 18px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            text-decoration: none;
            cursor: pointer;
        }

        .btn-cancel {
            background: #f0f1f5;
            color: #45495b;
        }

        .btn-save {
            background: #ed174c;
            color: white;
        }

        @media (max-width: 500px) {
            body {
                padding: 20px 14px;
            }

            .form-card {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Tambah Tugas Kebersihan</h1>
            <p>Lengkapi informasi tugas yang akan diberikan.</p>
        </div>

        <div class="form-card">
            @if ($errors->any())
                <div style="background:#fff0f3; padding:12px; border-radius:8px; margin-bottom:20px;">
                    <p style="color:#d81243; font-size:14px;">
                        Periksa kembali data yang kamu masukkan.
                    </p>
                </div>
            @endif

            <form action="{{ route('cleaning-tasks.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="task_name">Nama Tugas</label>
                    <input
                        type="text"
                        id="task_name"
                        name="task_name"
                        value="{{ old('task_name') }}"
                        placeholder="Contoh: Membersihkan meja kerja"
                        required
                    >
                    @error('task_name')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="area">Area</label>
                    <input
                        type="text"
                        id="area"
                        name="area"
                        value="{{ old('area') }}"
                        placeholder="Contoh: Ruang Kerja"
                        required
                    >
                    @error('area')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="assigned_to">Nama Petugas</label>
                    <input
                        type="text"
                        id="assigned_to"
                        name="assigned_to"
                        value="{{ old('assigned_to') }}"
                        placeholder="Masukkan nama petugas"
                    >
                    @error('assigned_to')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="task_date">Tanggal Tugas</label>
                    <input
                        type="date"
                        id="task_date"
                        name="task_date"
                        value="{{ old('task_date', date('Y-m-d')) }}"
                        required
                    >
                    @error('task_date')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="status">Status Tugas</label>
                    <select id="status" name="status" required>
                        <option value="pending" {{ old('status', 'pending') === 'pending' ? 'selected' : '' }}>
                            Pending
                        </option>
                        <option value="completed" {{ old('status') === 'completed' ? 'selected' : '' }}>
                            Selesai
                        </option>
                    </select>
                    @error('status')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="actions">
                    <a href="{{ route('cleaning-tasks.index') }}" class="btn btn-cancel">
                        Batal
                    </a>

                    <button type="submit" class="btn btn-save">
                        Simpan Tugas
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
