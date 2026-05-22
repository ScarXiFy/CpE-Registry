<?php
/**
 * admin/login.php
 * Admin login page.
 *
 * Flow (Phase 6):
 *  1. Display username + password form.
 *  2. On submit → verify against hardcoded or `admin` table credentials.
 *  3. On success → start session, redirect to admin/dashboard.php.
 *  4. On failure → show error message.
 */

session_start();
require_once '../config/db.php';

$error = '';

// If already logged in, redirect to dashboard
if (!empty($_SESSION['admin_logged_in'])) {
    header('Location: dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password.';
    } else {
        $stmt = $pdo->prepare("SELECT admin_id, password FROM admin WHERE username = ?");
        $stmt->execute([$username]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password'])) {
            // Success
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $admin['admin_id'];
            $_SESSION['admin_username'] = $username;
            
            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Invalid username or password.';
        }
    }
}

require_once '../includes/header.php';
?>

<main class="container">
    <div class="card">
        <h1>Admin Login</h1>
        <p>Sign in to access the visitor management dashboard.</p>

        <?php if ($error): ?>
            <div class="alert error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="login.php" class="form-group mt-3">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required autofocus>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <div class="actions">
                <a href="../index.php" class="btn btn-secondary">Back to Site</a>
                <button type="submit" class="btn btn-primary">Login</button>
            </div>
        </form>
    </div>
</main>

<?php require_once '../includes/footer.php'; ?>
