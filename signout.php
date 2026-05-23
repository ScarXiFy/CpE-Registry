<?php

require_once 'config/db.php';

$id_number = $_GET['id'] ?? ($_POST['id_number'] ?? '');
$status = $_GET['status'] ?? '';
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($id_number)) {
    // Lookup visitor
    $stmt = $pdo->prepare("SELECT id, first_name FROM visitors WHERE id_number = ?");
    $stmt->execute([$id_number]);
    $visitor = $stmt->fetch();

    if ($visitor) {
        // Find active visit (sign_out IS NULL)
        $stmtLog = $pdo->prepare("SELECT log_id FROM visit_logs WHERE visitor_id = ? AND sign_out IS NULL ORDER BY sign_in DESC LIMIT 1");
        $stmtLog->execute([$visitor['id']]);
        $activeLog = $stmtLog->fetch();

        if ($activeLog) {
            // Update sign_out timestamp
            $update = $pdo->prepare("UPDATE visit_logs SET sign_out = CURRENT_TIMESTAMP WHERE log_id = ?");
            $update->execute([$activeLog['log_id']]);
            
            header("Location: signout.php?status=success&name=" . urlencode($visitor['first_name']));
            exit;
        } else {
            $error = 'No active sign-in found for this ID.';
        }
    } else {
        $error = 'Visitor ID not found.';
    }
}

require_once 'includes/header.php';
?>

<main class="container">
    <div class="card">
        <h1>Sign Out</h1>
        
        <?php if ($status === 'success'): ?>
            <div class="alert success">
                <strong>Sign Out Successful!</strong>
                <p>Thank you for visiting, <?= htmlspecialchars($_GET['name'] ?? 'Visitor') ?>. Have a great day!</p>
            </div>
            <a href="index.php" class="btn btn-primary">Go to Home</a>
            
        <?php else: ?>
            <p>Enter your ID number to sign out.</p>
            
            <?php if ($error): ?>
                <div class="alert error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST" action="signout.php" class="form-group mt-3">
                <label for="id_number">ID Number:</label>
                <input type="text" id="id_number" name="id_number" value="<?= htmlspecialchars($id_number) ?>" required autofocus>
                
                <div class="actions">
                    <a href="index.php" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-danger">Sign Out</button>
                </div>
            </form>
        <?php endif; ?>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>
