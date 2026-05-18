<?php
/**
 * index.php
 * Visitor landing page.
 */
require_once 'includes/header.php';
?>

<main class="landing-page">
    <section class="hero-section">
        <div class="hero-content">
            <div class="hero-logo-large">
                <i class="fa-solid fa-microchip"></i>
            </div>
            <h1 class="hero-title">USC CpE Registry</h1>
            <p class="hero-subtitle">Dedicated digital infrastructure for the University of San Carlos, Department of Computer Engineering. Built for efficiency, security, and accessibility.</p>
            
            <div class="hero-actions">
                <a href="signin.php" class="btn btn-primary btn-lg">Sign In</a>
                <a href="register.php" class="btn btn-outline btn-lg">Register</a>
            </div>
        </div>
    </section>

    <section class="features-section">
        <div class="container features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fa-regular fa-user"></i>
                </div>
                <h3 class="feature-title">Register Once</h3>
                <p class="feature-desc">First-time visitors fill out a short form.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fa-regular fa-id-badge"></i>
                </div>
                <h3 class="feature-title">Quick Sign In</h3>
                <p class="feature-desc">Return with just your ID number.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fa-regular fa-circle-check"></i>
                </div>
                <h3 class="feature-title">Sign Out When Leaving</h3>
                <p class="feature-desc">Always log your exit for accurate records.</p>
            </div>
        </div>
    </section>
</main>

<?php require_once 'includes/footer.php'; ?>
