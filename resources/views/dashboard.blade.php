
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Office Care Kelola</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body>
<div class="app">

    <aside class="sidebar">
        <div class="side-brand">
            <img src="{{ asset('images/Logo.PNG.png') }}" alt="Logo Kelola">
            <div>
                <h2>Kelola Apps</h2>
                <span>Office Care</span>
            </div>
        </div>

        <nav class="menu">
            <a href="{{ url('/dashboard') }}" class="menu-item active">
                <span>▦</span> Dashboard <b>›</b>
            </a>
            <a href="#" class="menu-item"><span>♙</span> Employee Management</a>
            <p class="menu-label">Menu</p>
            <a href="#" class="menu-item"><span>▣</span> Duty Roster</a>
            <a href="#" class="menu-item"><span>♧</span> Area Management</a>
            <a href="#" class="menu-item"><span>☷</span> Cleaning Task Management</a>
            <a href="#" class="menu-item"><span>◩</span> Cleaning Activities</a>
            <a href="#" class="menu-item"><span>⚑</span> Announcement</a>
        </nav>
    </aside>

    <main class="main-content">
        <header class="topbar">
            <div class="top-logo">
                <img src="{{ asset('images/Logo.PNG.png') }}" alt="Logo">
                <div class="top-logo-text">
                    <strong>Kelola.biz</strong>
                    <small>K E L O L A &nbsp; A P P S</small>
                </div>
            </div>

            <div class="top-actions">
                <input type="search" placeholder="⌕  Search Modules....">
                <span class="notification">♧</span>
                <div class="avatar">K</div>
            </div>
        </header>

        <section class="page-heading">
            <div class="heading-icon">♧</div>
            <div>
                <h1>Dashboard</h1>
                <p>Ringkasan aktivitas kebersihan kantor</p>
            </div>
        </section>

        
<section class="stats">
    <div class="stat-card green">
        <div class="stat-icon">♙</div>
        <div>
            <strong>10</strong>
            <p>Karyawan hadir</p>
        </div>
    </div>

    <div class="stat-card blue">
        <div class="stat-icon">☷</div>
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
    <!-- Progress Kebersihan -->
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

    <!-- Aktivitas Terbaru -->
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
        <p>Belum ada aktivitas terbaru.</p>
    @endforelse
</div>
    </article>

    <!-- Tugas Hari Ini -->
    <article class="panel">
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
                            <td colspan="3">
                                Belum ada tugas untuk hari ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </article>

    <!-- Informasi -->
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
            <div><a href="#">Whatsapp</a><a href="#">Email</a><a href="#">Kebijakan Privasi</a><a href="#">Legal Cookie</a></div>
        </footer>
    </main>
</div>
</body>
</html>
