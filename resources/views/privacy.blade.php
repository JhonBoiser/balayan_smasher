@extends('layouts.app')

@section('title', 'Privacy Policy')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <h1 class="mb-3">Privacy Policy</h1>
            <p class="text-muted">Last updated: {{ date('F d, Y') }}</p>

            <section class="mt-4">
                <h4>1. Information We Collect</h4>
                <p>We may collect personal information you provide when placing orders, creating an account, or contacting support. We also collect technical data via cookies and logs.</p>
            </section>

            <section class="mt-3">
                <h4>2. How We Use Your Information</h4>
                <p>We use information to process orders, communicate with you, improve our services, and comply with legal obligations.</p>
            </section>

            <section class="mt-3">
                <h4>3. Sharing and Disclosure</h4>
                <p>We do not sell your personal information. We may share data with service providers who assist in order fulfillment and payment processing under confidentiality obligations.</p>
            </section>

            <section class="mt-3">
                <h4>4. Your Choices</h4>
                <p>You may update or delete your account information, and manage marketing preferences. For requests, contact our support team.</p>
            </section>

            <section class="mt-4">
                <p class="small text-muted">For detailed questions about privacy, please see our <a href="/contact">Contact</a> page.</p>
            </section>
        </div>
    </div>
</div>
@endsection
