<!-- Page Header -->
<div class="mb-6">
    <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">AI Chat Assistant</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Ask questions, get help with assignments, and explore learning resources</p>
        </div>
        <button onclick="clearChat()" class="mt-3 sm:mt-0 inline-flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
            Clear Chat
        </button>
    </div>
</div>

<!-- Chat Container -->
<div class="flex flex-col rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900" style="height: calc(100vh - 16rem);">
    
    <!-- Chat Messages -->
    <div id="chatMessages" class="flex-1 overflow-y-auto p-4 lg:p-6 space-y-4">
        
        <!-- Welcome Message -->
        <div class="flex items-start gap-3">
            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-emerald-100 dark:bg-emerald-900">
                <svg class="h-4 w-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0112 15a9.065 9.065 0 00-6.23.693L5 14.5m14.8.8l1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0112 21c-2.773 0-5.491-.235-8.135-.687-1.718-.293-2.3-2.379-1.067-3.61L5 14.5"/>
                </svg>
            </div>
            <div class="max-w-[80%] rounded-2xl rounded-tl-sm bg-gray-100 px-4 py-3 dark:bg-gray-800">
                <p class="text-sm text-gray-900 dark:text-white">Hello! I'm your AI learning assistant. I can help you with:</p>
                <ul class="mt-2 space-y-1 text-sm text-gray-600 dark:text-gray-400">
                    <li class="flex items-center gap-2">
                        <svg class="h-3 w-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        Answering questions about your subjects
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="h-3 w-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        Explaining concepts and topics
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="h-3 w-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        Helping with assignments and homework
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="h-3 w-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        Providing study tips and resources
                    </li>
                </ul>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">How can I help you today?</p>
            </div>
        </div>

    </div>

    <!-- Quick Actions -->
    <div class="border-t border-gray-100 px-4 py-3 dark:border-gray-800">
        <div class="flex flex-wrap gap-2 mb-3">
            <button onclick="sendQuickMessage('Explain the water cycle')" class="rounded-full border border-gray-200 bg-white px-3 py-1.5 text-xs font-medium text-gray-600 hover:bg-emerald-50 hover:border-emerald-200 hover:text-emerald-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-emerald-900/30 dark:hover:border-emerald-800 dark:hover:text-emerald-300 transition-colors">
                Explain the water cycle
            </button>
            <button onclick="sendQuickMessage('Help me with math fractions')" class="rounded-full border border-gray-200 bg-white px-3 py-1.5 text-xs font-medium text-gray-600 hover:bg-emerald-50 hover:border-emerald-200 hover:text-emerald-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-emerald-900/30 dark:hover:border-emerald-800 dark:hover:text-emerald-300 transition-colors">
                Help with math fractions
            </button>
            <button onclick="sendQuickMessage('What are the causes of climate change?')" class="rounded-full border border-gray-200 bg-white px-3 py-1.5 text-xs font-medium text-gray-600 hover:bg-emerald-50 hover:border-emerald-200 hover:text-emerald-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-emerald-900/30 dark:hover:border-emerald-800 dark:hover:text-emerald-300 transition-colors">
                Causes of climate change
            </button>
            <button onclick="sendQuickMessage('Give me study tips for exams')" class="rounded-full border border-gray-200 bg-white px-3 py-1.5 text-xs font-medium text-gray-600 hover:bg-emerald-50 hover:border-emerald-200 hover:text-emerald-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-emerald-900/30 dark:hover:border-emerald-800 dark:hover:text-emerald-300 transition-colors">
                Study tips for exams
            </button>
        </div>

        <!-- Input Area -->
        <form onsubmit="sendMessage(event)" class="flex items-end gap-2">
            <div class="flex-1 relative">
                <textarea id="chatInput" rows="1" placeholder="Type your message..." class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 pr-12 text-sm text-gray-900 placeholder:text-gray-400 focus:border-emerald-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder:text-gray-500 resize-none" onkeydown="handleChatKeydown(event)" oninput="autoResize(this)"></textarea>
            </div>
            <button type="submit" id="sendBtn" class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-emerald-600 text-white shadow-sm hover:bg-emerald-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/>
                </svg>
            </button>
        </form>
    </div>
</div>

<script>
    function sendMessage(e) {
        e.preventDefault();
        const input = document.getElementById('chatInput');
        const message = input.value.trim();
        if (!message) return;

        // Add user message
        addMessage(message, 'user');
        input.value = '';
        input.style.height = 'auto';

        // Simulate AI response
        setTimeout(() => {
            addMessage("Thank you for your question! The AI assistant is currently being configured. Once connected to an AI model, I'll be able to provide detailed answers to your questions.", 'assistant');
        }, 1000);
    }

    function sendQuickMessage(text) {
        document.getElementById('chatInput').value = text;
        sendMessage(new Event('submit'));
    }

    function addMessage(text, sender) {
        const container = document.getElementById('chatMessages');
        const div = document.createElement('div');
        
        if (sender === 'user') {
            div.className = 'flex items-start gap-3 justify-end';
            div.innerHTML = `
                <div class="max-w-[80%] rounded-2xl rounded-tr-sm bg-emerald-600 px-4 py-3">
                    <p class="text-sm text-white">${escapeHtml(text)}</p>
                </div>
                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-emerald-600 text-xs font-bold text-white">
                    ${document.querySelector('[data-user-initials]')?.dataset.userInitials || 'U'}
                </div>
            `;
        } else {
            div.className = 'flex items-start gap-3';
            div.innerHTML = `
                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-emerald-100 dark:bg-emerald-900">
                    <svg class="h-4 w-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0112 15a9.065 9.065 0 00-6.23.693L5 14.5m14.8.8l1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0112 21c-2.773 0-5.491-.235-8.135-.687-1.718-.293-2.3-2.379-1.067-3.61L5 14.5"/>
                    </svg>
                </div>
                <div class="max-w-[80%] rounded-2xl rounded-tl-sm bg-gray-100 px-4 py-3 dark:bg-gray-800">
                    <p class="text-sm text-gray-900 dark:text-white">${escapeHtml(text)}</p>
                </div>
            `;
        }

        container.appendChild(div);
        container.scrollTop = container.scrollHeight;
    }

    function clearChat() {
        if (confirm('Clear all chat messages?')) {
            const container = document.getElementById('chatMessages');
            container.innerHTML = '';
            // Re-add welcome message
            location.reload();
        }
    }

    function handleChatKeydown(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage(e);
        }
    }

    function autoResize(el) {
        el.style.height = 'auto';
        el.style.height = Math.min(el.scrollHeight, 120) + 'px';
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
</script>
