<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes" />
    <meta name="description" content="Sistem Informasi Manajemen Mutu, PPI, dan Dokumen Rumah Sakit" />
    <meta name="author" content="Much Roziq, S.Kom" />
    <title>RS AIRLANGGA V2 · Masuk</title>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />

    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            /* -- Warna dasar: navy klinis + emerald + aksen amber hangat -- */
            --navy-950: #060B14;
            --navy-900: #0A1628;
            --navy-800: #101F35;
            --navy-700: #17304D;
            --paper: #FBFAF7;
            --paper-panel: #FFFFFF;
            --ink-900: #10151F;
            --ink-600: #4B5665;
            --ink-400: #8891A0;
            --ink-300: #C7CDD6;
            --ink-200: #E4E7EC;
            --ink-100: #F1F3F6;
            --emerald: #12B886;
            --emerald-dark: #0D9268;
            --emerald-glow: rgba(18, 184, 134, 0.20);
            --amber: #F0A93F;
            --danger: #DC3B57;
            --danger-bg: #FDF1F3;
            --r-xl: 28px;
            --r-lg: 18px;
            --r-md: 13px;
            --r-sm: 9px;
            --font-display: 'Plus Jakarta Sans', 'Inter', sans-serif;
            --font-body: 'Inter', sans-serif;
            --ease: 0.3s cubic-bezier(0.25, 1, 0.5, 1);
        }

        html, body {
            height: 100%;
            font-family: var(--font-body);
            background: var(--paper);
            color: var(--ink-900);
            -webkit-font-smoothing: antialiased;
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { animation-duration: 0.001ms !important; transition-duration: 0.001ms !important; }
        }

        .auth-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px;
            background: var(--paper);
        }

        .auth-card {
            width: 100%;
            max-width: 1180px;
            min-height: 660px;
            display: grid;
            grid-template-columns: 1.05fr 1fr;
            background: var(--paper-panel);
            border-radius: var(--r-xl);
            box-shadow: 0 40px 80px -32px rgba(6, 11, 20, 0.35), 0 0 0 1px rgba(16, 21, 31, 0.04);
            overflow: hidden;
        }

        /* ================= BRAND SIDE ================= */
        .brand-side {
            position: relative;
            background: linear-gradient(172deg, var(--navy-900) 0%, var(--navy-950) 100%);
            padding: 52px 48px 40px;
            display: flex;
            flex-direction: column;
            color: #EEF2F6;
            overflow: hidden;
        }

        /* Motif garis EKG halus — satu momen visual khas, bukan dekorasi acak */
        .ecg-line {
            position: absolute;
            left: 0;
            right: 0;
            top: 46%;
            height: 120px;
            opacity: 0.55;
            pointer-events: none;
        }
        .ecg-line path {
            fill: none;
            stroke: url(#ecgGradient);
            stroke-width: 1.6;
            stroke-linecap: round;
            stroke-linejoin: round;
            stroke-dasharray: 1400;
            stroke-dashoffset: 1400;
            animation: draw-ecg 3.6s ease-out 0.3s forwards;
        }
        @keyframes draw-ecg {
            to { stroke-dashoffset: 0; }
        }

        .brand-side::after {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse 620px 420px at 12% 0%, rgba(18, 184, 134, 0.14), transparent 60%);
            pointer-events: none;
        }

        .crest-row {
            position: relative;
            z-index: 2;
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 44px;
        }
        .crest-mark {
            width: 46px;
            height: 46px;
            border-radius: var(--r-sm);
            background: rgba(18, 184, 134, 0.14);
            border: 1px solid rgba(18, 184, 134, 0.28);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .crest-mark svg { width: 24px; height: 24px; stroke: #4CDBA8; fill: none; stroke-width: 2; }
        .crest-text h1 {
            font-family: var(--font-display);
            font-size: 17px;
            font-weight: 700;
            letter-spacing: 0.01em;
            color: #FFFFFF;
        }
        .crest-text p { font-size: 12.5px; color: var(--ink-400); margin-top: 1px; }

        .brand-headline { position: relative; z-index: 2; margin-bottom: 30px; }
        .brand-headline h2 {
            font-family: var(--font-display);
            font-size: clamp(30px, 3vw, 37px);
            font-weight: 800;
            line-height: 1.22;
            color: #FFFFFF;
            max-width: 15ch;
        }
        .brand-headline p {
            font-size: 14.5px;
            line-height: 1.75;
            color: #A8B3C2;
            max-width: 38ch;
            margin-top: 14px;
        }

        /* Panel modul — daftar nyata, bukan kartu generik seragam */
        .module-panel {
            position: relative;
            z-index: 2;
            margin-top: auto;
            padding-top: 8px;
        }
        .module-row {
            display: flex;
            align-items: center;
            gap: 13px;
            padding: 13px 0;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
        }
        .module-row:first-child { border-top: 1px solid rgba(255, 255, 255, 0.08); }
        .module-dot {
            width: 7px; height: 7px; border-radius: 50%;
            background: #4CDBA8; flex-shrink: 0;
        }
        .module-row span.name { font-size: 13.5px; font-weight: 600; color: #F1F3F6; }
        .module-row span.desc { font-size: 12px; color: var(--ink-400); margin-left: auto; text-align: right; }

        /* E-Office — kartu utama yang dipertahankan, kaca premium */
        .eoffice-card {
            position: relative;
            z-index: 2;
            display: flex;
            gap: 15px;
            align-items: flex-start;
            background: rgba(255, 255, 255, 0.06);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border: 1px solid rgba(255, 255, 255, 0.14);
            border-radius: var(--r-lg);
            padding: 18px 20px;
            margin-bottom: 22px;
            box-shadow: 0 18px 40px -18px rgba(0, 0, 0, 0.5);
        }
        .eoffice-icon {
            width: 38px; height: 38px; border-radius: var(--r-sm);
            background: linear-gradient(150deg, var(--emerald), var(--emerald-dark));
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        .eoffice-icon svg { width: 19px; height: 19px; stroke: #FFFFFF; fill: none; stroke-width: 2; }
        .eoffice-text strong { display: block; font-size: 13.5px; font-weight: 700; color: #FFFFFF; margin-bottom: 3px; }
        .eoffice-text span { font-size: 12px; line-height: 1.6; color: #ADB8C6; }

        .brand-foot {
            position: relative; z-index: 2;
            display: flex; align-items: center; gap: 8px;
            font-size: 11.5px; color: #6C7A8C;
            padding-top: 18px;
        }
        .pulse-dot {
            width: 6px; height: 6px; border-radius: 50%; background: #4CDBA8;
            box-shadow: 0 0 0 0 rgba(76, 219, 168, 0.6);
            animation: ping 2.2s ease-out infinite;
        }
        @keyframes ping {
            0% { box-shadow: 0 0 0 0 rgba(76, 219, 168, 0.5); }
            70% { box-shadow: 0 0 0 7px rgba(76, 219, 168, 0); }
            100% { box-shadow: 0 0 0 0 rgba(76, 219, 168, 0); }
        }

        /* ================= FORM SIDE ================= */
        .form-side {
            padding: 56px 54px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .form-container { max-width: 372px; margin: 0 auto; width: 100%; }

        .form-header { margin-bottom: 30px; }
        .form-header span.tag { font-size: 12.5px; font-weight: 600; color: var(--emerald-dark); }
        .form-header h3 {
            font-family: var(--font-display);
            font-size: 27px; font-weight: 800; color: var(--ink-900);
            margin-top: 6px;
        }
        .form-header p { font-size: 13.5px; color: var(--ink-600); margin-top: 5px; }

        .flash-message:not(:empty) {
            background: var(--danger-bg);
            border: 1px solid rgba(220, 59, 87, 0.15);
            color: var(--danger);
            padding: 12px 15px;
            border-radius: var(--r-sm);
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 22px;
        }

        .field { margin-bottom: 19px; }
        .field label {
            display: block; font-size: 12.5px; font-weight: 700;
            color: var(--ink-900); margin-bottom: 7px;
        }

        .input-wrap {
            display: flex; align-items: center;
            background: var(--ink-100);
            border: 1.5px solid var(--ink-100);
            border-radius: var(--r-md);
            transition: border-color var(--ease), background var(--ease), box-shadow var(--ease);
        }
        .input-wrap:hover { border-color: var(--ink-300); }
        .input-wrap:focus-within {
            background: #FFFFFF;
            border-color: var(--emerald);
            box-shadow: 0 0 0 4px var(--emerald-glow);
        }
        .input-wrap .icon-left { width: 17px; height: 17px; margin-left: 15px; color: var(--ink-400); flex-shrink: 0; }
        .input-wrap input {
            width: 100%; border: none; background: transparent;
            padding: 13px 15px; font-size: 14px; color: var(--ink-900);
            outline: none; font-family: var(--font-body);
        }
        .input-wrap input::placeholder { color: var(--ink-400); }

        .toggle-pass {
            background: none; border: none; padding: 0 15px;
            color: var(--ink-400); cursor: pointer;
            display: flex; align-items: center;
            transition: color var(--ease);
        }
        .toggle-pass:hover { color: var(--emerald-dark); }
        .toggle-pass svg { width: 19px; height: 19px; }

        .field-error { display: block; font-size: 12px; font-weight: 600; color: var(--danger); margin-top: 6px; }

        .form-options { display: flex; justify-content: space-between; align-items: center; margin: 4px 0 24px; }
        .remember-me {
            display: flex; align-items: center; gap: 9px;
            font-size: 13px; font-weight: 600; color: var(--ink-600); cursor: pointer;
        }
        .remember-me input[type="checkbox"] {
            appearance: none; -webkit-appearance: none;
            width: 18px; height: 18px;
            border: 1.5px solid var(--ink-300); border-radius: 6px;
            background: #FFFFFF; cursor: pointer; position: relative;
            transition: background var(--ease), border-color var(--ease);
        }
        .remember-me input[type="checkbox"]:checked { background: var(--emerald); border-color: var(--emerald); }
        .remember-me input[type="checkbox"]:checked::after {
            content: ''; position: absolute; left: 5px; top: 2px;
            width: 4px; height: 8px; border: solid #FFFFFF;
            border-width: 0 2px 2px 0; transform: rotate(45deg);
        }

        .btn-login {
            width: 100%; border: none; border-radius: var(--r-md);
            padding: 14.5px; background: linear-gradient(135deg, var(--emerald), var(--emerald-dark));
            color: #FFFFFF; font-family: var(--font-display);
            font-size: 14.5px; font-weight: 700; cursor: pointer;
            box-shadow: 0 10px 24px -8px rgba(13, 146, 104, 0.5);
            transition: transform var(--ease), box-shadow var(--ease);
        }
        .btn-login:hover { transform: translateY(-2px); box-shadow: 0 16px 32px -10px rgba(13, 146, 104, 0.55); }
        .btn-login:active { transform: translateY(0); }
        .btn-login:focus-visible { outline: 2px solid var(--navy-900); outline-offset: 3px; }

        .form-divider { border: none; border-top: 1px solid var(--ink-200); margin: 26px 0 18px; }
        .form-footer { text-align: center; font-size: 12.5px; color: var(--ink-400); line-height: 1.6; }
        .form-footer .mono { display: block; font-size: 11px; color: var(--ink-300); margin-top: 4px; }

        a, button { font-family: inherit; }
        :focus-visible { outline: 2px solid var(--emerald-dark); outline-offset: 2px; }

        /* ================= RESPONSIVE ================= */
        @media (max-width: 900px) {
            .auth-card { grid-template-columns: 1fr; max-width: 480px; min-height: 0; }
            .form-side { order: 1; padding: 40px 30px 30px; }
            .brand-side { order: 2; padding: 34px 30px; }
            .module-panel { margin-top: 26px; }
            .ecg-line { display: none; }
        }

        @media (max-width: 520px) {
            .auth-wrapper { padding: 0; align-items: stretch; }
            .auth-card { border-radius: 0; max-width: 100%; box-shadow: none; }
            .form-side { padding: 32px 22px 24px; }
            .brand-side { padding: 28px 22px; }
        }
    </style>
</head>
<body>

    <div class="auth-wrapper">
        <div class="auth-card">

            <!-- ================= FORM SIDE ================= -->
            <div class="form-side">
                <div class="form-container">
                    <div class="form-header">
                        <span class="tag">Portal internal</span>
                        <h3>Masuk ke akun Anda</h3>
                        <p>Gunakan kredensial staf RS Airlangga untuk melanjutkan.</p>
                    </div>

                    <div class="flash-message">
                        <?= $this->session->flashdata('message'); ?>
                    </div>

                    <form class="user" method="POST" action="">
                        <!-- Email -->
                        <div class="field">
                            <label for="emailInput">Alamat email</label>
                            <div class="input-wrap">
                                <svg class="icon-left" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                                    <polyline points="22,6 12,13 2,6" />
                                </svg>
                                <input type="text" id="emailInput" placeholder="nama@rsairlangga.co.id" autocomplete="off" name="email" value="<?= set_value('email'); ?>" />
                            </div>
                            <?= form_error('email', '<small class="field-error">', '</small>'); ?>
                        </div>

                        <!-- Password -->
                        <div class="field">
                            <label for="passwordInput">Kata sandi</label>
                            <div class="input-wrap">
                                <svg class="icon-left" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                                </svg>
                                <input type="password" id="passwordInput" name="password" placeholder="••••••••" autocomplete="off" />
                                <button type="button" class="toggle-pass" id="togglePass" aria-label="Tampilkan kata sandi">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" id="eyeIcon">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                        <circle cx="12" cy="12" r="3" />
                                    </svg>
                                </button>
                            </div>
                            <?= form_error('password', '<small class="field-error">', '</small>'); ?>
                        </div>

                        <!-- Options -->
                        <div class="form-options">
                            <label class="remember-me" for="customCheck">
                                <input type="checkbox" id="customCheck" />
                                Ingat sesi saya
                            </label>
                        </div>

                        <!-- Submit -->
                        <button type="submit" class="btn-login">Masuk ke sistem</button>
                    </form>

                    <hr class="form-divider" />
                    <div class="form-footer">
                        Kendalikan akses medis secara aman. Hubungi Tim IT jika ada kendala.
                        <span class="mono">RSA-SIM · Build V2.0</span>
                    </div>
                </div>
            </div>

            <!-- ================= BRAND SIDE ================= -->
            <div class="brand-side">
                <svg class="ecg-line" viewBox="0 0 600 120" preserveAspectRatio="none" aria-hidden="true">
                    <defs>
                        <linearGradient id="ecgGradient" x1="0" y1="0" x2="1" y2="0">
                            <stop offset="0%" stop-color="#4CDBA8" stop-opacity="0" />
                            <stop offset="20%" stop-color="#4CDBA8" stop-opacity="0.9" />
                            <stop offset="80%" stop-color="#4CDBA8" stop-opacity="0.9" />
                            <stop offset="100%" stop-color="#4CDBA8" stop-opacity="0" />
                        </linearGradient>
                    </defs>
                    <path d="M0,60 L120,60 L145,60 L160,20 L180,100 L200,60 L230,60 L260,60 L280,40 L300,60 L340,60 L360,60 L385,20 L400,100 L420,60 L450,60 L600,60" />
                </svg>

                <div class="crest-row">
                    <div class="crest-mark">
                        <svg viewBox="0 0 52 52" xmlns="http://www.w3.org/2000/svg">
                            <path d="M26 4 L46 11 V25 C46 37 37.5 45.5 26 48 C14.5 45.5 6 37 6 25 V11 Z" />
                            <path d="M26 16 V32 M18 24 H34" stroke="#4CDBA8" stroke-width="2" fill="none" />
                        </svg>
                    </div>
                    <div class="crest-text">
                        <h1>RS AIRLANGGA</h1>
                        <p>Jombang, Jawa Timur</p>
                    </div>
                </div>

                <div class="brand-headline">
                    <h2>Satu portal untuk semua sistem RS Airlangga</h2>
                    <p>Akses MUKESPI, SIDOKTA, SIPARDI, SIASSET, dan aplikasi internal lain dari satu pintu masuk.</p>
                </div>

                <div class="eoffice-card">
                    <div class="eoffice-icon">
                        <svg viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                            <polyline points="22,6 12,13 2,6" />
                            <path d="M8 11l4 3 4-3" />
                        </svg>
                    </div>
                    <div class="eoffice-text">
                        <strong>E-Office RSA</strong>
                        <span>Manajemen surat internal & eksternal — pengajuan, penomoran, disposisi, tanda tangan, dan pelacakan dokumen.</span>
                    </div>
                </div>

                <div class="module-panel">
                    <div class="module-row">
                        <span class="module-dot"></span>
                        <span class="name">MUKESPI</span>
                        <span class="desc">Mutu, insiden & PPI</span>
                    </div>
                    <div class="module-row">
                        <span class="module-dot"></span>
                        <span class="name">SIDOKTA</span>
                        <span class="desc">Dokumen unit terpadu</span>
                    </div>
                    <div class="module-row">
                        <span class="module-dot"></span>
                        <span class="name">SIPARDI</span>
                        <span class="desc">Penilaian & akreditasi</span>
                    </div>
                    <div class="module-row">
                        <span class="module-dot"></span>
                        <span class="name">SIASSET</span>
                        <span class="desc">Aset & maintenance</span>
                    </div>
                    <div class="module-row">
                        <span class="module-dot"></span>
                        <span class="name">SIMONIKA</span>
                        <span class="desc">Monitoring aplikasi Kemenkes</span>
                    </div>
                </div>

                <div class="brand-foot">
                    <span class="pulse-dot"></span>
                    Server online &nbsp;·&nbsp; &copy; 2026 Much Roziq, S.Kom
                </div>
            </div>

        </div>
    </div>

    <script>
        (function () {
            const toggle = document.getElementById('togglePass');
            const pass = document.getElementById('passwordInput');
            const eye = document.getElementById('eyeIcon');

            if (toggle && pass && eye) {
                toggle.addEventListener('click', function () {
                    const isPass = pass.getAttribute('type') === 'password';
                    pass.setAttribute('type', isPass ? 'text' : 'password');
                    this.setAttribute('aria-label', isPass ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi');

                    eye.innerHTML = isPass
                        ? '<path d="M17.94 17.94A10.94 10.94 0 0 1 12 20c-7 0-11-8-11-8a21.6 21.6 0 0 1 5.06-6.06M9.9 4.24A10.94 10.94 0 0 1 12 4c7 0 11 8 11 8a21.6 21.6 0 0 1-3.22 4.38M1 1l22 22"/><path d="M14.12 14.12A3 3 0 1 1 9.88 9.88"/>'
                        : '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
                });
            }
        })();
    </script>

</body>
</html>