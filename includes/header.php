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
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/CpE-Registry/assets/css/style.css">
</head>
<body>
    <header class="main-header">
        <div class="header-container">
            <a href="/CpE-Registry/index.php" class="logo-link">
                <!-- Using an icon as proxy for the actual logo -->
                <div class="logo-circle">
                    <i class="fa-solid fa-microchip"></i>
                </div>
                <span class="logo-text">USC CpE Registry</span>
            </a>
            <nav class="header-nav">
                <a href="/CpE-Registry/admin/login.php" class="nav-link">Admin Login</a>
            </nav>
        </div>
    </header>
