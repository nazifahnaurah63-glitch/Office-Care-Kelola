<link rel="stylesheet" href="{{ asset('css/partials/sidebar.css') }}">

<aside class="sidebar">
    <div class="side-brand">
        <!-- Bungkus logo dan teks dalam satu div agar aman berdampingan dengan tombol -->
        <div class="side-brand-content">
            <img src="{{ asset('images/Logo.PNG.png') }}" alt="Logo Kelola">
            <div class="side-brand-text">
                <h2>Kelola Apps</h2>
                <span>Office Care</span>
            </div>
        </div>

        <button
            type="button"
            class="sidebar-toggle"
            id="sidebarToggle"
            aria-label="Perkecil sidebar"
            aria-expanded="true"
            title="Perkecil sidebar"
        >
            <svg xmlns="http://www.w3.org/2000/svg"
                width="20" height="20"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="1.8"
                stroke-linecap="round"
                stroke-linejoin="round">
                <rect x="3" y="3" width="18" height="18" rx="2"/>
                <path d="M9 3v18"/>
                <path d="m14 9 3 3-3 3"/>
            </svg>
        </button>
    </div>

    @php
        $currentRole = auth()->user()->role;

        $can = fn ($permission) =>
            $currentRole && $currentRole->hasPermission($permission);
    @endphp

    <nav class="menu">
        @if ($can('dashboard.view'))
            <a href="{{ url('/dashboard') }}" class="menu-item {{ request()->is('dashboard') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="7" height="7" rx="1.5"/>
                    <rect x="14" y="3" width="7" height="7" rx="1.5"/>
                    <rect x="3" y="14" width="7" height="7" rx="1.5"/>
                    <rect x="14" y="14" width="7" height="7" rx="1.5"/>
                </svg>
                <span>Dashboard</span>
            </a>
        @endif

        @if ($can('users.view') || $can('roles.view'))
            <a href="{{ route('employee-management.index') }}" class="menu-item {{ request()->routeIs('employee-management.*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="10" cy="7" r="4"/>
                    <path d="M3 21v-2a7 7 0 0 1 14 0v2H3"/>
                    <path d="M19 8v6M16 11h6"/>
                </svg>
                <span>Employee Management</span> 
            </a>
        @endif

        <p class="menu-label">Menu</p>
        
        @if ($can('duty_roster.view'))
            <a href="{{ route('duty-roster.index') }}" class="menu-item {{ request()->routeIs('duty-roster.*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="5" width="18" height="16" rx="2"/>
                    <path d="M16 3v4M8 3v4M3 10h18"/>
                    <path d="m8 15 3 3 5-5"/>
                </svg>
                <span>Duty Roster</span>
            </a>
        @endif

        @if ($can('areas.view'))
            <a href="{{ route('areas.index') }}" class="menu-item {{ request()->routeIs('areas.*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 10c0 5-8 12-8 12S4 15 4 10a8 8 0 1 1 16 0Z"/>
                    <circle cx="12" cy="10" r="3"/>
                </svg>
                <span>Area Management</span>
            </a>
        @endif

        @if ($can('cleaning_tasks.view'))
            <a href="{{ route('cleaning-tasks.index') }}" class="menu-item {{ request()->routeIs('cleaning-tasks.*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 6h12M9 12h12M9 18h12"/>
                    <path d="M3 5h2v2H3zM3 11h2v2H3zM3 17h2v2H3z"/>
                </svg>
                <span>Cleaning Task</span> 
            </a>
        @endif

        @if ($can('cleaning_activities.view'))
            <a href="{{ route('cleaning-activities.index') }}" class="menu-item {{ request()->routeIs('cleaning-activities*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="2.5" y="2.5" width="19" height="19" rx="4"/>
                    <path d="M6 7h5l7 10h-5L6 7Z"/>
                </svg>
                <span>Cleaning Activities</span>
            </a>
        @endif

        @if ($can('announcements.view'))
            <a href="#" class="menu-item">
               <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 8.5C3 8.5 2 10 2 12s1 3.5 3 3.5"/>
                    <path d="M5 8.5C10 8.5 15 6.5 20 4v16c-5-2.5-10-4.5-15-4.5z"/>
                    <path d="M5 15.5 6 20a1.5 1.5 0 0 0 3 0v-4.8"/>
                    <path d="M20 10h1a2 2 0 0 1 0 4h-1"/>
                    <path d="m22 7 1.5-1.5"/>
                    <path d="m22 17 1.5 1.5"/>
                    <path d="M23 12h1"/>
                </svg>
                <span>Announcement</span>
            </a>
        @endif
    </nav>
</aside>


<script>
document.addEventListener('DOMContentLoaded', function () {
    const app = document.querySelector('.app');
    const toggle = document.getElementById('sidebarToggle');

    if (!app || !toggle) return;

    const savedState = localStorage.getItem('officeCareSidebarCollapsed');

    if (savedState === 'true') {
        app.classList.add('sidebar-collapsed');
    }

    function updateToggle() {
        const collapsed = app.classList.contains('sidebar-collapsed');

        toggle.setAttribute('aria-expanded', String(!collapsed));
        toggle.setAttribute(
            'aria-label',
            collapsed ? 'Perlebar sidebar' : 'Perkecil sidebar'
        );
        toggle.setAttribute(
            'title',
            collapsed ? 'Perlebar sidebar' : 'Perkecil sidebar'
        );
    }

    toggle.addEventListener('click', function () {
        app.classList.toggle('sidebar-collapsed');

        localStorage.setItem(
            'officeCareSidebarCollapsed',
            app.classList.contains('sidebar-collapsed')
        );

        updateToggle();
    });

    updateToggle();
});
</script>