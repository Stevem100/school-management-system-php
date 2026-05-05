<?php
$aiSettings = isset($aiSettings) ? $aiSettings : [];
$setting = $aiSettings[0] ?? [];
?>

<!-- Page Header -->
<div class="mb-6">
    <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">AI Settings</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Configure AI model parameters and access control</p>
        </div>
        <button onclick="saveSettings()" class="mt-3 sm:mt-0 inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-emerald-700 transition-colors">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
            </svg>
            Save Settings
        </button>
    </div>
</div>

<!-- Settings Cards -->
<div class="space-y-6">

    <!-- AI Model Configuration -->
    <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
        <div class="flex items-center gap-3 mb-6">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-100 dark:bg-emerald-900">
                <svg class="h-5 w-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3"/>
                </svg>
            </div>
            <div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Model Configuration</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Configure the AI model and API settings</p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">AI Provider</label>
                <select id="aiProvider" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                    <option value="ollama" <?= ($setting['openai_api_url'] ?? '') !== '' && strpos($setting['openai_api_url'] ?? '', 'localhost') !== false ? '' : '' ?>>Ollama (Local)</option>
                    <option value="openai">OpenAI</option>
                    <option value="custom">Custom Endpoint</option>
                </select>
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Model Name</label>
                <input type="text" id="aiModel" value="<?= htmlspecialchars($setting['openai_model'] ?? 'llama3') ?>" placeholder="e.g. llama3, gpt-3.5-turbo" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 placeholder:text-gray-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
            </div>
            <div class="sm:col-span-2">
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">API Endpoint URL</label>
                <input type="url" id="aiApiUrl" value="<?= htmlspecialchars($setting['openai_api_url'] ?? 'http://localhost:11434/v1/chat/completions') ?>" placeholder="http://localhost:11434/v1/chat/completions" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 placeholder:text-gray-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">API Key (if required)</label>
                <input type="password" id="aiApiKey" placeholder="sk-..." class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 placeholder:text-gray-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Max Tokens</label>
                <input type="number" id="aiMaxTokens" value="<?= htmlspecialchars($setting['max_tokens'] ?? '2048') ?>" min="100" max="16000" step="100" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 placeholder:text-gray-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Temperature: <span id="tempValue"><?= htmlspecialchars($setting['temperature'] ?? '0.7') ?></span></label>
                <input type="range" id="aiTemperature" min="0" max="1" step="0.1" value="<?= htmlspecialchars($setting['temperature'] ?? '0.7') ?>" class="w-full accent-emerald-600" oninput="document.getElementById('tempValue').textContent = this.value">
                <div class="flex justify-between text-xs text-gray-400 mt-1">
                    <span>Precise</span>
                    <span>Creative</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Access Control -->
    <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
        <div class="flex items-center gap-3 mb-6">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-amber-100 dark:bg-amber-900">
                <svg class="h-5 w-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Access Control</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Manage who can use the AI assistant</p>
            </div>
        </div>

        <div class="space-y-4">
            <div class="flex items-center justify-between rounded-lg border border-gray-100 px-4 py-3 dark:border-gray-800">
                <div>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">Enable AI Assistant</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Turn on/off the AI chat feature for the school</p>
                </div>
                <label class="relative inline-flex cursor-pointer items-center">
                    <input type="checkbox" id="aiEnabled" class="peer sr-only" <?= ($setting['is_openai_enabled'] ?? 0) ? 'checked' : '' ?>>
                    <div class="h-6 w-11 rounded-full bg-gray-200 peer-checked:bg-emerald-600 after:absolute after:left-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:bg-white after:transition-all peer-checked:after:translate-x-full"></div>
                </label>
            </div>
            <div class="flex items-center justify-between rounded-lg border border-gray-100 px-4 py-3 dark:border-gray-800">
                <div>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">Student Access</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Allow students to use the AI chat</p>
                </div>
                <label class="relative inline-flex cursor-pointer items-center">
                    <input type="checkbox" id="aiStudentAccess" class="peer sr-only" <?= ($setting['is_student_access'] ?? 0) ? 'checked' : '' ?>>
                    <div class="h-6 w-11 rounded-full bg-gray-200 peer-checked:bg-emerald-600 after:absolute after:left-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:bg-white after:transition-all peer-checked:after:translate-x-full"></div>
                </label>
            </div>
            <div class="flex items-center justify-between rounded-lg border border-gray-100 px-4 py-3 dark:border-gray-800">
                <div>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">Teacher Monitoring</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Allow teachers to monitor student AI conversations</p>
                </div>
                <label class="relative inline-flex cursor-pointer items-center">
                    <input type="checkbox" id="aiTeacherMonitor" class="peer sr-only" <?= ($setting['is_teacher_monitor'] ?? 0) ? 'checked' : '' ?>>
                    <div class="h-6 w-11 rounded-full bg-gray-200 peer-checked:bg-emerald-600 after:absolute after:left-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:bg-white after:transition-all peer-checked:after:translate-x-full"></div>
                </label>
            </div>
            <div class="flex items-center justify-between rounded-lg border border-gray-100 px-4 py-3 dark:border-gray-800">
                <div>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">Free Chat Mode</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Allow unrestricted AI conversations (not just academic)</p>
                </div>
                <label class="relative inline-flex cursor-pointer items-center">
                    <input type="checkbox" id="aiFreeChat" class="peer sr-only" <?= ($setting['allow_free_chat'] ?? 0) ? 'checked' : '' ?>>
                    <div class="h-6 w-11 rounded-full bg-gray-200 peer-checked:bg-emerald-600 after:absolute after:left-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:bg-white after:transition-all peer-checked:after:translate-x-full"></div>
                </label>
            </div>
        </div>
    </div>

    <!-- System Prompt -->
    <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
        <div class="flex items-center gap-3 mb-4">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-violet-100 dark:bg-violet-900">
                <svg class="h-5 w-5 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                </svg>
            </div>
            <div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">System Prompt</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Define the AI assistant's behavior and personality</p>
            </div>
        </div>
        <textarea id="systemPrompt" rows="4" placeholder="You are a helpful AI tutor for a school management system..." class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 placeholder:text-gray-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">You are a helpful AI learning assistant for students at Greenfield Academy. Provide clear, educational answers. Help students understand concepts, solve problems, and learn effectively. Always encourage critical thinking and provide explanations that are age-appropriate.</textarea>
    </div>

</div>

<script>
    function saveSettings() {
        // Visual feedback
        const btn = event.target.closest('button');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Saving...';
        btn.disabled = true;

        setTimeout(() => {
            btn.innerHTML = '<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg> Saved!';
            btn.classList.remove('bg-emerald-600', 'hover:bg-emerald-700');
            btn.classList.add('bg-emerald-500');
            
            setTimeout(() => {
                btn.innerHTML = originalText;
                btn.disabled = false;
                btn.classList.remove('bg-emerald-500');
                btn.classList.add('bg-emerald-600', 'hover:bg-emerald-700');
            }, 2000);
        }, 1500);
    }
</script>
