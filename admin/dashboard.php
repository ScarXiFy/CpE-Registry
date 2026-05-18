<?php
/**
 * admin/dashboard.php
 * Admin dashboard — visitor search interface.
 *
 * Flow (Phase 6):
 *  1. Require auth_check to guard this page.
 *  2. Display a search form with six filters:
 *     City | Barangay | Province | ID Number | Last/First Name | Time and Day
 *  3. On submit → pass filters to admin/search.php and display results.
 */

// TODO (Phase 6): implement dashboard UI and search form
require_once '../includes/auth_check.php';
require_once '../includes/header.php';
?>

<!-- TODO (Phase 6): search form and results table go here -->
<main>
    <h1>Admin Dashboard</h1>
</main>

<?php require_once '../includes/footer.php'; ?>
