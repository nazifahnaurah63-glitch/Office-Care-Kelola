<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password | Kelola Apps</title>
    <link rel="stylesheet" href="{{ asset('css/forgot-password.css') }}">
</head>
<body>
    <main class="forgot-page">
        <header class="brand">
            <img
                src="{{ asset('images/Logo.PNG.png') }}"
                alt="Logo Office Care Kelola"
                class="brand-image"
            >
            <h1>Kelola Apps</h1>
        </header>

        <section class="forgot-card">
            <h2>Lupa Password</h2>
            <p class="subtitle">
                Masukkan email Anda dan kami akan mengirimkan link untuk mereset password.
            </p>

            <form action="#" method="POST">
                @csrf

                <div class="form-group">
                    <label for="email">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="Masukkan email Anda"
                        autocomplete="email"
                        required
                    >
                </div>

                <button type="submit" class="reset-button">
                    Kirim Link Reset Password
                </button>
            </form>

            <div class="back-login">
                <a href="{{ url('/login') }}">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="20"
                        height="20"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <path d="M19 12H5"/>
                        <path d="M12 19l-7-7 7-7"/>
                    </svg>
                    Kembali ke Login
                </a>
            </div>
        </section>
    </main>
</body>
</html>