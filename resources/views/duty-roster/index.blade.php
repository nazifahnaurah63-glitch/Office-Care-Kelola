<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Duty Roster | Office Care Kelola</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/design-system.css') }}">
    <link rel="stylesheet" href="{{ asset('css/duty-roster.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
@php
    $currentRole = auth()->user()->role;

    $can = fn ($permission) =>
        $currentRole && $currentRole->hasPermission($permission);

    $calendarStart = $currentMonth->copy()
        ->startOfMonth()->startOfWeek(\Carbon\Carbon::SUNDAY);

    $calendarEnd = $currentMonth->copy()
        ->endOfMonth()->endOfWeek(\Carbon\Carbon::SATURDAY);

    $calendarDays = collect();

    for ($date = $calendarStart->copy(); $date->lte($calendarEnd); $date->addDay()) {
        $calendarDays->push($date->copy());
    }

    $previousMonth = $currentMonth->copy()->subMonth()->format('Y-m');
    $nextMonth = $currentMonth->copy()->addMonth()->format('Y-m');

    $monthNames = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
        4 => 'April', 5 => 'Mei', 6 => 'Juni',
        7 => 'Juli', 8 => 'Agustus', 9 => 'September',
        10 => 'Oktober', 11 => 'November', 12 => 'Desember',
    ];
@endphp

