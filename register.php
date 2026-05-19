<?php
/**
 * register.php
 * First-time visitor registration form.
 *
 * Flow (Phase 5):
 *  1. Display a form: Optional ID Number, Full Name, Address, Contact, Email.
 *  2. On submit → INSERT into `visitors`, then auto-sign-in (INSERT into `visit_logs`).
 *  3. Redirect to a sign-in confirmation screen.
 */

require_once 'config/db.php';
require_once 'includes/visit_log.php';

$id_number = $_GET['id'] ?? '';
$visitor_type = 'usc';
$error = '';
$first_name = '';
$last_name = '';
$barangay = '';
$city = '';
$province = '';
$contact_number = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $visitor_type = $_POST['visitor_type'] ?? 'usc';
    $id_number = trim($_POST['id_number'] ?? '');
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $barangay = trim($_POST['barangay'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $province = trim($_POST['province'] ?? '');
    $contact_number = trim($_POST['contact_number'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if (!in_array($visitor_type, ['usc', 'non_usc'], true)) {
        $error = 'Please select whether you are from USC.';
    } elseif ($id_number === '') {
        $error = $visitor_type === 'usc'
            ? 'Please enter your USC ID number.'
            : 'Please generate your visitor reference number.';
    } elseif (empty($first_name) || empty($last_name) || empty($barangay) || empty($city) || empty($province) || empty($contact_number) || empty($email)) {
        $error = 'Please complete all required fields.';
    } elseif ($visitor_type === 'usc' && !preg_match('/^[A-Za-z0-9-]{3,30}$/', $id_number)) {
        $error = 'ID number may only contain letters, numbers, and dashes.';
    } elseif ($visitor_type === 'non_usc' && !preg_match('/^[0-9]{5}$/', $id_number)) {
        $error = 'Visitor reference number must be 5 digits.';
    } elseif (strlen($first_name) < 2 || strlen($last_name) < 2) {
        $error = 'First name and last name must be at least 2 characters.';
    } elseif (!preg_match('/^[0-9]{7,20}$/', $contact_number)) {
        $error = 'Contact number must contain numbers only.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        try {
            $pdo->beginTransaction();

            $stored_id_number = $id_number;

            // Insert new visitor
            $stmt = $pdo->prepare("INSERT INTO visitors (id_number, first_name, last_name, barangay, city, province, contact_number, email) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$stored_id_number, $first_name, $last_name, $barangay, $city, $province, $contact_number, $email]);
            $visitor_id = $pdo->lastInsertId();

            // Auto sign-in
            recordVisitorSignIn($pdo, (int) $visitor_id);

            $pdo->commit();

            // Redirect to signed-in visitor page
            header("Location: welcome.php?id=" . urlencode($stored_id_number) . "&status=registered");
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

<main class="form-page registration-page">
    <section class="form-shell reveal-on-scroll">
        <div class="registration-card">
            <div class="form-card-header">
                <span class="form-card-kicker">Required Information</span>
                <h1>New Visitor Registration</h1>
                <p>Create your visitor record once, then you will be automatically signed in for this visit.</p>
            </div>

            <?php if ($error): ?>
                <div class="alert error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST" action="register.php" class="form-group enhanced-form" data-enhanced-form data-membership-form>
                <div class="field-grid">
                    <fieldset class="field full-width membership-field">
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

                    <div class="field full-width">
                        <label for="id_number"><span data-membership-label>ID Number</span> <span class="optional-label" data-membership-note>USC only</span></label>
                        <div class="reference-input-row">
                            <input
                                type="text"
                                id="id_number"
                                name="id_number"
                                value="<?= htmlspecialchars($id_number) ?>"
                                placeholder="21700003"
                                pattern="[A-Za-z0-9-]{3,30}"
                                minlength="3"
                                maxlength="30"
                                readonly
                                <?= $id_number ? '' : 'autofocus' ?>
                                data-membership-id
                            >
                            <button type="button" class="btn btn-secondary copy-reference-btn" data-copy-reference hidden>Copy</button>
                        </div>
                        <small data-membership-help>USC visitors should enter their school ID number.</small>
                    </div>

                    <div class="field">
                        <label for="first_name">First Name</label>
                        <input
                            type="text"
                            id="first_name"
                            name="first_name"
                            value="<?= htmlspecialchars($first_name) ?>"
                            placeholder="John Enrico"
                            minlength="2"
                            maxlength="80"
                            required
                            <?= $id_number ? 'autofocus' : '' ?>
                        >
                    </div>

                    <div class="field">
                        <label for="last_name">Last Name</label>
                        <input
                            type="text"
                            id="last_name"
                            name="last_name"
                            value="<?= htmlspecialchars($last_name) ?>"
                            placeholder="Lauron"
                            minlength="2"
                            maxlength="80"
                            required
                        >
                    </div>

                    <div class="field">
                        <label for="barangay">Barangay</label>
                        <input
                            type="text"
                            id="barangay"
                            name="barangay"
                            value="<?= htmlspecialchars($barangay) ?>"
                            placeholder="Talamban"
                            maxlength="100"
                            required
                        >
                    </div>

                    <div class="field">
                        <label for="city">City</label>
                        <input
                            type="text"
                            id="city"
                            name="city"
                            value="<?= htmlspecialchars($city) ?>"
                            placeholder="Cebu City"
                            maxlength="100"
                            required
                        >
                    </div>

                    <div class="field">
                        <label for="province">Province</label>
                        <input
                            type="text"
                            id="province"
                            name="province"
                            value="<?= htmlspecialchars($province) ?>"
                            placeholder="Cebu"
                            maxlength="100"
                            required
                        >
                    </div>

                    <div class="field">
                        <label for="contact_number">Contact Number</label>
                        <input
                            type="tel"
                            id="contact_number"
                            name="contact_number"
                            value="<?= htmlspecialchars($contact_number) ?>"
                            placeholder="09459650774"
                            pattern="[0-9]{7,20}"
                            inputmode="numeric"
                            minlength="7"
                            maxlength="20"
                            required
                        >
                    </div>

                    <div class="field full-width">
                        <label for="email">Email Address</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="<?= htmlspecialchars($email) ?>"
                            placeholder="johnenricolauron@gmail.com"
                            maxlength="120"
                            required
                        >
                    </div>
                </div>

                <div class="actions form-actions">
                    <a href="index.php" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary btn-with-motion">Register & Sign In</button>
                </div>
            </form>
        </div>
    </section>
</main>

<?php require_once 'includes/footer.php'; ?>
