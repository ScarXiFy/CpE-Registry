<?php
/**
 * index.php
 * Visitor landing page — ID lookup + routing.
 * 
 * Flow (Phase 5):
 *  1. Show a form asking for the visitor's ID number.
 *  2. If the ID exists in `visitors` → redirect to signin.php.
 *  3. If the ID does not exist → redirect to register.php.
 */

// TODO (Phase 5): implement lookup logic
require_once 'includes/header.php';
?>

<!-- TODO (Phase 5): ID lookup form goes here -->
<main>
    <h1>CpE Department — Contact Tracing</h1>
    <p>Enter your ID number to sign in or register.</p>
</main>

<?php require_once 'includes/footer.php'; ?>
