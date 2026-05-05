<?php
// ─────────────────────────────────────────────────────────────────────────────
// Contact Page
//
// Expected variables:
//   $siteSettings — array of website_settings
//   $menuItems    — array of navigation menu items
//   $flashSuccess — success flash message (optional)
//   $flashError   — error flash message (optional)
//   $old          — previous form input for repopulation (optional)
// ─────────────────────────────────────────────────────────────────────────────

$siteSettings = $siteSettings ?? [];
$menuItems    = $menuItems ?? [];
$old          = $old ?? [];

$contactPhone = $siteSettings['contact_phone'] ?? '+1 (555) 123-4567';
$contactEmail = $siteSettings['contact_email'] ?? 'info@greenfieldacademy.edu';
$contactAddr  = $siteSettings['contact_address'] ?? '123 Education Lane, Academic City, AC 12345';
$socialFB     = $siteSettings['social_facebook'] ?? '';
$socialTW     = $siteSettings['social_twitter'] ?? '';
$socialIG     = $siteSettings['social_instagram'] ?? '';
$socialLI     = $siteSettings['social_linkedin'] ?? '';
$socialYT     = $siteSettings['social_youtube'] ?? '';

ob_start();
?>

<!-- ─── PAGE HEADER ───────────────────────────────────────────────────────── -->
<section class="bg-emerald-900 relative overflow-hidden">
    <div class="absolute inset-0 hero-pattern opacity-20"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-20 relative z-10">
        <div class="max-w-3xl">
            <p class="text-emerald-300 text-sm font-semibold uppercase tracking-wider mb-3">Contact</p>
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white mb-4">Get in Touch</h1>
            <p class="text-emerald-200 text-lg max-w-xl">
                We would love to hear from you. Whether you have questions about admissions, programs, or anything else, our team is ready to help.
            </p>
        </div>
    </div>
    <div class="absolute -bottom-8 -right-8 w-64 h-64 rounded-full bg-emerald-800/30"></div>
</section>

<!-- ─── BREADCRUMB ────────────────────────────────────────────────────────── -->
<nav class="bg-white border-b border-gray-100" aria-label="Breadcrumb">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
        <ol class="flex items-center gap-2 text-sm text-gray-500">
            <li><a href="/" class="hover:text-emerald-600 transition-colors">Home</a></li>
            <li><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg></li>
            <li class="text-emerald-600 font-medium">Contact</li>
        </ol>
    </div>
</nav>

<!-- ─── CONTACT INFO CARDS ───────────────────────────────────────────────── -->
<section class="py-12 bg-gray-50 border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Address -->
            <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 flex items-start gap-4">
                <div class="w-12 h-12 rounded-lg bg-emerald-100 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 0115 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 mb-1">Our Address</h3>
                    <p class="text-gray-600 text-sm"><?= htmlspecialchars($contactAddr) ?></p>
                </div>
            </div>

            <!-- Phone -->
            <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 flex items-start gap-4">
                <div class="w-12 h-12 rounded-lg bg-emerald-100 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 mb-1">Phone</h3>
                    <a href="tel:<?= htmlspecialchars($contactPhone) ?>" class="text-emerald-600 text-sm hover:text-emerald-700 transition-colors"><?= htmlspecialchars($contactPhone) ?></a>
                </div>
            </div>

            <!-- Email -->
            <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 flex items-start gap-4">
                <div class="w-12 h-12 rounded-lg bg-emerald-100 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 mb-1">Email</h3>
                    <a href="mailto:<?= htmlspecialchars($contactEmail) ?>" class="text-emerald-600 text-sm hover:text-emerald-700 transition-colors"><?= htmlspecialchars($contactEmail) ?></a>
                </div>
            </div>

            <!-- Hours -->
            <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 flex items-start gap-4">
                <div class="w-12 h-12 rounded-lg bg-emerald-100 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 mb-1">Office Hours</h3>
                    <p class="text-gray-600 text-sm">Mon - Fri: 8:00 AM - 4:00 PM</p>
                    <p class="text-gray-600 text-sm">Sat: 9:00 AM - 12:00 PM</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ─── CONTACT FORM + MAP ───────────────────────────────────────────────── -->
