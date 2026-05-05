<?php
// ─────────────────────────────────────────────────────────────────────────────
// Forgot Password Page (Standalone — Full HTML)
//
// Expected variables:
//   $error      — string, error message (optional)
//   $success    — string, success message (optional)
//   $csrfToken  — string, CSRF token
//   $appName    — string, application name (optional)
// ─────────────────────────────────────────────────────────────────────────────

$error     = $error ?? null;
$success   = $success ?? null;
$csrfToken = $csrfToken ?? '';
$appName   = $appName ?? config('app_name', 'School Management System');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password — <?= htmlspecialchars($appName) ?></title>
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

    <!-- Forgot Password Card -->
    <div class="relative z-10 w-full max-w-md">
        <div class="rounded-2xl bg-white/95 shadow-2xl backdrop-blur-sm border border-white/20">

            <!-- Header / Branding -->
            <div class="px-8 pt-8 pb-4 text-center">
                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-700 shadow-lg shadow-emerald-600/30">
                    <svg class="h-9 w-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-900">Forgot your password?</h1>
                <p class="mt-1 text-sm text-gray-500">No worries, we'll send you reset instructions.</p>
            </div>

            <!-- Forgot Password Form -->
            <form id="forgotForm" method="POST" action="/forgot-password" class="px-8 pb-6 space-y-4">
                <input type="hidden" name="_token" value="<?= htmlspecialchars($csrfToken) ?>">

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
                        <span><?= htmlspecialchars($success) ?></span>
                    </div>
                <?php endif; ?>

                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email Address</label>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                            </svg>
                        </div>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            required
                            autocomplete="email"
                            placeholder="Enter your email address"
                            class="block w-full rounded-lg border border-gray-300 py-2.5 pl-10 pr-3 text-sm text-gray-900 placeholder-gray-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none transition-colors"
                        >
                    </div>
                    <p class="mt-2 text-xs text-gray-500">
                        Enter the email address associated with your account and we'll send you a link to reset your password.
                    </p>
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    id="submitBtn"
                    class="flex w-full items-center justify-center gap-2 rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm shadow-emerald-600/30 hover:bg-emerald-700 focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 focus:outline-none transition-all active:scale-[0.98]"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/>
                    </svg>
                    <span id="submitBtnText">Send Reset Link</span>
                    <svg id="submitSpinner" class="h-4 w-4 hidden animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>
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
        document.getElementById('forgotForm').addEventListener('submit', function(e) {
            const btn = document.getElementById('submitBtn');
            const btnText = document.getElementById('submitBtnText');
            const spinner = document.getElementById('submitSpinner');
            btn.disabled = true;
            btnText.textContent = 'Sending...';
            spinner.classList.remove('hidden');
        });
    </script>
</body>
</html>
