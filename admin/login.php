<?php
/**
 * admin/login.php
 * Admin login page.
 *
 * Flow (Phase 6):
 *  1. Display username + password form.
 *  2. On submit → verify against hardcoded or `admin` table credentials.
 *  3. On success → start session, redirect to admin/dashboard.php.
 *  4. On failure → show error message.
 */

// TODO (Phase 6): implement admin login logic
require_once '../includes/header.php';
?>

<!-- TODO (Phase 6): admin login form goes here -->
<main>
    <h1>Admin Login</h1>
</main>

<?php require_once '../includes/footer.php'; ?>
