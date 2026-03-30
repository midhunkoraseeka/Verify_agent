<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Agent | Real Estate Admin</title>

    @include('admin.includes.header_links')

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #f8f9fa 0%, #f1f3f5 100%);
            color: #0f172a;
            overflow: hidden;
        }

        .split-layout {
            display: grid;
            grid-template-columns: 1fr 1fr;
            height: 100vh;
        }

        /* LEFT ── Visual / Branding */
        .visual-side {
            position: relative;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            overflow: hidden;
        }

        .visual-side::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url('https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&q=80&w=1973') center/cover no-repeat;
            opacity: 0.18;
            mix-blend-mode: luminosity;
        }

        .visual-content {
            position: relative;
            z-index: 2;
            max-width: 440px;
            text-align: center;
        }

        .logo-circle {
            width: 100px;
            height: 100px;
            margin: 0 auto 24px;
            background: linear-gradient(135deg, #fff7ed, #fed7aa);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 36px rgba(249, 115, 22, 0.32);
        }

        .logo-circle i {
            font-size: 48px;
            color: #ea580c;
        }

        .visual-content h2 {
            font-size: 2.4rem;
            font-weight: 800;
            letter-spacing: -1px;
            margin-bottom: 14px;
        }

        .visual-content p {
            font-size: 1.1rem;
            opacity: 0.88;
            line-height: 1.5;
            margin-bottom: 32px;
        }

        .feature-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
            font-size: 1.02rem;
            opacity: 0.92;
            text-align: left;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .feature-item i {
            color: #f97316;
            font-size: 1.35rem;
        }

        /* RIGHT ── Simple Glass Card – no floating labels */
        .form-side {
            background: rgb(254 246 239 / 94%);
            backdrop-filter: blur(48px);
            -webkit-backdrop-filter: blur(48px);
            border-left: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px 20px;
        }

        .glass-card {
            background: rgb(255 255 255 / 86%);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border: 1px solid rgba(249, 115, 22, 0.18);
            border-top: 3px solid #f97316;
            border-radius: 5px;
            padding: 36px 32px 32px;
            width: 100%;
            max-width: 520px;
            box-shadow:
                0 16px 40px rgba(0, 0, 0, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.5);
        }

        .form-header {
            text-align: center;
            margin-bottom: 36px;
        }

        .form-header h1 {
            font-size: 2.2rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 6px;
        }

        .form-header p {
            color: #64748b;
            font-size: 1rem;
            font-weight: 500;
        }

        /* Normal labels – no floating */
        label {
            display: block;
            margin-bottom: 8px;
            font-size: 0.92rem;
            font-weight: 600;
            color: #475569;
        }

        .form-group {
            margin-bottom: 24px;
        }

        input {
            width: 100%;
            padding: 10px 16px;
            font-size: 0.98rem;
            background: rgba(248, 250, 252, 0.7);
            border: 1px solid rgba(226, 232, 240, 0.75);
            border-radius: 5px;
            color: #0f172a;
            transition: all 0.28s ease;
        }

        input:focus {
            outline: none;
            border-color: #f97316;
            box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.12);
            background: white;
        }

        input::placeholder {
            color: #94a3b8;
        }

        .password-wrapper {
            position: relative;
        }

        .toggle-btn {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #64748b;
            font-size: 1.25rem;
            cursor: pointer;
            transition: color 0.25s;
        }

        .toggle-btn:hover {
            color: #f97316;
        }

        .options-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 16px 0 24px;
            font-size: 0.94rem;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #64748b;
            cursor: pointer;
        }

        .remember-me input {
            accent-color: #f97316;
            width: 16px;
            height: 16px;
        }

        .forgot-link {
            color: #f97316;
            text-decoration: none;
            font-weight: 600;
        }

        .forgot-link:hover {
            color: #ea580c;
            text-decoration: underline;
        }

        .btn-login {
            width: 100%;
            padding: 13px;
            font-size: 1.05rem;
            font-weight: 700;
            color: white;
            background: linear-gradient(135deg, #f97316, #ea580c);
            border: none;
            border-radius: 10px;
            cursor: pointer;
            box-shadow: 0 8px 24px rgba(249, 115, 22, 0.28);
            transition: all 0.32s ease;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 32px rgba(249, 115, 22, 0.42);
        }

        .form-footer {
            text-align: center;
            margin-top: 28px;
            font-size: 0.86rem;
            color: #64748b;
            line-height: 1.5;
        }

        .form-footer p {
            margin: 3px 0;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .split-layout {
                grid-template-columns: 1fr;
            }

            .visual-side {
                display: none;
            }

            .form-side {
                border-left: none;
                padding: 50px 25px;
            }
        }

        @media (max-width: 480px) {
            .glass-card {
                padding: 32px 24px 28px;
            }

            .form-header h1 {
                font-size: 1.95rem;
            }
        }
    </style>
</head>

<body>

    <div class="split-layout">

        <!-- LEFT SIDE -->
        <div class="visual-side">
            <div class="visual-content">
                <div class="logo-circle">
                    <i class="bi bi-buildings-fill"></i>
                </div>
                <h2>Agent Verification Portal</h2>
                <p>Secure access to manage listings, clients, inquiries & performance analytics in one powerful
                    dashboard.</p>

                <div class="feature-list">
                    <div class="feature-item"><i class="bi bi-check-circle-fill"></i> Real-time property updates</div>
                    <div class="feature-item"><i class="bi bi-check-circle-fill"></i> Lead & inquiry management</div>
                    <div class="feature-item"><i class="bi bi-check-circle-fill"></i> Commission & payout tracking</div>
                    <div class="feature-item"><i class="bi bi-check-circle-fill"></i> Advanced reporting & insights
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT SIDE -->
        <div class="form-side">
            <div class="glass-card">
                @if (session('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                @error('error')
                    <div class="alert alert-danger" role="alert">
                        {{ $message }}
                    </div>
                @enderror

                <div class="form-header">
                    <h1>Verify Agent</h1>
                    <p>Access your real estate dashboard</p>
                </div>

                <form action="{{ route('admin.login') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="mobile_number">Mobile Number</label>
                        <input type="text" id="mobile_number" name="mobile_number"
                            placeholder="Enter your mobile number" autocomplete="mobile_number">
                        @error('mobile_number')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group password-wrapper">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="Enter your password"
                            autocomplete="current-password">
                        @error('password')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror

                    </div>

                    <div class="options-row">
                        <label class="remember-me">
                            <input type="checkbox" id="remember">
                            <span>Remember me</span>
                        </label>
                        <a href="#" class="forgot-link">Forgot password?</a>
                    </div>


                    <button type="submit" class="btn-login">Sign In</button>
                </form>



                <div class="form-footer">
                    <p>© 2026 Real Estate Agent Verification System</p>
                    <p>For official and authorized use only</p>
                </div>

            </div>
        </div>

    </div>
    @include('admin.includes.footer_links')
</body>

</html>
