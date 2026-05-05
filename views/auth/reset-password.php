<?php
// ─────────────────────────────────────────────────────────────────────────────
// Reset Password Page (Standalone — Full HTML)
//
// Expected variables:
//   $error       — string, error message (optional)
//   $success     — string, success message (optional)
//   $csrfToken   — string, CSRF token
//   $appName     — string, application name (optional)
//   $token       — string, reset token (for hidden field)
// ─────────────────────────────────────────────────────────────────────────────

$error     = $error ?? null;
$success   = $success ?? null;
$csrfToken = $csrfToken ?? '';
$appName   = $appName ?? config('app_name', 'School Management System');
$token     = $token ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password — <?= htmlspecialchars($appName) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#ecfdf5', 100: '#d1fae5', 200: '#a7f3d0', 300: '#6ee7b7',
                            400: '#34d399', 500: '#10b981', 600: '#059669', 700: '#047857',
                            800: '#065f46', 900: '#064e3b', 950: '#022c22',
                        },
                    },
                },
            },
        }
    </script>
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        .float-anim { animation: float 6s ease-in-out infinite; }
        .float-anim-delay { animation: float 8s ease-in-out 2s infinite; }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-emerald-600 via-emerald-700 to-teal-800 flex items-center justify-center p-4 relative overflow-hidden">

    <!-- Decorative Background Elements -->
    <div class="absolute top-20 left-10 w-72 h-72 bg-emerald-400/20 rounded-full blur-3xl float-anim"></div>
    <div class="absolute bottom-20 right-10 w-96 h-96 bg-teal-400/20 rounded-full blur-3xl float-anim-delay"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-emerald-300/10 rounded-full blur-3xl"></div>

    <!-- Reset Password Card -->
    <div class="relative z-10 w-full max-w-md">
        <div class="rounded-2xl bg-white/95 shadow-2xl backdrop-blur-sm border border-white/20">

            <!-- Header / Branding -->
            <div class="px-8 pt-8 pb-4 text-center">
                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-700 shadow-lg shadow-emerald-600/30">
                    <svg class="h-9 w-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-900">Set new password</h1>
                <p class="mt-1 text-sm text-gray-500">Your new password must be different from previously used passwords.</p>
            </div>

            <!-- Reset Password Form -->
            <form id="resetForm" method="POST" action="/reset-password" class="px-8 pb-6 space-y-4">
                <input type="hidden" name="_token" value="<?= htmlspecialchars($csrfToken) ?>">
                <?php if (!empty($token)): ?>
                    <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                <?php endif; ?>

                <!-- Error Message -->
                <?php if ($error): ?>
                    <div class="rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700 flex items-start gap-2">
                        <svg class="h-4 w-4 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                        <span><?= htmlspecialchars($error) ?></span>
                    </div>
                <?php endif; ?>

                <!-- Success Message -->
                <?php if ($success): ?>
                    <div class="rounded-lg border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-700 flex items-start gap-2">
                        <svg class="h-5 w-5 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <span><?= htmlspecialchars($success) ?></span>
                            <div class="mt-3">
                                <a href="/login" class="inline-flex items-center gap-1.5 text-sm font-medium text-emerald-700 hover:text-emerald-800">
                                    Go to Sign In
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (!$success): ?>
                <!-- New Password Field -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">New Password</label>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            minlength="8"
                            autocomplete="new-password"
                            placeholder="Minimum 8 characters"
                            class="block w-full rounded-lg border border-gray-300 py-2.5 pl-10 pr-10 text-sm text-gray-900 placeholder-gray-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none transition-colors"
                        >
                        <button type="button" onclick="togglePassword('password', 'eyeIcon1', 'eyeOffIcon1')" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600" aria-label="Toggle password visibility">
                            <svg id="eyeIcon1" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg id="eyeOffIcon1" class="h-4 w-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                    <!-- Password strength hint -->
                    <div class="mt-2 flex items-center gap-1.5">
                        <div class="h-1.5 flex-1 rounded-full bg-gray-200 overflow-hidden">
                            <div id="strengthBar" class="h-full rounded-full bg-gray-300 transition-all duration-300" style="width: 0%"></div>
                        </div>
                        <span id="strengthText" class="text-xs text-gray-400 whitespace-nowrap"></span>
                    </div>
                </div>

                <!-- Confirm Password Field -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1.5">Confirm New Password</label>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
                            </svg>
                        </div>
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            required
                            minlength="8"
                            autocomplete="new-password"
                            placeholder="Confirm your new password"
                            class="block w-full rounded-lg border border-gray-300 py-2.5 pl-10 pr-10 text-sm text-gray-900 placeholder-gray-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none transition-colors"
                        >
                        <button type="button" onclick="togglePassword('password_confirmation', 'eyeIcon2', 'eyeOffIcon2')" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600" aria-label="Toggle password visibility">
                            <svg id="eyeIcon2" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg id="eyeOffIcon2" class="h-4 w-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Requirements -->
                <div class="bg-gray-50 rounded-lg p-3 space-y-1.5">
                    <p class="text-xs font-medium text-gray-600 mb-1">Password requirements:</p>
                    <ul class="text-xs text-gray-500 space-y-1">
                        <li class="flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                            Minimum 8 characters
                        </li>
                        <li class="flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                            At least one uppercase letter
                        </li>
                        <li class="flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                            At least one number
                        </li>
                    </ul>
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    id="resetBtn"
                    class="flex w-full items-center justify-center gap-2 rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm shadow-emerald-600/30 hover:bg-emerald-700 focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 focus:outline-none transition-all active:scale-[0.98]"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
                    </svg>
                    <span id="resetBtnText">Reset Password</span>
                    <svg id="resetSpinner" class="h-4 w-4 hidden animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>
                <?php endif; ?>
            </form>

            <!-- Footer -->
            <div class="border-t border-gray-100 px-8 py-4 text-center">
                <a href="/login" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-emerald-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                    </svg>
                    Back to Sign In
                </a>
            </div>
        </div>

        <!-- Bottom text -->
        <div class="mt-6 text-center">
            <p class="text-sm text-emerald-100/60">&copy; <?= date('Y') ?> <?= htmlspecialchars($appName) ?></p>
        </div>
    </div>

    <script>
        function togglePassword(inputId, eyeId, eyeOffId) {
            const input = document.getElementById(inputId);
            const eye = document.getElementById(eyeId);
            const eyeOff = document.getElementById(eyeOffId);
            if (input.type === 'password') {
                input.type = 'text';
                eye.classList.add('hidden');
                eyeOff.classList.remove('hidden');
            } else {
                input.type = 'password';
                eye.classList.remove('hidden');
                eyeOff.classList.add('hidden');
            }
        }

        // Password strength indicator
        const passwordInput = document.getElementById('password');
        if (passwordInput) {
            passwordInput.addEventListener('input', function() {
                const val = this.value;
                let strength = 0;
                if (val.length >= 8) strength++;
                if (/[A-Z]/.test(val)) strength++;
                if (/[0-9]/.test(val)) strength++;
                if (/[^A-Za-z0-9]/.test(val)) strength++;

                const bar = document.getElementById('strengthBar');
                const text = document.getElementById('strengthText');
                if (!bar || !text) return;

                const levels = [
                    { width: '0%', color: 'bg-gray-300', label: '' },
                    { width: '25%', color: 'bg-red-500', label: 'Weak' },
                    { width: '50%', color: 'bg-amber-500', label: 'Fair' },
                    { width: '75%', color: 'bg-emerald-500', label: 'Good' },
                    { width: '100%', color: 'bg-emerald-600', label: 'Strong' },
                ];
                const level = levels[strength];
                bar.style.width = level.width;
                bar.className = 'h-full rounded-full transition-all duration-300 ' + level.color;
                text.textContent = level.label;
            });
        }

        // Form submission
        const form = document.getElementById('resetForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                const password = document.getElementById('password')?.value;
                const confirm = document.getElementById('password_confirmation')?.value;
                if (password && confirm && password !== confirm) {
                    e.preventDefault();
                    const confirmField = document.getElementById('password_confirmation');
                    confirmField.classList.add('border-red-500', 'ring-2', 'ring-red-500/20');
                    confirmField.focus();
                    setTimeout(() => {
                        confirmField.classList.remove('border-red-500', 'ring-2', 'ring-red-500/20');
                    }, 3000);
                    return;
                }
                const btn = document.getElementById('resetBtn');
                const btnText = document.getElementById('resetBtnText');
                const spinner = document.getElementById('resetSpinner');
                if (btn && btnText && spinner) {
                    btn.disabled = true;
                    btnText.textContent = 'Resetting...';
                    spinner.classList.remove('hidden');
                }
            });
        }
    </script>
</body>
</html>
