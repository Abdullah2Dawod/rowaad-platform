<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Consultant;
use App\Models\FeasibilityStudy;
use App\Models\InvestmentOpportunity;
use App\Models\NewsletterSubscriber;
use App\Models\Specialization;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Comprehensive demo data — populates ALL public sections so the admin
 * has realistic data to test with. Idempotent (safe to re-run).
 *
 *   php artisan db:seed --class=DemoDataSeeder --force
 */
class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🌱 Starting demo data seed...');

        // 1) Site settings (branding, socials, marketing)
        $this->call(SiteSettingsSeeder::class);

        // 2) Services catalog (8 services)
        $this->call(ServicesSeeder::class);

        // 3) Specializations (used by consultants)
        $this->call(SpecializationSeeder::class);

        // 4) Admin user
        $admin = User::updateOrCreate(
            ['email' => 'admin@rowaad.org'],
            [
                'name' => 'مدير النظام',
                'password' => Hash::make('rowaad@2026'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );
        $this->command->info("✅ Admin user: admin@rowaad.org / rowaad@2026");

        // 5) Consultants (8 approved + rich profiles)
        $this->call(ConsultantSeeder::class);

        // 6) Regular users (5 sample clients)
        $clients = [];
        foreach (['أحمد الشمري','فاطمة الزهراني','خالد العتيبي','مريم القحطاني','عبدالله المطيري'] as $i => $name) {
            $clients[] = User::updateOrCreate(
                ['email' => "client{$i}@example.com"],
                [
                    'name' => $name,
                    'password' => Hash::make('password'),
                    'role' => 'user',
                    'email_verified_at' => now(),
                    'phone' => '+96650' . str_pad($i * 1234, 7, '0'),
                ]
            );
        }
        $this->command->info('✅ ' . count($clients) . ' demo clients');

        // 7) Sample bookings across statuses (mixes paid / confirmed / completed / cancelled)
        $consultants = Consultant::approved()->take(5)->get();
        $statuses = [
            Booking::STATUS_PAID,
            Booking::STATUS_CONFIRMED,
            Booking::STATUS_COMPLETED,
            Booking::STATUS_COMPLETED,
            Booking::STATUS_CANCELLED,
        ];
        $bookingCount = 0;
        foreach ($clients as $ci => $client) {
            foreach ($consultants as $co => $consultant) {
                if ($bookingCount >= 12) break 2;
                $duration = [30, 45, 60, 90][$bookingCount % 4];
                $base = Booking::computeAmount((float) $consultant->hourly_rate, $duration);
                $pricing = Booking::computePricing($base);
                $status = $statuses[$bookingCount % count($statuses)];

                Booking::updateOrCreate(
                    ['reference' => 'DEMO-BK-' . str_pad($bookingCount + 1, 4, '0', STR_PAD_LEFT)],
                    [
                        'user_id' => $client->id,
                        'consultant_id' => $consultant->id,
                        'preferred_date' => now()->addDays(rand(-30, 30))->toDateString(),
                        'preferred_time' => sprintf('%02d:00', rand(9, 20)),
                        'duration_min' => $duration,
                        'service_title' => ['استشارة فردية','مراجعة خطة عمل','تحليل مالي','دراسة جدوى مبدئية'][$bookingCount % 4],
                        'amount' => $pricing['total'],
                        'consultant_share' => $pricing['consultantShare'],
                        'platform_share' => $pricing['platformShare'],
                        'zakat_amount' => $pricing['zakat'],
                        'status' => $status,
                        'payment_method' => 'mada',
                        'payment_ref' => 'DEMO-' . Str::upper(Str::random(8)),
                        'paid_at' => $status !== Booking::STATUS_PENDING_PAYMENT ? now()->subDays(rand(1, 30)) : null,
                        'confirmed_at' => in_array($status, [Booking::STATUS_CONFIRMED, Booking::STATUS_COMPLETED]) ? now()->subDays(rand(1, 20)) : null,
                        'meeting_url' => in_array($status, [Booking::STATUS_CONFIRMED, Booking::STATUS_COMPLETED]) ? 'https://meet.rowaad.org/demo-' . Str::random(8) : null,
                    ]
                );
                $bookingCount++;
            }
        }
        $this->command->info("✅ {$bookingCount} demo bookings across all statuses");

        // 8) Feasibility studies
        $this->call(FeasibilityStudySeeder::class);

        // 9) Investment opportunities
        $this->call(InvestmentOpportunitySeeder::class);

        // 10) Newsletter subscribers (confirmed + pending)
        $emails = [
            'reader1@example.com'  => ['قارئ ١', true],
            'reader2@example.com'  => ['قارئ ٢', true],
            'reader3@example.com'  => ['قارئ ٣', false],
            'reader4@example.com'  => ['قارئ ٤', true],
            'reader5@example.com'  => ['قارئ ٥', false],
        ];
        foreach ($emails as $email => [$name, $confirmed]) {
            NewsletterSubscriber::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'locale' => 'ar',
                    'source' => 'demo',
                    'confirmation_token' => Str::random(64),
                    'confirmed_at' => $confirmed ? now()->subDays(rand(1, 30)) : null,
                ]
            );
        }
        $this->command->info('✅ 5 newsletter subscribers');

        $this->command->newLine();
        $this->command->info('════════════════════════════════════════');
        $this->command->info('✅ Demo data seed complete!');
        $this->command->info('════════════════════════════════════════');
        $this->command->info('👤 Admin login: admin@rowaad.org / rowaad@2026');
        $this->command->info('👥 Client login: client0@example.com / password');
        $this->command->info('🌐 Visit: https://rowaad.org');
    }
}
