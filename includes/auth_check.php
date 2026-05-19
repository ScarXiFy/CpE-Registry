<?php
/**
 * includes/auth_check.php
 * Session guard for admin pages — to be implemented in Phase 7.
 *
 * Include this at the top of every admin page.
 * If the admin is not logged in, it redirects to admin/login.php.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}
