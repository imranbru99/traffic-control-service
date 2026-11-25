<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\KnowledgeArticle;
use App\Models\Payment;
use App\Models\Post;
use App\Models\Product;
use App\Models\Project;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['admin', 'manager', 'staff'] as $role) {
            Role::findOrCreate($role);
        }

        $admin = User::query()->updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '(303) 555-0100',
            ]
        );
        $admin->syncRoles(['admin']);

        User::query()->updateOrCreate(
            ['email' => 'manager@flarepathtraffic.com'],
            [
                'name' => 'Jordan Hale',
                'password' => Hash::make('password'),
                'role' => 'manager',
                'phone' => '(303) 555-0101',
            ]
        )->syncRoles(['manager']);

        User::query()->updateOrCreate(
            ['email' => 'crew@flarepathtraffic.com'],
            [
                'name' => 'Sam Rivera',
                'password' => Hash::make('password'),
                'role' => 'staff',
                'phone' => '(303) 555-0102',
            ]
        )->syncRoles(['staff']);

        $settings = [
            'company_name' => 'FlarePath Traffic Control',
            'company_phone' => '(303) 555-0184',
            'company_email' => 'hello@flarepathtraffic.com',
            'whatsapp' => '13035550184',
            'service_area' => 'Denver, Aurora, Boulder & Front Range',
            'tax_rate' => '8.81',
        ];
        foreach ($settings as $key => $value) {
            Setting::setValue($key, $value);
        }

        $products = [
            ['name' => '28" Traffic Cone (reflective)', 'sku' => 'CN-28-REF', 'category' => 'cone', 'unit_cost' => 12, 'unit_price' => 22, 'stock_qty' => 420],
            ['name' => '36" Traffic Cone (reflective)', 'sku' => 'CN-36-REF', 'category' => 'cone', 'unit_cost' => 18, 'unit_price' => 32, 'stock_qty' => 260],
            ['name' => 'Roll-Up STOP/SLOW Paddle', 'sku' => 'PD-SS-1', 'category' => 'paddle', 'unit_cost' => 55, 'unit_price' => 95, 'stock_qty' => 48],
            ['name' => 'Advance Warning Sign Kit', 'sku' => 'SG-ADV-4', 'category' => 'sign', 'unit_cost' => 140, 'unit_price' => 260, 'stock_qty' => 36],
            ['name' => 'Type III Barricade', 'sku' => 'BR-T3-1', 'category' => 'barricade', 'unit_cost' => 85, 'unit_price' => 150, 'stock_qty' => 64],
            ['name' => 'Arrow Board (solar)', 'sku' => 'AB-SOL-1', 'category' => 'board', 'unit_cost' => 2200, 'unit_price' => 95, 'stock_qty' => 8, 'unit' => 'day'],
            ['name' => 'Portable Message Board', 'sku' => 'MB-PRT-1', 'category' => 'board', 'unit_cost' => 4800, 'unit_price' => 185, 'stock_qty' => 5, 'unit' => 'day'],
            ['name' => 'Radio Pair (work-zone)', 'sku' => 'RD-PAIR-2', 'category' => 'radio', 'unit_cost' => 90, 'unit_price' => 35, 'stock_qty' => 30, 'unit' => 'day'],
        ];
        foreach ($products as $product) {
            $unit = $product['unit'] ?? 'ea';
            unset($product['unit']);
            Product::query()->updateOrCreate(['sku' => $product['sku']], $product + ['unit' => $unit, 'is_active' => true]);
        }

        $customers = collect([
            ['name' => 'Ava Chen', 'email' => 'ava@summitcivil.co', 'phone' => '(303) 555-2201', 'company' => 'Summit Civil Constructors', 'city' => 'Denver', 'state' => 'CO', 'zip' => '80202', 'address' => '1600 Wynkoop St'],
            ['name' => 'Derek Walsh', 'email' => 'derek@aurorautilities.gov', 'phone' => '(720) 555-2210', 'company' => 'Aurora Utilities', 'city' => 'Aurora', 'state' => 'CO', 'zip' => '80012', 'address' => '15151 E Alameda Pkwy'],
            ['name' => 'Priya Nair', 'email' => 'priya@frontrangeevents.com', 'phone' => '(303) 555-2299', 'company' => 'Front Range Events', 'city' => 'Boulder', 'state' => 'CO', 'zip' => '80302', 'address' => '1100 Pearl St'],
            ['name' => 'Marcus Cole', 'email' => 'marcus@ridgewaypaving.com', 'phone' => '(970) 555-2188', 'company' => 'Ridgeway Paving', 'city' => 'Fort Collins', 'state' => 'CO', 'zip' => '80525', 'address' => '2800 E Harmony Rd'],
            ['name' => 'Elena Ruiz', 'email' => 'elena@metrofiber.net', 'phone' => '(303) 555-2177', 'company' => 'MetroFiber Net', 'city' => 'Lakewood', 'state' => 'CO', 'zip' => '80226', 'address' => '480 S Allison Pkwy'],
        ])->map(fn ($c) => Customer::query()->updateOrCreate(['email' => $c['email']], $c));

        $projects = [
            ['customer' => 0, 'title' => 'I-25 mill — night lane closure', 'service_type' => 'lane_closure', 'status' => 'scheduled', 'sq_ft' => 2400, 'quoted_amount' => 18600],
            ['customer' => 1, 'title' => 'Gas main dig — flagging + signs', 'service_type' => 'utility', 'status' => 'in_progress', 'sq_ft' => 800, 'quoted_amount' => 6400],
            ['customer' => 2, 'title' => 'Downtown 10K street closure', 'service_type' => 'event', 'status' => 'quoted', 'sq_ft' => 5200, 'quoted_amount' => 11200],
            ['customer' => 3, 'title' => 'Arterial overlay TCP package', 'service_type' => 'tcp', 'status' => 'completed', 'sq_ft' => 3600, 'quoted_amount' => 22400, 'final_amount' => 23150],
            ['customer' => 4, 'title' => 'Fiber drop — same-day protection', 'service_type' => 'flagging', 'status' => 'invoiced', 'sq_ft' => 450, 'quoted_amount' => 2100, 'final_amount' => 2280],
        ];

        $projectModels = [];
        foreach ($projects as $i => $p) {
            $customer = $customers[$p['customer']];
            $projectModels[] = Project::query()->updateOrCreate(
                ['job_number' => 'JOB-26'.str_pad((string) ($i + 1), 3, '0', STR_PAD_LEFT)],
                [
                    'customer_id' => $customer->id,
                    'title' => $p['title'],
                    'service_type' => $p['service_type'],
                    'status' => $p['status'],
                    'sq_ft' => $p['sq_ft'],
                    'quoted_amount' => $p['quoted_amount'],
                    'final_amount' => $p['final_amount'] ?? null,
                    'start_date' => now()->subDays(10 - $i)->toDateString(),
                    'site_address' => trim(($customer->address ?? '').', '.$customer->city.', '.$customer->state),
                    'notes' => 'Seeded demo job for FlarePath ERP. Zone length stored in sq_ft field (linear ft).',
                ]
            );
        }

        foreach ([0, 1, 3, 4] as $idx) {
            $project = $projectModels[$idx];
            $invoice = Invoice::query()->updateOrCreate(
                ['invoice_number' => 'INV-26'.str_pad((string) ($idx + 1), 3, '0', STR_PAD_LEFT)],
                [
                    'customer_id' => $project->customer_id,
                    'project_id' => $project->id,
                    'issue_date' => now()->subDays(8 - $idx)->toDateString(),
                    'due_date' => now()->addDays(14)->toDateString(),
                    'subtotal' => $project->final_amount ?? $project->quoted_amount,
                    'tax' => round(($project->final_amount ?? $project->quoted_amount) * 0.0881, 2),
                    'total' => round(($project->final_amount ?? $project->quoted_amount) * 1.0881, 2),
                    'amount_paid' => in_array($project->status, ['completed', 'invoiced'], true) ? round(($project->final_amount ?? $project->quoted_amount) * 1.0881, 2) : 0,
                    'status' => in_array($project->status, ['completed', 'invoiced'], true) ? 'paid' : 'sent',
                ]
            );

            if ($invoice->status === 'paid') {
                Payment::query()->updateOrCreate(
                    ['reference' => 'PMT-'.$invoice->invoice_number],
                    [
                        'invoice_id' => $invoice->id,
                        'amount' => $invoice->total,
                        'paid_at' => now()->subDays(3)->toDateString(),
                        'method' => 'ach',
                        'notes' => 'Demo payment',
                    ]
                );
            }
        }

        Appointment::query()->updateOrCreate(
            ['title' => 'TCP walkthrough — Summit Civil'],
            [
                'customer_id' => $customers[0]->id,
                'project_id' => $projectModels[0]->id,
                'type' => 'site_visit',
                'starts_at' => now()->addDays(2)->setTime(8, 30),
                'ends_at' => now()->addDays(2)->setTime(9, 30),
                'status' => 'scheduled',
                'location' => $customers[0]->address,
            ]
        );
        Appointment::query()->updateOrCreate(
            ['title' => 'Night lane closure — crew call'],
            [
                'customer_id' => $customers[0]->id,
                'project_id' => $projectModels[0]->id,
                'type' => 'install',
                'starts_at' => now()->addDays(5)->setTime(21, 0),
                'ends_at' => now()->addDays(6)->setTime(5, 0),
                'status' => 'confirmed',
                'location' => 'I-25 NB corridor staging',
            ]
        );

        $posts = [
            [
                'title' => 'Why Short Tapers Fail on Front Range Arterials',
                'excerpt' => 'Saving a few cones is never worth a rear-end crash — here is how FlarePath sizes MUTCD tapers for Denver speeds.',
                'cover' => 'https://images.unsplash.com/photo-1449965408869-eaa3f722e40d?auto=format&fit=crop&w=1400&q=80',
                'body' => "Drivers need time to react. When crews compress advance warning or taper length to finish early, approach speeds turn the work zone into a hazard.\n\nFlarePath measures posted speed, lane width, and sight distance before the first cone drops. If your GC asks for a shorter taper, we document the risk and propose a safer phasing plan instead of a silent compromise.",
            ],
            [
                'title' => 'Same-Day Utility Digs Need Ready Kits',
                'excerpt' => 'Gas and fiber tickets rarely wait for ideal traffic windows. Stocked kits keep FlarePath crews rolling.',
                'cover' => 'https://images.unsplash.com/photo-1621905252507-b35492cc74b4?auto=format&fit=crop&w=1400&q=80',
                'body' => "Utility work zones fail when the dig starts before devices arrive. Our Aurora yard keeps cone stacks, advance signs, paddles, and radios staged for rapid dispatch.\n\nCall as soon as the ticket hits — we can often protect a Denver or Aurora dig the same shift with a two-person flagging team and a clean temporary layout.",
            ],
            [
                'title' => 'Night Shifts: Lighting, Overtime, and Reopen Discipline',
                'excerpt' => 'Overnight lane closures cut peak conflicts — if lighting and reopen timing are planned, not improvised.',
                'cover' => 'https://images.unsplash.com/photo-1541888946425-d81bb19240f5?auto=format&fit=crop&w=1400&q=80',
                'body' => "Night work on I-25 and city arterials reduces daytime backups, but only with proper lighting, radio discipline, and a hard reopen clock.\n\nFlarePath schedules overtime-eligible crews, stages arrow boards early, and tears down before peak traffic returns. Weather holds beat ghost setups every time.",
            ],
        ];
        foreach ($posts as $post) {
            Post::query()->updateOrCreate(
                ['slug' => Str::slug($post['title'])],
                [
                    'title' => $post['title'],
                    'excerpt' => $post['excerpt'],
                    'body' => $post['body'],
                    'cover_image' => $post['cover'],
                    'is_published' => true,
                    'published_at' => now()->subDays(random_int(2, 20)),
                ]
            );
        }

        $articles = [
            ['category' => 'issues', 'title' => 'Cones blowing over in Front Range wind', 'body' => "1. Use heavier bases or sandbags on exposed arterials.\n2. Check spacing — overcrowded cones catch more gusts.\n3. Stage arrow boards upwind of the taper when possible.\n4. Pause setup during red-flag wind advisories."],
            ['category' => 'issues', 'title' => 'Drivers ignoring stop/slow paddles', 'body' => "Confirm advance warning is visible and correctly spaced. Add a second flagger or shadow vehicle on high-speed approaches. Verify radio contact so both ends of the corridor never contradict each other."],
            ['category' => 'guides', 'title' => 'Night lane-closure timeline', 'body' => "Evening: Stage devices off-road, brief radio channels, confirm permits.\nNight: Drop advance signs, set taper, open buffer, hand corridor to GC.\nPre-dawn: Tear down in reverse order; reopen lanes before peak.\nFollow-up: Photo report and invoice in ERP."],
            ['category' => 'guides', 'title' => 'How GCs should prep before FlarePath arrives', 'body' => "Share drawings, posted speeds, shift windows, and permit numbers. Unlock staging lots, identify underground conflicts, and name an on-site contact with radio access."],
            ['category' => 'admin', 'title' => 'Admin ERP quick start', 'body' => "Login at /admin with admin@example.com / password.\n1. Add customers in CRM.\n2. Create a Job/Project with corridor length and quote.\n3. Schedule TCP walkthroughs and night shifts.\n4. Convert completed jobs to invoices and record payments.\n5. Use AI Studio for blog drafts and troubleshooting.\n6. Update Settings for phone, WhatsApp, and tax rate."],
            ['category' => 'admin', 'title' => 'Roles: Admin, Manager, Staff', 'body' => "Admin: full ERP + settings + users.\nManager: jobs, inventory, invoices, scheduling.\nStaff: field-facing records and appointment updates.\nAssign roles on the Users resource."],
            ['category' => 'troubleshoot', 'title' => 'Job running over flagging hours', 'body' => "Compare actual shift length vs quote. Check for GC delays, weather holds, or extra lane counts. Log overtime on the job notes, create a change-order invoice line, and update device rental days if boards stayed out longer."],
            ['category' => 'troubleshoot', 'title' => 'Chatbot giving demo answers only', 'body' => "Demo mode is expected without API keys. Add OPENAI_API_KEY or GEMINI_API_KEY to .env, then clear config cache. The public chatbot and AI Studio will prefer live models when keys are present."],
        ];
        foreach ($articles as $article) {
            KnowledgeArticle::query()->updateOrCreate(
                ['slug' => Str::slug($article['title'])],
                [
                    'title' => $article['title'],
                    'category' => $article['category'],
                    'body' => $article['body'],
                    'is_published' => true,
                ]
            );
        }
    }
}
