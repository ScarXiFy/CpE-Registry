<?php
/**
 * register.php
 * First-time visitor registration form.
 *
 * Flow (Phase 5):
 *  1. Display a form: ID Number, Full Name, Address, Contact, Email.
 *  2. On submit → INSERT into `visitors`, then auto-sign-in (INSERT into `visit_logs`).
 *  3. Redirect to a sign-in confirmation screen.
 */

require_once 'config/db.php';

$id_number = $_GET['id'] ?? '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_number = trim($_POST['id_number'] ?? '');
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $barangay = trim($_POST['barangay'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $province = trim($_POST['province'] ?? '');
    $contact_number = trim($_POST['contact_number'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if (empty($id_number) || empty($first_name) || empty($last_name) || empty($barangay) || empty($city) || empty($province) || empty($contact_number) || empty($email)) {
        $error = 'All fields are required.';
    } else {
        try {
            $pdo->beginTransaction();

            // Insert new visitor
            $stmt = $pdo->prepare("INSERT INTO visitors (id_number, first_name, last_name, barangay, city, province, contact_number, email) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$id_number, $first_name, $last_name, $barangay, $city, $province, $contact_number, $email]);
            $visitor_id = $pdo->lastInsertId();

            // Auto sign-in
            $stmtLog = $pdo->prepare("INSERT INTO visit_logs (visitor_id) VALUES (?)");
            $stmtLog->execute([$visitor_id]);

            $pdo->commit();

            // Redirect to success page
            header("Location: signin.php?id=" . urlencode($id_number) . "&status=registered");
            exit;
        } catch (\PDOException $e) {
            $pdo->rollBack();
            if ($e->getCode() == 23000) { // Unique constraint violation
                $error = 'A visitor with this ID number is already registered.';
            } else {
                $error = 'Registration failed. Please try again.';
            }
        }
    }
}

require_once 'includes/header.php';
?>

<main class="container">
    <div class="card">
        <h1>New Visitor Registration</h1>
        <p>Please fill out the form to register and automatically sign in.</p>

        <?php if ($error): ?>
            <div class="alert error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="register.php" class="form-group">
            <label for="id_number">ID Number</label>
            <input type="text" id="id_number" name="id_number" value="<?= htmlspecialchars($id_number) ?>" required readonly>

            <label for="first_name">First Name</label>
            <input type="text" id="first_name" name="first_name" required autofocus>

            <label for="last_name">Last Name</label>
            <input type="text" id="last_name" name="last_name" required>

            <label for="barangay">Barangay</label>
            <input type="text" id="barangay" name="barangay" required>

            <label for="city">City</label>
            <input type="text" id="city" name="city" required>

            <label for="province">Province</label>
            <input type="text" id="province" name="province" required>

            <label for="contact_number">Contact Number</label>
            <input type="text" id="contact_number" name="contact_number" required>

            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" required>

            <div class="actions">
                <a href="index.php" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Register & Sign In</button>
            </div>
        </form>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>
