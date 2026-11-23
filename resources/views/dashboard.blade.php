<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Office Care Kelola</title>
    <link rel="stylesheet" href="{{ asset('css/partials/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/partials/topbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/design-system.css') }}">
</head>
<body>
<div class="app">

    @include('partials.sidebar')

    <main class="main-content">

        @include('partials.topbar')

        <section class="page-heading">
            <div class="heading-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="7" height="7" rx="1.5"/>
                    <rect x="14" y="3" width="7" height="7" rx="1.5"/>
                    <rect x="3" y="14" width="7" height="7" rx="1.5"/>
                    <rect x="14" y="14" width="7" height="7" rx="1.5"/>
                </svg>
            </div>
            <div>
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
                    <p>Karyawan hadir</p>
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
                <div class="stat-icon">◷</div>
                <div>
                    <strong>{{ $pendingTasks }}</strong>
                    <p>Tugas Pending</p>
                </div>
            </div>

            <div class="stat-card red">
                <div class="stat-icon">⚠</div>
                <div>
                    <strong>10</strong>
                    <p>Area Perlu Perhatian</p>
                </div>
            </div>
        </section>

        <section class="dashboard-grid">
            <article class="panel">
                <div class="panel-heading">
                    <span>▥</span> Progress Kebersihan
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
                    <span>▦</span> Aktivitas Terbaru
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
                    <span>☑</span> Tugas Hari Ini
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
                    <span>⚑</span> Informasi
                </div>

                @forelse ($announcements as $announcement)
                    <div class="announcement">
                        <div class="announcement-icon">⚑</div>

                        <div>
                            <h3>{{ $announcement->title }}</h3>
                            <p>{{ $announcement->content }}</p>

                            <div class="announcement-date">
                                ▣ &nbsp;
                                {{ $announcement->announcement_date
                                    ? $announcement->announcement_date->format('d F Y')
                                    : '-' }}
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="announcement">
                        <div class="announcement-icon">⚑</div>
                        <div>
                            <h3>Pengumuman</h3>
                            <p>Belum ada pengumuman aktif.</p>
                        </div>
                    </div>
                @endforelse
            </article>
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
</body>
</html>