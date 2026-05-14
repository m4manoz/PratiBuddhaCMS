@extends('layouts.app')

@section('title', 'Gallery | PRATIBUDDHA')

@section('content')
<section class="inner-page-shell">
    <div class="container">
        <div class="inner-hero">
            <p class="home-kicker">Gallery</p>
            <h1>Visual updates from our brand, dealer events and service work</h1>
            <p>Browse snapshots of our team, installations, and customer support activities updated by CMS.</p>
        </div>

        <div class="inner-section">
            <div class="gallery-grid">
                @foreach($items as $item)
                    <article class="gallery-item">
                        <img src="{{ asset('images/' . $item['media']) }}" alt="{{ $item['title'] }}">
                        <div class="gallery-caption">
                            <h3>{{ $item['title'] }}</h3>
                            <p>{{ $item['summary'] }}</p>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endsection
