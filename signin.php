<?php
/**
 * signin.php
 * Returning visitor sign-in.
 *
 * Flow (Phase 5):
 *  1. Pre-fill visitor info fetched by ID from `visitors`.
 *  2. On confirm → INSERT a new row into `visit_logs` with sign_in timestamp.
 *  3. Show a success confirmation.
 */

// TODO (Phase 5): implement sign-in logic
require_once 'includes/header.php';
?>

<!-- TODO (Phase 5): sign-in confirmation UI goes here -->
<main>
    <h1>Sign In</h1>
</main>

<?php require_once 'includes/footer.php'; ?>