<div class="app">

    @include('partials.sidebar')

    <main class="main-content">

        @include('partials.topbar')
        
        {{-- PAGE HEADING GAYA DASHBOARD --}}
        <section class="kelola-page-heading">
            <div class="kelola-heading-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
            </div>
            <div class="kelola-heading-text">
                <h1>Duty Roster</h1>
                <p>Kelola jadwal piket karyawan</p>
            </div>
        </section>

        {{-- NOTIFIKASI BERHASIL --}}
        @if (session('success'))
            <div class="kelola-flash kelola-flash-success" id="flashNotification">
                <span class="kelola-flash-icon">✓</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- NOTIFIKASI VALIDASI --}}
        @if ($errors->any())
            <div class="kelola-flash kelola-flash-error" id="flashNotification">
                <span class="kelola-flash-icon">!</span>
                <div>
                    <strong>Jadwal belum berhasil disimpan:</strong>
                    <ul style="margin: 5px 0 0; padding-left: 18px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        {{-- KALENDER --}}
        <section class="roster-panel">
            <div class="calendar-toolbar">
                <a class="month-arrow"
                   href="{{ route('duty-roster.index', ['month' => $previousMonth]) }}"
                   aria-label="Bulan sebelumnya">
                    ‹
                </a>

                <a class="month-arrow"
                   href="{{ route('duty-roster.index', ['month' => $nextMonth]) }}"
                   aria-label="Bulan berikutnya">
                    ›
                </a>

                <h3>
                    {{ $monthNames[$currentMonth->month] }}
                    {{ $currentMonth->year }}
                </h3>
            </div>

            <div class="calendar-scroll">
                <div class="calendar-grid">
                    @foreach ([
                        'Minggu', 'Senin', 'Selasa', 'Rabu',
                        'Kamis', 'Jumat', 'Sabtu'
                    ] as $weekday)
                        <div class="calendar-weekday">
                            {{ $weekday }}
                        </div>
                    @endforeach

                    @foreach ($calendarDays as $day)
                        @php
                            $dayRosters = $dutyRosters->filter(function ($item) use ($day) {
                                $start = \Carbon\Carbon::parse(
                                    $item->start_date ?? $item->period
                                )->startOfDay();

                                $end = \Carbon\Carbon::parse(
                                    $item->end_date ?? $item->period
                                )->startOfDay();

                                return $day->copy()->startOfDay()
                                    ->betweenIncluded($start, $end);
                            });

                            $isCurrentMonth = $day->month === $currentMonth->month;
                            $isToday = $day->isToday();
                        @endphp

                        <div class="calendar-day
                            {{ $isCurrentMonth ? '' : 'other-month' }}
                            {{ $isToday ? 'is-today' : '' }}">

                            <div class="day-number">
                                {{ $day->day }}
                            </div>

                            @foreach ($dayRosters as $roster)
                                <div class="roster-event">
                                    <div class="event-title">
                                        <span class="event-dot"></span>
                                        {{ $roster->status }}
                                    </div>

                                    @foreach ([
                                        $roster->member1,
                                        $roster->member2,
                                        $roster->member3,
                                        $roster->member4
                                    ] as $member)
                                        @if ($member)
                                            <div class="member-name">
                                                {{ $member->name }}
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @endforeach

                            @if ($dayRosters->isEmpty() && $isCurrentMonth)
                                <span class="empty-day">
                                    Belum ada piket
                                </span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="calendar-legend">
                <span><i></i> Jadwal piket aktif</span>
                <span class="legend-muted">
                    Maksimal 4 anggota per jadwal
                </span>
            </div>
        </section>

        {{-- TABEL JADWAL MENGGUNAKAN KELOLA-TABLE-CARD --}}
        <section class="kelola-table-card">
            <div class="kelola-table-header">
                <h2>Daftar Jadwal Piket</h2>
                <div class="kelola-table-controls">

                    @if ($can('duty_roster.add'))
                        <button type="button"
                                class="kelola-btn kelola-btn-primary"
                                id="openRosterModal">
                            <span>＋</span> Buat Jadwal
                        </button>
                    @endif
                    
                    <select class="kelola-page-length" id="entriesSelect" aria-label="Jumlah data">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
            </div>

            <div class="kelola-table-wrap">
                <table class="kelola-table" id="rosterTable">
                    <thead>
                        <tr>
                            <th>NO</th>
                            <th>PERIOD</th>
                            <th>MEMBER</th>
                            <th>STATUS</th>
                            <th>ACTION</th>
                        </tr>
                        <tr class="kelola-search-row">
                            <th><input type="search" data-column="0" placeholder="Cari No"></th>
                            <th><input type="search" data-column="1" placeholder="Cari Period"></th>
                            <th><input type="search" data-column="2" placeholder="Cari Member"></th>
                            <th><input type="search" data-column="3" placeholder="Cari Status"></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="rosterTableBody">
                        @forelse ($allDutyRosters as $roster)
                            @php
                                $startDate = \Carbon\Carbon::parse(
                                    $roster->start_date ?? $roster->period
                                );

                                $endDate = \Carbon\Carbon::parse(
                                    $roster->end_date ?? $roster->period
                                );

                                $members = collect([
                                    $roster->member1,
                                    $roster->member2,
                                    $roster->member3,
                                    $roster->member4,
                                ])->filter();
                            @endphp

                            <tr class="roster-table-row">
                                <td class="row-number">{{ $loop->iteration }}</td>
                                <td>
                                    {{ $startDate->format('d-m-Y') }}
                                    @if (!$startDate->isSameDay($endDate))
                                        – {{ $endDate->format('d-m-Y') }}
                                    @endif
                                </td>
                                <td>
                                    <div class="table-members">
                                        @foreach ($members as $member)
                                            <span>{{ $member->name }}</span>
                                        @endforeach
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $isActive = $roster->status === 'Active';
                                    @endphp
                                    <span class="kelola-badge {{ $isActive ? 'kelola-badge-success' : 'kelola-badge-danger' }}">
                                        {{ in_array($roster->status, ['Inactive', 'Non-Active'], true) ? 'Non-Active' : $roster->status }}
                                    </span>
                                </td>
                                <td>
                                    <div class="kelola-table-actions">
                                        @if ($can('duty_roster.edit'))
                                            <button type="button"
                                                    class="kelola-action-icon edit-roster-btn"
                                                    data-open-edit="editRosterModal-{{ $roster->id }}"
                                                    title="Edit jadwal">
                                                <i class="fas fa-pen"></i>
                                            </button>
                                        @endif

                                        @if ($can('duty_roster.delete'))
                                            <form method="POST"
                                                  action="{{ route('duty-roster.destroy', $roster) }}"
                                                  data-confirm-delete style="display:inline-flex; margin:0;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="kelola-action-icon table-delete-btn"
                                                        title="Hapus jadwal">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr class="no-data-row">
                                <td colspan="5" style="text-align: center; padding: 20px;">
                                    Belum ada jadwal piket.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="kelola-table-footer">
                <span class="kelola-table-info" id="tableInfo">Menampilkan data jadwal</span>
                <div class="kelola-pagination" id="tablePagination"></div>
            </div>
        </section>

        @include('partials.footer')

    </main>
