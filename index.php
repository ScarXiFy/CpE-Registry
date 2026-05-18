<?php
/**
 * index.php
 * Visitor landing page.
 */
require_once 'includes/header.php';
?>

<main class="landing-page">
    <section class="hero-section infinite-grid-hero" data-grid-hero>
        <div class="grid-layer grid-layer-soft" aria-hidden="true"></div>
        <div class="grid-layer grid-layer-active" aria-hidden="true"></div>
        <div class="hero-glow hero-glow-gold" aria-hidden="true"></div>
        <div class="hero-glow hero-glow-blue" aria-hidden="true"></div>
        <div class="hero-glow hero-glow-cyan" aria-hidden="true"></div>

        <div class="hero-content reveal-on-scroll">
            <div class="hero-logo-large">
                <img src="/CpE-Registry/assets/img/usc-cpe-logo.png" alt="USC CpE Logo" class="hero-logo-img">
            </div>
            <h1 class="hero-title">USC CpE Registry</h1>
            <p class="hero-subtitle">Fast, accurate visitor tracking for students, faculty, and guests entering the CpE department spaces.</p>

            <div class="hero-actions">
                <a href="signin.php" class="btn btn-primary btn-lg">Sign In</a>
                <a href="register.php" class="btn btn-outline btn-lg">Register</a>
            </div>
        </div>
    </section>

    <section class="features-section">
        <div class="container">
            <div class="section-heading reveal-on-scroll">
                <span class="section-eyebrow">Visitor Flow</span>
                <h2>Simple, fast, and easy to follow.</h2>
                <p>Each step is designed for quick campus entry while keeping visit records clear for administrators.</p>
            </div>

            <div class="features-grid">
            <div class="feature-card reveal-on-scroll">
                <div class="feature-icon">
                    <i class="fa-regular fa-user"></i>
                </div>
                <h3 class="feature-title">Register Once</h3>
                <p class="feature-desc">First-time visitors fill out a short form.</p>
            </div>

            <div class="feature-card reveal-on-scroll">
                <div class="feature-icon">
                    <i class="fa-regular fa-id-badge"></i>
                </div>
                <h3 class="feature-title">Quick Sign In</h3>
                <p class="feature-desc">Return with just your ID number.</p>
            </div>

            <div class="feature-card reveal-on-scroll">
                <div class="feature-icon">
                    <i class="fa-regular fa-circle-check"></i>
                </div>
                <h3 class="feature-title">Sign Out When Leaving</h3>
                <p class="feature-desc">Always log your exit for accurate records.</p>
            </div>
            </div>
        </div>
    </section>
</main>

<?php require_once 'includes/footer.php'; ?>
