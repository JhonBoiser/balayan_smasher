@extends('layouts.app')

@section('title', 'About Us - Balayan Smashers Hub')

@section('content')
<style>
    /* Hero Section */
    .about-hero {
        background: linear-gradient(135deg, #6ba932 0%, #5a9028 100%);
        padding: 60px 0;
        position: relative;
        overflow: hidden;
    }

    .about-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg width="60" height="60" xmlns="http://www.w3.org/2000/svg"><circle cx="30" cy="30" r="25" fill="rgba(255,255,255,0.05)"/></svg>');
        background-size: 60px 60px;
        opacity: 0.3;
    }

    .hero-content {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 24px;
        text-align: center;
        position: relative;
        z-index: 1;
    }

    .hero-content h1 {
        color: white;
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 12px;
        text-shadow: 0 2px 10px rgba(0,0,0,0.2);
        animation: fadeInDown 0.8s ease;
    }

    .hero-content p {
        color: rgba(255,255,255,0.95);
        font-size: 1.1rem;
        max-width: 700px;
        margin: 0 auto;
        line-height: 1.6;
        animation: fadeInUp 0.8s ease;
    }

    /* Main Content */
    .about-wrapper {
        background: linear-gradient(180deg, #f8f9fa 0%, #ffffff 100%);
        padding: 50px 0;
    }

    .about-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 24px;
    }

    /* Story Section */
    .story-section {
        background: white;
        border-radius: 16px;
        padding: 40px;
        margin-bottom: 35px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.06);
        animation: fadeIn 1s ease;
    }

    .section-title {
        font-size: 1.8rem;
        color: #2c3e50;
        font-weight: 700;
        margin-bottom: 20px;
        position: relative;
        padding-bottom: 12px;
    }

    .section-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 60px;
        height: 4px;
        background: linear-gradient(90deg, #6ba932 0%, #5a9028 100%);
        border-radius: 2px;
    }

    .story-content {
        color: #555;
        font-size: 0.95rem;
        line-height: 1.8;
    }

    .story-content p {
        margin-bottom: 16px;
    }

    .story-content p:last-child {
        margin-bottom: 0;
    }

    /* Features Grid */
    .features-section {
        margin-bottom: 35px;
    }

    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 20px;
    }

    .feature-card {
        background: white;
        border-radius: 14px;
        padding: 28px 24px;
        text-align: center;
        box-shadow: 0 5px 20px rgba(0,0,0,0.06);
        transition: all 0.3s ease;
        border: 2px solid transparent;
        animation: fadeInUp 0.8s ease;
    }

    .feature-card:hover {
        transform: translateY(-6px);
        border-color: #6ba932;
        box-shadow: 0 10px 30px rgba(107, 169, 50, 0.15);
    }

    .feature-icon {
        width: 65px;
        height: 65px;
        background: linear-gradient(135deg, #6ba932 0%, #5a9028 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 18px;
        transition: all 0.3s ease;
    }

    .feature-card:hover .feature-icon {
        transform: scale(1.1) rotate(5deg);
    }

    .feature-icon i {
        font-size: 28px;
        color: white;
    }

    .feature-card h3 {
        font-size: 1.1rem;
        color: #2c3e50;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .feature-card p {
        font-size: 0.88rem;
        color: #666;
        line-height: 1.6;
        margin: 0;
    }

    /* Values Section */
    .values-section {
        background: white;
        border-radius: 16px;
        padding: 40px;
        margin-bottom: 35px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.06);
    }

    .values-list {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 24px;
        margin-top: 24px;
    }

    .value-item {
        display: flex;
        gap: 16px;
        padding: 20px;
        background: #f8f9fa;
        border-radius: 12px;
        transition: all 0.3s ease;
        border-left: 4px solid #6ba932;
    }

    .value-item:hover {
        background: #fff;
        box-shadow: 0 4px 16px rgba(107, 169, 50, 0.12);
        transform: translateX(5px);
    }

    .value-icon {
        width: 45px;
        height: 45px;
        background: linear-gradient(135deg, #6ba932 0%, #5a9028 100%);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .value-icon i {
        font-size: 20px;
        color: white;
    }

    .value-content h4 {
        font-size: 1rem;
        color: #2c3e50;
        font-weight: 700;
        margin-bottom: 6px;
    }

    .value-content p {
        font-size: 0.88rem;
        color: #666;
        line-height: 1.6;
        margin: 0;
    }

    /* Mission Section */
    .mission-section {
        background: linear-gradient(135deg, #6ba932 0%, #5a9028 100%);
        border-radius: 16px;
        padding: 45px 40px;
        text-align: center;
        color: white;
        box-shadow: 0 8px 30px rgba(107, 169, 50, 0.3);
        position: relative;
        overflow: hidden;
    }

    .mission-section::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: pulse 4s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.1); opacity: 0.8; }
    }

    .mission-content {
        position: relative;
        z-index: 1;
    }

    .mission-section h2 {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 20px;
        text-shadow: 0 2px 8px rgba(0,0,0,0.2);
    }

    .mission-section p {
        font-size: 1rem;
        line-height: 1.8;
        max-width: 800px;
        margin: 0 auto 24px;
        opacity: 0.95;
    }

    .mission-quote {
        font-size: 1.15rem;
        font-weight: 600;
        font-style: italic;
        border-left: 4px solid white;
        padding-left: 20px;
        margin: 28px auto 0;
        max-width: 600px;
        text-align: left;
    }

    /* Animations */
    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    /* Responsive */
    @media (max-width: 768px) {
        .about-hero {
            padding: 40px 0;
        }

        .hero-content h1 {
            font-size: 2rem;
        }

        .hero-content p {
            font-size: 1rem;
        }

        .about-wrapper {
            padding: 35px 0;
        }

        .about-container {
            padding: 0 16px;
        }

        .story-section,
        .values-section {
            padding: 28px 20px;
            margin-bottom: 24px;
        }

        .section-title {
            font-size: 1.5rem;
            margin-bottom: 16px;
        }

        .story-content {
            font-size: 0.9rem;
        }

        .features-grid {
            gap: 16px;
        }

        .feature-card {
            padding: 24px 20px;
        }

        .mission-section {
            padding: 32px 24px;
            margin-bottom: 24px;
        }

        .mission-section h2 {
            font-size: 1.5rem;
        }

        .mission-section p {
            font-size: 0.95rem;
        }

        .mission-quote {
            font-size: 1.05rem;
        }
    }

    @media (max-width: 576px) {
        .hero-content h1 {
            font-size: 1.75rem;
        }

        .hero-content p {
            font-size: 0.95rem;
        }

        .story-section,
        .values-section {
            padding: 24px 16px;
        }

        .section-title {
            font-size: 1.35rem;
        }

        .feature-icon {
            width: 55px;
            height: 55px;
        }

        .feature-icon i {
            font-size: 24px;
        }

        .values-list {
            gap: 16px;
        }

        .value-item {
            padding: 16px;
        }

        .mission-section {
            padding: 28px 20px;
        }

        .mission-section h2 {
            font-size: 1.35rem;
        }

        .mission-quote {
            font-size: 1rem;
            padding-left: 16px;
        }
    }
</style>

<!-- Hero Section -->
<section class="about-hero">
    <div class="hero-content">
        <h1>About Balayan Smashers Hub</h1>
        <p>Empowering athletes and sports enthusiasts with quality equipment and dedicated service since day one.</p>
    </div>
</section>

<!-- Main Content -->
<div class="about-wrapper">
    <div class="about-container">

        <!-- Our Story -->
        <section class="story-section">
            <h2 class="section-title">Our Story</h2>
            <div class="story-content">
                <p>Balayan Smasher Hub is a growing sports shop in Balayan, Batangas, offering quality sports equipment for many different sports. The shop first started as a simple idea — a small place where people in the community could buy good sports items without needing to travel far. As more customers came and shared what they needed, the shop began to expand, adding more products and improving its service to support athletes, students, and active individuals.</p>

                <p>Today, Balayan Smasher Hub carries a wide range of sports items—from badminton and tennis gear to basketballs, volleyballs, shoes, fitness accessories, and training equipment. All products are carefully selected from trusted brands to make sure customers get reliable and affordable choices. Whether you are a beginner, a casual player, or someone preparing for competitions, the shop aims to provide the right sports equipment to help you play better and enjoy your sport.</p>
            </div>
        </section>

        <!-- What We Offer -->
        <section class="features-section">
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-store"></i>
                    </div>
                    <h3>Clean & Organized Space</h3>
                    <p>A welcoming store environment where you can easily browse and find what you need.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-box-open"></i>
                    </div>
                    <h3>Wide Product Selection</h3>
                    <p>Carefully curated sports equipment from trusted brands for all skill levels.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-user-friends"></i>
                    </div>
                    <h3>Friendly Service</h3>
                    <p>Knowledgeable staff providing honest advice and genuine assistance.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <h3>Smooth Experience</h3>
                    <p>Easy shopping both in-store and online, with secure transactions.</p>
                </div>
            </div>
        </section>

        <!-- Our Values -->
        <section class="values-section">
            <h2 class="section-title">Quality Products and Customer Care</h2>
            <div class="story-content" style="margin-bottom: 20px;">
                <p>At Balayan Smasher Hub, we believe that every player deserves the chance to improve. No matter your skill level, we understand your needs and challenges in your chosen sport. As a growing sports store in our community, we are in a good position to help players find the right gear that fits their style, comfort, and budget.</p>
            </div>

            <div class="values-list">
                <div class="value-item">
                    <div class="value-icon">
                        <i class="fas fa-medal"></i>
                    </div>
                    <div class="value-content">
                        <h4>Support Every Player</h4>
                        <p>From beginners to competitors, we're here to help you find the perfect equipment for your journey.</p>
                    </div>
                </div>

                <div class="value-item">
                    <div class="value-icon">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <div class="value-content">
                        <h4>Build Trust</h4>
                        <p>We value your confidence in us and work hard to provide authentic products and secure transactions.</p>
                    </div>
                </div>

                <div class="value-item">
                    <div class="value-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <div class="value-content">
                        <h4>Community Focus</h4>
                        <p>We're more than a store—we're partners in building a healthier, more active community.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Mission -->
        <section class="mission-section">
            <div class="mission-content">
                <h2>A Growing Legacy</h2>
                <p>Since the beginning, our mission has been simple: <strong>To support local athletes and help more people enjoy sports.</strong></p>

                <p>We believe that sports can change lives. It can build confidence, improve health, and bring people together. For this reason, Balayan Smasher Hub continues to grow—not only as a store but as a partner for the community.</p>

                <p>Through the years, we have become more than just a place to buy equipment. We are a reliable supporter of players in Balayan and nearby towns. We continue to share the benefits of sports by providing access to good equipment, joining local events, and encouraging people to stay active.</p>

                <div class="mission-quote">
                    "As we move forward, Balayan Smasher Hub remains committed to serving the community. We aim to bring more sports products, support more players, and inspire more people to enjoy an active lifestyle. We are here to stay, and we are here to help you play your best."
                </div>
            </div>
        </section>

    </div>
</div>
@endsection
