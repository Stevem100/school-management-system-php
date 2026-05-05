<?php
// ─────────────────────────────────────────────────────────────────────────────
// Generic Dynamic CMS Page
//
// Expected variables:
//   $page         — array of the page record from website_pages table
//   $siteSettings — array of website_settings
//   $menuItems    — array of navigation menu items
//
// $page keys:
//   title, slug, content (HTML), meta_title, meta_description, 
//   meta_keywords, status, featured_image, created_at, updated_at
// ─────────────────────────────────────────────────────────────────────────────

$siteSettings = $siteSettings ?? [];
$menuItems    = $menuItems ?? [];
$page         = $page ?? [];

$pageTitle     = htmlspecialchars($page['title'] ?? 'Page');
$pageContent   = $page['content'] ?? '';
$metaTitle     = htmlspecialchars($page['meta_title'] ?? $pageTitle);
$metaDesc      = htmlspecialchars($page['meta_description'] ?? '');
$metaKeywords  = htmlspecialchars($page['meta_keywords'] ?? '');
$featuredImage = $page['featured_image'] ?? '';
$publishedAt   = $page['created_at'] ?? '';
$updatedAt     = $page['updated_at'] ?? '';
$slug          = $page['slug'] ?? '';

ob_start();
?>

<!-- ─── PAGE HEADER ───────────────────────────────────────────────────────── -->
<section class="bg-emerald-900 relative overflow-hidden">
    <div class="absolute inset-0 hero-pattern opacity-20"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-20 relative z-10">
        <div class="max-w-3xl">
            <!-- Breadcrumb inside header -->
            <nav class="mb-4" aria-label="Breadcrumb">
                <ol class="flex items-center gap-2 text-sm text-emerald-300">
                    <li><a href="/" class="hover:text-white transition-colors">Home</a></li>
                    <li><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg></li>
                    <li class="text-white font-medium"><?= $pageTitle ?></li>
                </ol>
            </nav>
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white mb-4"><?= $pageTitle ?></h1>
            <?php if (!empty($publishedAt)): ?>
                <p class="text-emerald-300 text-sm">
                    <svg class="w-4 h-4 inline mr-1 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                    </svg>
                    Published <?= date('F j, Y', strtotime($publishedAt)) ?>
                    <?php if (!empty($updatedAt) && $updatedAt !== $publishedAt): ?>
                        &nbsp;&middot;&nbsp; Updated <?= date('F j, Y', strtotime($updatedAt)) ?>
                    <?php endif; ?>
                </p>
            <?php endif; ?>
        </div>
    </div>
    <div class="absolute -bottom-8 -right-8 w-64 h-64 rounded-full bg-emerald-800/30"></div>
</section>

