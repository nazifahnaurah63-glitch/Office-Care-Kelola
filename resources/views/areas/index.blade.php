<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Area Management | Office Care Kelola</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/partials/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/partials/topbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/design-system.css') }}">
    <link rel="stylesheet" href="{{ asset('css/areas/index.css') }}">
</head>
<body>

<div class="app">

    @include('partials.sidebar')

    <main class="main-content">

        @include('partials.topbar')

        <section class="areas-content">
  
            <div class="area-page-header">
                <div class="area-heading-icon">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        width="42" height="42" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor"
                        stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M20 10c0 5-8 12-8 12S4 15 4 10a8 8 0 1 1 16 0Z"/>
                        <circle cx="12" cy="10" r="3"/>
                    </svg>
                </div>

                <div>
                    <h1 class="kelola-page-title">Area Management</h1>
                    <p class="kelola-page-description">Kelola area - area di dalam kantor</p>
                </div>
            </div>

            <div class="area-toolbar kelola-card">
                <button type="button"
                        class="kelola-btn kelola-btn-primary"
                        data-open-modal="add-area-modal">
                    + Tambah Area
                </button>

                <input
                    type="search"
                    id="areaSearch"
                    class="kelola-input area-search"
                    placeholder="⌕  Search Area..."
                >
            </div>

            <div class="area-cards">
            @php
                $areaImages = [
                    'Lobby' => 'lobby.jpg',
                    'Ruang Meeting' => 'ruang-meeting.jpg',
                    'Pantry' => 'pantry.jpg',
                    'Toilet' => 'toilet.jpg',
                    'Ruang Kerja' => 'ruang-kerja.jpg',
                    'Gudang' => 'gudang.jpg',
                ];

                $areaDescriptions = [
                    'Lobby' => 'Area utama kantor dan ruang tunggu',
                    'Ruang Meeting' => 'Ruang rapat kantor',
                    'Pantry' => 'Area dapur dan ruang makan',
                    'Toilet' => 'Area toilet kantor',
                    'Ruang Kerja' => 'Area kerja karyawan',
                    'Gudang' => 'Ruang penyimpanan barang',
                ];
            @endphp

            @forelse ($areas as $area)
                <article class="area-card kelola-card"
                        data-area-name="{{ strtolower($area->name) }}">

                    <img
                        class="area-card-image"
                        src="{{ $area->photo
                            ? asset('storage/' . $area->photo)
                            : asset('images/areas/' . ($areaImages[$area->name] ?? 'lobby.jpg')) }}"
                        alt="{{ $area->name }}"
                    >

                    <div class="area-card-info">
                        <h3>{{ $area->name }}</h3>

                        <p>
                            {{ $area->description
                                ?: ($areaDescriptions[$area->name] ?? 'Area kantor') }}
                        </p>

                        <div class="area-card-count">
                            {{ $area->total_tasks }} Tasks
                        </div>
                    </div>

                    <div class="area-card-actions">
                        <button
                            type="button"
                            class="area-action"
                            data-open-modal="detail-area-{{ $area->id }}"
                        >
                            <span>◎</span> Detail
                        </button>

                        <button type="button" class="area-action"
                                disabled title="Fitur edit belum dibuat">
                            ✎ Edit
                        </button>

                        <button type="button" class="area-action"
                                disabled title="Fitur hapus belum dibuat">
                            ♧ Delete
                        </button>
                    </div>
                </article>

                @empty
                    <p class="kelola-page-description">Belum ada data area. Silakan tambahkan area baru.</p>
                @endforelse
            </div>

            {{-- MODAL DETAIL AREA --}}
            @foreach ($areas as $area)
                <div class="area-modal-overlay"
                    id="detail-area-{{ $area->id }}"
                    aria-hidden="true">

                    <div class="area-modal detail-area-modal"
                        role="dialog"
                        aria-modal="true"
                        aria-labelledby="detail-title-{{ $area->id }}">

                        <div class="area-modal-header">
                            <h2 id="detail-title-{{ $area->id }}">
                                Detail {{ $area->name }}
                            </h2>

                            <button type="button"
                                    class="area-modal-close"
                                    data-close-modal
                                    aria-label="Tutup">
                                &times;
                            </button>
                        </div>

                        <div class="area-modal-body">
                            <div class="detail-area-summary">
                                <img
                                    src="{{ $area->photo
                                        ? asset('storage/' . $area->photo)
                                        : asset('images/areas/' . ($areaImages[$area->name] ?? 'lobby.jpg')) }}"
                                    alt="{{ $area->name }}"
                                >

                                <div>
                                    <h3>{{ $area->name }}</h3>

                                    <p>
                                        {{ $area->description
                                            ?: ($areaDescriptions[$area->name] ?? 'Area kantor') }}
                                    </p>

                                    <strong>
                                        Total Tasks: {{ $area->cleaningTasks->count() }}
                                    </strong>
                                </div>
                            </div>

                            <h3 class="detail-task-heading">Daftar Cleaning Tasks</h3>

                            <div class="detail-task-table-wrap">
                                <table class="detail-task-table">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Task Name</th>
                                            <th>Task Date</th>
                                            <th>Assigned To</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @forelse ($area->cleaningTasks as $task)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $task->task_name }}</td>
                                                <td>
                                                    {{ $task->task_date
                                                        ? \Carbon\Carbon::parse($task->task_date)->format('d M Y')
                                                        : '-' }}
                                                </td>
                                                <td>{{ $task->assigned_to ?: '-' }}</td>
                                                <td>
                                                    <span class="kelola-badge {{ $task->status === 'completed' ? 'kelola-badge-success' : 'kelola-badge-warning' }}">
                                                        {{ $task->status === 'completed' ? 'Completed' : 'Pending' }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="detail-empty">
                                                    Belum ada tugas kebersihan untuk area
                                                    {{ $area->name }}.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            {{-- MODAL TAMBAH AREA --}}
            <div class="area-modal-overlay"
                id="add-area-modal"
                data-reopen="{{ $errors->any() ? 'true' : 'false' }}"
                aria-hidden="true">

                <div class="area-modal add-area-modal"
                    role="dialog"
                    aria-modal="true"
                    aria-labelledby="add-area-title">

                    <div class="area-modal-header">
                        <h2 id="add-area-title">Add Area</h2>

                        <button type="button"
                                class="area-modal-close"
                                data-close-modal
                                aria-label="Tutup">
                            &times;
                        </button>
                    </div>

                    <form action="{{ route('areas.store') }}"
                        method="POST"
                        enctype="multipart/form-data">

                        @csrf

                        <div class="area-modal-body add-area-body">
                            <div class="add-area-fields">
                                <div class="area-form-group">
                                    <label class="kelola-label" for="add-area-name">
                                        Name Area <span class="required-star">*</span>
                                    </label>

                                    <input
                                        type="text"
                                        id="add-area-name"
                                        name="name"
                                        class="kelola-input"
                                        placeholder="Name"
                                        value="{{ old('name') }}"
                                        maxlength="255"
                                        required
                                    >

                                    @error('name')
                                        <small class="area-form-error">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="area-form-group">
                                    <label class="kelola-label" for="add-area-description">Description</label>

                                    <textarea
                                        id="add-area-description"
                                        name="description"
                                        class="kelola-input"
                                        rows="3"
                                        maxlength="1000"
                                        placeholder="Description"
                                    >{{ old('description') }}</textarea>

                                    @error('description')
                                        <small class="area-form-error">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="add-area-upload-column">
                                <label class="kelola-label add-area-upload-label" for="add-area-photo">
                                    Upload Foto <span class="required-star">*</span>
                                </label>

                                <label class="area-upload-box" for="add-area-photo">
                                    <svg viewBox="0 0 48 48"
                                        width="45"
                                        height="45"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.5"
                                        aria-hidden="true">
                                        <path d="M14 35H11a8 8 0 0 1-1-16 14 14 0 0 1 27-2 9 9 0 0 1 2 18h-5"/>
                                        <path d="M24 42V23m-7 7 7-7 7 7"/>
                                    </svg>

                                    <span>Drag &amp; drop foto di sini</span>
                                    <strong>atau pilih file</strong>
                                    <small>Format: JPG, PNG, WEBP (maks. 2 MB)</small>
                                    <span id="add-area-file-name">Belum ada foto dipilih</span>
                                </label>

                                <input
                                    type="file"
                                    id="add-area-photo"
                                    name="photo"
                                    accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                                    hidden
                                >

                                @error('photo')
                                    <small class="area-form-error">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="area-modal-footer">
                            <button type="button"
                                    class="kelola-btn kelola-btn-secondary"
                                    data-close-modal>
                                Cancel
                            </button>

                            <button type="submit" class="kelola-btn kelola-btn-primary">
                                Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            @if (session('success'))
            <div class="area-success-toast kelola-alert kelola-alert-success" id="areaSuccessToast" role="status">
                <div class="area-toast-check">✓</div>

                <div class="area-toast-message">
                    <strong>Berhasil!</strong>
                    <p>{{ session('success') }}</p>
                </div>

                <button type="button" class="area-toast-close"
                        id="closeAreaToast" aria-label="Tutup">
                    &times;
                </button>
            </div>
            @endif
        </section>

        <footer>
            <span>© Copyright 2026, PT Kelola Bisnis Indonesia. All Rights Reserved.</span>
            <div>
                <a href="#">Whatsapp</a>
                <a href="#">Email</a>
                <a href="#">Kebijakan Privasi</a>
                <a href="#">Legal Cookie</a>
            </div>
        </footer>

    </main>
</div>

<script src="{{ asset('js/areas.js') }}" defer></script>

</body>
</html>