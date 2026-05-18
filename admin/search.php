<?php
/**
 * admin/search.php
 * Backend query handler for the six admin search filters.
 *
 * Accepted filters (Phase 6):
 *  - city
 *  - barangay
 *  - province
 *  - id_number
 *  - name (last or first)
 *  - date / time range
 *
 * Returns matching rows from `visitors` JOIN `visit_logs`.
 * All queries must use prepared statements (Phase 7).
 */

require_once '../includes/auth_check.php';
require_once '../config/db.php';

$results = [];
$filters = [
    'city' => $_GET['city'] ?? '',
    'barangay' => $_GET['barangay'] ?? '',
    'province' => $_GET['province'] ?? '',
    'id_number' => $_GET['id_number'] ?? '',
    'name' => $_GET['name'] ?? '',
    'visit_date' => $_GET['visit_date'] ?? ''
];

$has_filters = array_filter($filters);

if ($has_filters) {
    $sql = "SELECT v.id_number, v.first_name, v.last_name, v.barangay, v.city, v.province, v.contact_number, 
                   l.sign_in, l.sign_out 
            FROM visitors v
            JOIN visit_logs l ON v.id = l.visitor_id
            WHERE 1=1";
            
    $params = [];

    if (!empty($filters['city'])) {
        $sql .= " AND v.city LIKE ?";
        $params[] = "%" . $filters['city'] . "%";
    }
    if (!empty($filters['barangay'])) {
        $sql .= " AND v.barangay LIKE ?";
        $params[] = "%" . $filters['barangay'] . "%";
    }
    if (!empty($filters['province'])) {
        $sql .= " AND v.province LIKE ?";
        $params[] = "%" . $filters['province'] . "%";
    }
    if (!empty($filters['id_number'])) {
        $sql .= " AND v.id_number = ?";
        $params[] = $filters['id_number'];
    }
    if (!empty($filters['name'])) {
        $sql .= " AND (v.first_name LIKE ? OR v.last_name LIKE ?)";
        $params[] = "%" . $filters['name'] . "%";
        $params[] = "%" . $filters['name'] . "%";
    }
    if (!empty($filters['visit_date'])) {
        $sql .= " AND DATE(l.sign_in) = ?";
        $params[] = $filters['visit_date'];
    }

    $sql .= " ORDER BY l.sign_in DESC";

    // Using prepared statements (Phase 7 requirement handled early)
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $results = $stmt->fetchAll();
} else {
    // Show recent visits if no filters applied
    $stmt = $pdo->query("SELECT v.id_number, v.first_name, v.last_name, v.barangay, v.city, v.province, v.contact_number, 
                                l.sign_in, l.sign_out 
                         FROM visitors v
                         JOIN visit_logs l ON v.id = l.visitor_id
                         ORDER BY l.sign_in DESC LIMIT 50");
    $results = $stmt->fetchAll();
}
