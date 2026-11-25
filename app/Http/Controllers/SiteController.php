<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\KnowledgeArticle;
use App\Models\Post;
use App\Services\AiService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SiteController extends Controller
{
    public function home(): View
    {
        $posts = Post::query()->where('is_published', true)->latest('published_at')->take(3)->get();

        return view('pages.home', compact('posts'));
    }

    public function services(): View
    {
        return view('pages.services');
    }

    public function serviceShow(string $slug): View
    {
        $services = $this->serviceCatalog();
        abort_unless(isset($services[$slug]), 404);

        return view('services.show', [
            'slug' => $slug,
            'service' => $services[$slug],
            'services' => $services,
        ]);
    }

    public function about(): View
    {
        return view('pages.about');
    }

    public function contact(): View
    {
        return view('pages.contact');
    }

    public function contactStore(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:180'],
            'phone' => ['nullable', 'string', 'max:40'],
            'subject' => ['nullable', 'string', 'max:180'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        ContactMessage::query()->create($data);

        return back()->with('success', 'Thanks! FlarePath will follow up within one business day.');
    }

    public function blog(): View
    {
        $posts = Post::query()->where('is_published', true)->latest('published_at')->paginate(9);

        return view('blog.index', compact('posts'));
    }

    public function blogShow(string $slug): View
    {
        $post = Post::query()->where('slug', $slug)->where('is_published', true)->firstOrFail();

        return view('blog.show', compact('post'));
    }

    public function knowledge(): View
    {
        $articles = KnowledgeArticle::query()->where('is_published', true)->orderBy('category')->orderBy('title')->get()->groupBy('category');

        return view('kb.index', compact('articles'));
    }

    public function knowledgeShow(string $slug): View
    {
        $article = KnowledgeArticle::query()->where('slug', $slug)->where('is_published', true)->firstOrFail();

        return view('kb.show', compact('article'));
    }

    public function chat(Request $request, AiService $ai)
    {
        $data = $request->validate([
            'message' => ['required', 'string', 'max:2000'],
            'session_id' => ['nullable', 'string', 'max:80'],
        ]);

        $result = $ai->chat($data['message'], $data['session_id'] ?? null);

        return response()->json($result);
    }

    /** @return array<string, array<string, string>> */
    protected function serviceCatalog(): array
    {
        return [
            'certified-flagging' => [
                'title' => 'Certified Flagging',
                'summary' => 'ATSSA-trained flaggers with stop/slow paddles, radios, and corridor discipline.',
                'body' => 'FlarePath deploys certified flaggers for Denver Front Range construction, utility digs, and municipal work. Crews run stop/slow paddles, maintain radio contact with the foreman, and hold sightlines so drivers and workers both stay protected. Day shift, night shift, and multi-crew corridors available.',
                'image' => 'https://images.unsplash.com/photo-1504307651254-35680f356dfd?auto=format&fit=crop&w=1600&q=80',
            ],
            'lane-closures' => [
                'title' => 'Lane Closures',
                'summary' => 'Cone tapers, advance warning, and buffered lane shifts that match MUTCD spacing.',
                'body' => 'Weak tapers cause rear-end crashes. We stage advance-warning signs, cone spacing, and buffer zones to volume and posted speed before the first shovel hits the pavement. Ideal for mill-and-fill, bridge work, and shoulder repairs along I-25, US-36, and city arterials.',
                'image' => 'https://images.unsplash.com/photo-1541888946425-d81bb19240f5?auto=format&fit=crop&w=1600&q=80',
            ],
            'temporary-traffic-control' => [
                'title' => 'Temporary Traffic Control',
                'summary' => 'Full TCP design, setup, and teardown for multi-day work zones.',
                'body' => 'From permit packet to last cone pickup, FlarePath owns the temporary traffic control plan. We draft layouts around geometry, peak volumes, and agency rules, then stage devices, flag as needed, and tear down clean so GCs stay on schedule.',
                'image' => 'https://images.unsplash.com/photo-1589939705384-5185137a7f0f?auto=format&fit=crop&w=1600&q=80',
            ],
            'utility-work-zones' => [
                'title' => 'Utility Work Zones',
                'summary' => 'Short-notice protection for gas, water, telecom, and electric crews.',
                'body' => 'Utility cuts rarely wait for ideal traffic windows. FlarePath keeps rapid-response kits ready — cones, signs, flaggers, and shadow vehicles — so digs on Denver and Aurora corridors get legal, visible protection the same day.',
                'image' => 'https://images.unsplash.com/photo-1621905252507-b35492cc74b4?auto=format&fit=crop&w=1600&q=80',
            ],
            'special-event-control' => [
                'title' => 'Special Event Control',
                'summary' => 'Street closures, detours, and pedestrian corridors for races, festivals, and film.',
                'body' => 'Events need clear detours, pedestrian channels, and predictable reopen times. We coordinate with organizers and city traffic ops to stage barricades, message boards, and staffed intersections that keep fans moving without blocking emergency access.',
                'image' => 'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?auto=format&fit=crop&w=1600&q=80',
            ],
            'equipment-rental' => [
                'title' => 'Work-Zone Equipment',
                'summary' => 'Cones, signs, barricades, arrow boards, and message boards for short or long rentals.',
                'body' => 'Need devices without a full FlarePath crew? Rent MUTCD-grade cones, temporary signs, Type III barricades, arrow boards, and portable message boards from our Aurora yard. Delivery and pickup available along the Front Range.',
                'image' => 'https://images.unsplash.com/photo-1558618666-fcd25c85f82e?auto=format&fit=crop&w=1600&q=80',
            ],
        ];
    }
}
