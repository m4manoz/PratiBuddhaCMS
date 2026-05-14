@extends('layouts.app')

@php
    $heroTitle = trim($siteSettings->hero_title ?? '');
    $heroSubtitle = trim($siteSettings->hero_subtitle ?? '');
    $aboutText = trim($siteSettings->about_text ?? '');
    $ctaProducts = trim($siteSettings->hero_cta_products ?? 'Explore Products');
    $ctaDealers = trim($siteSettings->hero_cta_dealers ?? 'Find Dealers');
    $title = trim($siteSettings->site_name ?? 'PRATIBUDDHA');
@endphp

@section('title', ($title ? $title . ' | ' : '') . 'Premium Electronics')

@section('content')
<section class="home-hero section-shell">
    <div class="container home-hero-grid">
        <div class="home-hero-content">
            <p class="home-kicker">PRATIBUDDHA ENTERPRISES</p>
            <h1>{{ $heroTitle !== '' ? $heroTitle : 'Nepal\'s trusted electronics partner' }}</h1>
            <p class="home-lede">
                {{ $heroSubtitle !== '' ? $heroSubtitle : 'Discover verified premium products with transparent support and trusted nationwide dealer coverage.' }}
            </p>
            <div class="home-cta-group">
                <a href="{{ route('products.index') }}" class="btn btn-primary">{{ $ctaProducts }}</a>
                <a href="{{ route('dealers.index') }}" class="btn btn-outline">{{ $ctaDealers }}</a>
            </div>
            <ul class="home-highlights" aria-label="Quick support highlights">
                <li><i class="fa-solid fa-truck-fast home-highlight-icon" aria-hidden="true"></i> Free shipping in select areas</li>
                <li><i class="fa-solid fa-certificate home-highlight-icon" aria-hidden="true"></i> Certified appliances</li>
                <li><i class="fa-solid fa-headset home-highlight-icon" aria-hidden="true"></i> Sales advisory support</li>
                <li><i class="fa-solid fa-map-location-dot home-highlight-icon" aria-hidden="true"></i> Province, district, local level coverage</li>
            </ul>
        </div>

        <figure class="home-hero-media">
            <img src="{{ asset('images/hero-showroom.jpg') }}" alt="Pratibuddha showroom display">
        </figure>

        <aside class="home-hero-stats" aria-label="Brand summary">
            <h2>At a glance</h2>
            <p>Everything you need to find the right electronics quickly and confidently.</p>
            <div class="home-stat">
                <span class="home-stat-value">{{ $products->count() }}+</span>
                <span class="home-stat-label">Featured products</span>
            </div>
            <div class="home-stat">
                <span class="home-stat-value">3</span>
                <span class="home-stat-label">Active provinces</span>
            </div>
            <div class="home-stat">
                <span class="home-stat-value">24/7</span>
                <span class="home-stat-label">Support response</span>
            </div>
            <a href="{{ route('products.index') }}" class="home-ghost-btn">Browse our catalog</a>
        </aside>
    </div>
</section>

<section class="section-shell section-light">
    <div class="container quick-link-grid">
        <article class="quick-link-card">
            <p class="home-kicker">About</p>
            <h2>Built for reliability</h2>
            <p>Focused product sourcing, transparent enquiry management, and an expanding dealer footprint.</p>
            <a href="{{ route('about') }}" class="home-inline-link">Read about us</a>
        </article>
        <article class="quick-link-card">
            <p class="home-kicker">Gallery</p>
            <h2>See our story in action</h2>
            <p>Projects, service operations, and customer advisory moments from across our network.</p>
            <a href="{{ route('gallery') }}" class="home-inline-link">Open gallery</a>
        </article>
        <article class="quick-link-card">
            <p class="home-kicker">Events</p>
            <h2>Workshops and launches</h2>
            <p>Dealer workshops, launch events, and training sessions designed to improve customer outcomes.</p>
            <a href="{{ route('events') }}" class="home-inline-link">View events</a>
        </article>
        <article class="quick-link-card">
            <p class="home-kicker">Careers</p>
            <h2>Grow your career</h2>
            <p>Explore open roles in sales, operations, and support.</p>
            <a href="{{ route('careers') }}" class="home-inline-link">Open roles</a>
        </article>
        <article class="quick-link-card">
            <p class="home-kicker">Contact</p>
            <h2>Direct support</h2>
            <p>Share your requirement and we will route you to the right team quickly.</p>
            <a href="{{ route('contact') }}" class="home-inline-link">Send a query</a>
        </article>
    </div>
