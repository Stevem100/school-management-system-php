<?php
$userRoles = isset($userRoles) ? $userRoles : [];
$currentPage = isset($currentPage) ? $currentPage : 'dashboard';
$schoolName = isset($schoolName) ? $schoolName : 'Greenfield Academy';
$branchName = isset($branchName) ? $branchName : 'Main Campus';

/**
 * Helper: Check if user has any of the given roles
 */
function hasRole($userRoles, $roles) {
    if (in_array('*', $roles) || in_array('all', $roles)) return true;
    foreach ($roles as $role) {
        if (in_array($role, $userRoles)) return true;
    }
    return false;
}

/**
 * Helper: Check if a page is active (exact or parent match)
 */
function isActive($currentPage, $slug) {
    return $currentPage === $slug;
}

function isParentActive($currentPage, $slugs) {
    return in_array($currentPage, $slugs);
}

/**
 * Render a menu item
 */
function renderMenuItem($slug, $icon, $label, $currentPage, $extraClasses = '') {
    $active = isActive($currentPage, $slug);
    $linkClasses = $active
        ? 'flex items-center gap-3 rounded-lg bg-emerald-600 px-3 py-2.5 text-sm font-medium text-white shadow-sm shadow-emerald-600/30'
        : 'flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-emerald-100 hover:bg-emerald-800 hover:text-white transition-colors';
    return '<a href="/' . $slug . '" class="' . $linkClasses . ' ' . $extraClasses . '">' . $icon . '<span>' . $label . '</span></a>';
}

/**
 * Render a submenu toggle
 */
function renderSubmenuToggle($id, $icon, $label, $arrowId, $currentPage, $childSlugs) {
    $parentActive = isParentActive($currentPage, $childSlugs);
    $toggleClasses = $parentActive
        ? 'flex items-center justify-between w-full rounded-lg bg-emerald-800 px-3 py-2.5 text-sm font-medium text-white'
        : 'flex items-center justify-between w-full rounded-lg px-3 py-2.5 text-sm font-medium text-emerald-100 hover:bg-emerald-800 hover:text-white transition-colors';
    return '<button onclick="toggleSubmenu(\'' . $id . '\')" class="' . $toggleClasses . '">' . $icon . '<span>' . $label . '</span><svg id="' . $arrowId . '" class="h-4 w-4 transition-transform duration-200 ' . ($parentActive ? 'rotate-180' : '') . '" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg></button>';
}

/**
 * Render a submenu item
 */
function renderSubmenuItem($slug, $icon, $label, $currentPage) {
    $active = isActive($currentPage, $slug);
    $classes = $active
        ? 'flex items-center gap-3 rounded-lg bg-emerald-600/30 px-3 py-2 text-sm text-emerald-200 font-medium border-l-2 border-emerald-400'
        : 'flex items-center gap-3 rounded-lg px-3 py-2 text-sm text-emerald-200 hover:bg-emerald-800/50 hover:text-white transition-colors';
    return '<a href="/' . $slug . '" class="' . $classes . '">' . $icon . '<span>' . $label . '</span></a>';
}

// SVG Icon Definitions
$icons = [
    'dashboard' => '<svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>',
    'schools' => '<svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>',
    'branches' => '<svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 3v12m4-9v9m4-6v6m4-3v3"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 21h12"/></svg>',
    'users' => '<svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>',
    'shield' => '<svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>',
    'modules' => '<svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"/></svg>',
    'book-open' => '<svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>',
    'book-text' => '<svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>',
    'clipboard' => '<svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>',
    'bar-chart' => '<svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>',
    'calendar' => '<svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>',
    'award' => '<svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>',
    'graduation' => '<svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 14l6.16-3.422A12.083 12.083 0 0119.5 12c0 .818-.082 1.616-.244 2.386L12 14zm-6.16 3.422A12.083 12.083 0 004.5 12c0-.818.082-1.616.244-2.386L6 14l6 6z"/></svg>',
    'dollar' => '<svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
    'receipt' => '<svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>',
    'credit-card' => '<svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>',
    'monitor' => '<svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>',
    'bot' => '<svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0112 15a9.065 9.065 0 00-6.23.693L5 14.5m14.8.8l1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0112 21c-2.773 0-5.491-.235-8.135-.687-1.718-.293-2.3-2.379-1.067-3.61L5 14.5"/></svg>',
    'message-circle' => '<svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>',
    'settings' => '<svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>',
    'trending-up' => '<svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>',
    'bus' => '<svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 17h.01M16 17h.01M3 11l1.5-5A2 2 0 016.4 4.5h11.2a2 2 0 011.9 1.5L21 11M5 11h14v5a2 2 0 01-2 2H7a2 2 0 01-2-2v-5zm0 0a2 2 0 00-2 2v3h2m16-5v5a2 2 0 01-2 2h0"/></svg>',
    'library' => '<svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/></svg>',
    'home' => '<svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>',
    'bell' => '<svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>',
    'user-check' => '<svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/><path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-1 1m6-1l1 1"/></svg>',
    'file-bar-chart' => '<svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>',
    'subjects' => '<svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>',
    'exams' => '<svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>',
    'results' => '<svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>',
    'timetable' => '<svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>',
    'skills' => '<svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>',
    'fee-structure' => '<svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>',
    'payments' => '<svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>',
    'chat' => '<svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>',
    'analytics' => '<svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>',
    'ai-settings' => '<svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>',
    'students' => '<svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>',
    'attendance' => '<svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>',
    'communication' => '<svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>',
    'reports' => '<svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>',
];

