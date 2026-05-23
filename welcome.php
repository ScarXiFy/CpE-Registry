<?php

require_once 'config/db.php';

$id_number = $_GET['id'] ?? '';
$status = $_GET['status'] ?? '';
$visitor = null;
$activeVisit = null;

if ($id_number === '') {
    header('Location: signin.php');
    exit;
}

$stmt = $pdo->prepare("SELECT id, id_number, first_name, last_name FROM visitors WHERE id_number = ?");
$stmt->execute([$id_number]);
$visitor = $stmt->fetch();

if (!$visitor) {
    header("Location: signin.php?id=" . urlencode($id_number));
    exit;
}

$stmtLog = $pdo->prepare("SELECT sign_in FROM visit_logs WHERE visitor_id = ? AND sign_out IS NULL ORDER BY sign_in DESC LIMIT 1");
$stmtLog->execute([$visitor['id']]);
$activeVisit = $stmtLog->fetch();

$hide_admin_login = true;

require_once 'includes/header.php';
?>

<main class="signed-in-page">
    <section class="hero-section infinite-grid-hero signed-in-hero" data-grid-hero>
        <div class="grid-layer grid-layer-soft" aria-hidden="true"></div>
        <div class="grid-layer grid-layer-active" aria-hidden="true"></div>
        <div class="hero-glow hero-glow-gold" aria-hidden="true"></div>
        <div class="hero-glow hero-glow-blue" aria-hidden="true"></div>
        <div class="hero-glow hero-glow-cyan" aria-hidden="true"></div>

        <div class="hero-content signed-in-content reveal-on-scroll">
            <div class="hero-logo-large">
                <img src="/CpE-Registry/assets/img/usc-cpe-logo.png" alt="USC CpE Logo" class="hero-logo-img">
            </div>

            <?php if ($status === 'registered'): ?>
                <span class="welcome-kicker">Registration Complete</span>
            <?php else: ?>
                <span class="welcome-kicker">Signed In</span>
            <?php endif; ?>

            <h1 class="hero-title">Welcome to USC CpE!</h1>
            <p class="hero-subtitle">
                Hello, <?= htmlspecialchars($visitor['first_name'] . ' ' . $visitor['last_name']) ?>. Your visit has been recorded.
            </p>

            <?php if ($activeVisit): ?>
                <p class="welcome-meta">
                    Signed in at <?= htmlspecialchars(date('M d, Y h:i A', strtotime($activeVisit['sign_in']))) ?>
                </p>
            <?php endif; ?>

            <?php if (preg_match('/^[0-9]{5}$/', $visitor['id_number'])): ?>
                <p class="welcome-reference">
                    Reference Code: <strong><?= htmlspecialchars($visitor['id_number']) ?></strong>
                </p>
            <?php endif; ?>

            <div class="hero-actions signed-in-actions">
                <form method="POST" action="signout.php" class="signout-inline-form">
                    <input type="hidden" name="id_number" value="<?= htmlspecialchars($visitor['id_number']) ?>">
                    <button type="submit" class="btn btn-danger btn-lg">Sign Out</button>
                </form>
            </div>
        </div>
    </section>
</main>

<?php require_once 'includes/footer.php'; ?>
