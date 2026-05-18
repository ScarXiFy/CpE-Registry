<?php
/**
 * admin/dashboard.php
 * Admin dashboard — visitor search interface.
 *
 * Flow (Phase 6):
 *  1. Require auth_check to guard this page.
 *  2. Display a search form with six filters:
 *     City | Barangay | Province | ID Number | Last/First Name | Time and Day
 *  3. On submit → pass filters to admin/search.php and display results.
 */

require_once '../includes/auth_check.php';
require_once 'search.php'; // this populates $results and $filters
require_once '../includes/header.php';
?>

<main class="container admin-dashboard">
    <div class="header-actions">
        <h1>Admin Dashboard — Visitor Search</h1>
        <!-- Phase 7 logout placeholder (can be linked later) -->
    </div>

    <div class="card mb-3">
        <h3>Filter Records</h3>
        <form method="GET" action="dashboard.php" class="search-form">
            <div class="form-row">
                <div class="form-col">
                    <label for="id_number">ID Number</label>
                    <input type="text" id="id_number" name="id_number" value="<?= htmlspecialchars($filters['id_number']) ?>">
                </div>
                <div class="form-col">
                    <label for="name">Name (First/Last)</label>
                    <input type="text" id="name" name="name" value="<?= htmlspecialchars($filters['name']) ?>">
                </div>
                <div class="form-col">
                    <label for="visit_date">Date</label>
                    <input type="date" id="visit_date" name="visit_date" value="<?= htmlspecialchars($filters['visit_date']) ?>">
                </div>
            </div>
            
            <div class="form-row mt-2">
                <div class="form-col">
                    <label for="city">City</label>
                    <input type="text" id="city" name="city" value="<?= htmlspecialchars($filters['city']) ?>">
                </div>
                <div class="form-col">
                    <label for="barangay">Barangay</label>
                    <input type="text" id="barangay" name="barangay" value="<?= htmlspecialchars($filters['barangay']) ?>">
                </div>
                <div class="form-col">
                    <label for="province">Province</label>
                    <input type="text" id="province" name="province" value="<?= htmlspecialchars($filters['province']) ?>">
                </div>
            </div>

            <div class="actions mt-3">
                <a href="dashboard.php" class="btn btn-secondary">Clear Filters</a>
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </form>
    </div>

    <div class="card">
        <h3>Visit Logs</h3>
        <?php if (count($results) > 0): ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID Number</th>
                            <th>Name</th>
                            <th>Location</th>
                            <th>Contact</th>
                            <th>Sign In</th>
                            <th>Sign Out</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($results as $row): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['id_number']) ?></td>
                                <td><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></td>
                                <td><?= htmlspecialchars($row['barangay'] . ', ' . $row['city'] . ', ' . $row['province']) ?></td>
                                <td><?= htmlspecialchars($row['contact_number']) ?></td>
                                <td><?= htmlspecialchars(date('M d, Y h:i A', strtotime($row['sign_in']))) ?></td>
                                <td>
                                    <?php if ($row['sign_out']): ?>
                                        <?= htmlspecialchars(date('M d, Y h:i A', strtotime($row['sign_out']))) ?>
                                    <?php else: ?>
                                        <span class="badge active">Active</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p>No records found matching your search criteria.</p>
        <?php endif; ?>
    </div>
</main>

<?php require_once '../includes/footer.php'; ?>
