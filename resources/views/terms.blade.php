@extends('layouts.app')

@section('title', 'Terms of Service')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <h1 class="mb-3">Terms of Service</h1>
            <p class="text-muted">Last updated: {{ date('F d, Y') }}</p>

            <section class="mt-4">
                <h4>1. Introduction</h4>
                <p>Welcome to Balayan Smashers Hub. By using our website you agree to these Terms of Service. Please read them carefully.</p>
            </section>

            <section class="mt-3">
                <h4>2. Using the Service</h4>
                <p>Use of the site is subject to compliance with applicable laws and these terms. You agree not to misuse the service.</p>
            </section>

            <section class="mt-3">
                <h4>3. Orders and Payments</h4>
                <p>Order acceptance, payment processing, cancellations, returns and other commerce-related rules are governed by our policies and applicable law.</p>
            </section>

            <section class="mt-3">
                <h4>4. Intellectual Property</h4>
                <p>All content on this site is the property of Balayan Smashers Hub or its licensors and is protected by intellectual property laws.</p>
            </section>

            <section class="mt-3">
                <h4>5. Limitation of Liability</h4>
                <p>To the fullest extent permitted by law, we are not liable for indirect or consequential damages arising from use of the service.</p>
            </section>

            <section class="mt-4">
                <p class="small text-muted">If you have questions about these Terms, contact us via the <a href="/contact">Contact</a> page.</p>
            </section>
        </div>
    </div>
</div>
@endsection
