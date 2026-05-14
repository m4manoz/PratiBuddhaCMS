@extends('layouts.app')

@section('title', 'Contact | PRATIBUDDHA')

@section('content')
@php
    $socialLinks = \App\Models\SiteSetting::getContactSocialLinks($contactContent ?? []);
@endphp
<section class="inner-page-shell">
    <div class="container">
        <div class="inner-hero">
            <p class="home-kicker">Contact</p>
            <h1>Get in Touch with Pratibuddha</h1>
            <p>Share your message, connect with our team, or visit our office for in-person support.</p>
        </div>

        <div class="contact-grid">
            <article class="contact-card">
                <div class="contact-intro">
                    <p class="home-kicker">Support & enquiries</p>
                    <h2>{{ $contactContent['office_title'] ?? 'Head Office' }}</h2>
                    <p class="text-muted">
                        We are available to assist with product enquiries, dealer support, and service guidance across Nepal.
                    </p>
                </div>

                <div class="office-person-grid">
                    <div class="contact-meta-grid">
                        <h3>Office Contact Information</h3>
                        @foreach($contactLocations as $location)
                            <article class="location-item">
                                <p><strong>{{ $location['office_title'] }}</strong></p>
                                <p><i class="fa-solid fa-location-dot meta-icon" aria-hidden="true"></i> {{ $location['address'] }}</p>
                                <p><i class="fa-solid fa-phone meta-icon" aria-hidden="true"></i> {{ $location['phone'] }}</p>
                                <p><i class="fa-solid fa-envelope meta-icon" aria-hidden="true"></i> {{ $location['email'] }}</p>
                                <p><i class="fa-solid fa-clock meta-icon" aria-hidden="true"></i> {{ $location['hours'] }}</p>
                            </article>
                        @endforeach
                    </div>

                    <div class="contact-person-card">
                        <h3>Contact Person</h3>
                        <p><i class="fa-solid fa-user" aria-hidden="true"></i> <strong>Name:</strong> {{ $contactContent['contact_person_name'] ?? 'Support Team' }}</p>
                        <p><i class="fa-solid fa-badge-check" aria-hidden="true"></i> <strong>Role:</strong> {{ $contactContent['contact_person_role'] ?? 'Customer Support' }}</p>
                        <p><i class="fa-solid fa-phone" aria-hidden="true"></i> <strong>Phone:</strong> {{ $contactContent['contact_person_phone'] ?? ($contactContent['phone'] ?? '+977-9849124657') }}</p>
                        <p><i class="fa-solid fa-envelope" aria-hidden="true"></i> <strong>Email:</strong> {{ $contactContent['contact_person_email'] ?? ($contactContent['email'] ?? 'info@pratibuddha.com') }}</p>
                    </div>

                    @if (count($socialLinks))
                        <section class="contact-social-wrap">
                            <h3>Follow Us</h3>
                            <div class="contact-social-links" aria-label="Social networking links">
                                @foreach($socialLinks as $social)
                                    <a
                                        href="{{ $social['url'] }}"
                                        class="contact-social-link-text"
                                        target="{{ $social['is_web'] ? '_blank' : '_self' }}"
                                        rel="{{ $social['is_web'] ? 'noopener noreferrer' : '' }}"
                                        aria-label="{{ $social['label'] }}"
                                    >
                                        <i class="{{ $social['icon'] }}" aria-hidden="true"></i>
                                        <span>{{ $social['label'] }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </section>
                    @endif
                </div>

            <div class="contact-support-actions">
                <a
                    href="#"
                    class="btn btn-outline contact-call-trigger"
                    data-phone="{{ $contactContent['phone'] ?? '+977-9849124657' }}"
                    data-name="{{ $contactContent['office_title'] ?? 'Head Office' }}"
                    data-message="Need help with my inquiry"
                >
                    <i class="fa-solid fa-phone" aria-hidden="true"></i> Call now
                </a>
                    <a href="mailto:{{ $contactContent['email'] ?? 'info@pratibuddha.com' }}" class="btn btn-outline">
                        <i class="fa-solid fa-envelope" aria-hidden="true"></i> Write email
                    </a>
                </div>
            </article>

            <form method="POST" action="{{ route('contact.store') }}" class="contact-form">
                @csrf
                <input type="text" name="company_website" autocomplete="off" tabindex="-1" class="honeypot" aria-hidden="true">
                @if(session('contact_success'))
                    <div class="feedback feedback-success">{{ session('contact_success') }}</div>
                @endif
                @if(session('contact_error'))
                    <div class="feedback feedback-error">{{ session('contact_error') }}</div>
                @endif
                @if($errors->any())
                    <div class="feedback feedback-error">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <input class="form-control" type="text" name="name" placeholder="Your full name" value="{{ old('name') }}" required>
                <input class="form-control" type="email" name="email" placeholder="Email address" value="{{ old('email') }}" required>
                <input class="form-control" type="text" name="phone" placeholder="Phone number" value="{{ old('phone') }}" required>
                <input class="form-control" type="text" name="subject" placeholder="Subject" value="{{ old('subject') }}">
                <textarea class="form-control" name="message" placeholder="How can we help?" rows="5" required>{{ old('message') }}</textarea>
                <button class="btn btn-primary" type="submit">
                    <i class="fa-solid fa-paper-plane" aria-hidden="true"></i>
                    Send message
                </button>
            </form>
        </div>
    </div>
</section>

<div id="contact-call-options-dialog" class="call-options-dialog" aria-hidden="true">
    <div class="call-options-overlay" data-contact-call-close></div>
    <div class="call-options-panel" role="dialog" aria-modal="true" aria-labelledby="contact-call-dialog-title">
        <h3 id="contact-call-dialog-title">Call Contact</h3>
        <p id="contact-call-dialog-subtitle">Choose a communication method.</p>
        <div class="call-option-buttons">
            <a id="contact-call-dialog-viber" href="#" class="btn btn-primary">Viber</a>
            <a id="contact-call-dialog-sim" href="#" class="btn btn-outline">Call via SIM Dialer</a>
            <a id="contact-call-dialog-whatsapp" href="#" class="btn btn-outline">WhatsApp</a>
        </div>
        <button id="contact-call-dialog-close" type="button" class="btn btn-outline">Cancel</button>
    </div>
</div>
@endsection

@push('styles')
<style>
    .contact-grid {
        align-items: stretch;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    }

    .contact-intro h2 {
        margin: 0.2rem 0 0.6rem;
        font-size: 1.45rem;
    }

    .contact-meta-grid {
        margin-top: 1rem;
        display: grid;
        gap: 0.6rem;
    }

    .contact-meta-grid p {
        display: flex;
        align-items: flex-start;
        gap: 0.55rem;
        margin: 0;
    }

    .office-person-grid {
        margin-top: 1rem;
        display: grid;
        gap: 0.9rem;
        grid-template-columns: 1.35fr 1fr;
    }

    .location-item {
        padding: 0.8rem;
        border: 1px solid var(--line);
        border-radius: var(--radius-md);
        background: #f8fafc;
    }

    .location-item p {
        margin: 0 0 0.48rem;
        display: flex;
        gap: 0.55rem;
        align-items: flex-start;
    }

    .location-item p:last-child {
        margin-bottom: 0;
    }

    .location-item p strong {
        display: block;
    }

    .contact-person-card {
        margin-top: 1.1rem;
        padding: 0.9rem;
        border: 1px solid var(--line);
        border-radius: var(--radius-md);
        background: #f8fafc;
    }

    .contact-person-card h3 {
        margin: 0 0 0.6rem;
        font-size: 1.02rem;
    }

    .contact-person-card p {
        display: flex;
        align-items: flex-start;
        gap: 0.55rem;
        margin: 0 0 0.52rem;
    }

    .contact-person-card p:last-child {
        margin-bottom: 0;
    }

    .contact-social-wrap {
        margin-top: 1rem;
        padding: 0.9rem;
        border: 1px solid var(--line);
        border-radius: var(--radius-md);
        background: #f8fafc;
    }

    .contact-social-wrap h3 {
        margin: 0 0 0.55rem;
        font-size: 1.02rem;
        color: #0f172a;
    }

    .contact-social-links {
        display: flex;
        flex-wrap: wrap;
        gap: 0.7rem;
    }

    .contact-social-link-text {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0;
        color: #0f172a;
        font-size: 0.92rem;
        text-decoration: underline;
        text-underline-offset: 0.2rem;
    }

    .contact-social-link-text:hover,
    .contact-social-link-text:focus-visible {
        color: var(--primary);
    }

    .contact-support-actions {
        margin-top: 1.1rem;
        display: flex;
        flex-wrap: wrap;
        gap: 0.65rem;
    }

    .contact-support-actions .btn {
        width: 100%;
        justify-content: center;
        display: inline-flex;
        gap: 0.45rem;
        align-items: center;
    }

    .contact-form .form-control {
        min-height: 44px;
        padding: 0.72rem 0.85rem;
    }

    .contact-form textarea.form-control {
        min-height: 140px;
        line-height: 1.5;
    }

    .contact-form .btn i {
        margin-right: 0.25rem;
    }

    .call-options-dialog {
        position: fixed;
        inset: 0;
        z-index: 120;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 1rem;
        background: rgba(2, 6, 23, 0.35);
    }

    .call-options-dialog.is-open {
        display: flex;
    }

    .call-options-overlay {
        position: absolute;
        inset: 0;
        cursor: pointer;
    }

    .call-options-panel {
        position: relative;
        z-index: 1;
        width: min(95%, 420px);
        background: #fff;
        border-radius: 0.85rem;
        padding: 1rem;
        border: 1px solid var(--line);
        box-shadow: var(--shadow-md);
        display: grid;
        gap: 0.7rem;
    }

    .call-option-buttons {
        display: grid;
        gap: 0.6rem;
        margin-top: 0.2rem;
    }

    .contact-meta-grid h3,
    .contact-person-card h3 {
        margin: 0 0 0.55rem;
        font-size: 1.02rem;
    }

    @media (max-width: 640px) {
        .office-person-grid {
            grid-template-columns: 1fr;
        }

        .contact-support-actions {
            flex-direction: column;
        }
    }
</style>
@endpush

@push('scripts')
<script>
function sanitizePhoneNumber(rawPhone) {
    return String(rawPhone || '').replace(/[^+\d]/g, '');
}

function contactDefaultMessage(contextLabel, customMessage = '') {
    const fallback = `Hello ${contextLabel}, I would like to get in touch regarding my enquiry.`;

    return String(customMessage || fallback).trim();
}

function openContactCallOptions(rawPhone, contextLabel, customMessage = '') {
    const normalized = sanitizePhoneNumber(rawPhone);
    const digitsOnly = normalized.replace(/\D/g, '');
    const message = contactDefaultMessage(contextLabel, customMessage);

    const dialog = document.getElementById('contact-call-options-dialog');
    const title = document.getElementById('contact-call-dialog-title');
    const subtitle = document.getElementById('contact-call-dialog-subtitle');
    const simButton = document.getElementById('contact-call-dialog-sim');
    const whatsappButton = document.getElementById('contact-call-dialog-whatsapp');
    const viberButton = document.getElementById('contact-call-dialog-viber');

    if (!dialog || !title || !subtitle || !simButton || !whatsappButton || !viberButton) {
        window.location.href = `tel:${normalized}`;
        return;
    }

    title.textContent = `Call ${contextLabel}`;
    subtitle.textContent = `Connect using ${normalized} via your preferred method.`;
    simButton.href = `tel:${normalized}`;
    whatsappButton.href = `https://wa.me/${digitsOnly}?text=${encodeURIComponent(message)}`;
    whatsappButton.target = '_blank';
    whatsappButton.rel = 'noopener';
    viberButton.href = `viber://chat?number=${encodeURIComponent(normalized)}&text=${encodeURIComponent(message)}`;
    dialog.classList.add('is-open');
}

function closeContactCallOptions() {
    const dialog = document.getElementById('contact-call-options-dialog');
    if (dialog) {
        dialog.classList.remove('is-open');
    }
}

document.querySelectorAll('.contact-call-trigger').forEach((callLink) => {
    callLink.addEventListener('click', (event) => {
        event.preventDefault();
        const phone = callLink.dataset.phone || '';
        const name = callLink.dataset.name || 'Contact';
        const customMessage = callLink.dataset.message || '';
        openContactCallOptions(phone, name, customMessage);
    });
});

document.getElementById('contact-call-dialog-close')?.addEventListener('click', () => {
    closeContactCallOptions();
});

document.querySelectorAll('[data-contact-call-close]').forEach((node) => {
    node.addEventListener('click', () => {
        closeContactCallOptions();
    });
});

document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
        closeContactCallOptions();
    }
});
</script>
@endpush
