
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Kelola Apps</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@700;800;900&display=swap" rel="stylesheet">
</head>
<body>
    <main class="login-page">
        <header class="brand">
           
<img
    src="{{ asset('images/Logo.PNG.png') }}"
    alt="Logo Office Care Kelola"
    class="brand-image"
>

            <h1>Kelola Apps</h1>
        </header>

        <section class="login-card">
            <h2>Welcome back</h2>
            <p class="subtitle">Sign in to your account to continue</p>

            <form action="#" method="POST">
                @csrf

             <div class="form-group">
    <label for="email">Email</label>
    <input
        type="email"
        id="email"
        name="email"
        placeholder="Masukkan email"
        autocomplete="username"
        required
    >
</div>   
<div class="form-group">
    <label for="password">Password</label>

    <div class="password-wrapper">
        <input
    type="password"
    id="password"
    name="password"
    placeholder="Masukkan password"
    required
>
        <button
    type="button"
    id="togglePassword"
    class="toggle-password"
    aria-label="Tampilkan password"
>
            <svg
                id="eyeIcon"
                xmlns="http://www.w3.org/2000/svg"
                width="21"
                height="21"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="1.8"
                stroke-linecap="round"
                stroke-linejoin="round"
            >
                <path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7-10-7-10-7z"/>
                <circle cx="12" cy="12" r="3"/>
            </svg>
        </button>
    </div>
</div>
</div>

                <div class="form-options">
                    <label class="remember">
                        <input type="checkbox" name="remember">
                        <span>Ingat saya</span>
                    </label>

                   <a href="{{ url('/forgot-password') }}" class="forgot-password">
    Lupa password?
</a>
                </div>

                <button type="submit" class="login-button">
                    Login
                </button>
            </form>
        </section>
    </main>

    <script>
document.addEventListener('DOMContentLoaded', function () {
    const passwordInput = document.getElementById('password');
    const toggleButton = document.getElementById('togglePassword');
    const eyeIcon = document.getElementById('eyeIcon');

    if (!passwordInput || !toggleButton || !eyeIcon) {
        console.error('Elemen password atau ikon mata tidak ditemukan.');
        return;
    }

    toggleButton.addEventListener('click', function () {
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';

            eyeIcon.innerHTML = `
                <path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7-10-7-10-7z"/>
                <circle cx="12" cy="12" r="3"/>
            `;

            toggleButton.setAttribute(
                'aria-label',
                'Sembunyikan password'
            );
        } else {
            passwordInput.type = 'password';

            eyeIcon.innerHTML = `
                <path d="M3 3l18 18"/>
                <path d="M10.6 5.1A10.8 10.8 0 0 1 12 5c6.4 0 10 7 10 7a14.5 14.5 0 0 1-3.1 3.8"/>
                <path d="M6.2 6.2C3.5 8 2 12 2 12s3.6 7 10 7a10.5 10.5 0 0 0 4-.8"/>
            `;

            toggleButton.setAttribute(
                'aria-label',
                'Tampilkan password'
            );
        }
    });
});
</script>
</body>
</html>
