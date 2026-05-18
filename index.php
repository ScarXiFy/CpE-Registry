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

require_once 'config/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_number = trim($_POST['id_number'] ?? '');
    
    if (!empty($id_number)) {
        // Check if ID exists in visitors
        $stmt = $pdo->prepare("SELECT id FROM visitors WHERE id_number = ?");
        $stmt->execute([$id_number]);
        $visitor = $stmt->fetch();
        
        if ($visitor) {
            // Visitor exists, go to sign in
            header("Location: signin.php?id=" . urlencode($id_number));
            exit;
        } else {
            // Visitor does not exist, go to register
            header("Location: register.php?id=" . urlencode($id_number));
            exit;
        }
    } else {
        $error = 'Please enter your ID number.';
    }
}

require_once 'includes/header.php';
?>

<main class="container">
    <div class="card">
        <h1>CpE Department — Contact Tracing</h1>
        <p>Enter your ID number to sign in or register.</p>
        
        <?php if ($error): ?>
            <div class="alert error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="index.php" class="form-group">
            <label for="id_number">ID Number:</label>
            <input type="text" id="id_number" name="id_number" required autofocus>
            <button type="submit" class="btn btn-primary">Continue</button>
        </form>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>
