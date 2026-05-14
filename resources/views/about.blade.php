@extends('layouts.app')

@section('title', 'About PRATIBUDDHA')

@section('content')
<section class="inner-page-shell">
    <div class="container">
        <div class="inner-hero">
            <p class="home-kicker">About Pratibuddha</p>
            <h1>{{ $aboutSettings['hero_title'] ?? 'Built for dependable appliances, service and growth' }}</h1>
            <p>{{ $aboutSettings['hero_text'] ?? 'We help customers discover trusted products, compare options, and connect with local authorized dealers through a streamlined enquiry-first shopping flow.' }}</p>
        </div>

        <div class="inner-section">
            <div class="inner-grid-2">
                <article class="inner-card">
                    <h2>Our Mission</h2>
                    <p>{{ $aboutSettings['hero_subtitle'] ?? 'Deliver reliable electronics solutions with practical support to every household and business across Nepal.' }}</p>
                    <ul class="home-steps">
                        @forelse($missionItems as $point)
                            <li>{{ $point }}</li>
                        @empty
                            <li>Build a verified dealer network with location clarity and fast directions.</li>
                            <li>Provide transparent product details and dependable after-sales guidance.</li>
                            <li>Enable quick communication from enquiry to installation follow-up.</li>
                        @endforelse
                    </ul>
                </article>
                <article class="inner-card">
                    <h2>What We Deliver</h2>
                    <div class="inner-stats">
                        @foreach($metrics as $metric)
                            <p><span class="inner-stat">{{ $metric['value'] }}</span> {{ $metric['label'] }}</p>
                        @endforeach
                    </div>
                    <a href="{{ route('dealers.index') }}" class="home-inline-link">Explore dealer directory <span aria-hidden="true">&rarr;</span></a>
                </article>
            </div>
        </div>

        <div class="inner-section">
                <h2>Growth timeline</h2>
                <div class="timeline-grid">
                    @foreach($milestones as $milestone)
                        <article class="timeline-item inner-card">
                        <p class="timeline-year">{{ $milestone['year'] }}</p>
                        <h3>{{ $milestone['label'] }}</h3>
                        <p>{{ $milestone['text'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endsection