</section>

<section class="section-shell home-section">
    <div class="container section-head">
        <div>
            <p class="home-kicker">Product Listing</p>
            <h2>Top picks from our catalog</h2>
        </div>
        <a href="{{ route('products.index') }}" class="home-link-more">View all products</a>
    </div>

    <div class="container home-products-grid">
        @forelse($products as $product)
            <article class="home-product-card">
                <a class="home-product-image-wrap" href="{{ route('products.show', $product->slug) }}" aria-label="View {{ $product->title }}">
                    @if($product->image_path)
                        <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->title }}">
                    @else
                        <img src="{{ asset('images/product-placeholder.svg') }}" alt="Product image placeholder">
                    @endif
                </a>
                <div class="home-card-body">
                    <p class="home-pill">{{ $product->category ?? 'General' }}</p>
                    <h3>{{ $product->title }}</h3>
                    <p>{{ $product->description ? \Illuminate\Support\Str::limit($product->description, 96) : 'High-performance solution for home and business use.' }}</p>
                    <p class="home-price">{{ $product->price !== null ? 'NPR ' . number_format($product->price) : 'Price on Request' }}</p>
                    <a href="{{ route('products.show', $product->slug) }}" class="home-inline-link">See details <span aria-hidden="true">&rarr;</span></a>
                </div>
            </article>
        @empty
            <div class="home-empty-card">
                <p>No featured products are available at the moment.</p>
                <a href="{{ route('products.index') }}" class="home-inline-link">Browse all products</a>
            </div>
        @endforelse
    </div>
</section>

<section class="section-shell home-section alt">
    <div class="container home-two-column">
        <div>
            <p class="home-kicker">About Pratibuddha</p>
            <h2>Built for reliability, service, and trust</h2>
            <p>
                {{ $aboutText !== '' ? $aboutText : 'Our team focuses on dependable service, accurate product data, and a simple buying journey for every customer across Nepal.' }}
            </p>
            <ul class="home-steps">
                <li><strong>Browse</strong> complete product profiles with specifications and stock status.</li>
                <li><strong>Connect</strong> through direct enquiries on any listing.</li>
                <li><strong>Complete</strong> purchase with your nearest authorized dealer.</li>
            </ul>
        </div>

        <div class="home-two-column-card">
            <h3>Need help choosing?</h3>
            <p>Tell us your budget and usage. We route your inquiry to the right advisor and nearest dealer.</p>
            <a href="{{ route('contact') }}" class="btn btn-outline">Talk to our team</a>
        </div>
    </div>
</section>

<section class="section-shell">
    <div class="container section-head">
        <div>
            <p class="home-kicker">Authorized network</p>
            <h2>Find dealers by location fast</h2>
            <p>Search by province, district, local level, ward and street/tole, then get direct directions.</p>
        </div>
        <a href="{{ route('dealers.index') }}" class="home-link-more">Open directory</a>
    </div>
    <div class="container home-directory-banner">
        <img src="{{ asset('images/dealer-map-banner.svg') }}" alt="Dealer map network">
        <a href="{{ route('dealers.index') }}" class="home-inline-link">Explore nearby dealers <span aria-hidden="true">&rarr;</span></a>
    </div>
</section>

<section class="section-shell section-light">
    <div class="container">
        <div class="section-head">
            <div>
                <p class="home-kicker">Brand lineup</p>
                <h2>Find products by brand</h2>
            </div>
            <a href="{{ route('products.index') }}" class="home-link-more">See full catalog</a>
        </div>
        <div class="brand-grid">
            @forelse($brands as $brand)
                <a href="{{ route('products.index', ['brand' => $brand]) }}" class="brand-card">
                    <p class="home-kicker">Brand Collection</p>
                    <p class="quick-stat">{{ $brand }}</p>
                    <p>Explore curated products under this brand category.</p>
                </a>
            @empty
                <div class="home-empty-card">
                    <p>Brand collections will appear once product brand data is added.</p>
                    <a href="{{ route('products.index') }}" class="home-inline-link">Browse products now</a>
                </div>
            @endforelse
        </div>
    </div>
</section>
@endsection