</div>

{{-- MODAL TAMBAH JADWAL (MENGGUNAKAN KELOLA-MODAL) --}}
@if ($can('duty_roster.add'))
    <div class="kelola-modal-overlay roster-modal" id="rosterModal" aria-hidden="true">
        <div class="kelola-modal roster-modal-card" style="max-width: 650px;">
            <div class="kelola-modal-header modal-heading">
                <h2>Add Duty Roster</h2>
                <button type="button" class="kelola-modal-close modal-close" data-close-modal="rosterModal" aria-label="Tutup">&times;</button>
            </div>

            <form method="POST" action="{{ route('duty-roster.store') }}">
                @csrf
                <div class="kelola-modal-body modal-body">
                    <div class="kelola-form-grid date-status-grid">
                        <div class="kelola-form-group form-field">
                            <label class="kelola-label" for="add_start_date">Tanggal Mulai <span class="kelola-required">*</span></label>
                            <input type="date" id="add_start_date" name="start_date" class="kelola-input" value="{{ old('start_date', now()->format('Y-m-d')) }}" required>
                        </div>

                        <div class="kelola-form-group form-field">
                            <label class="kelola-label" for="add_end_date">Tanggal Selesai <span class="kelola-required">*</span></label>
                            <input type="date" id="add_end_date" name="end_date" class="kelola-input" value="{{ old('end_date', now()->format('Y-m-d')) }}" required>
                        </div>

                        <div class="kelola-form-group form-field">
                            <label class="kelola-label" for="add_status">Status <span class="kelola-required">*</span></label>
                            <select id="add_status" name="status" class="kelola-select" required>
                                <option value="Active" @selected(old('status', 'Active') === 'Active')>Active</option>
                                <option value="Non-Active" @selected(old('status') === 'Non-Active')>Non-Active</option>
                            </select>
                        </div>
                    </div>

                    <div class="checkbox-member-card" style="margin-top: 20px; padding: 16px; border: 1px solid #e5e7eb; border-radius: 8px; background: #fff;">
                        <div class="checkbox-member-title" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; font-weight: 600;">
                            <span>Anggota Piket</span>
                            <span class="selected-count kelola-badge kelola-badge-danger">0/4</span>
                        </div>
                        <div class="checkbox-member-grid">
                            @foreach ($users as $user)
                                <label class="member-checkbox">
                                    <input type="checkbox" name="members[]" value="{{ $user->id }}"
                                           @checked(in_array((string) $user->id, array_map('strval', old('members', [])), true))>
                                    <span>{{ $user->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="kelola-modal-footer modal-actions">
                    <button type="button" class="kelola-btn kelola-btn-cancel cancel-btn" data-close-modal="rosterModal">Batal</button>
                    <button type="submit" class="kelola-btn kelola-btn-primary save-roster-btn">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endif

{{-- MODAL EDIT JADWAL --}}
@if ($can('duty_roster.edit'))
    @foreach ($allDutyRosters as $roster)
        @php
            $savedMemberIds = collect([
                $roster->member1_id,
                $roster->member2_id,
                $roster->member3_id,
                $roster->member4_id,
            ])->filter()->map(fn ($id) => (string) $id)->all();

            $isOldEdit = (string) old('roster_id') === (string) $roster->id;
            $oldEditMembers = $isOldEdit
                ? array_map('strval', old('members', []))
                : $savedMemberIds;
        @endphp

        <div class="kelola-modal-overlay roster-modal" id="editRosterModal-{{ $roster->id }}" aria-hidden="true">
            <div class="kelola-modal roster-modal-card" style="max-width: 650px;">
                <div class="kelola-modal-header modal-heading">
                    <h2>Edit Duty Roster</h2>
                    <button type="button" class="kelola-modal-close modal-close" data-close-modal="editRosterModal-{{ $roster->id }}" aria-label="Tutup">&times;</button>
                </div>

                <form method="POST" action="{{ route('duty-roster.update', $roster) }}">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="roster_id" value="{{ $roster->id }}">

                    <div class="kelola-modal-body modal-body">
                        <div class="kelola-form-grid date-status-grid">
                            <div class="kelola-form-group form-field">
                                <label class="kelola-label" for="edit_start_{{ $roster->id }}">Tanggal Mulai <span class="kelola-required">*</span></label>
                                <input type="date" id="edit_start_{{ $roster->id }}" name="start_date" class="kelola-input"
                                       value="{{ $isOldEdit ? old('start_date') : \Carbon\Carbon::parse($roster->start_date ?? $roster->period)->format('Y-m-d') }}" required>
                            </div>

                            <div class="kelola-form-group form-field">
                                <label class="kelola-label" for="edit_end_{{ $roster->id }}">Tanggal Selesai <span class="kelola-required">*</span></label>
                                <input type="date" id="edit_end_{{ $roster->id }}" name="end_date" class="kelola-input"
                                       value="{{ $isOldEdit ? old('end_date') : \Carbon\Carbon::parse($roster->end_date ?? $roster->period)->format('Y-m-d') }}" required>
                            </div>

                            <div class="kelola-form-group form-field">
                                <label class="kelola-label" for="edit_status_{{ $roster->id }}">Status <span class="kelola-required">*</span></label>
                                <select id="edit_status_{{ $roster->id }}" name="status" class="kelola-select" required>
                                    <option value="Active" @selected($isOldEdit ? old('status') === 'Active' : $roster->status === 'Active')>Active</option>
                                    <option value="Non-Active" @selected($isOldEdit ? old('status') === 'Non-Active' : in_array($roster->status, ['Non-Active', 'Inactive'], true))>Non-Active</option>
                                </select>
                            </div>
                        </div>

                        <div class="checkbox-member-card" style="margin-top: 20px; padding: 16px; border: 1px solid #e5e7eb; border-radius: 8px; background: #fff;">
                            <div class="checkbox-member-title" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; font-weight: 600;">
                                <span>Anggota Piket</span>
                                <span class="selected-count kelola-badge kelola-badge-danger">0/4</span>
                            </div>
                            <div class="checkbox-member-grid">
                                @foreach ($users as $user)
                                    <label class="member-checkbox">
                                        <input type="checkbox" name="members[]" value="{{ $user->id }}"
                                               @checked(in_array((string) $user->id, $oldEditMembers, true))>
                                        <span>{{ $user->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="kelola-modal-footer modal-actions">
                        <button type="button" class="kelola-btn kelola-btn-cancel cancel-btn" data-close-modal="editRosterModal-{{ $roster->id }}">Batal</button>
                        <button type="submit" class="kelola-btn kelola-btn-primary save-roster-btn">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach
@endif

{{-- MODAL KONFIRMASI HAPUS --}}
@if ($can('duty_roster.delete'))
    <div class="delete-confirm-overlay kelola-modal-overlay" id="deleteConfirmModal" aria-hidden="true">
        <div class="delete-confirm-backdrop" data-close-delete></div>
        <section class="delete-confirm-card kelola-modal kelola-modal-sm" role="dialog" aria-modal="true">
            <div class="delete-confirm-heading kelola-modal-header" style="background: var(--kelola-danger);">
                <h2 id="deleteConfirmTitle">
                    <span aria-hidden="true">⚠️</span> Hapus Data?
                </h2>
                <button type="button" class="delete-confirm-close kelola-modal-close" data-close-delete aria-label="Tutup">×</button>
            </div>

            <div class="delete-confirm-body kelola-modal-body" style="text-align: center;">
                <p style="margin: 0; font-size: 15px; color: #222;">Apakah Anda yakin ingin menghapus jadwal piket ini?</p>
            </div>

            <div class="delete-confirm-actions kelola-modal-footer">
                <button type="button" class="kelola-btn kelola-btn-cancel" data-close-delete>Batal</button>
                <button type="button" class="kelola-btn kelola-btn-danger" id="confirmDeleteRoster">Ya, Lanjutkan</button>
            </div>
        </section>
    </div>

    <form id="actualDeleteRosterForm" method="POST" action="" hidden>
        @csrf
        @method('DELETE')
    </form>
@endif

<script src="{{ asset('js/duty-roster.js') }}" defer></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        @if ($errors->any())
            @if (!old('roster_id'))
                if (typeof showRosterModal === 'function') {
                    showRosterModal('rosterModal');
                }
            @else
                if (typeof showRosterModal === 'function') {
                    showRosterModal('editRosterModal-{{ old('roster_id') }}');
                }
            @endif
        @endif
    });
</script>

</body>
</html>