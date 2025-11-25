@extends('layouts.app')

@section('title', 'Contact Us - Balayan Smashers Hub')

@section('content')
<style>
    .contact-wrapper {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: calc(100vh - 180px);
        padding: 45px 16px;
    }

    .contact-container {
        max-width: 1200px;
        margin: 0 auto;
    }

    .contact-intro {
        text-align: center;
        margin-bottom: 40px;
        animation: fadeInDown 0.8s ease;
    }

    .contact-intro h1 {
        font-size: 2rem;
        color: #2c3e50;
        margin-bottom: 12px;
        font-weight: 700;
    }

    .contact-intro p {
        font-size: 1rem;
        color: #546e7a;
        max-width: 600px;
        margin: 0 auto;
    }

    .contact-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 24px;
        margin-bottom: 40px;
    }

    .contact-card {
        background: white;
        border-radius: 14px;
        padding: 32px 24px;
        text-align: center;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        animation: fadeInUp 0.8s ease;
        position: relative;
        overflow: hidden;
    }

    .contact-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 3px;
        background: linear-gradient(135deg, #6ba932 0%, #5a9028 100%);
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }

    .contact-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 32px rgba(107, 169, 50, 0.2);
    }

    .contact-card:hover::before {
        transform: scaleX(1);
    }

    .contact-icon {
        width: 70px;
        height: 70px;
        margin: 0 auto 20px;
        background: linear-gradient(135deg, #6ba932 0%, #5a9028 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .contact-card:hover .contact-icon {
        transform: rotate(360deg) scale(1.1);
    }

    .contact-icon i {
        font-size: 30px;
        color: white;
    }

    .contact-card h3 {
        font-size: 1.25rem;
        color: #2c3e50;
        margin-bottom: 12px;
        font-weight: 600;
    }

    .contact-details {
        color: #546e7a;
        line-height: 1.7;
    }

    .contact-details p {
        margin: 7px 0;
        font-size: 0.9rem;
    }

    .phone-number {
        font-size: 1.15rem;
        font-weight: 700;
        color: #6ba932;
        margin: 12px 0;
        display: block;
    }

    .address-text {
        font-size: 0.95rem;
        color: #2c3e50;
        font-weight: 500;
        line-height: 1.6;
    }

    .contact-map {
        background: white;
        border-radius: 14px;
        padding: 24px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        animation: fadeIn 1s ease;
    }

    .contact-map h3 {
        font-size: 1.3rem;
        color: #2c3e50;
        margin-bottom: 16px;
        text-align: center;
        font-weight: 600;
    }

    .map-container {
        width: 100%;
        height: 400px;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .map-container iframe {
        width: 100%;
        height: 100%;
        border: none;
    }

    .map-actions {
        display: flex;
        gap: 12px;
        margin-top: 16px;
        justify-content: center;
        flex-wrap: wrap;
    }

    .map-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        background: linear-gradient(135deg, #6ba932 0%, #5a9028 100%);
        color: white;
        text-decoration: none;
        border-radius: 8px;
        font-size: 0.9rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .map-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(107, 169, 50, 0.3);
        color: white;
        text-decoration: none;
    }

    .map-btn.outline {
        background: transparent;
        border: 2px solid #6ba932;
        color: #6ba932;
    }

    .map-btn.outline:hover {
        background: #6ba932;
        color: white;
    }

    .store-hours {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 16px;
        margin-top: 16px;
        text-align: center;
    }

    .store-hours h4 {
        color: #2c3e50;
        margin-bottom: 8px;
        font-size: 1rem;
    }

    .store-hours p {
        color: #546e7a;
        margin: 4px 0;
        font-size: 0.9rem;
    }

    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-24px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(24px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    @keyframes ripple {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }

    @media (max-width: 768px) {
        .contact-wrapper {
            padding: 32px 12px;
        }

        .contact-intro h1 {
            font-size: 1.75rem;
        }

        .contact-intro p {
            font-size: 0.95rem;
        }

        .contact-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .contact-card {
            padding: 24px 16px;
        }

        .contact-icon {
            width: 60px;
            height: 60px;
        }

        .contact-icon i {
            font-size: 26px;
        }

        .contact-card h3 {
            font-size: 1.15rem;
        }

        .map-container {
            height: 300px;
        }

        .map-actions {
            flex-direction: column;
            align-items: center;
        }

        .map-btn {
            width: 100%;
            justify-content: center;
        }
    }

    @media (max-width: 576px) {
        .contact-wrapper {
            padding: 24px 10px;
        }

        .contact-intro {
            margin-bottom: 28px;
        }

        .contact-intro h1 {
            font-size: 1.5rem;
            margin-bottom: 10px;
        }

        .contact-intro p {
            font-size: 0.9rem;
        }

        .contact-card {
            padding: 20px 14px;
        }

        .contact-icon {
            width: 55px;
            height: 55px;
            margin-bottom: 16px;
        }

        .contact-icon i {
            font-size: 24px;
        }

        .contact-card h3 {
            font-size: 1.1rem;
            margin-bottom: 10px;
        }

        .contact-details p {
            font-size: 0.85rem;
        }

        .phone-number {
            font-size: 1.05rem;
            margin: 10px 0;
        }

        .contact-map {
            padding: 20px;
        }

        .contact-map h3 {
            font-size: 1.15rem;
            margin-bottom: 14px;
        }

        .map-container {
            height: 250px;
        }
    }
</style>

<div class="contact-wrapper">
    <div class="contact-container">
        <!-- Introduction Section -->
        <div class="contact-intro">
            <h1>Get In Touch With Us</h1>
            <p>Have questions? We're here to help! Reach out to us through any of the channels below.</p>
        </div>

        <!-- Contact Cards Grid -->
        <div class="contact-grid">
            <!-- Email Contact -->
            <div class="contact-card">
                <div class="contact-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <h3>Send Us a Message</h3>
                <div class="contact-details">
                    <p>We're available to chat</p>
                    <p><strong>9:00 AM – 6:00 PM</strong></p>
                    <p>Monday to Sunday</p>
                </div>
            </div>

            <!-- Phone Contact -->
            <div class="contact-card">
                <div class="contact-icon">
                    <i class="fas fa-phone-alt"></i>
                </div>
                <h3>Call Us</h3>
                <div class="contact-details">
                    <span class="phone-number">0966 793 3067</span>
                    <p><strong>Products & Orders:</strong></p>
                    <p>9:00 AM – 6:00 PM, Mon-Sun</p>
                    <p style="margin-top: 8px;"><strong>General Questions:</strong></p>
                    <p>9:00 AM – 6:00 PM, Mon-Sat</p>
                </div>
            </div>

            <!-- Location Contact -->
            <div class="contact-card">
                <div class="contact-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <h3>Visit Our Store</h3>
                <div class="contact-details">
                    <p class="address-text">
                        Brgy. Calzada, Ermita<br>
                        Balayan, Batangas<br>
                        Philippines
                    </p>
                    <p style="margin-top: 12px; color: #6ba932; font-weight: 600;">
                        <i class="fas fa-clock"></i> Open Daily
                    </p>
                </div>
            </div>
        </div>

        <!-- Map Section -->
        <div class="contact-map">
            <h3><i class="fas fa-location-arrow"></i> Find Us Here</h3>

            <!-- Google Maps Embed -->
            <div class="map-container">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3871.238215381647!2d120.7276702!3d13.9439333!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33bda3002328a353%3A0x78e382a94d1a948e!2sBalayan%20Smasher%E2%80%99s%20Hub!5e0!3m2!1sen!2sph!4v1698765432100!5m2!1sen!2sph"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>

            <!-- Map Actions -->
            <div class="map-actions">
                <a href="https://www.google.com/maps/dir//Balayan+Smasher%E2%80%99s+Hub,+Brgy.+Calzada,+Ermita,+Balayan,+Batangas/@13.9439333,120.7276702,17z/data=!4m8!4m7!1m0!1m5!1m1!1s0x33bda3002328a353:0x78e382a94d1a948e!2m2!1d120.7302451!2d13.9439333?entry=ttu"
                   target="_blank"
                   class="map-btn">
                    <i class="fas fa-directions"></i>
                    Get Directions
                </a>
                <a href="https://www.google.com/maps/place/Balayan+Smasher%E2%80%99s+Hub/@13.9439333,120.7276702,17z/data=!3m1!4b1!4m6!3m5!1s0x33bda3002328a353:0x78e382a94d1a948e!8m2!3d13.9439333!4d120.7302451!16s%2Fg%2F11wr3tqm0h?entry=ttu"
                   target="_blank"
                   class="map-btn outline">
                    <i class="fas fa-external-link-alt"></i>
                    Open in Google Maps
                </a>
            </div>

            <!-- Store Hours -->
            <div class="store-hours">
                <h4><i class="fas fa-store"></i> Store Hours</h4>
                <p><strong>Monday - Sunday:</strong> 9:00 AM - 6:00 PM</p>
                <p><em>We're open every day to serve your sports equipment needs!</em></p>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
// Add interactive functionality to the map section
document.addEventListener('DOMContentLoaded', function() {
    const mapContainer = document.querySelector('.map-container');

    // Add loading animation
    mapContainer.addEventListener('mouseenter', function() {
        this.style.transform = 'scale(1.02)';
        this.style.transition = 'transform 0.3s ease';
    });

    mapContainer.addEventListener('mouseleave', function() {
        this.style.transform = 'scale(1)';
    });

    // Add click animation to buttons
    const mapButtons = document.querySelectorAll('.map-btn');
    mapButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            // Add ripple effect
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;

            ripple.style.cssText = `
                position: absolute;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.6);
                transform: scale(0);
                animation: ripple 0.6s linear;
                width: ${size}px;
                height: ${size}px;
                left: ${x}px;
                top: ${y}px;
            `;

            this.style.position = 'relative';
            this.style.overflow = 'hidden';
            this.appendChild(ripple);

            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });
});
</script>
@endsection
@endsection
