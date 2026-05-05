<?php
// ─────────────────────────────────────────────────────────────────────────────
// Public Admission Form (Standalone — rendered without admin layout)
//
// Expected variables:
//   $settings      — array from admission_settings
//   $groupedFields — array of sections => fields (each field has id, label, field_type, etc.)
//   $classes       — array of available classes (id, name)
//   $academic_year — current academic year string
//   $pageTitle     — page title (optional)
// ─────────────────────────────────────────────────────────────────────────────

$settings      = $settings ?? [];
$groupedFields = $groupedFields ?? [];
$classes       = $classes ?? [];
$academic_year = $academic_year ?? '';
$pageTitle     = $pageTitle ?? 'Admission Application';

$schoolName    = htmlspecialchars($settings['school_name'] ?? config('app_name', 'School Management System'));
$instructions  = $settings['instructions'] ?? '';
$applicationFee = $settings['application_fee'] ?? 0;
$endDate       = $settings['end_date'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?> — <?= $schoolName ?></title>
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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', system-ui, -apple-system, sans-serif; }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up { animation: fadeInUp 0.5s ease-out both; }
        .hero-gradient {
            background: linear-gradient(135deg, #064e3b 0%, #059669 50%, #34d399 100%);
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 antialiased min-h-screen flex flex-col">

    <!-- ─── TOP BAR ──────────────────────────────────────────────────────────── -->
    <div class="bg-emerald-900 text-white text-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2 text-center">
            <span class="text-emerald-200"><?= $schoolName ?> &mdash; Admission Portal</span>
        </div>
    </div>

    <!-- ─── HEADER ───────────────────────────────────────────────────────────── -->
    <header class="bg-white border-b border-gray-100 shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <a href="/" class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-700 flex items-center justify-center shadow-sm">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l6.16-3.422A12.083 12.083 0 0119.5 12c0 .818-.082 1.616-.244 2.386L12 14zm-6.16 3.422A12.083 12.083 0 004.5 12c0-.818.082-1.616.244-2.386L6 14l6 6z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-base font-bold text-gray-900 leading-tight"><?= $schoolName ?></p>
                        <p class="text-xs text-gray-500 leading-tight">Admission Application Form</p>
                    </div>
                </a>
                <a href="/" class="text-sm text-gray-500 hover:text-emerald-600 transition-colors flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                    </svg>
                    Back to Home
                </a>
            </div>
        </div>
    </header>

    <!-- ─── HERO BANNER ─────────────────────────────────────────────────────── -->
    <section class="hero-gradient relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 sm:py-20 relative z-10">
            <div class="max-w-3xl mx-auto text-center">
                <p class="text-emerald-300 text-sm font-semibold uppercase tracking-wider mb-3">Admissions <?= $academic_year ? '&mdash; ' . htmlspecialchars($academic_year) : '' ?></p>
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white mb-4">Apply for Admission</h1>
                <p class="text-emerald-100 text-lg max-w-xl mx-auto">
                    Complete the application form below to begin your admission journey. All fields marked with <span class="text-white font-semibold">*</span> are required.
                </p>
            </div>
        </div>
        <div class="absolute -bottom-8 -right-8 w-64 h-64 rounded-full bg-emerald-800/30"></div>
        <div class="absolute -top-24 -left-24 w-96 h-96 rounded-full bg-white/5"></div>
    </section>

    <!-- ─── FORM CONTENT ─────────────────────────────────────────────────────── -->
    <main class="flex-1">
        <section class="py-12 sm:py-16 bg-gray-50">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

                <!-- Instructions Banner -->
                <?php if (!empty($instructions)): ?>
                <div class="bg-amber-50 border border-amber-200 rounded-xl p-5 mb-8 animate-fade-in-up">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-amber-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                        </svg>
                        <div>
                            <h3 class="text-sm font-semibold text-amber-800 mb-1">Important Instructions</h3>
                            <p class="text-amber-700 text-sm leading-relaxed"><?= nl2br(htmlspecialchars($instructions)) ?></p>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Info Cards -->
                <div class="grid sm:grid-cols-3 gap-4 mb-8">
                    <div class="bg-white rounded-xl p-4 border border-gray-100 text-center">
                        <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Academic Year</p>
                        <p class="text-lg font-bold text-gray-900"><?= htmlspecialchars($academic_year) ?: 'N/A' ?></p>
                    </div>
                    <?php if ($applicationFee > 0): ?>
                    <div class="bg-white rounded-xl p-4 border border-gray-100 text-center">
                        <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Application Fee</p>
                        <p class="text-lg font-bold text-gray-900">$<?= number_format((float) $applicationFee, 2) ?></p>
                    </div>
                    <?php endif; ?>
                    <div class="bg-white rounded-xl p-4 border border-gray-100 text-center">
                        <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Available Classes</p>
                        <p class="text-lg font-bold text-emerald-600"><?= count($classes) ?></p>
                    </div>
                </div>

                <!-- Admission Form -->
                <form id="admissionForm" action="/admission/submit" method="POST" enctype="multipart/form-data" class="space-y-8">

                    <!-- Applicant Information -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden animate-fade-in-up">
                        <div class="bg-emerald-50 px-6 py-4 border-b border-emerald-100">
                            <h2 class="text-lg font-semibold text-emerald-900 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                                </svg>
                                Applicant Information
                            </h2>
                        </div>
                        <div class="p-6 space-y-5">
                            <div class="grid sm:grid-cols-2 gap-5">
                                <div>
                                    <label for="applicant_name" class="block text-sm font-medium text-gray-700 mb-1.5">Full Name <span class="text-red-500">*</span></label>
                                    <input type="text" id="applicant_name" name="applicant_name" required
                                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm"
                                        placeholder="Enter full name">
                                </div>
                                <div>
                                    <label for="applicant_email" class="block text-sm font-medium text-gray-700 mb-1.5">Email Address <span class="text-red-500">*</span></label>
                                    <input type="email" id="applicant_email" name="applicant_email" required
                                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm"
                                        placeholder="you@example.com">
                                </div>
                            </div>
                            <div class="grid sm:grid-cols-2 gap-5">
                                <div>
                                    <label for="applicant_phone" class="block text-sm font-medium text-gray-700 mb-1.5">Phone Number</label>
                                    <input type="tel" id="applicant_phone" name="applicant_phone"
                                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm"
                                        placeholder="+1 (555) 000-0000">
                                </div>
                                <div>
                                    <label for="class_id" class="block text-sm font-medium text-gray-700 mb-1.5">Applying for Class <span class="text-red-500">*</span></label>
                                    <select id="class_id" name="class_id" required
                                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm">
                                        <option value="">Select a class</option>
                                        <?php foreach ($classes as $cls): ?>
                                            <option value="<?= htmlspecialchars($cls['id'] ?? '') ?>"><?= htmlspecialchars($cls['name'] ?? 'Unknown') ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label for="academic_year" class="block text-sm font-medium text-gray-700 mb-1.5">Academic Year <span class="text-red-500">*</span></label>
                                <input type="text" id="academic_year" name="academic_year" required readonly
                                    value="<?= htmlspecialchars($academic_year) ?>"
                                    class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-gray-50 text-gray-700 text-sm cursor-not-allowed">
                            </div>
                        </div>
                    </div>

                    <!-- Dynamic Form Fields by Section -->
                    <?php if (!empty($groupedFields)): ?>
                        <?php $sectionIdx = 0; ?>
                        <?php foreach ($groupedFields as $sectionName => $fields): ?>
                            <?php $sectionIdx++; ?>
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="bg-gray-50 px-6 py-4 border-b border-gray-100">
                                <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                    <span class="w-7 h-7 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-bold"><?= $sectionIdx ?></span>
                                    <?= htmlspecialchars($sectionName) ?>
                                </h2>
                            </div>
                            <div class="p-6">
                                <div class="grid sm:grid-cols-<?= count($fields) > 2 ? '2' : (count($fields) === 1 ? '1' : '2') ?> gap-5">
                                    <?php foreach ($fields as $field):
                                        $fieldId       = $field['id'] ?? '';
                                        $fieldLabel    = htmlspecialchars($field['label'] ?? '');
                                        $fieldType     = $field['field_type'] ?? 'text';
                                        $fieldKey      = 'field_' . $fieldId;
                                        $placeholder   = htmlspecialchars($field['placeholder'] ?? '');
                                        $helpText      = htmlspecialchars($field['help_text'] ?? '');
                                        $isRequired    = (int) ($field['is_required'] ?? 0) === 1;
                                        $options       = $field['options_array'] ?? [];
                                        $defaultValue  = htmlspecialchars($field['default_value'] ?? '');
                                        $maxSize       = $field['max_size'] ?? '';
                                    ?>
                                        <div class="<?= in_array($fieldType, ['textarea']) ? 'sm:col-span-2' : '' ?>">
                                            <label for="<?= $fieldKey ?>" class="block text-sm font-medium text-gray-700 mb-1.5">
                                                <?= $fieldLabel ?>
                                                <?php if ($isRequired): ?><span class="text-red-500">*</span><?php endif; ?>
                                            </label>

                                            <?php if ($fieldType === 'text' || $fieldType === 'email' || $fieldType === 'phone' || $fieldType === 'number' || $fieldType === 'date'): ?>
                                                <input type="<?= $fieldType === 'phone' ? 'tel' : $fieldType ?>"
                                                    id="<?= $fieldKey ?>" name="<?= $fieldKey ?>"
                                                    <?= $isRequired ? 'required' : '' ?>
                                                    <?= !empty($maxSize) && $fieldType !== 'date' ? 'maxlength="' . (int) $maxSize . '"' : '' ?>
                                                    value="<?= $defaultValue ?>"
                                                    placeholder="<?= $placeholder ?>"
                                                    class="w-full px-4 py-2.5 rounded-lg border border-gray-300 bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm">

                                            <?php elseif ($fieldType === 'textarea'): ?>
                                                <textarea id="<?= $fieldKey ?>" name="<?= $fieldKey ?>"
                                                    <?= $isRequired ? 'required' : '' ?>
                                                    <?= !empty($maxSize) ? 'maxlength="' . (int) $maxSize . '"' : '' ?>
                                                    rows="4"
                                                    placeholder="<?= $placeholder ?>"
                                                    class="w-full px-4 py-2.5 rounded-lg border border-gray-300 bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm resize-y"><?= $defaultValue ?></textarea>

                                            <?php elseif ($fieldType === 'select'): ?>
                                                <select id="<?= $fieldKey ?>" name="<?= $fieldKey ?>"
                                                    <?= $isRequired ? 'required' : '' ?>
                                                    class="w-full px-4 py-2.5 rounded-lg border border-gray-300 bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm">
                                                    <option value="">Select an option</option>
                                                    <?php foreach ($options as $opt): ?>
                                                        <option value="<?= htmlspecialchars($opt) ?>" <?= $defaultValue === htmlspecialchars($opt) ? 'selected' : '' ?>><?= htmlspecialchars($opt) ?></option>
                                                    <?php endforeach; ?>
                                                </select>

                                            <?php elseif ($fieldType === 'radio'): ?>
                                                <div class="space-y-2 mt-1">
                                                    <?php foreach ($options as $opt): ?>
                                                        <label class="flex items-center gap-2 cursor-pointer">
                                                            <input type="radio" name="<?= $fieldKey ?>"
                                                                value="<?= htmlspecialchars($opt) ?>"
                                                                <?= $defaultValue === htmlspecialchars($opt) ? 'checked' : '' ?>
                                                                <?= $isRequired ? 'required' : '' ?>
                                                                class="w-4 h-4 text-emerald-600 border-gray-300 focus:ring-emerald-500">
                                                            <span class="text-sm text-gray-700"><?= htmlspecialchars($opt) ?></span>
                                                        </label>
                                                    <?php endforeach; ?>
                                                </div>

                                            <?php elseif ($fieldType === 'checkbox'): ?>
                                                <div class="space-y-2 mt-1">
                                                    <?php foreach ($options as $opt): ?>
                                                        <label class="flex items-center gap-2 cursor-pointer">
                                                            <input type="checkbox" name="<?= $fieldKey ?>[]"
                                                                value="<?= htmlspecialchars($opt) ?>"
                                                                class="w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500">
                                                            <span class="text-sm text-gray-700"><?= htmlspecialchars($opt) ?></span>
                                                        </label>
                                                    <?php endforeach; ?>
                                                </div>

                                            <?php elseif ($fieldType === 'file'): ?>
                                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-emerald-400 transition-colors cursor-pointer relative"
                                                     onclick="this.querySelector('input[type=file]').click()">
                                                    <input type="file" id="<?= $fieldKey ?>" name="<?= $fieldKey ?>"
                                                        <?= $isRequired ? 'required' : '' ?>
                                                        accept="image/*,.pdf,.doc,.docx"
                                                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                                        onchange="this.parentElement.querySelector('.file-label').textContent = this.files[0]?.name || 'Click to upload or drag & drop'">
                                                    <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
                                                    </svg>
                                                    <p class="text-sm text-gray-500 file-label">Click to upload or drag & drop</p>
                                                    <p class="text-xs text-gray-400 mt-1">PDF, DOC, DOCX, JPG, PNG (Max 10MB)</p>
                                                </div>

                                            <?php endif; ?>

                                            <?php if (!empty($helpText)): ?>
                                                <p class="mt-1.5 text-xs text-gray-500"><?= $helpText ?></p>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <!-- Terms & Conditions -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-6">
                            <label class="flex items-start gap-3 cursor-pointer">
                                <input type="checkbox" name="agree_terms" required
                                    class="w-4 h-4 mt-0.5 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500">
                                <span class="text-sm text-gray-700">
                                    I confirm that all information provided in this application is accurate and complete. I agree to the
                                    <a href="#" class="text-emerald-600 hover:text-emerald-700 underline">Terms & Conditions</a> and
                                    <a href="#" class="text-emerald-600 hover:text-emerald-700 underline">Privacy Policy</a> of the school.
                                    I understand that providing false information may result in rejection of my application. <span class="text-red-500">*</span>
                                </span>
                            </label>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex flex-col sm:flex-row items-center gap-4 justify-between">
                        <a href="/" class="text-sm text-gray-500 hover:text-gray-700 transition-colors flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                            </svg>
                            Back to Home
                        </a>
                        <button type="submit" id="submitBtn"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold px-10 py-3.5 rounded-xl transition-colors shadow-sm text-base">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/>
                            </svg>
                            Submit Application
                        </button>
                    </div>
                </form>
            </div>
        </section>
    </main>

    <!-- ─── FOOTER ───────────────────────────────────────────────────────────── -->
    <footer class="bg-emerald-950 text-emerald-100 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-2 text-sm text-emerald-400">
                <p>&copy; <?= date('Y') ?> <?= $schoolName ?>. All rights reserved.</p>
                <a href="/" class="hover:text-white transition-colors">Back to Homepage</a>
            </div>
        </div>
    </footer>

    <!-- Submission loading overlay -->
    <div id="formOverlay" class="hidden fixed inset-0 bg-black/40 z-50 flex items-center justify-center">
        <div class="bg-white rounded-2xl p-8 text-center shadow-2xl max-w-sm mx-4">
            <div class="w-12 h-12 mx-auto border-4 border-emerald-200 border-t-emerald-600 rounded-full animate-spin mb-4"></div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Submitting Application</h3>
            <p class="text-sm text-gray-500">Please wait while we process your application...</p>
        </div>
    </div>

    <script>
        const form = document.getElementById('admissionForm');
        const overlay = document.getElementById('formOverlay');
        if (form && overlay) {
            form.addEventListener('submit', function(e) {
                const agreeBox = form.querySelector('input[name="agree_terms"]');
                if (agreeBox && !agreeBox.checked) {
                    e.preventDefault();
                    agreeBox.focus();
                    agreeBox.parentElement.style.outline = '2px solid #ef4444';
                    agreeBox.parentElement.style.outlineOffset = '2px';
                    agreeBox.parentElement.style.borderRadius = '6px';
                    setTimeout(() => {
                        agreeBox.parentElement.style.outline = '';
                        agreeBox.parentElement.style.outlineOffset = '';
                    }, 2000);
                    return;
                }
                overlay.classList.remove('hidden');
            });
        }
    </script>
</body>
</html>
