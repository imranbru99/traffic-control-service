@extends('layouts.app')

@section('title', 'FlarePath Traffic Control')
@section('meta_description', 'Certified flagging, lane closures, and temporary traffic control for Denver Front Range construction, utilities, and special events by FlarePath Traffic Control.')

@section('content')
<section class="hero">
    <div class="wrap hero-inner">
        <h1>FlarePath<br>Traffic Control</h1>
        <p>Certified flaggers, MUTCD-compliant setups, and rapid lane-closure crews that keep Front Range work zones safe, legal, and moving.</p>
        <div class="hero-ctas">
            <a class="btn btn-primary" href="{{ route('contact') }}">Request a TCP quote</a>
            <a class="btn btn-ghost" href="{{ route('services') }}">View traffic services</a>
        </div>
    </div>
</section>

<section class="section">
    <div class="wrap">
        <h2>Traffic control services</h2>
        <p class="lede">From stop/slow paddles to full temporary traffic control plans — crews built for construction, utilities, and event streets.</p>
        <div class="grid-3">
            @foreach([
                ['certified-flagging','Certified Flagging','ATSSA-trained flaggers with stop/slow paddles and radio coordination.','https://images.unsplash.com/photo-1504307651254-35680f356dfd?auto=format&fit=crop&w=1200&q=80'],
                ['lane-closures','Lane Closures','Cone tapers, advance warning, and buffered lane shifts done right.','https://images.unsplash.com/photo-1541888946425-d81bb19240f5?auto=format&fit=crop&w=1200&q=80'],
                ['temporary-traffic-control','Temporary Traffic Control','Full TCP design, setup, and teardown for multi-day work zones.','https://images.unsplash.com/photo-1589939705384-5185137a7f0f?auto=format&fit=crop&w=1200&q=80'],
            ] as [$slug,$title,$blurb,$img])
                <a class="service-tile" href="{{ route('services.show', $slug) }}">
                    <img src="{{ $img }}" alt="{{ $title }}">
                    <div class="pad">
                        <h3>{{ $title }}</h3>
                        <p>{{ $blurb }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>

<section class="section" style="padding-top:0">
    <div class="wrap">
        <h2>Why FlarePath</h2>
        <p class="lede">Denver crews that treat MUTCD layout, sightlines, and radio discipline as non-negotiable — because a weak taper is a liability.</p>
        <div class="grid-3">
            <div class="panel">
                <h3>Plan-first TCPs</h3>
                <p>We draft temporary traffic control plans around volume, geometry, and permit rules before cones hit the asphalt.</p>
            </div>
            <div class="panel">
                <h3>Certified crews</h3>
                <p>Flaggers and supervisors trained for Colorado DOT and municipal corridors — day shift or overnight.</p>
            </div>
            <div class="panel">
                <h3>ERP-run dispatch</h3>
                <p>Quotes, crew schedules, device inventory, and invoices live in one ops stack.</p>
            </div>
        </div>
    </div>
</section>

<section class="section" style="padding-top:0">
    <div class="wrap">
        <h2>Ops dashboard preview</h2>
        <p class="lede">A glimpse of the FlarePath ERP used to dispatch flaggers, track cones and signs, and close invoices.</p>
        <div class="dash-preview">
            <div>
                <div class="grid-3" style="grid-template-columns:repeat(3,1fr)">
                    <div class="metric"><strong>48</strong> Active zones</div>
                    <div class="metric"><strong>$186k</strong> Pipeline</div>
                    <div class="metric"><strong>36</strong> Night shifts</div>
                </div>
                <div class="bars">
                    <span style="height:45%"></span><span style="height:70%"></span><span style="height:55%"></span>
                    <span style="height:85%"></span><span style="height:60%"></span><span style="height:92%"></span>
                    <span style="height:68%"></span>
                </div>
            </div>
            <div>
                <h3 style="font-family:var(--font-display);text-transform:uppercase;margin-top:0">Live modules</h3>
                <ul style="line-height:1.9;padding-left:1.1rem;color:rgba(255,255,255,.86)">
                    <li>Jobs & crew scheduling</li>
                    <li>Cone / sign inventory</li>
                    <li>Invoices & payments</li>
                    <li>AI content + troubleshooting</li>
                </ul>
                <a class="btn btn-primary" style="margin-top:1rem" href="/admin">Open admin ERP</a>
            </div>
        </div>
    </div>
</section>

<section class="section" style="padding-top:0">
    <div class="wrap">
        <h2>From the work zone</h2>
        <p class="lede">Guides and field notes for GCs, utilities, and event coordinators.</p>
        <div class="grid-3">
            @forelse($posts as $post)
                <a class="panel" href="{{ route('blog.show', $post->slug) }}">
                    <h3>{{ $post->title }}</h3>
                    <p>{{ $post->excerpt }}</p>
                </a>
            @empty
                <div class="panel"><p>Blog posts will appear after seeding.</p></div>
            @endforelse
        </div>
    </div>
</section>
@endsection
