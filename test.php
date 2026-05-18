<?php
session_start();
$_SESSION['admin_logged_in'] = true;
$_GET['city'] = '';
ob_start();
include('admin/dashboard.php');
$output = ob_get_clean();
if (preg_match('/Fatal error|Parse error|Warning|Notice/', $output)) {
    echo $output;
} else {
    echo 'No errors found in output.';
}