<!-- ─── FEATURED IMAGE ────────────────────────────────────────────────────── -->
<?php if (!empty($featuredImage)): ?>
<section class="bg-gray-100">
    <div class="max-w-7xl mx-auto">
        <div class="max-w-4xl mx-auto">
            <img src="<?= htmlspecialchars($featuredImage) ?>" alt="<?= $pageTitle ?>" class="w-full max-h-96 object-cover">
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ─── PAGE CONTENT ──────────────────────────────────────────────────────── -->
<section class="py-12 sm:py-16 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <article class="prose prose-lg prose-gray max-w-none
            prose-headings:font-bold prose-headings:text-gray-900 prose-headings:scroll-mt-20
            prose-h2:text-2xl prose-h2:mt-10 prose-h2:mb-4
            prose-h3:text-xl prose-h3:mt-8 prose-h3:mb-3
            prose-p:text-gray-600 prose-p:leading-relaxed prose-p:mb-5
            prose-a:text-emerald-600 prose-a:no-underline hover:prose-a:underline
            prose-img:rounded-xl prose-img:shadow-lg
            prose-strong:text-gray-900
            prose-ul:my-4 prose-ul:pl-6 prose-li:text-gray-600 prose-li:mb-1
            prose-ol:my-4 prose-ol:pl-6 prose-li:text-gray-600 prose-li:mb-1
            prose-blockquote:border-l-emerald-500 prose-blockquote:text-gray-600
            prose-table:border prose-th:bg-gray-50 prose-th:px-4 prose-th:py-2 prose-th:text-left prose-th:text-sm prose-th:font-semibold prose-th:text-gray-700
            prose-td:px-4 prose-td:py-2 prose-td:text-sm prose-td:text-gray-600 prose-td:border-t
            prose-code:text-emerald-700 prose-code:bg-emerald-50 prose-code:px-1.5 prose-code:py-0.5 prose-code:rounded prose-code:text-sm prose-code:before:content-none prose-code:after:content-none
            prose-pre:bg-gray-900 prose-pre:text-gray-100 prose-pre:rounded-xl
            prose-hr:border-gray-200 prose-hr:my-8
        ">
            <?= $pageContent ?>
        </article>

        <!-- Share buttons -->
        <div class="mt-12 pt-8 border-t border-gray-200">
            <p class="text-sm font-medium text-gray-500 mb-3">Share this page:</p>
            <div class="flex items-center gap-3">
                <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode($_SERVER['REQUEST_URI'] ?? '/') ?>" target="_blank" rel="noopener" class="w-9 h-9 flex items-center justify-center rounded-lg bg-gray-100 hover:bg-blue-100 hover:text-blue-600 text-gray-500 transition-colors" aria-label="Share on Facebook">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                </a>
                <a href="https://twitter.com/intent/tweet?url=<?= urlencode($_SERVER['REQUEST_URI'] ?? '/') ?>&text=<?= urlencode($pageTitle) ?>" target="_blank" rel="noopener" class="w-9 h-9 flex items-center justify-center rounded-lg bg-gray-100 hover:bg-sky-100 hover:text-sky-500 text-gray-500 transition-colors" aria-label="Share on Twitter">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                </a>
                <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?= urlencode($_SERVER['REQUEST_URI'] ?? '/') ?>" target="_blank" rel="noopener" class="w-9 h-9 flex items-center justify-center rounded-lg bg-gray-100 hover:bg-blue-100 hover:text-blue-700 text-gray-500 transition-colors" aria-label="Share on LinkedIn">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                </a>
                <button onclick="navigator.clipboard.writeText(window.location.href); this.innerHTML='<svg class=\'w-4 h-4\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\' stroke-width=\'2\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M4.5 12.75l6 6 9-13.5\'/></svg>'; setTimeout(() => this.innerHTML='<svg class=\'w-4 h-4\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\' stroke-width=\'2\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101\'/><path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M10.686 13.828a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1\'/></svg>', 2000)"
                    class="w-9 h-9 flex items-center justify-center rounded-lg bg-gray-100 hover:bg-emerald-100 hover:text-emerald-600 text-gray-500 transition-colors" aria-label="Copy link">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101"/><path stroke-linecap="round" stroke-linejoin="round" d="M10.686 13.828a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                </button>
            </div>
        </div>
    </div>
</section>

<!-- ─── BACK TO HOME CTA ─────────────────────────────────────────────────── -->
<section class="py-10 bg-gray-50 border-t border-gray-100">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <p class="text-gray-600 mb-3">Want to learn more about our school?</p>
        <div class="flex flex-wrap items-center justify-center gap-3">
            <a href="/" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold px-6 py-2.5 rounded-lg transition-colors text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                </svg>
                Back to Home
            </a>
            <a href="/contact" class="inline-flex items-center gap-2 border border-gray-300 text-gray-700 hover:border-emerald-300 hover:text-emerald-700 font-semibold px-6 py-2.5 rounded-lg transition-colors text-sm">
                Contact Us
            </a>
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
    'pageTitle'    => $pageTitle,
];
extract($_layoutData, EXTR_SKIP);
require dirname(__DIR__) . '/../layouts/website.php';
?>