<section class="py-16 sm:py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-5 gap-12 lg:gap-16">

            <!-- Contact Form -->
            <div class="lg:col-span-3">
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">Send Us a Message</h2>
                <p class="text-gray-600 mb-8">Fill out the form below and we will get back to you within 24 hours.</p>

                <form action="/contact" method="POST" class="space-y-5">
                    <div class="grid sm:grid-cols-2 gap-5">
                        <!-- Name -->
                        <div>
                            <label for="contact_name" class="block text-sm font-medium text-gray-700 mb-1.5">Full Name <span class="text-red-500">*</span></label>
                            <input type="text" id="contact_name" name="name" required
                                value="<?= htmlspecialchars($old['name'] ?? '') ?>"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors text-sm"
                                placeholder="Your full name">
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-1.5">Email Address <span class="text-red-500">*</span></label>
                            <input type="email" id="contact_email" name="email" required
                                value="<?= htmlspecialchars($old['email'] ?? '') ?>"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors text-sm"
                                placeholder="you@example.com">
                        </div>
                    </div>

                    <div class="grid sm:grid-cols-2 gap-5">
                        <!-- Phone -->
                        <div>
                            <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-1.5">Phone Number</label>
                            <input type="tel" id="contact_phone" name="phone"
                                value="<?= htmlspecialchars($old['phone'] ?? '') ?>"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors text-sm"
                                placeholder="+1 (555) 000-0000">
                        </div>

                        <!-- Subject -->
                        <div>
                            <label for="contact_subject" class="block text-sm font-medium text-gray-700 mb-1.5">Subject <span class="text-red-500">*</span></label>
                            <select id="contact_subject" name="subject" required
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors text-sm">
                                <option value="">Select a subject</option>
                                <option value="admissions" <?= ($old['subject'] ?? '') === 'admissions' ? 'selected' : '' ?>>Admissions Inquiry</option>
                                <option value="academics" <?= ($old['subject'] ?? '') === 'academics' ? 'selected' : '' ?>>Academic Programs</option>
                                <option value="fees" <?= ($old['subject'] ?? '') === 'fees' ? 'selected' : '' ?>>Fee Structure</option>
                                <option value="transport" <?= ($old['subject'] ?? '') === 'transport' ? 'selected' : '' ?>>Transport Services</option>
                                <option value="general" <?= ($old['subject'] ?? '') === 'general' ? 'selected' : '' ?>>General Inquiry</option>
                                <option value="feedback" <?= ($old['subject'] ?? '') === 'feedback' ? 'selected' : '' ?>>Feedback</option>
                                <option value="other" <?= ($old['subject'] ?? '') === 'other' ? 'selected' : '' ?>>Other</option>
                            </select>
                        </div>
                    </div>

                    <!-- Message -->
                    <div>
                        <label for="contact_message" class="block text-sm font-medium text-gray-700 mb-1.5">Message <span class="text-red-500">*</span></label>
                        <textarea id="contact_message" name="message" required rows="5"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors text-sm resize-y"
                            placeholder="How can we help you?"><?= htmlspecialchars($old['message'] ?? '') ?></textarea>
                    </div>

                    <!-- Submit -->
                    <div>
                        <button type="submit" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold px-8 py-3 rounded-lg transition-colors shadow-sm text-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/>
                            </svg>
                            Send Message
                        </button>
                    </div>
                </form>
            </div>

            <!-- Map & Info Sidebar -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Map Placeholder -->
                <div class="rounded-xl overflow-hidden border border-gray-200 bg-gray-100 aspect-[4/3] relative">
                    <div class="absolute inset-0 flex flex-col items-center justify-center text-gray-400">
                        <svg class="w-16 h-16 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 0115 0z"/>
                        </svg>
                        <p class="text-sm font-medium">Map View</p>
                        <p class="text-xs">Embed your Google Maps iframe here</p>
                    </div>
                    <!-- Uncomment and replace with your Google Maps embed:
                    <iframe
                        src="https://www.google.com/maps/embed?pb=..."
                        width="100%" height="100%" style="border:0;" allowfullscreen=""
                        loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                    -->
                </div>

                <!-- Social Media -->
                <div class="bg-gray-50 rounded-xl p-6 border border-gray-100">
                    <h3 class="text-base font-semibold text-gray-900 mb-4">Connect With Us</h3>
                    <p class="text-gray-600 text-sm mb-4">Follow us on social media for the latest updates, events, and school activities.</p>
                    <div class="flex flex-wrap gap-3">
                        <?php if (!empty($socialFB)): ?>
                            <a href="<?= htmlspecialchars($socialFB) ?>" target="_blank" rel="noopener" class="flex items-center gap-2 bg-white border border-gray-200 rounded-lg px-4 py-2 text-sm text-gray-700 hover:border-emerald-300 hover:text-emerald-700 transition-colors">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                Facebook
                            </a>
                        <?php endif; ?>
                        <?php if (!empty($socialTW)): ?>
                            <a href="<?= htmlspecialchars($socialTW) ?>" target="_blank" rel="noopener" class="flex items-center gap-2 bg-white border border-gray-200 rounded-lg px-4 py-2 text-sm text-gray-700 hover:border-emerald-300 hover:text-emerald-700 transition-colors">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                                Twitter
                            </a>
                        <?php endif; ?>
                        <?php if (!empty($socialIG)): ?>
                            <a href="<?= htmlspecialchars($socialIG) ?>" target="_blank" rel="noopener" class="flex items-center gap-2 bg-white border border-gray-200 rounded-lg px-4 py-2 text-sm text-gray-700 hover:border-emerald-300 hover:text-emerald-700 transition-colors">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                                Instagram
                            </a>
                        <?php endif; ?>
                        <?php if (!empty($socialLI)): ?>
                            <a href="<?= htmlspecialchars($socialLI) ?>" target="_blank" rel="noopener" class="flex items-center gap-2 bg-white border border-gray-200 rounded-lg px-4 py-2 text-sm text-gray-700 hover:border-emerald-300 hover:text-emerald-700 transition-colors">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                                LinkedIn
                            </a>
                        <?php endif; ?>
                        <?php if (!empty($socialYT)): ?>
                            <a href="<?= htmlspecialchars($socialYT) ?>" target="_blank" rel="noopener" class="flex items-center gap-2 bg-white border border-gray-200 rounded-lg px-4 py-2 text-sm text-gray-700 hover:border-emerald-300 hover:text-emerald-700 transition-colors">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                                YouTube
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Quick FAQ -->
                <div class="bg-emerald-50 rounded-xl p-6 border border-emerald-100">
                    <h3 class="text-base font-semibold text-gray-900 mb-3">Frequently Asked</h3>
                    <ul class="space-y-3">
                        <li>
                            <a href="/admission/form" class="flex items-center gap-2 text-emerald-700 text-sm hover:text-emerald-800 transition-colors">
                                <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                                How do I apply for admission?
                            </a>
                        </li>
                        <li>
                            <a href="/classes" class="flex items-center gap-2 text-emerald-700 text-sm hover:text-emerald-800 transition-colors">
                                <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                                What programs do you offer?
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center gap-2 text-emerald-700 text-sm hover:text-emerald-800 transition-colors">
                                <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                                What are the fee details?
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center gap-2 text-emerald-700 text-sm hover:text-emerald-800 transition-colors">
                                <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                                When is the next campus tour?
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<?php $content = ob_get_clean(); ?>

<?php
$_layoutData = [
    'siteSettings' => $siteSettings,
    'menuItems'    => $menuItems,
    'content'      => $content,
    'showHero'     => false,
    'pageTitle'    => 'Contact Us',
    'flashSuccess' => $flashSuccess ?? null,
    'flashError'   => $flashError ?? null,
];
extract($_layoutData, EXTR_SKIP);
require dirname(__DIR__) . '/../layouts/website.php';
?>
