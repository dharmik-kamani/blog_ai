<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} — @yield('page-title', 'Welcome')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        *, *::before, *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Outfit', sans-serif;
        }

        html, body {
            height: 100%;
            overflow: hidden;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            background: #f4f4f5;
            position: relative;
        }

        /* ── Animated blobs ── */
        body::before,
        body::after {
            content: '';
            position: fixed;
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
        }

        body::before {
            width: 560px;
            height: 560px;
            background: radial-gradient(circle, rgba(24,24,27,0.06), transparent 70%);
            top: -160px;
            left: -100px;
            animation: blobA 9s ease-in-out infinite alternate;
        }

        body::after {
            width: 640px;
            height: 640px;
            background: radial-gradient(circle, rgba(24,24,27,0.04), transparent 70%);
            bottom: -220px;
            right: -160px;
            animation: blobB 11s ease-in-out infinite alternate;
        }

        @keyframes blobA {
            from { transform: translateY(0px);  }
            to   { transform: translateY(45px); }
        }

        @keyframes blobB {
            from { transform: translateX(0px);   }
            to   { transform: translateX(-55px); }
        }

        /* ── Card wrapper ── */
        .auth-card {
            position: relative;
            z-index: 1;
            width: 960px;
            height: 600px;
            display: flex;
            border-radius: 32px;
            overflow: hidden;
            background: #ffffff;
            box-shadow:
                0 4px 6px  rgba(24,24,27,0.02),
                0 12px 40px rgba(24,24,27,0.06),
                0 40px 80px rgba(0,0,0,0.08);
            margin: 20px;
        }

        /* ── Left panel ── */
        .left-panel {
            width: 42%;
            height: 100%;
            background: linear-gradient(150deg, #09090b 0%, #18181b 55%, #27272a 100%);
            padding: 52px 44px;
            color: #fff;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .blob {
            position: absolute;
            border-radius: 50%;
            background: rgba(255,255,255,0.08);
            pointer-events: none;
        }
        .blob-1 { width: 240px; height: 240px; top: -65px;  right: -65px;  }
        .blob-2 { width: 180px; height: 180px; bottom: -55px; left: -45px; }
        .blob-3 { width:  90px; height:  90px; top: 46%; right: 24px; opacity: 0.55; }

        .brand-name {
            font-size: 30px;
            font-weight: 800;
            letter-spacing: -0.5px;
            margin-bottom: 10px;
            position: relative;
            z-index: 2;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .brand-badge {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            background: rgba(255,255,255,0.18);
            border: 1px solid rgba(255,255,255,0.25);
            border-radius: 8px;
            padding: 3px 10px;
        }

        .brand-desc {
            font-size: 13.5px;
            line-height: 1.85;
            color: rgba(255,255,255,0.78);
            margin-bottom: 32px;
            position: relative;
            z-index: 2;
        }

        .feat {
            position: relative;
            z-index: 2;
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 16px;
            padding: 16px 20px;
            margin-bottom: 12px;
            transition: transform 0.3s ease, background 0.3s ease;
            backdrop-filter: blur(6px);
        }
        .feat:hover {
            transform: translateX(7px);
            background: rgba(255,255,255,0.15);
        }
        .feat h3 { font-size: 14.5px; font-weight: 700; margin-bottom: 3px; }
        .feat p  { font-size: 12.5px; color: rgba(255,255,255,0.73); }

        /* ── Right panel ── */
        .right-panel {
            width: 58%;
            height: 100%;
            position: relative;
            overflow: hidden;
            background: #fff;
        }

        /* ── Sliding form boxes ── */
        .form-box {
            position: absolute;
            inset: 0;                     /* top:0 right:0 bottom:0 left:0 */
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px 56px;
            transition: transform 0.65s cubic-bezier(0.77, 0, 0.18, 1);
        }

        .form-box.login    { transform: translateX(0); }
        .form-box.register { transform: translateX(100%); }

        .auth-card.show-register .form-box.login    { transform: translateX(-100%); }
        .auth-card.show-register .form-box.register { transform: translateX(0); }

        /* ── Form inner ── */
        .form-inner {
            width: 100%;
            max-width: 355px;
        }

        .form-inner h2 {
            font-size: 32px;
            font-weight: 800;
            color: #18181b;
            margin-bottom: 5px;
            letter-spacing: -0.5px;
        }

        .form-subtitle {
            font-size: 13.5px;
            color: #71717a;
            margin-bottom: 26px;
        }

        /* ── Flash message ── */
        .flash-success {
            background: #f4f4f5;
            color: #18181b;
            padding: 11px 15px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 16px;
            border: 1px solid #e4e4e7;
        }

        /* ── Input group ── */
        .igroup {
            margin-bottom: 14px;
        }

        .igroup label {
            display: block;
            font-size: 12.5px;
            font-weight: 600;
            color: #27272a;
            margin-bottom: 6px;
        }

        .igroup input {
            width: 100%;
            padding: 13px 17px;
            border: 1.5px solid #e4e4e7;
            border-radius: 13px;
            background: #fafafa;
            color: #18181b;
            font-size: 14px;
            outline: none;
            transition: border-color 0.22s, box-shadow 0.22s, background 0.22s;
        }

        .igroup input:focus {
            border-color: #18181b;
            background: #fff;
            box-shadow: 0 0 0 4px rgba(24,24,27,0.06);
        }

        .igroup input::placeholder { color: #a1a1aa; }

        .err { margin-top: 4px; font-size: 11.5px; color: #ef4444; font-weight: 500; }

        /* ── Password row (two cols) ── */
        .pass-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        /* ── Meta row (remember / forgot) ── */
        .meta-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 2px 0 20px;
        }

        .meta-row label {
            font-size: 12.5px;
            color: #71717a;
            display: flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
        }

        .meta-row input[type="checkbox"] {
            accent-color: #18181b;
            width: 14px;
            height: 14px;
        }

        .meta-row a {
            font-size: 12.5px;
            font-weight: 600;
            color: #18181b;
            text-decoration: none;
            transition: color 0.2s;
        }
        .meta-row a:hover { color: #3f3f46; text-decoration: underline; }

        /* ── Submit button ── */
        .btn-auth {
            width: 100%;
            padding: 14px;
            border: 1px solid #18181b;
            border-radius: 13px;
            background: #18181b;
            color: #fff;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            letter-spacing: 0.3px;
            transition: all 0.22s ease;
        }
        .btn-auth:hover {
            transform: translateY(-2px);
            background: #27272a;
            border-color: #27272a;
            box-shadow: 0 10px 20px rgba(24, 24, 27, 0.15);
        }

        /* ── Switch link ── */
        .switch-row {
            margin-top: 20px;
            text-align: center;
            font-size: 13px;
            color: #71717a;
        }

        .switch-row button {
            background: none;
            border: none;
            color: #18181b;
            font-weight: 700;
            cursor: pointer;
            font-size: 13px;
            margin-left: 3px;
            padding: 0;
            transition: color 0.2s;
        }
        .switch-row button:hover { color: #3f3f46; text-decoration: underline; }

        /* ── Responsive ── */
        @media (max-width: 880px) {
            html, body { overflow: auto; }

            .auth-card {
                width: 100%;
                max-width: 480px;
                height: auto;
                flex-direction: column;
                margin: 16px;
            }

            .left-panel {
                width: 100%;
                height: auto;
                padding: 36px 28px;
            }

            .right-panel {
                width: 100%;
                height: 580px;
            }

            .form-box {
                padding: 36px 28px;
            }

            .pass-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<div class="auth-card" id="authCard">

    {{-- ═══════════════ LEFT PANEL ═══════════════ --}}
    <div class="left-panel">
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
        <div class="blob blob-3"></div>

        <div class="brand-name">
            BlogVibe
            <span class="brand-badge">AI Reviews</span>
        </div>

        <p class="brand-desc">
            Your go-to hub for expert AI tool reviews,<br>
            honest comparisons, and productivity tips.
        </p>

        <div class="feat">
            <h3>⚡ AI Tool Reviews</h3>
            <p>In-depth reviews of the latest AI tools and software.</p>
        </div>
        <div class="feat">
            <h3>🔍 Smart Comparisons</h3>
            <p>Side-by-side analysis to help you decide faster.</p>
        </div>
        <div class="feat">
            <h3>🎯 Exclusive Deals</h3>
            <p>Partner links with special discounts just for you.</p>
        </div>
    </div>

    {{-- ═══════════════ RIGHT PANEL ═══════════════ --}}
    <div class="right-panel">

        {{-- ─── LOGIN FORM ─── --}}
        <div class="form-box login" id="loginBox">
            <div class="form-inner">
                <h2>Welcome back</h2>
                <p class="form-subtitle">Sign in to access your account</p>

                @if (session('status'))
                    <div class="flash-success">{{ session('status') }}</div>
                @endif

                <form action="{{ route('login') }}" method="POST">
                    @csrf

                    <div class="igroup">
                        <label for="l_email">Email Address</label>
                        <input id="l_email" type="email" name="email"
                               placeholder="you@example.com"
                               value="{{ old('email') }}"
                               required autocomplete="email">
                        @error('email') <p class="err">{{ $message }}</p> @enderror
                    </div>

                    <div class="igroup">
                        <label for="l_password">Password</label>
                        <input id="l_password" type="password" name="password"
                               placeholder="••••••••"
                               required autocomplete="current-password">
                        @error('password') <p class="err">{{ $message }}</p> @enderror
                    </div>

                    <div class="meta-row">
                        <label>
                            <input type="checkbox" name="remember">
                            Remember me
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}">Forgot password?</a>
                        @endif
                    </div>

                    <button type="submit" class="btn-auth">Sign In</button>
                </form>

                <div class="switch-row">
                    Don't have an account?
                    <button onclick="showRegister()">Create one free</button>
                </div>
            </div>
        </div>

        {{-- ─── REGISTER FORM ─── --}}
        <div class="form-box register" id="registerBox">
            <div class="form-inner">
                <h2>Join BlogVibe</h2>
                <p class="form-subtitle">Create your free account today</p>

                <form action="{{ route('register') }}" method="POST">
                    @csrf

                    <div class="igroup">
                        <label for="r_name">Full Name</label>
                        <input id="r_name" type="text" name="name"
                               placeholder="John Doe"
                               value="{{ old('name') }}"
                               required autocomplete="name">
                        @error('name') <p class="err">{{ $message }}</p> @enderror
                    </div>

                    <div class="igroup">
                        <label for="r_email">Email Address</label>
                        <input id="r_email" type="email" name="email"
                               placeholder="you@example.com"
                               value="{{ old('email') }}"
                               required autocomplete="email">
                        @error('email') <p class="err">{{ $message }}</p> @enderror
                    </div>

                    <div class="pass-row">
                        <div class="igroup">
                            <label for="r_pass">Password</label>
                            <input id="r_pass" type="password" name="password"
                                   placeholder="••••••••"
                                   required autocomplete="new-password">
                            @error('password') <p class="err">{{ $message }}</p> @enderror
                        </div>
                        <div class="igroup">
                            <label for="r_pass_c">Confirm</label>
                            <input id="r_pass_c" type="password" name="password_confirmation"
                                   placeholder="••••••••"
                                   required autocomplete="new-password">
                        </div>
                    </div>

                    <button type="submit" class="btn-auth" style="margin-top:6px;">
                        Create Account
                    </button>
                </form>

                <div class="switch-row">
                    Already have an account?
                    <button onclick="showLogin()">Sign in</button>
                </div>
            </div>
        </div>

    </div>{{-- /right-panel --}}
</div>{{-- /auth-card --}}

<script>
    const card = document.getElementById('authCard');

    function showRegister() {
        card.classList.add('show-register');
    }
    function showLogin() {
        card.classList.remove('show-register');
    }

    // Auto-slide to register panel when there are register-side validation errors
    @if (request()->routeIs('register') || $errors->has('name'))
        showRegister();
    @endif

    // Intercept submits for SweetAlert loaders
    document.querySelector('#loginBox form')?.addEventListener('submit', function() {
        Swal.fire({
            title: 'Welcome Back',
            text: 'Signing you in...',
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    });

    document.querySelector('#registerBox form')?.addEventListener('submit', function() {
        Swal.fire({
            title: 'Creating Account',
            text: 'Setting up your profile...',
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    });
</script>
</body>
</html>
