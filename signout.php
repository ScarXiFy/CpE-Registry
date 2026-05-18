<?php
/**
 * signout.php
 * Visitor sign-out.
 *
 * Flow (Phase 5):
 *  1. Accept visitor ID (via GET or session).
 *  2. Find the open visit_log row (sign_out IS NULL) for this visitor.
 *  3. UPDATE that row with the current timestamp as sign_out.
 *  4. Show a sign-out success message.
 */

// TODO (Phase 5): implement sign-out logic
require_once 'includes/header.php';
?>

<!-- TODO (Phase 5): sign-out UI goes here -->
<main>
    <h1>Sign Out</h1>
</main>

<?php require_once 'includes/footer.php'; ?>
