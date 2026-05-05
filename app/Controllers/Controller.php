<?php

declare(strict_types=1);

namespace App\Controllers;

use Core\Controller as CoreController;
use Core\Session;

/**
 * Base Application Controller
 *
 * Extends the Core\Controller with layout rendering support.
 * All admin module controllers extend this class.
 */
class Controller extends CoreController
{
    /**
     * Render a view within the application layout.
     *
     * Wraps the given view content inside the main app layout
     * (layouts/app.php) which includes header, sidebar, footer,
     * and flash message handling.
     *
     * @param string $viewPath View path relative to views/ (e.g. 'schools.index')
     * @param array  $data     Data to pass to the view
     * @return void
     */
    protected function renderWithLayout(string $viewPath, array $data = []): void
    {
        $user = Session::get('user');
        $userRoles = Session::get('user_roles', []);
        $currentPage = $data['currentPage'] ?? '';

        // Build flash messages
        $data['flashSuccess'] = Session::getFlash('success', '');
        $data['flashError'] = Session::getFlash('error', '');
        $data['user'] = $user;
        $data['userRoles'] = $userRoles;

        // Start output buffering to capture view content
        ob_start();
        extract($data, EXTR_SKIP);

        $viewFile = dirname(dirname(__DIR__)) . '/views/' . str_replace('.', '/', $viewPath) . '.php';

        if (file_exists($viewFile)) {
            require $viewFile;
        }

        $data['content'] = ob_get_clean();

        // Render the layout with captured content
        $layoutFile = dirname(dirname(__DIR__)) . '/views/layouts/app.php';

        extract($data, EXTR_SKIP);
        require $layoutFile;
    }
}
