<!DOCTYPE html>
<html lang="en-US">
@php
    try {
        $siteSettings = \App\Models\SiteSetting::getSettings();
    } catch (\Throwable $e) {
        $siteSettings = new \App\Models\SiteSetting([
            'site_name' => 'PRATIBUDDHA',
            'hero_title' => 'Nepal\'s Premier Electronics Importer',
            'hero_subtitle' => 'Elevating Your Home with Quality Electronics',
            'about_text' => 'At Pratibuddha Enterprises, we are dedicated to transforming your home experience with cutting-edge technology and global expertise.',
            'hero_cta_products' => 'Explore Products',
            'hero_cta_dealers' => 'Authorized Dealers',
            'contact_address' => 'Janabahal, Kathmandu, Nepal',
            'contact_phone' => '+977-9849124657',
            'contact_email' => 'info@pratibuddha.com',
            'warehouse_location' => 'Sitapaila, Kathmandu',
        ]);
    }
    $compiledCssAvailable = false;
    try {
        $manifest = public_path('build/manifest.json');
        if (file_exists($manifest)) {
            $manifestPayload = json_decode(file_get_contents($manifest), true, 512, JSON_THROW_ON_ERROR);
            $compiledCssAvailable = isset($manifestPayload['resources/css/app.css']) || isset($manifestPayload['resources\\css\\app.css']);
        }
    } catch (\Throwable $e) {
        $compiledCssAvailable = false;
    }
@endphp
@php
    $socialLinks = \App\Models\SiteSetting::getContactSocialLinks($siteSettings->contact_page ?? null);
@endphp
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PRATIBUDDHA | Premium Quality Products')</title>
    <meta name="description" content="{{ $siteSettings->seo_description ?? 'Premium electronics and authorized dealer network in Nepal.' }}">
    <meta name="robots" content="index, follow">
    @if($siteSettings->canonical_url)
        <link rel="canonical" href="{{ $siteSettings->canonical_url }}">
    @endif
    @if($siteSettings->og_image)
        <meta property="og:image" content="{{ $siteSettings->og_image }}">
    @endif
    @if ($compiledCssAvailable)
        @vite('resources/css/app.css')
    @else
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @endif
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    {!! $siteSettings->analytics_script ?? '' !!}
    @stack('styles')