// Menu configuration
$academicChildSlugs = ['classes', 'subjects', 'exams', 'results', 'timetable', 'skills'];
$financeChildSlugs = ['fee-structure', 'payments'];
$aiChildSlugs = ['ai-chat', 'ai-settings', 'ai-analytics'];
$academicOpen = isParentActive($currentPage, $academicChildSlugs);
$financeOpen = isParentActive($currentPage, $financeChildSlugs);
$aiOpen = isParentActive($currentPage, $aiChildSlugs);
?>

<!-- Sidebar -->
<aside id="sidebar" class="fixed left-0 top-16 z-40 h-[calc(100vh-4rem)] w-64 overflow-y-auto bg-emerald-900 dark:bg-gray-950 sidebar-transition -translate-x-full lg:translate-x-0 border-r border-emerald-800 dark:border-gray-800">
    <div class="flex h-full flex-col">

        <!-- Logo / School Brand -->
        <div class="flex items-center gap-3 border-b border-emerald-800 dark:border-gray-800 px-4 py-4">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-600 text-white shadow-lg shadow-emerald-600/30">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l6.16-3.422A12.083 12.083 0 0119.5 12c0 .818-.082 1.616-.244 2.386L12 14zm-6.16 3.422A12.083 12.083 0 004.5 12c0-.818.082-1.616.244-2.386L6 14l6 6z"/>
                </svg>
            </div>
            <div class="min-w-0">
                <h2 class="truncate text-sm font-bold text-white"><?= htmlspecialchars($schoolName) ?></h2>
                <p class="truncate text-xs text-emerald-300"><?= htmlspecialchars($branchName) ?></p>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-4" aria-label="Main navigation">

            <!-- Dashboard (All roles) -->
            <?= renderMenuItem('dashboard', $icons['dashboard'], 'Dashboard', $currentPage) ?>

            <!-- Schools (SuperAdmin) -->
            <?php if (hasRole($userRoles, ['SuperAdmin'])): ?>
                <?= renderMenuItem('schools', $icons['schools'], 'Schools', $currentPage) ?>
            <?php endif; ?>

            <!-- Branches (SuperAdmin, SchoolAdmin) -->
            <?php if (hasRole($userRoles, ['SuperAdmin', 'SchoolAdmin'])): ?>
                <?= renderMenuItem('branches', $icons['branches'], 'Branches', $currentPage) ?>
            <?php endif; ?>

            <!-- Users (SuperAdmin, SchoolAdmin, BranchAdmin) -->
            <?php if (hasRole($userRoles, ['SuperAdmin', 'SchoolAdmin', 'BranchAdmin'])): ?>
                <?= renderMenuItem('users', $icons['users'], 'Users', $currentPage) ?>
            <?php endif; ?>

            <!-- Roles & Permissions (SuperAdmin) -->
            <?php if (hasRole($userRoles, ['SuperAdmin'])): ?>
                <?= renderMenuItem('roles', $icons['shield'], 'Roles & Permissions', $currentPage) ?>
            <?php endif; ?>

            <!-- Modules (SuperAdmin, SchoolAdmin) -->
            <?php if (hasRole($userRoles, ['SuperAdmin', 'SchoolAdmin'])): ?>
                <?= renderMenuItem('modules', $icons['modules'], 'Modules', $currentPage) ?>
            <?php endif; ?>

            <!-- Academic (submenu) -->
            <?php if (hasRole($userRoles, ['SuperAdmin', 'SchoolAdmin', 'BranchAdmin', 'Dean', 'Teacher', 'Student', 'Parent'])): ?>
                <div class="mt-1">
                    <?= renderSubmenuToggle('submenu-academic', $icons['book-open'], 'Academic', 'submenu-academic-arrow', $currentPage, $academicChildSlugs) ?>
                    <div id="submenu-academic" class="mt-1 ml-4 space-y-0.5 <?= $academicOpen ? '' : 'hidden' ?>">
                        <?= renderSubmenuItem('classes', $icons['book-open'], 'Classes', $currentPage) ?>
                        <?= renderSubmenuItem('subjects', $icons['subjects'], 'Subjects', $currentPage) ?>
                        <?= renderSubmenuItem('exams', $icons['exams'], 'Exams', $currentPage) ?>
                        <?= renderSubmenuItem('results', $icons['results'], 'Results', $currentPage) ?>
                        <?= renderSubmenuItem('timetable', $icons['timetable'], 'Timetable', $currentPage) ?>
                        <?= renderSubmenuItem('skills', $icons['skills'], 'Skills', $currentPage) ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Students -->
            <?php if (hasRole($userRoles, ['SuperAdmin', 'SchoolAdmin', 'BranchAdmin', 'Dean', 'Teacher', 'Parent'])): ?>
                <?= renderMenuItem('students', $icons['students'], 'Students', $currentPage) ?>
            <?php endif; ?>

            <!-- Attendance -->
            <?php if (hasRole($userRoles, ['SuperAdmin', 'SchoolAdmin', 'BranchAdmin', 'Dean', 'Teacher'])): ?>
                <?= renderMenuItem('attendance', $icons['attendance'], 'Attendance', $currentPage) ?>
            <?php endif; ?>

            <!-- Finance (submenu) -->
            <?php if (hasRole($userRoles, ['SuperAdmin', 'SchoolAdmin', 'BranchAdmin', 'Accountant', 'Parent'])): ?>
                <div class="mt-1">
                    <?= renderSubmenuToggle('submenu-finance', $icons['dollar'], 'Finance', 'submenu-finance-arrow', $currentPage, $financeChildSlugs) ?>
                    <div id="submenu-finance" class="mt-1 ml-4 space-y-0.5 <?= $financeOpen ? '' : 'hidden' ?>">
                        <?= renderSubmenuItem('fee-structure', $icons['fee-structure'], 'Fee Structure', $currentPage) ?>
                        <?= renderSubmenuItem('payments', $icons['payments'], 'Payments', $currentPage) ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- LMS -->
            <?php if (hasRole($userRoles, ['SuperAdmin', 'SchoolAdmin', 'BranchAdmin', 'Dean', 'Teacher', 'Student'])): ?>
                <?= renderMenuItem('lms', $icons['monitor'], 'LMS', $currentPage) ?>
            <?php endif; ?>

            <!-- AI Assistant (submenu) -->
            <?php if (hasRole($userRoles, ['SuperAdmin', 'SchoolAdmin', 'BranchAdmin', 'Dean', 'Teacher', 'Student'])): ?>
                <div class="mt-1">
                    <?= renderSubmenuToggle('submenu-ai', $icons['bot'], 'AI Assistant', 'submenu-ai-arrow', $currentPage, $aiChildSlugs) ?>
                    <div id="submenu-ai" class="mt-1 ml-4 space-y-0.5 <?= $aiOpen ? '' : 'hidden' ?>">
                        <?= renderSubmenuItem('ai-chat', $icons['chat'], 'Chat', $currentPage) ?>
                        <?= renderSubmenuItem('ai-settings', $icons['ai-settings'], 'Settings', $currentPage) ?>
                        <?= renderSubmenuItem('ai-analytics', $icons['analytics'], 'Analytics', $currentPage) ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Transport -->
            <?php if (hasRole($userRoles, ['SuperAdmin', 'SchoolAdmin', 'BranchAdmin', 'TransportManager', 'Parent'])): ?>
                <?= renderMenuItem('transport', $icons['bus'], 'Transport', $currentPage) ?>
            <?php endif; ?>

            <!-- Library -->
            <?php if (hasRole($userRoles, ['SuperAdmin', 'SchoolAdmin', 'BranchAdmin', 'Librarian', 'Teacher', 'Student'])): ?>
                <?= renderMenuItem('library', $icons['library'], 'Library', $currentPage) ?>
            <?php endif; ?>

            <!-- Hostel -->
            <?php if (hasRole($userRoles, ['SuperAdmin', 'SchoolAdmin', 'BranchAdmin', 'HostelManager'])): ?>
                <?= renderMenuItem('hostel', $icons['home'], 'Hostel', $currentPage) ?>
            <?php endif; ?>

            <!-- Communication -->
            <?php if (hasRole($userRoles, ['SuperAdmin', 'SchoolAdmin', 'BranchAdmin', 'Teacher', 'Parent', 'Student'])): ?>
                <?= renderMenuItem('communication', $icons['communication'], 'Communication', $currentPage) ?>
            <?php endif; ?>

            <!-- Reports -->
            <?php if (hasRole($userRoles, ['SuperAdmin', 'SchoolAdmin', 'BranchAdmin', 'Dean', 'Accountant'])): ?>
                <?= renderMenuItem('reports', $icons['reports'], 'Reports', $currentPage) ?>
            <?php endif; ?>

        </nav>

        <!-- Sidebar Footer -->
        <div class="border-t border-emerald-800 dark:border-gray-800 px-4 py-3">
            <div class="flex items-center gap-3 rounded-lg bg-emerald-800/50 px-3 py-2">
                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-emerald-600 text-xs font-bold text-white">
                    <?= $user ? strtoupper(substr($user['first_name'] ?? 'U', 0, 1)) : 'U' ?>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-xs font-medium text-white"><?= $user ? htmlspecialchars(trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? ''))) : 'User' ?></p>
                    <p class="truncate text-[10px] text-emerald-300"><?= $user ? htmlspecialchars($user['role'] ?? 'User') : 'Role' ?></p>
                </div>
            </div>
        </div>
    </div>
</aside>

<!-- Sidebar overlay spacer for desktop -->
<div class="hidden lg:block w-64 shrink-0"></div>
