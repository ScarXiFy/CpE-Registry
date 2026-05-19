<?php
/**
 * includes/header.php
 * Reusable page header partial.
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>USC CpE Registry</title>
    <!-- Google Fonts: Space Grotesk -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/CpE-Registry/assets/css/style.css">
</head>
<body>
    <header class="main-header">
        <div class="header-container">
            <a href="/CpE-Registry/index.php" class="logo-link">
                <img src="/CpE-Registry/assets/img/usc-cpe-logo.png" alt="USC CpE Logo" class="header-logo-img">
                <span class="logo-text">USC CpE Registry</span>
            </a>
            <?php if (empty($hide_admin_login)): ?>
                <nav class="header-nav">
                    <a href="/CpE-Registry/admin/login.php" class="nav-link">Admin Login</a>
                </nav>
            <?php endif; ?>
        </div>
    </header>
