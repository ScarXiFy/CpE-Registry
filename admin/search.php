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

// TODO (Phase 6): implement search query logic
// TODO (Phase 7): convert all queries to prepared statements
require_once '../includes/auth_check.php';
require_once '../config/db.php';
