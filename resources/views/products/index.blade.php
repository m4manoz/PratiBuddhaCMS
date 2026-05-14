@extends('layouts.app')
@section('title', 'Products | PRATIBUDDHA')

@section('content')
<section class="page-shell">
    <div class="container">
        <div class="page-title-wrap">
            <p class="home-kicker">Product Listing</p>
            <h1>Explore Products</h1>
            <p>Use filters to find products by title, category, budget, or popularity.</p>
        </div>

        <div class="brand-quick-filter" aria-label="Brand filters">
            <p class="filter-label">Shop by brand</p>
            <div class="brand-chip-list">
                @php
                    $brandParams = request()->except(['brand', 'category', 'page']);
                @endphp
                <a href="{{ route('products.index', $brandParams) }}" class="brand-chip {{ $brand === '' && $category === '' ? 'is-active' : '' }}">All Brands</a>
                @foreach($brands as $brandName)
                    @php
                        $brandActive = $brand === $brandName || $category === $brandName;
                    @endphp
                    <a
                        href="{{ route('products.index', array_merge($brandParams, ['brand' => $brandName])) }}"
                        class="brand-chip {{ $brandActive ? 'is-active' : '' }}"
                    >{{ $brandName }}</a>
                @endforeach
            </div>
        </div>

        <form method="GET" action="{{ route('products.index') }}" class="product-filters" id="product-filters">
            <label class="filter-field">
                <span>Search</span>
                <input type="text" name="q" value="{{ $search ?? '' }}" placeholder="Product title or category" class="form-control">
            </label>
            <label class="filter-field">
                <span>Brand</span>
                <select name="brand" class="form-control">
                    <option value="">Any Brand</option>
                    @foreach($brands as $brandName)
                        <option value="{{ $brandName }}" {{ $brand === $brandName ? 'selected' : '' }}>{{ $brandName }}</option>
                    @endforeach
                </select>
            </label>
            <label class="filter-field">
                <span>Category</span>
                <select name="category" class="form-control">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ ($category ?? '') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </label>
            <label class="filter-field">
                <span>Sort</span>
                <select name="sort" class="form-control">
                    <option value="newest" {{ ($sort ?? 'newest') === 'newest' ? 'selected' : '' }}>Newest</option>
                    <option value="name_asc" {{ ($sort ?? '') === 'name_asc' ? 'selected' : '' }}>Name (A-Z)</option>
                    <option value="name_desc" {{ ($sort ?? '') === 'name_desc' ? 'selected' : '' }}>Name (Z-A)</option>
                    <option value="price_asc" {{ ($sort ?? '') === 'price_asc' ? 'selected' : '' }}>Price (Low to High)</option>
                    <option value="price_desc" {{ ($sort ?? '') === 'price_desc' ? 'selected' : '' }}>Price (High to Low)</option>
                </select>
            </label>
            <label class="filter-field">
                <span>Price (NPR)</span>
                <div class="range-wrap">
                    <input type="number" min="0" name="min_price" value="{{ $minPrice }}" placeholder="Min" class="form-control">
                    <input type="number" min="0" name="max_price" value="{{ $maxPrice }}" placeholder="Max" class="form-control">
                </div>
            </label>
            <div class="filter-action">
                <button type="submit" class="btn btn-primary">Apply Filters</button>
                <a href="{{ route('products.index') }}" class="btn btn-outline">Reset</a>
            </div>
        </form>

        <div class="product-grid">
            @forelse($products as $product)
                <article class="product-card">
                    <a href="{{ route('products.show', $product->slug) }}" class="product-media">
                        @if($product->image_path)
                            <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->title }}">
                        @else
                            <img src="{{ asset('images/product-placeholder.svg') }}" alt="{{ $product->title }} placeholder">
                        @endif
                    </a>
                    <div class="product-card-body">
                        <p class="home-pill brand-pill">{{ $product->category ?? 'Brand' }}</p>
                        <p class="home-pill">{{ $product->category ? 'Type: ' . $product->category : 'General' }}</p>
                        <h3>{{ $product->title }}</h3>
                        <p>{{ Str::limit($product->description, 90) }}</p>
                        <div class="product-meta-row">
                            <span class="product-price">{{ $product->price !== null ? 'NPR ' . number_format($product->price) : 'Price on request' }}</span>
                            <span class="badge-soft {{ $product->price === null ? 'badge-soft-soft' : '' }}">{{ $product->price !== null ? 'In stock' : 'Contact us' }}</span>
                        </div>
                        <a href="{{ route('products.show', $product->slug) }}" class="home-inline-link">View Details <span aria-hidden="true">&rarr;</span></a>
                    </div>
                </article>
            @empty
                <div class="home-empty-card">
                    <p>No products available yet.</p>
                    <a href="{{ route('products.index') }}" class="home-inline-link">Refresh catalog</a>
                </div>
            @endforelse
        </div>

        <div class="pagination-wrap">
            {{ $products->links() }}
        </div>
    </div>
</section>
@endsection
