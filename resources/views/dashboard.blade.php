<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Office Care Kelola</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/design-system.css') }}">
</head>
<body>
<div class="app">

    @include('partials.sidebar')

    <main class="main-content">

        @include('partials.topbar')

        <section class="kelola-page-heading">
            <div class="kelola-heading-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="7" height="7" rx="1.5"/>
                    <rect x="14" y="3" width="7" height="7" rx="1.5"/>
                    <rect x="3" y="14" width="7" height="7" rx="1.5"/>
                    <rect x="14" y="14" width="7" height="7" rx="1.5"/>
                </svg>
            </div>
            <div class="kelola-heading-text">
                <h1>Dashboard</h1>
                <p>Ringkasan aktivitas kebersihan kantor</p>
            </div>
        </section>

        <section class="stats">
            <div class="stat-card green">
                <div class="stat-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="10" cy="7" r="4"/>
                        <path d="M20 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                </div>
                <div>
                    <strong>10</strong>
                    <p>Total Karyawan</p>
                </div>
            </div>

            <div class="stat-card blue">
                <div class="stat-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 6h12M9 12h12M9 18h12"/>
                        <rect x="3" y="5" width="2" height="2"/>
                        <rect x="3" y="11" width="2" height="2"/>
                        <rect x="3" y="17" width="2" height="2"/>
                    </svg>
                </div>
                <div>
                    <strong>{{ $completedTasks }}</strong>
                    <p>Tugas Selesai</p>
                </div>
            </div>

            <div class="stat-card orange">
                <div class="stat-icon">
                   <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                   <circle cx="12" cy="12" r="9"/>
                   <path d="M12 7v5l3 2"/>
                 </svg>
                </div>
                <div>
                    <strong>{{ $pendingTasks }}</strong>
                    <p>Tugas Pending</p>
                </div>
            </div>

            <div class="stat-card red">
                <div class="stat-icon">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M10.3 3.9 2.5 17.5a2 2 0 0 0 1.7 3h15.6a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0Z"/>
                  <path d="M12 9v4"/>
                  <path d="M12 17h.01"/>
                 </svg>
                </div>
                <div>
                    <strong>10</strong>
                    <p>Area Perlu Perhatian</p>
                </div>
            </div>
        </section>

        <section class="dashboard-grid">
            <article class="panel">
                <div class="panel-heading">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 19V13"/>
                        <path d="M9 19V9"/>
                        <path d="M14 19V5"/>
                        <path d="M19 19V2"/>
                    </svg>
                    <div>
                    <p>Progress Kebersihan</p>
                    </div>
                </div>

                <div class="progress-content">
                    <div class="donut" style="--progress: {{ $progress }}%;">
                        <div>
                            <strong>{{ $progress }}%</strong>
                            <small>Selesai</small>
                        </div>
                    </div>

                    <div class="legend">
                        <p>
                            <i class="dot completed"></i>
                            Selesai
                            <b>{{ $progress }}%</b>
                        </p>

                        <p>
                            <i class="dot pending"></i>
                            Pending
                            <b>{{ 100 - $progress }}%</b>
                        </p>
                    </div>
                </div>
            </article>

            <article class="panel">
                <div class="panel-heading">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="5" width="18" height="16" rx="1.5"/>
                            <path d="M7 3v4"/>
                            <path d="M17 3v4"/>
                            <path d="M3 9h18"/>
                            <path d="M7 12h2"/>
                            <path d="M11 12h2"/>
                            <path d="M15 12h2"/>
                            <path d="M7 16h2"/>
                            <path d="M11 16h2"/>
                            <path d="M15 16h2"/>
                        </svg>
                    <div>
                    <p>Aktivitas Terbaru</p>
                    </div>
                </div>

                <div class="activity-list">
                    @forelse ($recentItems as $item)
                        <div class="activity">
                            <i></i>

                            <div>
                                <strong>{{ $item->name }}</strong>
                                <p>{{ $item->description }}</p>
                            </div>

                            <time>
                                {{ $item->time?->format('H:i') ?? '-' }}
                            </time>
                        </div>
                    @empty
                        <p style="padding: 20px; color: #666;">Belum ada aktivitas terbaru.</p>
                    @endforelse
                </div>
            </article>

            <article class="panel today-tasks-panel">
                <div class="panel-heading">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="9"/>
                        <path d="m8 12 3 3 5-6"/>
                    </svg>
                    <div>
                        <p>Tugas Hari Ini</p>
                    </div>
                </div>

                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Nama Tugas</th>
                                <th>Area</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($todayTasks as $task)
                                <tr>
                                    <td>{{ $task->task_name }}</td>
                                    <td>{{ $task->area }}</td>
                                    <td>
                                        @if ($task->status === 'completed')
                                            <span class="status done">
                                                ✓ Selesai
                                            </span>
                                        @else
                                            <span class="status wait">
                                                ◷ Pending
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" style="text-align: center; padding: 20px;">
                                        Belum ada tugas untuk hari ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </article>

            <article class="panel">
                <div class="panel-heading">
                 <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 8.5C3 8.5 2 10 2 12s1 3.5 3 3.5"/>
                    <path d="M5 8.5C10 8.5 15 6.5 20 4v16c-5-2.5-10-4.5-15-4.5z"/>
                    <path d="M5 15.5 6 20a1.5 1.5 0 0 0 3 0v-4.8"/>
                    <path d="M20 10h1a2 2 0 0 1 0 4h-1"/>
                    <path d="m22 7 1.5-1.5"/>
                    <path d="m22 17 1.5 1.5"/>
                    <path d="M23 12h1"/>
                </svg>
                    <div>
                        <p>Pengumuman</p>
                    </div>
                </div>

                @forelse ($announcements as $announcement)
                    <div class="announcement">
                        <div class="announcement-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="84" height="84" viewBox="0 0 72 64" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M14 15 C7 15 4 20 4 26 C4 32 7 37 14 37"/>
                                    <path d="M14 15 C27 13 43 8 53 3"/>
                                    <path d="M14 37 C27 35 43 40 53 45"/>
                                    <path d="M14 15 V37"/>
                                    <path d="M53 3 C58 3 60 13 60 24 C60 35 58 45 53 45"/>
                                    <path d="M53 3 C49 13 49 35 53 45"/>
                                    <path d="M53 19 C57 19 58 21 58 24 C58 27 57 29 53 29" fill="currentColor"/>
                                    <path d="M14 37 C18 39 19 42 19 46 V58 C19 62 28 62 28 58 V38"/>
                                    <path d="M64 12 L68 8"/>
                                    <path d="M65 24 L70 24"/>
                                    <path d="M64 36 L68 40"/>
                                </svg>
                        </div>

                        <div>
                            <h3>{{ $announcement->title }}</h3>
                            <p>{{ $announcement->content }}</p>

                            <div class="announcement-date">
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="5" width="18" height="16" rx="1.5"/>
                            <path d="M7 3v4"/>
                            <path d="M17 3v4"/>
                            <path d="M3 9h18"/>
                            <path d="M7 12h2"/>
                            <path d="M11 12h2"/>
                            <path d="M15 12h2"/>
                            <path d="M7 16h2"/>
                            <path d="M11 16h2"/>
                            <path d="M15 16h2"/>
                        </svg> &nbsp;
                                {{ $announcement->announcement_date
                                    ? $announcement->announcement_date->format('d F Y')
                                    : '-' }}
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="announcement">
                        <div class="announcement-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="84" height="84" viewBox="0 0 72 64" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 15 C7 15 4 20 4 26 C4 32 7 37 14 37"/>
                            <path d="M14 15 C27 13 43 8 53 3"/>
                            <path d="M14 37 C27 35 43 40 53 45"/>
                            <path d="M14 15 V37"/>
                            <path d="M53 3 C58 3 60 13 60 24 C60 35 58 45 53 45"/>
                            <path d="M53 3 C49 13 49 35 53 45"/>
                            <path d="M53 19 C57 19 58 21 58 24 C58 27 57 29 53 29" fill="currentColor"/>
                            <path d="M14 37 C18 39 19 42 19 46 V58 C19 62 28 62 28 58 V38"/>
                            <path d="M64 12 L68 8"/>
                            <path d="M65 24 L70 24"/>
                            <path d="M64 36 L68 40"/>
                        </svg>
                        </div>
                        <div>
                            <h3>Pengumuman</h3>
                            <p>Belum ada pengumuman aktif.</p>
                        </div>
                    </div>
                @endforelse
            </article>
        </section>

        @include('partials.footer')

    </main>
</div>
</body>
</html>