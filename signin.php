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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['lookup_id_number'])) {
    $lookup_id_number = trim($_POST['lookup_id_number'] ?? '');

    if ($lookup_id_number === '') {
        $error = 'Please enter your ID number.';
    } else {
        $stmt = $pdo->prepare("SELECT id FROM visitors WHERE id_number = ?");
        $stmt->execute([$lookup_id_number]);

        if ($stmt->fetch()) {
            header("Location: signin.php?id=" . urlencode($lookup_id_number));
            exit;
        }

        $error = 'No registered visitor found for that ID number. Please register first.';
        $id_number = $lookup_id_number;
    }
}

if (!empty($id_number) && empty($error)) {
    // Fetch visitor info
    $stmt = $pdo->prepare("SELECT * FROM visitors WHERE id_number = ?");
    $stmt->execute([$id_number]);
    $visitor = $stmt->fetch();

    if (!$visitor) {
        header("Location: register.php?id=" . urlencode($id_number));
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($status) && $visitor) {
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
        <?php if (!$visitor): ?>
            <h1>Visitor Sign In</h1>
            <p>Enter your registered ID number to continue.</p>

            <?php if ($error): ?>
                <div class="alert error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST" action="signin.php" class="form-group mt-3">
                <label for="lookup_id_number">ID Number</label>
                <input
                    type="text"
                    id="lookup_id_number"
                    name="lookup_id_number"
                    value="<?= htmlspecialchars($id_number) ?>"
                    required
                    autofocus
                >

                <div class="actions">
                    <a href="index.php" class="btn btn-secondary">Back to Home</a>
                    <button type="submit" class="btn btn-primary">Continue</button>
                </div>
            </form>

            <p class="mt-3">First time visiting? <a href="register.php<?= $id_number ? '?id=' . urlencode($id_number) : '' ?>">Register here</a>.</p>
        <?php else: ?>
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
        <?php endif; ?>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>
