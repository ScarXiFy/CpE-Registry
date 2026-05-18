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

require_once 'config/db.php';

$id_number = $_GET['id'] ?? '';
$status = $_GET['status'] ?? '';
$error = '';
$visitor = null;

if (empty($id_number)) {
    header("Location: index.php");
    exit;
}

// Fetch visitor info
$stmt = $pdo->prepare("SELECT * FROM visitors WHERE id_number = ?");
$stmt->execute([$id_number]);
$visitor = $stmt->fetch();

if (!$visitor) {
    header("Location: register.php?id=" . urlencode($id_number));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($status)) {
    try {
        $stmtLog = $pdo->prepare("INSERT INTO visit_logs (visitor_id) VALUES (?)");
        $stmtLog->execute([$visitor['id']]);
        
        header("Location: signin.php?id=" . urlencode($id_number) . "&status=success");
        exit;
    } catch (\PDOException $e) {
        $error = 'Sign in failed. Please try again.';
    }
}

require_once 'includes/header.php';
?>

<main class="container">
    <div class="card">
        <h1>Sign In Confirmation</h1>
        
        <?php if ($error): ?>
            <div class="alert error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($status === 'registered'): ?>
            <div class="alert success">
                <strong>Registration Successful!</strong> You have been automatically signed in.
            </div>
            <p>Welcome, <?= htmlspecialchars($visitor['first_name']) ?>!</p>
            <a href="index.php" class="btn btn-primary">Go to Home</a>
            
        <?php elseif ($status === 'success'): ?>
            <div class="alert success">
                <strong>Sign In Successful!</strong>
            </div>
            <p>Welcome back, <?= htmlspecialchars($visitor['first_name']) ?>!</p>
            <a href="index.php" class="btn btn-primary">Go to Home</a>
            
        <?php else: ?>
            <p>Welcome back, <strong><?= htmlspecialchars($visitor['first_name'] . ' ' . $visitor['last_name']) ?></strong>!</p>
            <p>Please confirm your sign-in below.</p>
            
            <form method="POST" action="signin.php?id=<?= urlencode($id_number) ?>" class="form-group mt-3">
                <div class="actions">
                    <a href="index.php" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Confirm Sign In</button>
                </div>
            </form>
        <?php endif; ?>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>
