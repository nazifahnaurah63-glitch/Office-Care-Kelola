<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cleaning Activities - Office Care Kelola</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/design-system.css') }}">
    <link rel="stylesheet" href="{{ asset('css/cleaning-activities.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Memastikan seluruh area input date bertindak sebagai pemicu kalender */
        #filterDate::-webkit-calendar-picker-indicator {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
            opacity: 0;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="app">
        @include('partials.sidebar')

        <main class="main-content">
            @include('partials.topbar')

            {{-- NOTIFIKASI FLASH SUKSES --}}
            @if (session('success'))
                <div class="kelola-flash kelola-flash-success" id="flashNotification" style="position: fixed; top: 20px; left: 50%; transform: translateX(-50%); z-index: 10000; background: #dcfce7; color: #047857; border: 1px solid #86efac; padding: 16px 24px; border-radius: 8px; display: flex; align-items: center; gap: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                    <span style="font-size: 18px; font-weight: bold;">✓</span>
                    <span style="font-size: 14px; font-weight: 600;">{{ session('success') }}</span>
                </div>
            @endif

            <div class="container" style="padding: 20px 24px;">
                <header class="page-header" style="display: flex; align-items: center; gap: 16px; margin-bottom: 24px;">
                    <div class="header-icon-box" style="width: 56px; height: 56px; background: #f5d7e2; color: #ed174c; border-radius: 10px; display: grid; place-items: center;">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3v18h18"/><path d="M18 17V9"/><path d="M13 17V5"/><path d="M8 17v-3"/></svg>
                    </div>
                    <div>
                        <h1 style="margin: 0; font-size: 24px; font-weight: 700; color: #262626;">Cleaning Activities</h1>
                        <p style="margin: 4px 0 0 0; color: #595959; font-size: 14px;">Catat aktivitas kebersihan yang telah dilakukan</p>
                    </div>
                </header>

                {{-- CARD SUMMARY --}}
                <section class="stats-grid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 24px;">
                    <article class="stat-card" style="background: #fff; padding: 20px; border-radius: 12px; border: 1px solid #e5e7eb; display: flex; align-items: center; gap: 16px;">
                        <div style="width: 50px; height: 50px; background: #e0f2fe; color: #0284c7; border-radius: 10px; display: grid; place-items: center;">📋</div>
                        <div>
                            <h2 style="margin: 0; font-size: 26px; font-weight: 700; color: #0284c7;">{{ $totalActivities }}</h2>
                            <p style="margin: 2px 0 0; color: #6b7280; font-size: 14px;">Total Aktivitas</p>
                        </div>
                    </article>

                    <article class="stat-card" style="background: #fff; padding: 20px; border-radius: 12px; border: 1px solid #e5e7eb; display: flex; align-items: center; gap: 16px;">
                        <div style="width: 50px; height: 50px; background: #fef3c7; color: #d97706; border-radius: 10px; display: grid; place-items: center;">📅</div>
                        <div>
                            <h2 style="margin: 0; font-size: 26px; font-weight: 700; color: #d97706;">{{ $todayActivities }}</h2>
                            <p style="margin: 2px 0 0; color: #6b7280; font-size: 14px;">Hari ini</p>
                        </div>
                    </article>

                    <article class="stat-card" style="background: #fff; padding: 20px; border-radius: 12px; border: 1px solid #e5e7eb; display: flex; align-items: center; gap: 16px;">
                        <div style="width: 50px; height: 50px; background: #fee2e2; color: #dc2626; border-radius: 10px; display: grid; place-items: center;">📊</div>
                        <div>
                            <h2 style="margin: 0; font-size: 26px; font-weight: 700; color: #dc2626;">{{ $completionRate }}%</h2>
                            <p style="margin: 2px 0 0; color: #6b7280; font-size: 14px;">Tingkat Penyelesaian</p>
                        </div>
                    </article>
                </section>

                {{-- TABEL & TOOLBAR --}}
                <section class="table-card" style="background: #fff; border-radius: 12px; border: 1px solid #e5e7eb; overflow: hidden;">
                    <div class="table-toolbar" style="padding: 20px 24px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e5e7eb; flex-wrap: wrap; gap: 12px;">
                        <div style="display: flex; gap: 12px; align-items: center;">
                            <button type="button" class="kelola-btn kelola-btn-primary" id="openAddModalBtn" style="background: #c60024; color: #fff; padding: 10px 18px; border-radius: 6px; border: none; font-weight: 600; cursor: pointer;">
                                + Tambah Aktivitas
                            </button>

                            {{-- Tombol Delete Massal (Muncul saat checkbox dicentang) --}}
                            <form id="bulkDeleteForm" action="{{ route('cleaning-activities.bulk-destroy') }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="button" id="deleteSelectedBtn" class="kelola-btn" style="background: #dc2626; color: #fff; padding: 10px 18px; border-radius: 6px; border: none; font-weight: 600; cursor: pointer;" hidden>
                                    🗑 Delete (<span id="selectedCount">0</span>)
                                </button>
                            </form>
                        </div>

                        {{-- DROPDOWN FILTER UTAMA --}}
                        <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                            <select id="filterArea" style="padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; background: #fff; font-size: 13px; color: #374151; cursor: pointer;">
                                <option value="">Semua Area</option>
                                @foreach ($areas as $area)
                                    <option value="{{ strtolower($area->name) }}">{{ $area->name }}</option>
                                @endforeach
                            </select>

                            <select id="filterStatus" style="padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; background: #fff; font-size: 13px; color: #374151; cursor: pointer;">
                                <option value="">Semua Status</option>
                                <option value="selesai">Selesai</option>
                                <option value="pending">Pending</option>
                            </select>

                            {{-- TOMBOL PILIH TANGGAL --}}
                            <div style="display: inline-flex; align-items: center; gap: 6px;">
                                <div style="position: relative; display: inline-flex; align-items: center; height: 36px;">
                                    <div id="datePickerVisualBtn" style="display: inline-flex; align-items: center; gap: 8px; padding: 8px 14px; border: 1px solid #d1d5db; border-radius: 6px; background: #fff; font-size: 13px; color: #374151; height: 100%; box-sizing: border-box; pointer-events: none;">
                                        <i class="far fa-calendar-alt" style="color: #6b7280;"></i>
                                        <span id="datePickerLabel">Pilih Tanggal</span>
                                    </div>
                                    <input type="date" id="filterDate" style="position: absolute; inset: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer; z-index: 2;">
                                </div>
                                <button type="button" id="clearDateBtn" title="Reset Tanggal" style="display: none; background: #fee2e2; color: #dc2626; border: none; border-radius: 4px; padding: 6px 10px; font-size: 12px; cursor: pointer; height: 36px;">&times;</button>
                            </div>
                        </div>
                    </div>

                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse; text-align: left;">
                            <thead>
                                <tr style="background: #f9fafb; border-bottom: 1px solid #e5e7eb; color: #374151; font-size: 13px;">
                                    <th style="padding: 14px 20px; width: 40px;"><input type="checkbox" id="selectAll"></th>
                                    <th style="padding: 14px 20px;">
                                        <div>TANGGAL</div>
                                        <input type="text" id="searchTanggal" placeholder="Search Tanggal" style="margin-top: 6px; width: 100%; padding: 6px 10px; font-size: 12px; border: 1px solid #d1d5db; border-radius: 4px; font-weight: normal;">
                                    </th>
                                    <th style="padding: 14px 20px;">
                                        <div>AREA</div>
                                        <input type="text" id="searchArea" placeholder="Search Area" style="margin-top: 6px; width: 100%; padding: 6px 10px; font-size: 12px; border: 1px solid #d1d5db; border-radius: 4px; font-weight: normal;">
                                    </th>
                                    <th style="padding: 14px 20px;">
                                        <div>JENIS AKTIVITAS</div>
                                        <input type="text" id="searchAktivitas" placeholder="Search Aktivitas" style="margin-top: 6px; width: 100%; padding: 6px 10px; font-size: 12px; border: 1px solid #d1d5db; border-radius: 4px; font-weight: normal;">
                                    </th>
                                    <th style="padding: 14px 20px;">
                                        <div>PELAKSANA</div>
                                        <input type="text" id="searchPelaksana" placeholder="Search Pelaksana" style="margin-top: 6px; width: 100%; padding: 6px 10px; font-size: 12px; border: 1px solid #d1d5db; border-radius: 4px; font-weight: normal;">
                                    </th>
                                    <th style="padding: 14px 20px;">
                                        <div>STATUS</div>
                                        <input type="text" id="searchStatus" placeholder="Search Status" style="margin-top: 6px; width: 100%; padding: 6px 10px; font-size: 12px; border: 1px solid #d1d5db; border-radius: 4px; font-weight: normal;">
                                    </th>
                                    <th style="padding: 14px 20px; text-align: center;">ACTION</th>
                                </tr>
                            </thead>
                            <tbody id="activityTableBody">
                                @forelse ($activities as $act)
                                    @php
                                        $formattedDate = \Carbon\Carbon::parse($act->tanggal)->format('d-m-Y');
                                        $rawDateOnly = \Carbon\Carbon::parse($act->tanggal)->format('Y-m-d');
                                        $pelaksanaName = $act->employee_name ?? $act->pelaksana ?? '-';
                                    @endphp
                                    <tr class="activity-row"
                                        data-tanggal-raw="{{ $rawDateOnly }}"
                                        data-tanggal="{{ strtolower($formattedDate) }}"
                                        data-area="{{ strtolower($act->area) }}"
                                        data-aktivitas="{{ strtolower($act->jenis_aktivitas) }}"
                                        data-pelaksana="{{ strtolower($pelaksanaName) }}"
                                        data-status="{{ strtolower($act->status) }}"
                                        style="border-bottom: 1px solid #e5e7eb; font-size: 14px; color: #4b5563;">
                                        
                                        <td style="padding: 14px 20px;"><input type="checkbox" name="activity_ids[]" value="{{ $act->id }}" class="activity-checkbox" form="bulkDeleteForm"></td>
                                        <td style="padding: 14px 20px;">{{ $formattedDate }}</td>
                                        <td style="padding: 14px 20px;">{{ $act->area }}</td>
                                        <td style="padding: 14px 20px;">{{ $act->jenis_aktivitas }}</td>
                                        <td style="padding: 14px 20px;">{{ $pelaksanaName }}</td>
                                        <td style="padding: 14px 20px;">
                                            @if(strtolower($act->status) === 'selesai')
                                                <span style="background: #dcfce7; color: #166534; padding: 4px 12px; border-radius: 6px; font-size: 12px; font-weight: 600;">Selesai</span>
                                            @else
                                                <span style="background: #fef9c3; color: #854d0e; padding: 4px 12px; border-radius: 6px; font-size: 12px; font-weight: 600;">Pending</span>
                                            @endif
                                        </td>
                                        <td style="padding: 14px 20px; text-align: center;">
                                            <div class="row-actions" style="display: flex; align-items: center; justify-content: center; gap: 12px;">
                                                {{-- Tombol Edit --}}
                                                <button type="button"
                                                        class="action-icon edit-activity-btn"
                                                        title="Edit"
                                                        data-id="{{ $act->id }}"
                                                        data-tanggal="{{ $rawDateOnly }}"
                                                        data-jenis="{{ $act->jenis_aktivitas }}"
                                                        data-area="{{ $act->area }}"
                                                        data-pelaksana="{{ $pelaksanaName }}"
                                                        data-status="{{ $act->status }}"
                                                        style="background: none; border: none; color: #dc2626; cursor: pointer; font-size: 15px;">
                                                    <i class="fas fa-pen"></i>
                                                </button>

                                                {{-- Tombol Hapus Satuan --}}
                                                <form action="{{ route('cleaning-activities.destroy', $act->id) }}" method="POST" class="single-delete-form" style="display: inline; margin: 0;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="single-delete-btn" style="background: none; border: none; color: #dc2626; cursor: pointer; font-size: 16px;" title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr id="emptyRow">
                                        <td colspan="7" style="padding: 30px; text-align: center; color: #9ca3af;">Belum ada data aktivitas kebersihan.</td>
                                    </tr>
                                @endforelse
                                <tr id="noResultsRow" style="display: none;">
                                    <td colspan="7" style="padding: 30px; text-align: center; color: #9ca3af;">Tidak ada data yang cocok dengan pencarian.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    {{-- FOOTER & PAGINASI --}}
                    <div style="padding: 16px 24px; display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #e5e7eb; font-size: 14px; color: #6b7280; flex-wrap: wrap; gap: 10px;">
                        <div id="tableInfo">Showing 1 to {{ $activities->count() }} of {{ $activities->count() }} entries</div>
                        <div style="display: flex; gap: 4px; align-items: center;" id="paginationContainer"></div>
                    </div>
                </section>
            </div>
        </main>
    </div>

    {{-- MODAL TAMBAH AKTIVITAS --}}
    <div id="addModalOverlay" style="position: fixed; inset: 0; background: rgba(0,0,0,0.5); display: none; align-items: center; justify-content: center; z-index: 9999;">
        <div style="background: #fff; width: 100%; max-width: 650px; border-radius: 12px; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
            <div style="background: #c60024; color: #fff; padding: 18px 24px; display: flex; justify-content: space-between; align-items: center;">
                <h2 style="margin: 0; font-size: 18px; font-weight: 700;">Add Activities</h2>
                <button type="button" id="closeModalBtn" style="background: none; border: none; color: #fff; font-size: 22px; cursor: pointer;">&times;</button>
            </div>

            <form action="{{ route('cleaning-activities.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div style="padding: 24px; display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Tanggal Aktivitas <span style="color: #c60024;">*</span></label>
                        <input type="date" name="tanggal" required style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; box-sizing: border-box;" value="{{ date('Y-m-d') }}">
                    </div>

                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Jenis Aktivitas <span style="color: #c60024;">*</span></label>
                        <input type="text" name="jenis_aktivitas" placeholder="Pilih Jenis Aktivitas" required style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; box-sizing: border-box;">
                    </div>

                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Area <span style="color: #c60024;">*</span></label>
                        <select name="area" required style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; box-sizing: border-box; background: #fff;">
                            <option value="">Pilih Area</option>
                            @foreach ($areas as $area)
                                <option value="{{ $area->name }}">{{ $area->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Pelaksana <span style="color: #c60024;">*</span></label>
                        <input type="text" value="{{ auth()->user()->name ?? 'Administrator' }}" disabled style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; background: #f3f4f6; box-sizing: border-box; color: #6b7280;">
                    </div>

                    <div style="grid-column: span 2;">
                        <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Upload Foto <span style="color: #c60024;">*</span></label>
                        <label style="display: flex; flex-direction: column; align-items: center; justify-content: center; border: 1px solid #fca5a5; background: #fff5f5; padding: 24px; border-radius: 8px; cursor: pointer; text-align: center;">
                            <i class="fas fa-cloud-upload-alt" style="font-size: 28px; color: #c60024; margin-bottom: 8px;"></i>
                            <span style="color: #6b7280; font-size: 12px;">Drag & drop foto di sini<br>atau <strong style="color: #c60024;">dipilih file</strong><br>Format: JPG, PNG (Max 2MB)</span>
                            <input type="file" name="foto" accept="image/*" required hidden id="fotoInput">
                            <span id="fileNameDisplay" style="font-size: 12px; color: #374151; margin-top: 8px; font-weight: 600;"></span>
                        </label>
                    </div>
                </div>

                <div style="padding: 16px 24px; background: #f9fafb; border-top: 1px solid #e5e7eb; display: flex; justify-content: flex-end; gap: 12px;">
                    <button type="button" id="cancelModalBtn" style="padding: 10px 20px; border: none; background: #6b7280; color: #fff; border-radius: 6px; cursor: pointer; font-weight: 600;">Cancel</button>
                    <button type="submit" style="padding: 10px 20px; background: #c60024; color: #fff; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;">Save</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL EDIT AKTIVITAS --}}
    <div id="editModalOverlay" style="position: fixed; inset: 0; background: rgba(0,0,0,0.5); display: none; align-items: center; justify-content: center; z-index: 9999;">
        <div style="background: #fff; width: 100%; max-width: 650px; border-radius: 12px; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
            <div style="background: #c60024; color: #fff; padding: 18px 24px; display: flex; justify-content: space-between; align-items: center;">
                <h2 style="margin: 0; font-size: 18px; font-weight: 700;">Edit Activities</h2>
                <button type="button" id="closeEditModalBtn" style="background: none; border: none; color: #fff; font-size: 22px; cursor: pointer;">&times;</button>
            </div>

            <form id="editActivityForm" action="" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div style="padding: 24px; display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Tanggal Aktivitas <span style="color: #c60024;">*</span></label>
                        <input type="date" id="edit_tanggal" name="tanggal" required style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; box-sizing: border-box;">
                    </div>

                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Jenis Aktivitas <span style="color: #c60024;">*</span></label>
                        <input type="text" id="edit_jenis_aktivitas" name="jenis_aktivitas" placeholder="Pilih Jenis Aktivitas" required style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; box-sizing: border-box;">
                    </div>

                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Area <span style="color: #c60024;">*</span></label>
                        <select id="edit_area" name="area" required style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; box-sizing: border-box; background: #fff;">
                            <option value="">Pilih Area</option>
                            @foreach ($areas as $area)
                                <option value="{{ $area->name }}">{{ $area->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Pelaksana <span style="color: #c60024;">*</span></label>
                        <input type="text" id="edit_pelaksana" disabled style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; background: #f3f4f6; box-sizing: border-box; color: #6b7280;">
                    </div>

                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Status <span style="color: #c60024;">*</span></label>
                        <select id="edit_status" name="status" required style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; box-sizing: border-box; background: #fff;">
                            <option value="Selesai">Selesai</option>
                            <option value="Pending">Pending</option>
                        </select>
                    </div>

                    <div style="grid-column: span 2;">
                        <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Upload Foto (Opsional jika tidak diganti)</label>
                        <label style="display: flex; flex-direction: column; align-items: center; justify-content: center; border: 1px solid #fca5a5; background: #fff5f5; padding: 20px; border-radius: 8px; cursor: pointer; text-align: center;">
                            <i class="fas fa-cloud-upload-alt" style="font-size: 26px; color: #c60024; margin-bottom: 6px;"></i>
                            <span style="color: #6b7280; font-size: 12px;">Drag & drop foto baru di sini<br>atau <strong style="color: #c60024;">dipilih file</strong><br>Format: JPG, PNG (Max 2MB)</span>
                            <input type="file" name="foto" accept="image/*" hidden id="editFotoInput">
                            <span id="editFileNameDisplay" style="font-size: 12px; color: #374151; margin-top: 6px; font-weight: 600;"></span>
                        </label>
                    </div>
                </div>

                <div style="padding: 16px 24px; background: #f9fafb; border-top: 1px solid #e5e7eb; display: flex; justify-content: flex-end; gap: 12px;">
                    <button type="button" id="cancelEditModalBtn" style="padding: 10px 20px; border: none; background: #6b7280; color: #fff; border-radius: 6px; cursor: pointer; font-weight: 600;">Cancel</button>
                    <button type="submit" style="padding: 10px 20px; background: #c60024; color: #fff; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;">Save</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL KONFIRMASI DELETE --}}
    <div id="deleteConfirmModal" style="position: fixed; inset: 0; background: rgba(0,0,0,0.5); display: none; align-items: center; justify-content: center; z-index: 10000;">
        <div style="background: #fff; width: 100%; max-width: 480px; border-radius: 12px; overflow: visible; box-shadow: 0 10px 25px rgba(0,0,0,0.2); position: relative;">
            <button type="button" id="closeDeleteModalBtn" style="position: absolute; top: -12px; right: -12px; width: 28px; height: 28px; background: #fff; border: 1px solid #e5e7eb; border-radius: 6px; color: #6b7280; font-size: 16px; cursor: pointer; display: grid; place-items: center; box-shadow: 0 2px 6px rgba(0,0,0,0.15);">&times;</button>

            <div style="background: #c60024; color: #fff; padding: 16px 24px; border-radius: 12px 12px 0 0; display: flex; align-items: center; gap: 10px;">
                <span style="font-size: 18px;">⚠️</span>
                <h2 style="margin: 0; font-size: 17px; font-weight: 700;">Hapus Data?</h2>
            </div>

            <div style="padding: 24px 24px 16px 24px;">
                <p style="margin: 0; font-size: 14px; color: #1f2937; line-height: 1.6;">
                    Apakah Anda yakin ingin menghapus data aktivitas kebersihan ini? Data yang telah dihapus akan dikeluarkan dari sistem dan tidak dapat dikembalikan.
                </p>
            </div>

            <div style="padding: 12px 24px 20px 24px; display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" id="cancelDeleteModalBtn" style="padding: 8px 18px; border: 1px solid #d1d5db; background: #fff; color: #374151; border-radius: 6px; cursor: pointer; font-weight: 600; font-size: 13px;">Cancel</button>
                <button type="button" id="confirmDeleteActionBtn" style="padding: 8px 18px; background: #c60024; color: #fff; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; font-size: 13px;">Ya, Lanjutkan</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Notifikasi Toast Auto Hide
            const flash = document.getElementById('flashNotification');
            if (flash) {
                setTimeout(() => {
                    flash.style.transition = 'opacity 0.5s ease';
                    flash.style.opacity = '0';
                    setTimeout(() => flash.remove(), 500);
                }, 4000);
            }

            // Modal Add Logic
            const addModal = document.getElementById('addModalOverlay');
            document.getElementById('openAddModalBtn').addEventListener('click', () => addModal.style.display = 'flex');
            document.getElementById('closeModalBtn').addEventListener('click', () => addModal.style.display = 'none');
            document.getElementById('cancelModalBtn').addEventListener('click', () => addModal.style.display = 'none');

            // Modal Edit Logic
            const editModal = document.getElementById('editModalOverlay');
            const editForm = document.getElementById('editActivityForm');
            
            document.querySelectorAll('.edit-activity-btn').forEach(button => {
                button.addEventListener('click', function () {
                    const id = this.dataset.id;
                    editForm.action = `/cleaning-activities/${id}`;
                    document.getElementById('edit_tanggal').value = this.dataset.tanggal;
                    document.getElementById('edit_jenis_aktivitas').value = this.dataset.jenis;
                    document.getElementById('edit_area').value = this.dataset.area;
                    document.getElementById('edit_pelaksana').value = this.dataset.pelaksana;
                    document.getElementById('edit_status').value = this.dataset.status;
                    editModal.style.display = 'flex';
                });
            });

            document.getElementById('closeEditModalBtn').addEventListener('click', () => editModal.style.display = 'none');
            document.getElementById('cancelEditModalBtn').addEventListener('click', () => editModal.style.display = 'none');

            // Modal Konfirmasi Delete
            const deleteModal = document.getElementById('deleteConfirmModal');
            const confirmDeleteBtn = document.getElementById('confirmDeleteActionBtn');
            let activeDeleteForm = null;

            function openDeleteModal(form) {
                activeDeleteForm = form;
                deleteModal.style.display = 'flex';
            }

            function closeDeleteModal() {
                deleteModal.style.display = 'none';
                activeDeleteForm = null;
            }

            document.querySelectorAll('.single-delete-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    openDeleteModal(this.closest('form'));
                });
            });

            const bulkDeleteBtn = document.getElementById('deleteSelectedBtn');
            if (bulkDeleteBtn) {
                bulkDeleteBtn.addEventListener('click', function () {
                    openDeleteModal(document.getElementById('bulkDeleteForm'));
                });
            }

            confirmDeleteBtn.addEventListener('click', function () {
                if (activeDeleteForm) {
                    activeDeleteForm.submit();
                }
            });

            document.getElementById('closeDeleteModalBtn').addEventListener('click', closeDeleteModal);
            document.getElementById('cancelDeleteModalBtn').addEventListener('click', closeDeleteModal);

            // Checkbox & Bulk Delete Button Toggle
            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.activity-checkbox');
            const selectedCount = document.getElementById('selectedCount');

            function updateDeleteButton() {
                const checked = document.querySelectorAll('.activity-checkbox:checked');
                bulkDeleteBtn.hidden = checked.length === 0;
                selectedCount.textContent = checked.length;
            }

            if (selectAll) {
                selectAll.addEventListener('change', function () {
                    checkboxes.forEach(cb => cb.checked = selectAll.checked);
                    updateDeleteButton();
                });
            }

            checkboxes.forEach(cb => {
                cb.addEventListener('change', updateDeleteButton);
            });

            // Nama File Foto Display
            const fotoInput = document.getElementById('fotoInput');
            const fileNameDisplay = document.getElementById('fileNameDisplay');
            if (fotoInput) {
                fotoInput.addEventListener('change', function () {
                    if (this.files[0]) fileNameDisplay.textContent = this.files[0].name;
                });
            }

            const editFotoInput = document.getElementById('editFotoInput');
            const editFileNameDisplay = document.getElementById('editFileNameDisplay');
            if (editFotoInput) {
                editFotoInput.addEventListener('change', function () {
                    if (this.files[0]) editFileNameDisplay.textContent = this.files[0].name;
                });
            }

            // --- CUSTOM TOMBOL PILIH TANGGAL ---
            const filterDate = document.getElementById('filterDate');
            const datePickerLabel = document.getElementById('datePickerLabel');
            const clearDateBtn = document.getElementById('clearDateBtn');

            filterDate.addEventListener('click', function () {
                if (typeof this.showPicker === 'function') {
                    try {
                        this.showPicker();
                    } catch (e) {}
                }
            });

            filterDate.addEventListener('change', function () {
                if (this.value) {
                    const parts = this.value.split('-');
                    datePickerLabel.textContent = `${parts[2]}-${parts[1]}-${parts[0]}`;
                    clearDateBtn.style.display = 'inline-block';
                } else {
                    datePickerLabel.textContent = 'Pilih Tanggal';
                    clearDateBtn.style.display = 'none';
                }
                filterAndPaginate();
            });

            clearDateBtn.addEventListener('click', function () {
                filterDate.value = '';
                datePickerLabel.textContent = 'Pilih Tanggal';
                clearDateBtn.style.display = 'none';
                filterAndPaginate();
            });

            // --- FILTER, SEARCH & PAGINASI ---
            const filterArea = document.getElementById('filterArea');
            const filterStatus = document.getElementById('filterStatus');

            const searchTanggal = document.getElementById('searchTanggal');
            const searchArea = document.getElementById('searchArea');
            const searchAktivitas = document.getElementById('searchAktivitas');
            const searchPelaksana = document.getElementById('searchPelaksana');
            const searchStatus = document.getElementById('searchStatus');

            const allRows = Array.from(document.querySelectorAll('.activity-row'));
            const noResultsRow = document.getElementById('noResultsRow');
            const tableInfo = document.getElementById('tableInfo');
            const paginationContainer = document.getElementById('paginationContainer');

            const rowsPerPage = 5;
            let currentPage = 1;
            let filteredRows = [...allRows];

            function filterAndPaginate() {
                const fArea = filterArea.value.toLowerCase();
                const fStatus = filterStatus.value.toLowerCase();
                const fDate = filterDate.value;

                const sTanggal = searchTanggal.value.toLowerCase();
                const sArea = searchArea.value.toLowerCase();
                const sAktivitas = searchAktivitas.value.toLowerCase();
                const sPelaksana = searchPelaksana.value.toLowerCase();
                const sStatus = searchStatus.value.toLowerCase();

                filteredRows = allRows.filter(row => {
                    const rTanggalRaw = row.dataset.tanggalRaw;
                    const rTanggal = row.dataset.tanggal;
                    const rArea = row.dataset.area;
                    const rAktivitas = row.dataset.aktivitas;
                    const rPelaksana = row.dataset.pelaksana;
                    const rStatus = row.dataset.status;

                    return (!fArea || rArea.includes(fArea)) &&
                           (!fStatus || rStatus.includes(fStatus)) &&
                           (!fDate || rTanggalRaw === fDate) &&
                           (!sTanggal || rTanggal.includes(sTanggal)) &&
                           (!sArea || rArea.includes(sArea)) &&
                           (!sAktivitas || rAktivitas.includes(sAktivitas)) &&
                           (!sPelaksana || rPelaksana.includes(sPelaksana)) &&
                           (!sStatus || rStatus.includes(sStatus));
                });

                currentPage = 1;
                renderPage();
            }

            function renderPage() {
                const total = filteredRows.length;
                const totalPages = Math.max(1, Math.ceil(total / rowsPerPage));
                if (currentPage > totalPages) currentPage = totalPages;

                const start = (currentPage - 1) * rowsPerPage;
                const end = start + rowsPerPage;

                allRows.forEach(row => row.style.display = 'none');
                filteredRows.slice(start, end).forEach(row => row.style.display = '');

                if (noResultsRow) {
                    noResultsRow.style.display = (allRows.length > 0 && total === 0) ? '' : 'none';
                }

                if (tableInfo) {
                    if (total === 0) {
                        tableInfo.textContent = 'Showing 0 to 0 of 0 entries';
                    } else {
                        tableInfo.textContent = `Showing ${start + 1} to ${Math.min(end, total)} of ${total} entries`;
                    }
                }

                renderPaginationButtons(totalPages);
            }

            function renderPaginationButtons(totalPages) {
                paginationContainer.innerHTML = '';

                const createBtn = (label, page, isActive = false, isDisabled = false) => {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.textContent = label;
                    btn.disabled = isDisabled;
                    btn.style.padding = '6px 11px';
                    btn.style.borderRadius = '50%';
                    btn.style.cursor = isDisabled ? 'not-allowed' : 'pointer';
                    btn.style.fontSize = '13px';

                    if (isActive) {
                        btn.style.background = '#c60024';
                        btn.style.color = '#fff';
                        btn.style.border = 'none';
                        btn.style.fontWeight = '600';
                    } else {
                        btn.style.background = '#f3f4f6';
                        btn.style.color = isDisabled ? '#9ca3af' : '#374151';
                        btn.style.border = 'none';
                    }

                    if (!isDisabled) {
                        btn.addEventListener('click', () => {
                            currentPage = page;
                            renderPage();
                        });
                    }
                    paginationContainer.appendChild(btn);
                };

                createBtn('«', 1, false, currentPage === 1);
                createBtn('‹', currentPage - 1, false, currentPage === 1);

                for (let i = 1; i <= totalPages; i++) {
                    createBtn(String(i), i, i === currentPage, false);
                }

                createBtn('›', currentPage + 1, false, currentPage === totalPages);
                createBtn('»', totalPages, false, currentPage === totalPages);
            }

            [filterArea, filterStatus, searchTanggal, searchArea, searchAktivitas, searchPelaksana, searchStatus].forEach(el => {
                if (el) {
                    el.addEventListener('input', filterAndPaginate);
                    el.addEventListener('change', filterAndPaginate);
                }
            });

            renderPage();
        });
    </script>
</body>
</html>
```[cite: 5]
