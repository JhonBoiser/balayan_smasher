@extends('layouts.app')

@section('title', 'Privacy Policy')

@section('content')
<style>
    /* Hero Section */
    .privacy-hero {
        background: linear-gradient(135deg, #6ba932 0%, #5a9028 100%);
        padding: 50px 0;
        position: relative;
        overflow: hidden;
    }

    .privacy-hero::before {
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
    .privacy-wrapper {
        background: linear-gradient(180deg, #f8f9fa 0%, #ffffff 100%);
        padding: 45px 0;
    }

    .privacy-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 0 24px;
    }

    /* Intro Box */
    .privacy-intro {
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

    .privacy-intro p {
        color: #555;
        font-size: 0.95rem;
        line-height: 1.7;
        margin: 0;
        flex: 1;
    }

    /* Section Cards */
    .privacy-section {
        background: white;
        border-radius: 14px;
        padding: 32px;
        margin-bottom: 20px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.06);
        transition: all 0.3s ease;
        animation: fadeInUp 0.8s ease;
    }

    .privacy-section:hover {
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
        background: linear-gradient(135deg, #e8f5e9 0%, #f1f8f4 100%);
        border-left: 4px solid #4caf50;
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
        color: #2e7d32;
    }

    /* Rights Grid */
    .rights-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 16px;
        margin-top: 20px;
    }

    .right-item {
        background: #f8f9fa;
        padding: 16px;
        border-radius: 10px;
        display: flex;
        gap: 12px;
        align-items: flex-start;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }

    .right-item:hover {
        background: white;
        border-color: #6ba932;
        box-shadow: 0 4px 16px rgba(107, 169, 50, 0.12);
    }

    .right-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #6ba932 0%, #5a9028 100%);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .right-icon i {
        font-size: 18px;
        color: white;
    }

    .right-content p {
        margin: 0;
        font-size: 0.88rem;
        color: #555;
        line-height: 1.6;
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
        .privacy-hero {
            padding: 35px 0;
        }

        .hero-content h1 {
            font-size: 1.8rem;
        }

        .hero-content p {
            font-size: 0.95rem;
        }

        .privacy-wrapper {
            padding: 32px 0;
        }

        .privacy-container {
            padding: 0 16px;
        }

        .privacy-intro {
            padding: 20px 24px;
            flex-direction: column;
            gap: 16px;
        }

        .privacy-section {
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

        .privacy-intro {
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

        .privacy-section {
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

        .rights-grid {
            gap: 12px;
        }

        .right-item {
            padding: 14px;
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
<section class="privacy-hero">
    <div class="hero-content">
        <h1>Privacy Policy</h1>
        <p>Your privacy and data protection are important to us</p>
        <div class="last-updated">
            <i class="fas fa-calendar-alt"></i> Last Updated: November 2024
        </div>
    </div>
</section>

<!-- Main Content -->
<div class="privacy-wrapper">
    <div class="privacy-container">
        
        <!-- Introduction -->
        <div class="privacy-intro">
            <div class="intro-icon">
                <i class="fas fa-shield-alt"></i>
            </div>
            <p>This Privacy Policy explains how Balayan Smasher Hub collects, uses, and protects your personal information. By visiting our shop, using our website, contacting us, or making a purchase, you agree to the practices described in this policy.</p>
        </div>

        <!-- Section 1 -->
        <section class="privacy-section">
            <div class="section-header">
                <span class="section-number">1</span>
                <h2 class="section-title">Information We Collect</h2>
            </div>
            <div class="section-content">
                <p>We may collect the following types of personal information:</p>

                <div class="subsection">
                    <h4><i class="fas fa-user"></i> Personal Details</h4>
                    <ul>
                        <li>Name</li>
                        <li>Contact number</li>
                        <li>Email address</li>
                        <li>Address (only when needed for delivery or communication)</li>
                    </ul>
                </div>

                <div class="subsection">
                    <h4><i class="fas fa-shopping-bag"></i> Order and Payment Information</h4>
                    <ul>
                        <li>Items purchased</li>
                        <li>Date and time of purchase</li>
                        <li>Mode of payment (we do not store card numbers)</li>
                    </ul>
                </div>

                <div class="subsection">
                    <h4><i class="fas fa-comments"></i> Online Interaction</h4>
                    <p style="margin-bottom: 8px;">(If you use our website or message us online)</p>
                    <ul>
                        <li>Messages or inquiries</li>
                        <li>Feedback and product reviews</li>
                    </ul>
                </div>

                <div class="highlight-box">
                    <p><strong>We only collect information that is necessary</strong> to process orders, provide customer service, and improve our shop services.</p>
                </div>
            </div>
        </section>

        <!-- Section 2 -->
        <section class="privacy-section">
            <div class="section-header">
                <span class="section-number">2</span>
                <h2 class="section-title">How We Use Your Information</h2>
            </div>
            <div class="section-content">
                <p>Your information may be used for the following purposes:</p>
                <ul>
                    <li>Processing and completing purchases</li>
                    <li>Contacting you regarding your order, delivery, or pickup</li>
                    <li>Providing customer assistance</li>
                    <li>Keeping transaction records for business and legal purposes</li>
                    <li>Improving our products and services</li>
                </ul>
                <div class="highlight-box">
                    <p><strong>We do not sell, rent, or trade your personal information with outside parties.</strong></p>
                </div>
            </div>
        </section>

        <!-- Section 3 -->
        <section class="privacy-section">
            <div class="section-header">
                <span class="section-number">3</span>
                <h2 class="section-title">Data Protection and Security</h2>
            </div>
            <div class="section-content">
                <p>We implement reasonable and appropriate security measures to protect your personal information, including:</p>
                <ul>
                    <li>Secured storage systems</li>
                    <li>Limiting data access to authorized personnel only</li>
                    <li>Following safe payment handling procedures</li>
                </ul>
                <p>While we do our best to protect your information, no system is completely secure.</p>
            </div>
        </section>

        <!-- Section 4 -->
        <section class="privacy-section">
            <div class="section-header">
                <span class="section-number">4</span>
                <h2 class="section-title">Sharing of Information</h2>
            </div>
            <div class="section-content">
                <p>We may share your information only when necessary, such as:</p>
                <ul>
                    <li>With courier or delivery services</li>
                    <li>With service providers who help our business operate (e.g., inventory or payment systems)</li>
                </ul>
                <div class="highlight-box">
                    <p><strong>We do not share your information with third parties for marketing purposes.</strong></p>
                </div>
            </div>
        </section>

        <!-- Section 5 -->
        <section class="privacy-section">
            <div class="section-header">
                <span class="section-number">5</span>
                <h2 class="section-title">Cookies and Website Tracking</h2>
            </div>
            <div class="section-content">
                <p>Our website may use cookies to:</p>
                <ul>
                    <li>Improve browsing experience</li>
                    <li>Analyze website traffic</li>
                    <li>Store user preferences</li>
                </ul>
                <p>You may disable cookies in your browser settings if you prefer.</p>
            </div>
        </section>

        <!-- Section 6 -->
        <section class="privacy-section">
            <div class="section-header">
                <span class="section-number">6</span>
                <h2 class="section-title">Your Rights</h2>
            </div>
            <div class="section-content">
                <p>You have the right to:</p>
                
                <div class="rights-grid">
                    <div class="right-item">
                        <div class="right-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div class="right-content">
                            <p>Request a copy of the information we collected</p>
                        </div>
                    </div>

                    <div class="right-item">
                        <div class="right-icon">
                            <i class="fas fa-edit"></i>
                        </div>
                        <div class="right-content">
                            <p>Ask us to correct inaccurate or outdated information</p>
                        </div>
                    </div>

                    <div class="right-item">
                        <div class="right-icon">
                            <i class="fas fa-trash-alt"></i>
                        </div>
                        <div class="right-content">
                            <p>Request deletion of your information (subject to legal requirements)</p>
                        </div>
                    </div>

                    <div class="right-item">
                        <div class="right-icon">
                            <i class="fas fa-ban"></i>
                        </div>
                        <div class="right-content">
                            <p>Withdraw consent for data use</p>
                        </div>
                    </div>
                </div>

                <p style="margin-top: 16px;">To make a request, please contact us using the details provided below.</p>
            </div>
        </section>

        <!-- Section 7 -->
        <section class="privacy-section">
            <div class="section-header">
                <span class="section-number">7</span>
                <h2 class="section-title">Third-Party Websites</h2>
            </div>
            <div class="section-content">
                <p>If our website contains links to other websites, please note that we are not responsible for their privacy practices. We encourage you to review their policies separately.</p>
            </div>
        </section>

        <!-- Section 8 -->
        <section class="privacy-section">
            <div class="section-header">
                <span class="section-number">8</span>
                <h2 class="section-title">Changes to This Privacy Policy</h2>
            </div>
            <div class="section-content">
                <p>Balayan Smasher Hub may modify or update this Privacy Policy at any time without prior notice. Changes take effect immediately upon posting on our website or in-store.</p>
            </div>
        </section>

        <!-- Contact Section -->
        <div class="contact-box">
            <h3>Questions About Your Privacy?</h3>
            <p>If you have any questions or concerns about this Privacy Policy or how we handle your data, please contact us.</p>
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