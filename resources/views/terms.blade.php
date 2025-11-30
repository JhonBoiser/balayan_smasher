@extends('layouts.app')

@section('title', 'Terms of Service')

@section('content')
<style>
    /* Hero Section */
    .terms-hero {
        background: linear-gradient(135deg, #6ba932 0%, #5a9028 100%);
        padding: 50px 0;
        position: relative;
        overflow: hidden;
    }

    .terms-hero::before {
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
        font-size: 2.2rem;
        font-weight: 700;
        margin-bottom: 10px;
        text-shadow: 0 2px 10px rgba(0,0,0,0.2);
        animation: fadeInDown 0.8s ease;
    }

    .hero-content p {
        color: rgba(255,255,255,0.95);
        font-size: 1rem;
        max-width: 700px;
        margin: 0 auto;
        line-height: 1.6;
        animation: fadeInUp 0.8s ease;
    }

    .last-updated {
        display: inline-block;
        background: rgba(255,255,255,0.2);
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 0.85rem;
        margin-top: 12px;
        backdrop-filter: blur(10px);
    }

    /* Main Content */
    .terms-wrapper {
        background: linear-gradient(180deg, #f8f9fa 0%, #ffffff 100%);
        padding: 45px 0;
    }

    .terms-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 0 24px;
    }

    /* Intro Box */
    .terms-intro {
        background: white;
        border-radius: 14px;
        padding: 28px 32px;
        margin-bottom: 28px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.06);
        border-left: 4px solid #6ba932;
        animation: fadeIn 0.8s ease;
        display: flex;
        gap: 20px;
        align-items: flex-start;
    }

    .intro-icon {
        width: 55px;
        height: 55px;
        background: linear-gradient(135deg, #6ba932 0%, #5a9028 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .intro-icon i {
        font-size: 26px;
        color: white;
    }

    .terms-intro p {
        color: #555;
        font-size: 0.95rem;
        line-height: 1.7;
        margin: 0;
        flex: 1;
    }

    /* Section Cards */
    .terms-section {
        background: white;
        border-radius: 14px;
        padding: 32px;
        margin-bottom: 20px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.06);
        transition: all 0.3s ease;
        animation: fadeInUp 0.8s ease;
    }

    .terms-section:hover {
        box-shadow: 0 8px 30px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }

    .section-number {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 35px;
        height: 35px;
        background: linear-gradient(135deg, #6ba932 0%, #5a9028 100%);
        color: white;
        border-radius: 50%;
        font-weight: 700;
        font-size: 1rem;
        margin-right: 12px;
        flex-shrink: 0;
    }

    .section-header {
        display: flex;
        align-items: center;
        margin-bottom: 18px;
    }

    .section-title {
        font-size: 1.3rem;
        color: #2c3e50;
        font-weight: 700;
        margin: 0;
    }

    .section-content {
        color: #555;
        font-size: 0.92rem;
        line-height: 1.8;
    }

    .section-content p {
        margin-bottom: 14px;
    }

    .section-content p:last-child {
        margin-bottom: 0;
    }

    .section-content ul {
        margin: 14px 0;
        padding-left: 24px;
    }

    .section-content li {
        margin-bottom: 10px;
        position: relative;
        padding-left: 8px;
    }

    .section-content li::marker {
        color: #6ba932;
    }

    .section-content strong {
        color: #2c3e50;
        font-weight: 600;
    }

    /* Sub-sections */
    .subsection {
        background: #f8f9fa;
        padding: 16px 20px;
        border-radius: 10px;
        margin: 16px 0;
        border-left: 3px solid #6ba932;
    }

    .subsection h4 {
        font-size: 1rem;
        color: #2c3e50;
        font-weight: 700;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .subsection h4 i {
        color: #6ba932;
        font-size: 0.9rem;
    }

    .subsection ul {
        margin: 8px 0 0 0;
        padding-left: 20px;
    }

    .subsection li {
        margin-bottom: 6px;
        font-size: 0.9rem;
    }

    /* Highlight Box */
    .highlight-box {
        background: linear-gradient(135deg, #fff7ed 0%, #fffbf5 100%);
        border-left: 4px solid #ff9800;
        padding: 16px 20px;
        border-radius: 8px;
        margin: 16px 0;
    }

    .highlight-box p {
        margin: 0;
        color: #555;
        font-size: 0.9rem;
        line-height: 1.7;
    }

    .highlight-box strong {
        color: #e65100;
    }

    /* Steps Styling */
    .terms-steps {
        counter-reset: step-counter;
        margin: 20px 0;
    }

    .step-item {
        display: flex;
        align-items: flex-start;
        margin-bottom: 15px;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
        border-left: 3px solid #6ba932;
    }

    .step-item::before {
        counter-increment: step-counter;
        content: counter(step-counter);
        background: #6ba932;
        color: white;
        width: 25px;
        height: 25px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        font-weight: bold;
        margin-right: 12px;
        flex-shrink: 0;
    }

    /* Contact Box */
    .contact-box {
        background: linear-gradient(135deg, #6ba932 0%, #5a9028 100%);
        border-radius: 14px;
        padding: 32px;
        text-align: center;
        color: white;
        margin-top: 35px;
        box-shadow: 0 8px 30px rgba(107, 169, 50, 0.3);
    }

    .contact-box h3 {
        font-size: 1.4rem;
        font-weight: 700;
        margin-bottom: 12px;
    }

    .contact-box p {
        font-size: 0.95rem;
        margin-bottom: 20px;
        opacity: 0.95;
    }

    .contact-info {
        display: flex;
        flex-direction: column;
        gap: 10px;
        align-items: center;
        margin-top: 16px;
    }

    .contact-item {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 0.95rem;
    }

    .contact-item i {
        width: 20px;
        text-align: center;
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
        .terms-hero {
            padding: 35px 0;
        }

        .hero-content h1 {
            font-size: 1.8rem;
        }

        .hero-content p {
            font-size: 0.95rem;
        }

        .terms-wrapper {
            padding: 32px 0;
        }

        .terms-container {
            padding: 0 16px;
        }

        .terms-intro {
            padding: 20px 24px;
            flex-direction: column;
            gap: 16px;
        }

        .terms-section {
            padding: 24px 20px;
            margin-bottom: 16px;
        }

        .section-title {
            font-size: 1.15rem;
        }

        .contact-box {
            padding: 24px 20px;
            margin-top: 24px;
        }
    }

    @media (max-width: 576px) {
        .hero-content h1 {
            font-size: 1.6rem;
        }

        .hero-content p {
            font-size: 0.9rem;
        }

        .last-updated {
            font-size: 0.8rem;
            padding: 5px 12px;
        }

        .terms-intro {
            padding: 18px 20px;
            margin-bottom: 20px;
        }

        .intro-icon {
            width: 48px;
            height: 48px;
        }

        .intro-icon i {
            font-size: 22px;
        }

        .terms-section {
            padding: 20px 16px;
        }

        .section-number {
            width: 30px;
            height: 30px;
            font-size: 0.9rem;
            margin-right: 10px;
        }

        .section-title {
            font-size: 1.05rem;
        }

        .section-content {
            font-size: 0.88rem;
        }

        .subsection {
            padding: 14px 16px;
        }

        .contact-box {
            padding: 20px 16px;
        }

        .contact-box h3 {
            font-size: 1.2rem;
        }
    }
</style>

<!-- Hero Section -->
<section class="terms-hero">
    <div class="hero-content">
        <h1>Terms of Service</h1>
        <p>Please read these terms carefully before using our services</p>
        <div class="last-updated">
            <i class="fas fa-calendar-alt"></i> Last Updated: {{ date('F d, Y') }}
        </div>
    </div>
</section>

<!-- Main Content -->
<div class="terms-wrapper">
    <div class="terms-container">
        
        <!-- Introduction -->
        <div class="terms-intro">
            <div class="intro-icon">
                <i class="fas fa-file-contract"></i>
            </div>
            <p><strong>Welcome to Balayan Smashers Hub!</strong> These Terms of Service ("Terms") explain the rules for using our website, online shop, and all services we provide. By visiting our store, browsing our website, or buying any product from us, you agree to follow these Terms.</p>
        </div>

        <!-- Section 1 -->
        <section class="terms-section">
            <div class="section-header">
                <span class="section-number">1</span>
                <h2 class="section-title">Introduction</h2>
            </div>
            <div class="section-content">
                <p>Welcome to Balayan Smashers Hub. By using our website you agree to these Terms of Service. Please read them carefully.</p>
                <div class="highlight-box">
                    <p><strong>Important:</strong> By using our services or making a purchase, you confirm that you are at least 18 years old or have permission from a parent or guardian.</p>
                </div>
            </div>
        </section>

        <!-- Section 2 -->
        <section class="terms-section">
            <div class="section-header">
                <span class="section-number">2</span>
                <h2 class="section-title">Using the Service</h2>
            </div>
            <div class="section-content">
                <p>Use of the site is subject to compliance with applicable laws and these terms. You agree not to misuse the service.</p>
                
                <div class="subsection">
                    <h4><i class="fas fa-ban"></i> Prohibited Activities</h4>
                    <ul>
                        <li>Attempting to gain unauthorized access to our systems</li>
                        <li>Using the service for any illegal purpose</li>
                        <li>Interfering with the proper functioning of the service</li>
                        <li>Harassing other users or staff members</li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- Section 3 -->
        <section class="terms-section">
            <div class="section-header">
                <span class="section-number">3</span>
                <h2 class="section-title">Orders and Payments</h2>
            </div>
            <div class="section-content">
                <p>Order acceptance, payment processing, cancellations, returns and other commerce-related rules are governed by our policies and applicable law.</p>
                
                <div class="terms-steps">
                    <div class="step-item">
                        <div>All prices are in <strong>Philippine Pesos (PHP)</strong> and subject to change without notice</div>
                    </div>
                    <div class="step-item">
                        <div>We accept cash payments and other approved payment methods</div>
                    </div>
                    <div class="step-item">
                        <div>Orders are subject to product availability and verification</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section 4 -->
        <section class="terms-section">
            <div class="section-header">
                <span class="section-number">4</span>
                <h2 class="section-title">Intellectual Property</h2>
            </div>
            <div class="section-content">
                <p>All content on this site is the property of Balayan Smashers Hub or its licensors and is protected by intellectual property laws.</p>
                
                <div class="subsection">
                    <h4><i class="fas fa-copyright"></i> Protected Content</h4>
                    <ul>
                        <li>Website design and layout</li>
                        <li>Product images and descriptions</li>
                        <li>Brand logos and trademarks</li>
                        <li>Written content and product reviews</li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- Section 5 -->
        <section class="terms-section">
            <div class="section-header">
                <span class="section-number">5</span>
                <h2 class="section-title">Limitation of Liability</h2>
            </div>
            <div class="section-content">
                <p>To the fullest extent permitted by law, we are not liable for indirect or consequential damages arising from use of the service.</p>
                <div class="highlight-box">
                    <p><strong>Note:</strong> Balayan Smashers Hub is not responsible for injuries, damages, or losses caused by improper or unsafe use of any product purchased from our shop. Customers are responsible for using all sports equipment correctly and safely.</p>
                </div>
            </div>
        </section>

        <!-- Section 6 -->
        <section class="terms-section">
            <div class="section-header">
                <span class="section-number">6</span>
                <h2 class="section-title">Returns and Refunds</h2>
            </div>
            <div class="section-content">
                <p>We accept returns or exchanges within <strong>7 days of purchase</strong> under the following conditions:</p>
                <ul>
                    <li>The item is unused and in original condition</li>
                    <li>Original packaging is intact</li>
                    <li>Valid receipt is presented</li>
                    <li>Product is not on sale or marked as final sale</li>
                </ul>
                <p>Refunds will be processed using the original payment method.</p>
            </div>
        </section>

        <!-- Section 7 -->
        <section class="terms-section">
            <div class="section-header">
                <span class="section-number">7</span>
                <h2 class="section-title">Privacy Policy</h2>
            </div>
            <div class="section-content">
                <p>We value your privacy. Any personal information you share is used only for orders, delivery, and communication. We do not sell or share your information with third parties without your consent.</p>
                <p>For more details, please read our full <a href="/privacy" style="color: #6ba932; font-weight: 600;">Privacy Policy</a>.</p>
            </div>
        </section>

        <!-- Section 8 -->
        <section class="terms-section">
            <div class="section-header">
                <span class="section-number">8</span>
                <h2 class="section-title">Changes to Terms</h2>
            </div>
            <div class="section-content">
                <p>Balayan Smashers Hub may update or change these Terms at any time. Changes take effect immediately upon posting. Continued use of our services after changes constitutes acceptance of the modified terms.</p>
                <p>We encourage you to review these Terms periodically for any updates.</p>
            </div>
        </section>

        <!-- Contact Section -->
        <div class="contact-box">
            <h3>Questions About Our Terms?</h3>
            <p>If you have any questions or concerns about these Terms of Service, please don't hesitate to contact us.</p>
            <div class="contact-info">
                <div class="contact-item">
                    <i class="fas fa-phone"></i>
                    <span>0966 793 3067</span>
                </div>
                <div class="contact-item">
                    <i class="fas fa-envelope"></i>
                    <span>info@balayansmashers.com</span>
                </div>
                <div class="contact-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>Brgy. Calzada, Ermita, Balayan, Batangas</span>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection