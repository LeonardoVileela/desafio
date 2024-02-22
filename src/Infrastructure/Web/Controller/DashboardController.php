<?php

namespace Infrastructure\Web\Controller;


class DashboardController
{
    public function showDashboard()
    {
        require_once __DIR__ . '/../View/dashboard/dashboard.php';
    }
}
