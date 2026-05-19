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
require_once 'includes/visit_log.php';

$id_number = $_GET['id'] ?? '';
$visitor_type = 'usc';
$status = $_GET['status'] ?? '';
$error = '';
$visitor = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['lookup_id_number'])) {
    $visitor_type = $_POST['visitor_type'] ?? 'usc';
    $lookup_id_number = trim($_POST['lookup_id_number'] ?? '');
    $lookup_label = $visitor_type === 'non_usc' ? 'reference code' : 'ID number';

    if (!in_array($visitor_type, ['usc', 'non_usc'], true)) {
        $error = 'Please select whether you are from USC.';
    } elseif ($lookup_id_number === '') {
        $error = 'Please enter your ' . $lookup_label . '.';
    } elseif ($visitor_type === 'usc' && preg_match('/^[0-9]{5}$/', $lookup_id_number)) {
        $error = 'Please enter your USC ID number, not a visitor reference code.';
        $id_number = $lookup_id_number;
    } elseif ($visitor_type === 'non_usc' && !preg_match('/^[0-9]{5}$/', $lookup_id_number)) {
        $error = 'Please enter your 5-digit visitor reference code.';
        $id_number = $lookup_id_number;
    } else {
        $stmt = $pdo->prepare("SELECT id FROM visitors WHERE id_number = ?");
        $stmt->execute([$lookup_id_number]);

        if ($stmt->fetch()) {
            header("Location: signin.php?id=" . urlencode($lookup_id_number));
            exit;
        }

        $error = 'No registered visitor found for that ' . $lookup_label . '. Please register first.';
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
        recordVisitorSignIn($pdo, (int) $visitor['id']);
        
        header("Location: welcome.php?id=" . urlencode($id_number) . "&status=success");
        exit;
    } catch (\PDOException $e) {
        $error = 'Sign in failed. Please try again.';
    }
}

require_once 'includes/header.php';
?>

<main class="auth-page">
    <div class="card">
        <?php if (!$visitor): ?>
            <h1>Visitor Sign In</h1>
            <p>Tell us whether you are from USC, then enter your ID number or reference code.</p>

            <?php if ($error): ?>
                <div class="alert error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST" action="signin.php" class="form-group mt-3" data-membership-form>
                <fieldset class="field membership-field">
                    <legend>Are you from USC?</legend>
                    <div class="membership-options">
                        <label class="membership-option">
                            <input type="radio" name="visitor_type" value="usc" <?= $visitor_type === 'usc' ? 'checked' : '' ?>>
                            <span>Yes, from USC</span>
                        </label>
                        <label class="membership-option">
                            <input type="radio" name="visitor_type" value="non_usc" <?= $visitor_type === 'non_usc' ? 'checked' : '' ?>>
                            <span>No, visitor</span>
                        </label>
                    </div>
                </fieldset>

                <label for="lookup_id_number" data-membership-label data-reference-label="Reference Code">ID Number</label>
                <input
                    type="text"
                    id="lookup_id_number"
                    name="lookup_id_number"
                    value="<?= htmlspecialchars($id_number) ?>"
                    data-membership-id
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
            <a href="welcome.php?id=<?= urlencode($id_number) ?>&status=registered" class="btn btn-primary">Continue</a>
            
        <?php elseif ($status === 'success'): ?>
            <div class="alert success">
                <strong>Sign In Successful!</strong>
            </div>
            <p>Welcome back, <?= htmlspecialchars($visitor['first_name']) ?>!</p>
            <a href="welcome.php?id=<?= urlencode($id_number) ?>&status=success" class="btn btn-primary">Continue</a>
            
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
