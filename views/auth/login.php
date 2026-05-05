<?php
$error = isset($error) ? $error : null;
$oldEmail = isset($oldEmail) ? $oldEmail : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — School Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            200: '#a7f3d0',
                            300: '#6ee7b7',
                            400: '#34d399',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                            800: '#065f46',
                            900: '#064e3b',
                            950: '#022c22',
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
        .float-anim {
            animation: float 6s ease-in-out infinite;
        }
        .float-anim-delay {
            animation: float 8s ease-in-out 2s infinite;
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-emerald-600 via-emerald-700 to-teal-800 flex items-center justify-center p-4 relative overflow-hidden">

    <!-- Decorative Background Elements -->
    <div class="absolute top-20 left-10 w-72 h-72 bg-emerald-400/20 rounded-full blur-3xl float-anim"></div>
    <div class="absolute bottom-20 right-10 w-96 h-96 bg-teal-400/20 rounded-full blur-3xl float-anim-delay"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-emerald-300/10 rounded-full blur-3xl"></div>

    <!-- Login Card -->
    <div class="relative z-10 w-full max-w-md">
        <div class="rounded-2xl bg-white/95 shadow-2xl backdrop-blur-sm border border-white/20">

            <!-- Header / Branding -->
            <div class="px-8 pt-8 pb-4 text-center">
                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-700 shadow-lg shadow-emerald-600/30">
                    <svg class="h-9 w-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l6.16-3.422A12.083 12.083 0 0119.5 12c0 .818-.082 1.616-.244 2.386L12 14zm-6.16 3.422A12.083 12.083 0 004.5 12c0-.818.082-1.616.244-2.386L6 14l6 6z"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-900">School Management System</h1>
                <p class="mt-1 text-sm text-gray-500">Sign in to access your dashboard</p>
            </div>

            <!-- Login Form -->
            <form id="loginForm" method="POST" action="/login" class="px-8 pb-6 space-y-4">
                <input type="hidden" name="_token" value="<?= isset($csrfToken) ? htmlspecialchars($csrfToken) : '' ?>">

                <!-- Error Message -->
                <?php if ($error): ?>
                    <div class="rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700 flex items-start gap-2">
                        <svg class="h-4 w-4 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                        <span><?= htmlspecialchars($error) ?></span>
                    </div>
                <?php endif; ?>

                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email Address</label>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="<?= htmlspecialchars($oldEmail) ?>"
                            required
                            autocomplete="email"
                            placeholder="you@school.com"
                            class="block w-full rounded-lg border border-gray-300 py-2.5 pl-10 pr-3 text-sm text-gray-900 placeholder-gray-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none transition-colors"
                        >
                    </div>
                </div>

                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
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
                            autocomplete="current-password"
                            placeholder="Enter your password"
                            class="block w-full rounded-lg border border-gray-300 py-2.5 pl-10 pr-10 text-sm text-gray-900 placeholder-gray-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none transition-colors"
                        >
                        <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600" aria-label="Toggle password visibility">
                            <svg id="eyeIcon" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg id="eyeOffIcon" class="h-4 w-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Remember me + Forgot password -->
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="h-4 w-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                        <span class="text-sm text-gray-600">Remember me</span>
                    </label>
                    <a href="#" class="text-sm font-medium text-emerald-600 hover:text-emerald-700 transition-colors">Forgot password?</a>
                </div>

                <!-- Sign In Button -->
                <button
                    type="submit"
                    id="loginBtn"
                    class="flex w-full items-center justify-center gap-2 rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm shadow-emerald-600/30 hover:bg-emerald-700 focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 focus:outline-none transition-all active:scale-[0.98]"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                    </svg>
                    <span id="loginBtnText">Sign In</span>
                    <svg id="loginSpinner" class="h-4 w-4 hidden animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>
            </form>

            <!-- Divider -->
            <div class="px-8 py-4">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-200"></div>
                    </div>
                    <div class="relative flex justify-center text-xs">
                        <button onclick="toggleDemoAccounts()" class="flex items-center gap-1.5 rounded-full bg-gray-50 px-3 py-1 text-gray-500 hover:bg-gray-100 hover:text-gray-700 transition-colors border border-gray-200">
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Quick Login — 13 Demo Accounts
                            <svg id="demoArrow" class="h-3 w-3 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Demo Accounts List -->
            <div id="demoAccounts" class="hidden px-8 pb-6">
                <div class="max-h-72 overflow-y-auto rounded-lg border border-gray-200 bg-gray-50 space-y-1 p-2">
                    <?php
                    $demoAccounts = [
                        ['role' => 'SuperAdmin', 'email' => 'admin@school.com', 'password' => 'admin123', 'color' => 'red'],
                        ['role' => 'School Admin', 'email' => 'schooladmin@greenfield.ac.ke', 'password' => 'demo123', 'color' => 'orange'],
                        ['role' => 'Branch Admin (Main)', 'email' => 'branchadmin@greenfield.ac.ke', 'password' => 'demo123', 'color' => 'amber'],
                        ['role' => 'Branch Admin (West)', 'email' => 'westadmin@greenfield.ac.ke', 'password' => 'demo123', 'color' => 'amber'],
                        ['role' => 'Dean', 'email' => 'dean@greenfield.ac.ke', 'password' => 'demo123', 'color' => 'purple'],
                        ['role' => 'Teacher', 'email' => 'mary@greenfield.ac.ke', 'password' => 'demo123', 'color' => 'blue'],
                        ['role' => 'Teacher (West)', 'email' => 'faith@greenfield.ac.ke', 'password' => 'demo123', 'color' => 'blue'],
                        ['role' => 'Accountant', 'email' => 'accounts@greenfield.ac.ke', 'password' => 'demo123', 'color' => 'green'],
                        ['role' => 'Librarian', 'email' => 'library@greenfield.ac.ke', 'password' => 'demo123', 'color' => 'teal'],
                        ['role' => 'Transport Manager', 'email' => 'transport@greenfield.ac.ke', 'password' => 'demo123', 'color' => 'indigo'],
                        ['role' => 'Hostel Manager', 'email' => 'hostel@greenfield.ac.ke', 'password' => 'demo123', 'color' => 'pink'],
                        ['role' => 'Student (Main)', 'email' => 'brian.njorgemc@greenfield.ac.ke', 'password' => 'student123', 'color' => 'cyan'],
                        ['role' => 'Student (West)', 'email' => 'amina.hassanwc@greenfield.ac.ke', 'password' => 'student123', 'color' => 'cyan'],
                    ];

                    $colorClasses = [
                        'red' => 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300',
                        'orange' => 'bg-orange-100 text-orange-700 dark:bg-orange-900 dark:text-orange-300',
                        'amber' => 'bg-amber-100 text-amber-700 dark:bg-amber-900 dark:text-amber-300',
                        'purple' => 'bg-purple-100 text-purple-700 dark:bg-purple-900 dark:text-purple-300',
                        'blue' => 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300',
                        'green' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900 dark:text-emerald-300',
                        'teal' => 'bg-teal-100 text-teal-700 dark:bg-teal-900 dark:text-teal-300',
                        'indigo' => 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900 dark:text-indigo-300',
                        'pink' => 'bg-pink-100 text-pink-700 dark:bg-pink-900 dark:text-pink-300',
                        'cyan' => 'bg-cyan-100 text-cyan-700 dark:bg-cyan-900 dark:text-cyan-300',
                    ];
                    ?>

                    <?php foreach ($demoAccounts as $account): ?>
                        <form method="POST" action="/login" class="w-full">
                            <input type="hidden" name="_token" value="<?= isset($csrfToken) ? htmlspecialchars($csrfToken) : '' ?>">
                            <input type="hidden" name="email" value="<?= htmlspecialchars($account['email']) ?>">
                            <input type="hidden" name="password" value="<?= htmlspecialchars($account['password']) ?>">
                            <button type="submit" class="flex w-full items-center justify-between rounded-md px-3 py-2 text-left text-sm hover:bg-white hover:shadow-sm transition-all group">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-gray-200 text-[10px] font-bold text-gray-600 group-hover:bg-emerald-100 group-hover:text-emerald-700 transition-colors">
                                        <?= strtoupper(substr($account['email'], 0, 1)) ?>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-medium text-gray-900 truncate"><?= htmlspecialchars($account['role']) ?></p>
                                        <p class="text-[11px] text-gray-500 truncate"><?= htmlspecialchars($account['email']) ?></p>
                                    </div>
                                </div>
                                <span class="shrink-0 ml-2 rounded-full px-2 py-0.5 text-[10px] font-medium <?= $colorClasses[$account['color']] ?>"><?= htmlspecialchars($account['role']) ?></span>
                            </button>
                        </form>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Footer -->
            <div class="border-t border-gray-100 px-8 py-4 text-center">
                <p class="text-xs text-gray-400">&copy; 2024 School Management System — Powered by Supabase</p>
            </div>
        </div>

        <!-- Bottom decorative text -->
        <div class="mt-6 text-center">
            <p class="text-sm text-emerald-100/60">Greenfield Academy &mdash; Excellence in Education</p>
        </div>
    </div>

    <script>
        // Toggle password visibility
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            const eyeOffIcon = document.getElementById('eyeOffIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.add('hidden');
                eyeOffIcon.classList.remove('hidden');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('hidden');
                eyeOffIcon.classList.add('hidden');
            }
        }

        // Toggle demo accounts
        function toggleDemoAccounts() {
            const demoAccounts = document.getElementById('demoAccounts');
            const demoArrow = document.getElementById('demoArrow');

            demoAccounts.classList.toggle('hidden');
            demoArrow.classList.toggle('rotate-180');
        }

        // Form validation
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value.trim();

            if (!email) {
                e.preventDefault();
                highlightField('email');
                return;
            }

            if (!password) {
                e.preventDefault();
                highlightField('password');
                return;
            }

            // Show loading state
            const loginBtn = document.getElementById('loginBtn');
            const loginBtnText = document.getElementById('loginBtnText');
            const loginSpinner = document.getElementById('loginSpinner');

            loginBtn.disabled = true;
            loginBtnText.textContent = 'Signing in...';
            loginSpinner.classList.remove('hidden');
        });

        function highlightField(fieldId) {
            const field = document.getElementById(fieldId);
            field.classList.add('border-red-500', 'ring-2', 'ring-red-500/20');
            field.focus();
            setTimeout(() => {
                field.classList.remove('border-red-500', 'ring-2', 'ring-red-500/20');
            }, 3000);
        }

        // Clear error on input
        document.getElementById('email').addEventListener('input', function() {
            const errorDiv = this.closest('form').querySelector('.border-red-200');
            if (errorDiv) errorDiv.style.display = 'none';
        });
        document.getElementById('password').addEventListener('input', function() {
            const errorDiv = this.closest('form').querySelector('.border-red-200');
            if (errorDiv) errorDiv.style.display = 'none';
        });
    </script>
</body>
</html>
