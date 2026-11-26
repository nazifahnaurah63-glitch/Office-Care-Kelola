<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Area Management | Office Care Kelola</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/design-system.css') }}">
    <link rel="stylesheet" href="{{ asset('css/area-management.css') }}">
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

        <section class="areas-content">
  
            {{-- PAGE HEADING --}}
            <div class="kelola-page-heading">
                <div class="kelola-heading-icon">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        width="32" height="32" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor"
                        stroke-width="1.8" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M20 10c0 5-8 12-8 12S4 15 4 10a8 8 0 1 1 16 0Z"/>
                        <circle cx="12" cy="10" r="3"/>
                    </svg>
                </div>

                <div class="kelola-heading-text">
                    <h1 class="kelola-page-title">Area Management</h1>
                    <p class="kelola-page-description">Kelola area - area di dalam kantor</p>
                </div>
            </div>

            {{-- TOOLBAR --}}
            <div class="kelola-card area-toolbar">
                @if ($can('areas.add'))
                <button type="button"
                        class="kelola-btn kelola-btn-primary"
                        data-open-modal="add-area-modal">
                    <span>+</span> Tambah Area
                </button>
                @else
                <div></div>
                @endif

                <input
                    type="search"
                    id="areaSearch"
                    class="kelola-input area-search"
                    placeholder="⌕ Cari Area..."
                >
            </div>

            {{-- KARTU AREA GRID --}}
            <div class="area-cards">
            @php
                $areaImages = [
                    'Lobby' => 'lobby.jpg',
                    'Ruang Meeting' => 'ruang-meeting.jpg',
                    'Pantry' => 'pantry.jpg',
                    'Toilet' => 'toilet.jpg',
                    'Ruang Kerja' => 'ruang-kerja.jpg',
                    'Gudang' => 'gudang.png',
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

            @forelse ($areas ?? [] as $area)
                <article class="kelola-card area-card"
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
                            {{ $area->total_tasks ?? 0 }} Tugas Kebersihan
                        </div>
                    </div>

                    <div class="area-card-actions">
                        <button
                            type="button"
                            class="area-action"
                            data-open-modal="detail-area-{{ $area->id }}"
                        >
                            <type="submit" class="area-icon i"><i class="fas fa-eye"></i> Detail
                        </button>

                        @if ($can('areas.edit'))
                        <button type="button"
                                class="area-action"
                                data-edit-area="{{ $area->id }}">
                                <type="submit" class="area-icon i"><i class="fas fa-pen"></i> Edit
                        </button>
                        @endif

                        @if ($can('areas.delete'))
                        <button type="button"
                                class="area-action"
                                data-delete-area="{{ $area->id }}"
                                data-area-name="{{ $area->name }}"
                                style="background: var(--kelola-danger-bg); color: var(--kelola-danger);">
                                <type="submit" class="area-icon i"><i class="fas fa-trash"></i> Delete
                        </button>
                        @endif
                    </div>
                </article>

            @empty
                <p class="kelola-page-description" style="grid-column: 1 / -1; text-align: center; padding: 30px;">Belum ada data area. Silakan tambahkan area baru.</p>
            @endforelse
            </div>

            {{-- MODAL DETAIL AREA --}}
            @foreach ($areas ?? [] as $area)
                <div class="area-modal-overlay"
                    id="detail-area-{{ $area->id }}"
                    aria-hidden="true">

                    <div class="kelola-modal area-modal detail-area-modal"
                        role="dialog"
                        aria-modal="true"
                        aria-labelledby="detail-title-{{ $area->id }}">

                        <div class="kelola-modal-header area-modal-header">
                            <h2 id="detail-title-{{ $area->id }}">
                                Detail {{ $area->name }}
                            </h2>

                            <button type="button"
                                    class="kelola-modal-close area-modal-close"
                                    data-close-modal
                                    aria-label="Tutup">
                                &times;
                            </button>
                        </div>

                        <div class="kelola-modal-body area-modal-body">
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
                                        Total Tasks: {{ optional($area->cleaningTasks)->count() ?? 0 }}
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
                                        @forelse ($area->cleaningTasks ?? [] as $task)
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
                        <div class="kelola-modal-footer area-modal-footer">
                            <button type="button" class="kelola-btn kelola-btn-cancel" data-close-modal>Tutup</button>
                        </div>
                    </div>
                </div>
            @endforeach

            {{-- MODAL EDIT AREA --}}
            @if ($can('areas.edit'))
            @foreach ($areas ?? [] as $area)
                <div class="area-modal-overlay"
                     id="edit-area-{{ $area->id }}"
                     aria-hidden="true">

                    <div class="kelola-modal area-modal add-area-modal"
                         role="dialog"
                         aria-modal="true"
                         aria-labelledby="edit-title-{{ $area->id }}">

                        <div class="kelola-modal-header area-modal-header">
                            <h2 id="edit-title-{{ $area->id }}">Edit Area: {{ $area->name }}</h2>
                            <button type="button" class="kelola-modal-close area-modal-close" data-close-modal aria-label="Tutup">&times;</button>
                        </div>

                        <form action="{{ route('areas.update', $area->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="kelola-modal-body area-modal-body add-area-body">
                                <div class="add-area-fields">
                                    <div class="kelola-form-group area-form-group">
                                        <label class="kelola-label" for="edit-name-{{ $area->id }}">Name Area <span class="required-star">*</span></label>
                                        <input type="text" id="edit-name-{{ $area->id }}" name="name" class="kelola-input" placeholder="Name" value="{{ $area->name }}" maxlength="255" required>
                                    </div>

                                    <div class="kelola-form-group area-form-group">
                                        <label class="kelola-label" for="edit-description-{{ $area->id }}">Deskripsi</label>
                                        <textarea id="edit-description-{{ $area->id }}" name="description" class="kelola-textarea" rows="3" maxlength="1000" placeholder="Deskripsi">{{ $area->description }}</textarea>
                                    </div>
                                </div>

                                <div class="add-area-upload-column">
                                    <label class="kelola-label add-area-upload-label" for="edit-area-photo-{{ $area->id }}">Upload Foto (Opsional)</label>

                                    <label class="area-upload-box" for="edit-area-photo-{{ $area->id }}">
                                        @if ($area->photo)
                                            <img src="{{ asset('storage/' . $area->photo) }}" alt="{{ $area->name }}" class="edit-area-photo-preview">
                                        @endif
                                        <span>Drag &amp; drop foto di sini</span>
                                        <strong>atau pilih file</strong>
                                        <small>Format: JPG, PNG, WEBP (maks. 2 MB)</small>
                                        <span id="edit-area-file-name-{{ $area->id }}">Foto saat ini: {{ $area->photo ? basename($area->photo) : 'Belum ada foto dipilih' }}</span>
                                    </label>

                                    <input type="file" id="edit-area-photo-{{ $area->id }}" name="photo" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" data-filename="edit-area-file-name-{{ $area->id }}" hidden>
                                    <small class="area-photo-note">Kosongkan jika tidak ingin mengganti foto.</small>
                                </div>
                            </div>

                            <div class="kelola-modal-footer area-modal-footer">
                                <button type="button" class="kelola-btn kelola-btn-cancel" data-close-modal>Batal</button>
                                <button type="submit" class="kelola-btn kelola-btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            @endforeach
            @endif

            {{-- MODAL KONFIRMASI DELETE AREA --}}
            @if ($can('areas.delete'))
            @foreach ($areas ?? [] as $area)
                <div class="area-modal-overlay"
                    id="delete-area-{{ $area->id }}"
                    aria-hidden="true">

                    <div class="kelola-modal area-modal"
                        role="dialog"
                        aria-modal="true"
                        aria-labelledby="delete-area-title-{{ $area->id }}"
                        style="max-width: 480px;">

                        <div class="kelola-modal-header area-modal-header">
                            <h2 id="delete-area-title-{{ $area->id }}">⚠️Hapus Data?</h2>

                            <button type="button"
                                    class="kelola-modal-close area-modal-close"
                                    data-close-modal
                                    aria-label="Tutup">
                                &times;
                            </button>
                        </div>

                        <div class="kelola-modal-body area-modal-body" style="text-align: center; padding: 28px 24px;">
                            <p style="margin: 0 0 8px; font-size: 15px; color: var(--kelola-text);">
                                Apakah kamu yakin ingin menghapus area <strong>{{ $area->name }}</strong>?
                            </p>
                            <p style="margin: 0; font-size: 13px; color: var(--kelola-text-secondary);">
                                Data area beserta sub-area terkait akan dikeluarkan dari sistem.
                            </p>
                        </div>

                        <div class="kelola-modal-footer area-modal-footer">
                            <button type="button"
                                    class="kelola-btn kelola-btn-cancel"
                                    data-close-modal>
                                Batal
                            </button>

                            <form action="{{ route('areas.destroy', $area->id) }}"
                                method="POST" style="margin: 0; display: inline;">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        class="kelola-btn kelola-btn-danger">
                                    Ya, Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
            @endif

            {{-- MODAL TAMBAH AREA --}}
            @if ($can('areas.add'))
            <div class="area-modal-overlay"
                id="add-area-modal"
                data-reopen="{{ $errors->any() ? 'true' : 'false' }}"
                aria-hidden="true">

                <div class="kelola-modal area-modal add-area-modal"
                    role="dialog"
                    aria-modal="true"
                    aria-labelledby="add-area-title">

                    <div class="kelola-modal-header area-modal-header">
                        <h2 id="add-area-title">Tambah Area Baru</h2>

                        <button type="button"
                                class="kelola-modal-close area-modal-close"
                                data-close-modal
                                aria-label="Tutup">
                            &times;
                        </button>
                    </div>

                    <form action="{{ route('areas.store') }}"
                        method="POST"
                        enctype="multipart/form-data">

                        @csrf

                        <div class="kelola-modal-body area-modal-body add-area-body">
                            <div class="add-area-fields">
                                <div class="kelola-form-group area-form-group">
                                    <label class="kelola-label" for="add-area-name">
                                        Nama Area <span class="required-star">*</span>
                                    </label>

                                    <input
                                        type="text"
                                        id="add-area-name"
                                        name="name"
                                        class="kelola-input"
                                        placeholder="Masukkan nama area"
                                        value="{{ old('name') }}"
                                        maxlength="255"
                                        required
                                    >

                                    @error('name')
                                        <small class="area-form-error">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="kelola-form-group area-form-group">
                                    <label class="kelola-label" for="add-area-description">Deskripsi</label>

                                    <textarea
                                        id="add-area-description"
                                        name="description"
                                        class="kelola-textarea"
                                        rows="3"
                                        maxlength="1000"
                                        placeholder="Masukkan deskripsi area"
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
                                        width="35"
                                        height="35"
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

                        <div class="kelola-modal-footer area-modal-footer">
                            <button type="button"
                                    class="kelola-btn kelola-btn-cancel"
                                    data-close-modal>
                                Batal
                            </button>

                            <button type="submit" class="kelola-btn kelola-btn-primary">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @endif

        </section>

        @include('partials.footer')

    </main>
</div>

<script src="{{ asset('js/areas.js') }}" defer></script>

</body>
</html>