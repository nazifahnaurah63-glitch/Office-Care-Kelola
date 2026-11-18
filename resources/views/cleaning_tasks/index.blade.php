
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Tugas - Office Care Kelola</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: #f7f8fc;
            color: #292d3e;
            padding: 35px;
        }

        .container {
            max-width: 1200px;
            margin: auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 27px;
            margin-bottom: 8px;
        }

        .header p {
            color: #85899a;
            font-size: 14px;
        }

        .back-btn {
            display: inline-block;
            text-decoration: none;
            color: #ed174c;
            background: white;
            border: 1px solid #f1d6df;
            padding: 11px 16px;
            border-radius: 9px;
            font-size: 14px;
        }

        .table-card {
            background: white;
            border-radius: 14px;
            padding: 25px;
            box-shadow: 0 4px 20px rgba(30, 35, 60, 0.05);
            overflow-x: auto;
        }

        .table-header {
            margin-bottom: 22px;
        }

        .table-header h2 {
            font-size: 19px;
            margin-bottom: 7px;
        }

        .table-header p {
            font-size: 13px;
            color: #85899a;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 650px;
        }

        th, td {
            text-align: left;
            padding: 16px 12px;
            border-bottom: 1px solid #f0f1f5;
            font-size: 13px;
        }

        th {
            background: #fafafd;
            color: #85899a;
            font-weight: 600;
        }

        td {
            color: #45495b;
        }

        .status {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .completed {
            background: #e5f8ed;
            color: #16834a;
        }

        .pending {
            background: #fff3dc;
            color: #b77910;
        }

        .empty {
            text-align: center;
            padding: 35px;
            color: #85899a;
        }

        @media (max-width: 600px) {
            body {
                padding: 18px;
            }

            .header h1 {
                font-size: 23px;
            }

            .table-card {
                padding: 16px;
            }
        }

.add-btn {
    display: inline-block;
    text-decoration: none;
    background: #ed174c;
    color: white;
    padding: 11px 16px;
    border-radius: 9px;
    font-size: 14px;
    font-weight: 600;
}

.add-btn:hover {
    background: #d81243;
}

    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div>
                <h1>Cleaning Task Management</h1>
                <p>Kelola dan pantau seluruh tugas kebersihan kantor.</p>
            </div>

            
<div style="display: flex; gap: 10px; flex-wrap: wrap;">
    <a href="/dashboard" class="back-btn">
        ← Kembali ke Dashboard
    </a>

    <a href="{{ route('cleaning-tasks.create') }}" class="add-btn">
        + Tambah Tugas
    </a>
</div>
        </div>

        <div class="table-card">
            <div class="table-header">
                <h2>Daftar Tugas Kebersihan</h2>
                <p>Berikut adalah daftar tugas yang tersimpan di sistem.</p>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama Tugas</th>
                        <th>Area</th>
                        <th>Petugas</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($tasks as $task)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $task->task_name }}</td>
                            <td>{{ $task->area }}</td>
                            <td>{{ $task->assigned_to ?? '-' }}</td>
                            <td>
                                {{ $task->task_date
                                    ? \Carbon\Carbon::parse($task->task_date)->format('d/m/Y')
                                    : '-' }}
                            </td>
                            <td>
                                @if ($task->status === 'completed')
                                    <span class="status completed">Selesai</span>
                                @else
                                    <span class="status pending">Pending</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty">
                                Belum ada tugas kebersihan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
