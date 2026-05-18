<?php
/**
 * register.php
 * First-time visitor registration form.
 *
 * Flow (Phase 5):
 *  1. Display a form: ID Number, Full Name, Address, Contact, Email.
 *  2. On submit → INSERT into `visitors`, then auto-sign-in (INSERT into `visit_logs`).
 *  3. Redirect to a sign-in confirmation screen.
 */

// TODO (Phase 5): implement registration logic
require_once 'includes/header.php';
?>

<!-- TODO (Phase 5): registration form goes here -->
<main>
    <h1>New Visitor Registration</h1>
</main>

<?php require_once 'includes/footer.php'; ?>
