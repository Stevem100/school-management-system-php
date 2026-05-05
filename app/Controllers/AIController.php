<?php

declare(strict_types=1);

namespace App\Controllers;

/**
 * AI Assistant Controller
 *
 * Handles AI chat, settings, and analytics pages.
 */
class AIController extends Controller
{
    /**
     * AI Chat page.
     */
    public function chat(): void
    {
        $this->requireAuth();
        $this->requirePermission('ai.view');

        $this->renderWithLayout('ai/chat', [
            'pageTitle'  => 'AI Chat',
            'currentPage' => 'ai-chat',
        ]);
    }

    /**
     * AI Settings page.
     */
    public function settings(): void
    {
        $this->requireAuth();
        $this->requirePermission('ai.manage');

        // Fetch AI settings from database
        $aiSettings = [];
        try {
            $aiSettings = $this->db->select('ai_settings', [], 'id DESC', 10);
        } catch (\RuntimeException $e) {
            // Return defaults
        }

        $this->renderWithLayout('ai/settings', [
            'pageTitle'  => 'AI Settings',
            'currentPage' => 'ai-settings',
            'aiSettings' => $aiSettings,
        ]);
    }

    /**
     * AI Analytics page.
     */
    public function analytics(): void
    {
        $this->requireAuth();
        $this->requirePermission('ai.view');

        // Fetch AI usage statistics
        $stats = [
            'total_conversations' => 0,
            'total_messages'      => 0,
            'active_users'        => 0,
            'total_tokens'        => 0,
        ];

        try {
            $result = $this->db->raw("SELECT COUNT(*) as cnt FROM ai_conversations");
            $stats['total_conversations'] = (int) ($result[0]['cnt'] ?? 0);

            $result = $this->db->raw("SELECT COUNT(*) as cnt FROM ai_messages");
            $stats['total_messages'] = (int) ($result[0]['cnt'] ?? 0);

            $result = $this->db->raw("SELECT COUNT(DISTINCT user_id) as cnt FROM ai_conversations");
            $stats['active_users'] = (int) ($result[0]['cnt'] ?? 0);
        } catch (\RuntimeException $e) {
            // Return defaults
        }

        $this->renderWithLayout('ai/analytics', [
            'pageTitle'  => 'AI Analytics',
            'currentPage' => 'ai-analytics',
            'stats'      => $stats,
        ]);
    }
}
