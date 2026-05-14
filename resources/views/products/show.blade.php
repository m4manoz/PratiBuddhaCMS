@extends('layouts.app')
@section('title', $product->title . ' | PRATIBUDDHA')

@section('content')
<section class="product-detail-shell">
    <div class="container">
        <nav class="breadcrumb" aria-label="Breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <span>Products</span>
            <span>{{ $product->title }}</span>
        </nav>

        @if(session('enquiry_success'))
            <div class="feedback feedback-success">{{ session('enquiry_success') }}</div>
        @endif

        @if(session('error'))
            <div class="feedback feedback-error">{{ session('error') }}</div>
        @endif

        <div class="product-detail-layout">
            <div class="product-gallery">
                <figure class="product-main-visual">
                    @if($product->image_path)
                        <img id="featured-product-image" src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->title }}">
                    @else
                        <img id="featured-product-image" src="{{ asset('images/product-placeholder.svg') }}" alt="{{ $product->title }}">
                    @endif
                </figure>
                <button class="product-zoom-trigger" type="button">Tap to zoom</button>
            </div>

            <aside class="product-detail-side">
                <div class="product-card product-summary">
                    <p class="home-pill brand-pill">{{ $product->category ?? 'Brand' }}</p>
                    <p class="badge-soft">{{ $product->category ?? 'General' }}</p>
                    <h1>{{ $product->title }}</h1>
                    <p class="product-availability {{ $availability ? 'in-stock' : 'contact-required' }}">{{ $availability ? 'In stock' : 'Price on request' }}</p>
                    <p class="product-price">{{ $product->price ? 'NPR ' . number_format($product->price) : 'Price on Request' }}</p>
                    <p>{{ $product->description }}</p>

                    @if($product->specifications)
                        <dl class="spec-table">
                            @foreach($product->specifications as $key => $value)
                                <div>
                                    <dt>{{ $key }}</dt>
                                    <dd>{{ $value }}</dd>
                                </div>
                            @endforeach
                        </dl>
                    @endif
                </div>

                <form action="{{ route('products.enquiry.store', $product->slug) }}" method="POST" class="form-card">
                    @csrf
                    <input type="text" name="company_website" autocomplete="off" tabindex="-1" class="honeypot" aria-hidden="true">
                    <h3>Interested in this product?</h3>
                    <p>Send a request and our team will follow up quickly.</p>
                    @if ($errors->any())
                        <div class="feedback feedback-error">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <input type="text" name="name" placeholder="Your Name" class="form-control" value="{{ old('name') }}" required>
                    <input type="email" name="email" placeholder="Email Address" class="form-control" value="{{ old('email') }}" required>
                    <input type="text" name="phone" placeholder="Phone Number" class="form-control" value="{{ old('phone') }}" required>
                    <input type="text" name="subject" placeholder="Subject (Optional)" class="form-control" value="Enquiry for {{ $product->title }}">
                    <textarea name="message" placeholder="Message" class="form-control" rows="6" required>{{ old('message') }}</textarea>
                    <button type="submit" class="btn btn-primary">Send Enquiry</button>
                </form>
            </aside>
        </div>

        @if($relatedProducts->isNotEmpty())
            <section class="related-section">
                <h2>Related Products</h2>
                <div class="product-grid">
                    @foreach($relatedProducts as $related)
                        <article class="product-card">
                            <a href="{{ route('products.show', $related->slug) }}" class="product-media">
                                @if($related->image_path)
                                    <img src="{{ asset('storage/' . $related->image_path) }}" alt="{{ $related->title }}">
                                @else
                                    <img src="{{ asset('images/product-placeholder.svg') }}" alt="{{ $related->title }} placeholder">
                                @endif
                            </a>
                            <div class="product-card-body">
                                <h3>{{ $related->title }}</h3>
                                <a href="{{ route('products.show', $related->slug) }}" class="home-inline-link">View</a>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif
    </div>
</section>
@endsection
