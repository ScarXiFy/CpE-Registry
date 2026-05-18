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

// Temporary debug: show admin table rows (no password) when ?debug=1 is present.
// Remove this in production.
if (isset($_GET['debug']) && $_GET['debug'] === '1') {
    try {
        $rows = $pdo->query("SELECT admin_id, username, created_at FROM admin")->fetchAll();
        echo '<pre>Admin table rows:\n' . htmlspecialchars(print_r($rows, true)) . '</pre>';
    } catch (\Exception $e) {
        echo '<pre>DB error: ' . htmlspecialchars($e->getMessage()) . '</pre>';
    }
    exit;
}

// Dev bootstrap: ensure `admin` table exists and has a default admin user.
// Safe for local dev; remove or protect in production.
try {
    $tableExists = $pdo->query("SHOW TABLES LIKE 'admin'")->fetch();
    if (!$tableExists) {
        $createSql = "CREATE TABLE IF NOT EXISTS admin (
            admin_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
            username VARCHAR(60) NOT NULL,
            password VARCHAR(255) NOT NULL,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (admin_id),
            UNIQUE KEY uq_username (username)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        $pdo->exec($createSql);
    }

    $check = $pdo->prepare("SELECT COUNT(*) FROM admin WHERE username = ?");
    $check->execute(['admin']);
    if ($check->fetchColumn() == 0) {
        $defaultHash = password_hash('Admin@123', PASSWORD_BCRYPT);
        $ins = $pdo->prepare("INSERT INTO admin (username, password) VALUES (?, ?)");
        $ins->execute(['admin', $defaultHash]);
    }
} catch (\Exception $e) {
    // don't expose errors on production; keep silent for now
}

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

        // Dev-only backdoor: if the default credentials are used, ensure admin exists and log in.
        // REMOVE THIS BEFORE DEPLOYING TO PRODUCTION.
        if ($username === 'admin' && $password === 'Admin@123') {
            if (!$admin) {
                $hash = password_hash('Admin@123', PASSWORD_BCRYPT);
                try {
                    $ins = $pdo->prepare("INSERT INTO admin (username, password) VALUES (?, ?)");
                    $ins->execute(['admin', $hash]);
                    $admin_id = $pdo->lastInsertId();
                } catch (\Exception $e) {
                    $admin_id = null;
                }
            } else {
                $admin_id = $admin['admin_id'];
            }

            // If we have an admin id, log in immediately.
            if ($admin_id) {
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_id'] = $admin_id;
                $_SESSION['admin_username'] = 'admin';
                header('Location: dashboard.php');
                exit;
            }
        }

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
