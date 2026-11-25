<?php

namespace App\Services;

use App\Models\ChatLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class AiService
{
    /** @var array<int, array{q: string, a: string}> */
    protected array $faq = [
        [
            'q' => 'flagger certified flagging rate cost hour',
            'a' => 'Front Range certified flagging typically bills by the hour with a half-day or full-day minimum, depending on shift length and overtime. Night work and multi-crew corridors cost more. Call (303) 555-0184 for a written quote tied to your TCP.',
        ],
        [
            'q' => 'lane closure taper mutcd spacing cone',
            'a' => 'FlarePath follows MUTCD taper and advance-warning spacing based on posted speed and geometry. We never invent a short taper to save cones — weak tapers are the #1 crash risk on our corridors. Share speed limit and lane count for a plan sketch.',
        ],
        [
            'q' => 'temporary traffic control tcp plan permit',
            'a' => 'We draft temporary traffic control plans for Denver, Aurora, Boulder, and CDOT corridors, then stage setup and teardown. Permit lead times vary by agency — send drawings early so devices and flaggers are booked before the notice to proceed.',
        ],
        [
            'q' => 'utility dig emergency same day response',
            'a' => 'Utility cuts often need same-day protection. FlarePath keeps rapid-response kits (cones, signs, paddles, radios) ready for gas, water, telecom, and electric digs. Call dispatch as soon as the dig ticket lands.',
        ],
        [
            'q' => 'special event street closure festival race',
            'a' => 'We staff street closures, detours, and pedestrian corridors for races, festivals, and film. Share the route map, peak arrival times, and reopen deadline so we can stage barricades and message boards correctly.',
        ],
        [
            'q' => 'equipment rental cone sign arrow board message',
            'a' => 'Rent cones, temporary signs, Type III barricades, arrow boards, and portable message boards from our Aurora yard. Delivery and pickup available along the Front Range when you do not need a full FlarePath crew.',
        ],
        [
            'q' => 'schedule appointment quote contact denver',
            'a' => 'Call or text (303) 555-0184, use the contact form, or WhatsApp us. TCP reviews for Denver, Aurora, Boulder, and nearby Front Range sites are usually turned around within 1–2 business days.',
        ],
        [
            'q' => 'night work overnight shift overtime weather',
            'a' => 'Night shifts reduce peak traffic conflicts on arterials and freeways. We staff overtime-eligible crews, light the work zone properly, and watch wind/rain that can tip signs or reduce visibility. Weather holds are safer than ghost setups.',
        ],
    ];

    public function chat(string $message, ?string $sessionId = null): array
    {
        $reply = null;
        $source = 'demo';

        if ($key = config('services.openai.key')) {
            try {
                $reply = $this->callOpenAi($message, $key);
                $source = 'openai';
            } catch (\Throwable) {
                $reply = null;
            }
        }

        if (! $reply && ($gemini = config('services.gemini.key'))) {
            try {
                $reply = $this->callGemini($message, $gemini);
                $source = 'gemini';
            } catch (\Throwable) {
                $reply = null;
            }
        }

        if (! $reply) {
            $reply = $this->demoReply($message);
            $source = 'demo';
        }

        ChatLog::query()->create([
            'session_id' => $sessionId,
            'message' => $message,
            'reply' => $reply,
            'source' => $source,
        ]);

        return ['reply' => $reply, 'source' => $source];
    }

    public function generate(string $type, string $prompt): string
    {
        $message = match ($type) {
            'blog' => "Write a concise SEO blog post outline and intro for a traffic control / flagging company about: {$prompt}",
            'description' => "Write a compelling product/service description for temporary traffic control: {$prompt}",
            'automation' => "Suggest 5 practical AI automations for a traffic control SME ERP related to: {$prompt}",
            'troubleshoot' => "Troubleshoot this traffic control / work-zone operations issue with clear steps: {$prompt}",
            default => $prompt,
        };

        $result = $this->chat($message);

        return $result['reply'];
    }

    protected function demoReply(string $message): string
    {
        $hay = Str::lower($message);
        $best = null;
        $bestScore = 0;

        foreach ($this->faq as $item) {
            $score = 0;
            foreach (explode(' ', $item['q']) as $word) {
                if ($word !== '' && str_contains($hay, $word)) {
                    $score++;
                }
            }
            if ($score > $bestScore) {
                $bestScore = $score;
                $best = $item['a'];
            }
        }

        if ($best && $bestScore > 0) {
            return $best;
        }

        return 'Thanks for reaching FlarePath Traffic Control. We provide certified flagging, lane closures, temporary traffic control plans, utility work zones, event control, and device rental across the Denver Front Range. Ask about MUTCD tapers, TCP permits, night shifts, or scheduling—or call (303) 555-0184.';
    }

    protected function callOpenAi(string $message, string $key): string
    {
        $response = Http::withToken($key)
            ->timeout(30)
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => config('services.openai.model', 'gpt-4o-mini'),
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are the FlarePath Traffic Control assistant for a Denver Front Range temporary traffic control and flagging contractor. Be concise, practical, and focused on MUTCD setups, flagging, lane closures, utility work zones, events, and equipment rental. Never invent illegal claims.',
                    ],
                    ['role' => 'user', 'content' => $message],
                ],
                'temperature' => 0.4,
            ]);

        $response->throw();

        return (string) data_get($response->json(), 'choices.0.message.content', 'Unable to generate a response.');
    }

    protected function callGemini(string $message, string $key): string
    {
        $model = config('services.gemini.model', 'gemini-1.5-flash');
        $response = Http::timeout(30)
            ->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$key}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => "You are FlarePath Traffic Control assistant (Denver Front Range flagging & temporary traffic control). Answer briefly:\n{$message}"],
                        ],
                    ],
                ],
            ]);

        $response->throw();

        return (string) data_get($response->json(), 'candidates.0.content.parts.0.text', 'Unable to generate a response.');
    }
}
