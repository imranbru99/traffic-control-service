@extends('layouts.app')
@section('title', 'Traffic Control Services')
@section('content')
<section class="section">
    <div class="wrap">
        <h2>Services</h2>
        <p class="lede">FlarePath covers flagging, lane closures, temporary traffic control plans, utility protection, special events, and work-zone equipment rental across the Denver Front Range.</p>
        <div class="grid-3">
            @foreach([
                ['certified-flagging','Certified Flagging','https://images.unsplash.com/photo-1504307651254-35680f356dfd?auto=format&fit=crop&w=1200&q=80'],
                ['lane-closures','Lane Closures','https://images.unsplash.com/photo-1541888946425-d81bb19240f5?auto=format&fit=crop&w=1200&q=80'],
                ['temporary-traffic-control','Temporary Traffic Control','https://images.unsplash.com/photo-1589939705384-5185137a7f0f?auto=format&fit=crop&w=1200&q=80'],
                ['utility-work-zones','Utility Work Zones','https://images.unsplash.com/photo-1621905252507-b35492cc74b4?auto=format&fit=crop&w=1200&q=80'],
                ['special-event-control','Special Event Control','https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?auto=format&fit=crop&w=1200&q=80'],
                ['equipment-rental','Work-Zone Equipment','https://images.unsplash.com/photo-1558618666-fcd25c85f82e?auto=format&fit=crop&w=1200&q=80'],
            ] as [$slug,$title,$img])
                <a class="service-tile" href="{{ route('services.show', $slug) }}">
                    <img src="{{ $img }}" alt="{{ $title }}">
                    <div class="pad"><h3>{{ $title }}</h3></div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endsection