</head>
<body>
    @php
        $title = $siteSettings->site_name ?? 'PRATIBUDDHA';
    @endphp

    <a class="skip-link" href="#content-start">Skip to content</a>

    <header class="site-header">
        <div class="container site-navbar-shell">
            <nav class="site-navbar" aria-label="Primary Navigation">
                <a href="{{ route('home') }}" class="site-brand">
                    <span>{{ $title }}</span>
                </a>
                <button id="mobile-nav-toggle" class="nav-toggle" aria-expanded="false" aria-controls="site-nav-links" aria-label="Open navigation menu">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
                <div id="site-nav-links" class="site-nav-links">
                    <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'is-active' : '' }}">Home</a>
                    <a href="{{ route('products.index') }}" class="{{ request()->routeIs('products.index') ? 'is-active' : '' }}">Products</a>
                    <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'is-active' : '' }}">About</a>
                    <a href="{{ route('gallery') }}" class="{{ request()->routeIs('gallery') ? 'is-active' : '' }}">Gallery</a>
                    <a href="{{ route('events') }}" class="{{ request()->routeIs('events') ? 'is-active' : '' }}">Events</a>
                    <a href="{{ route('careers') }}" class="{{ request()->routeIs('careers') ? 'is-active' : '' }}">Careers</a>
                    <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'is-active' : '' }}">Contact</a>
                    <a href="{{ route('dealers.index') }}" class="{{ request()->routeIs('dealers.*') ? 'is-active' : '' }}">Dealers</a>
                    <a href="/admin" class="site-nav-action">Admin Login</a>
                </div>
            </nav>
        </div>
    </header>

    <main id="content-start" class="site-main">
        @yield('content')
    </main>

    <footer class="site-footer">
        <div class="container">
            <div class="site-footer-grid">
                <section>
                    <h3 class="site-footer-title">{{ $title }}</h3>
                    <p class="site-footer-text">{{ $siteSettings->about_text ?? 'Join us on a journey to elevate your home, one appliance at a time. Quality and innovation right at your doorstep.' }}</p>
                </section>
                <section>
                    <h4 class="site-footer-subtitle">Quick Links</h4>
                    <ul class="site-footer-links">
                        <li><a href="{{ route('home') }}">Home</a></li>
                        <li><a href="{{ route('products.index') }}">Products</a></li>
                        <li><a href="{{ route('about') }}">About</a></li>
                        <li><a href="{{ route('gallery') }}">Gallery</a></li>
                        <li><a href="{{ route('events') }}">Events</a></li>
                        <li><a href="{{ route('careers') }}">Careers</a></li>
                    </ul>
                </section>
                <section>
                    <h4 class="site-footer-subtitle">Extras</h4>
                    <ul class="site-footer-links">
                        <li><a href="{{ route('contact') }}">Contact</a></li>
                        <li><a href="{{ route('dealers.index') }}">Find a Dealer</a></li>
                    </ul>
                </section>
                <section>
                    <h4 class="site-footer-subtitle">Get in Touch</h4>
                    <ul class="site-footer-links">
                        <li>
                            <i class="fa-solid fa-location-dot" aria-hidden="true"></i>
                            <span>{{ $siteSettings->contact_address ?? 'Janabahal, Kathmandu, Nepal' }}</span>
                        </li>
                        <li>
                            <i class="fa-solid fa-phone" aria-hidden="true"></i>
                            <span>{{ $siteSettings->contact_phone ?? '+977-9849124657' }}</span>
                        </li>
                        <li>
                            <i class="fa-solid fa-warehouse" aria-hidden="true"></i>
                            <span>Warehouse: {{ $siteSettings->warehouse_location ?? 'Sitapaila, Kathmandu' }}</span>
                        </li>
                    </ul>
                </section>
            </div>
        </div>

        @if (count($socialLinks))
            <div class="site-footer-socials-wrapper">
                <div class="container">
                    <div class="site-footer-socials" aria-label="Social networking links">
                        <h3 class="site-footer-social-title">Follow Us</h3>
                        <div class="site-social-link-row">
                            @foreach($socialLinks as $social)
                                <a
                                    href="{{ $social['url'] }}"
                                    class="site-social-link-text"
                                    target="{{ $social['is_web'] ? '_blank' : '_self' }}"
                                    rel="{{ $social['is_web'] ? 'noopener noreferrer' : '' }}"
                                    aria-label="{{ $social['label'] }}"
                                >
                                    <i class="{{ $social['icon'] }}" aria-hidden="true"></i>
                                    <span>{{ $social['label'] }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="site-footer-bottom">
            <div class="container">
                <p class="site-footer-small">&copy; {{ date('Y') }} {{ $title }}. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        const navToggle = document.getElementById('mobile-nav-toggle');
        const navLinks = document.getElementById('site-nav-links');
        if (navToggle && navLinks) {
            navToggle.addEventListener('click', () => {
                const isOpen = navLinks.classList.toggle('open');
                navToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                navToggle.setAttribute('aria-label', isOpen ? 'Close navigation menu' : 'Open navigation menu');
            });

            navLinks.querySelectorAll('a').forEach((link) => {
                link.addEventListener('click', () => {
                    navLinks.classList.remove('open');
                    navToggle.setAttribute('aria-expanded', 'false');
                    navToggle.setAttribute('aria-label', 'Open navigation menu');
                });
            });
        }
    </script>
    <style>
        .site-footer-socials {
            margin-top: 0.7rem;
            margin-bottom: 0.5rem;
        }

        .site-social-link-row {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.7rem;
        }

        .site-footer-social-title {
            margin: 0 0 0.4rem;
            font-size: 0.93rem;
            color: #f8fafc;
        }

        .site-social-link-text {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.37rem 0.65rem;
            color: #f8fafc;
            font-size: 0.84rem;
            text-decoration: underline;
            text-underline-offset: 0.2rem;
        }

        .site-social-link-text:hover,
        .site-social-link-text:focus-visible {
            color: #bfdbfe;
        }
    </style>
    @stack('scripts')
</body>
</html>
