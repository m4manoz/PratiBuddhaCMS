@extends('layouts.app')

@section('title', 'Careers | PRATIBUDDHA')

@section('content')
<section class="inner-page-shell">
    <div class="container">
        <div class="inner-hero">
            <p class="home-kicker">Careers</p>
            <h1>Build your career with a growing electronics team</h1>
            <p>We are hiring across sales, operations, service coordination, and support.</p>
        </div>

        <section class="inner-section">
            <div class="career-grid">
                @foreach($openRoles as $role)
                    <article class="career-card">
                        <h3>{{ $role['title'] }}</h3>
                        <p><strong>Type:</strong> {{ $role['type'] }}</p>
                        <p><strong>Location:</strong> {{ $role['location'] }}</p>
                        <p>{{ $role['description'] }}</p>
                        <p class="career-closing">Last date: {{ $role['closing'] }}</p>
                        <a href="{{ route('contact') }}" class="home-inline-link">Apply now <span aria-hidden="true">&rarr;</span></a>
                    </article>
                @endforeach
            </div>
            <article class="inner-card">
                <h2>What we value</h2>
                <ul class="home-steps">
                    <li>Professional communication and ownership.</li>
                    <li>Curiosity to improve customer experiences.</li>
                    <li>Reliable execution and service-minded thinking.</li>
                </ul>
            </article>
        </section>
    </div>
</section>
@endsection
