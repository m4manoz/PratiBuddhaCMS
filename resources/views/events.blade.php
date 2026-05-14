@extends('layouts.app')

@section('title', 'Events | PRATIBUDDHA')

@section('content')
<section class="inner-page-shell">
    <div class="container">
        <div class="inner-hero">
            <p class="home-kicker">Events</p>
            <h1>Industry events and community programs</h1>
            <p>Join upcoming sessions or browse what we ran in recent seasons.</p>
        </div>

        <div class="inner-section">
            <div class="event-grid">
                @foreach($events as $event)
                    <article class="event-card">
                        <p class="event-tag {{ strtolower($event['status']) }}">{{ $event['status'] }}</p>
                        <h3>{{ $event['title'] }}</h3>
                        <p>{{ \Carbon\Carbon::parse($event['date'])->format('M d, Y') }} | {{ $event['venue'] }}</p>
                        <p>{{ $event['description'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endsection
