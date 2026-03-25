<?php

namespace Database\Seeders;

use App\Models\Employer;
use App\Models\Event;
use App\Models\Jobseeker;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * ActivityFeedSeeder
 *
 * Seeds realistic-looking data across Jobseeker, Employer, and Event records
 * so that the AdminActivityFeedController returns meaningful data in development.
 * 
 * Safe to re-run: only adds records if counts are low.
 */
class ActivityFeedSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        if (!$admin) {
            $this->command->warn('No admin user found — skipping ActivityFeedSeeder.');
            return;
        }

        // ── Jobseekers (if very few exist) ────────────────────────────────
        if (Jobseeker::count() < 5) {
            $seekers = [
                ['full_name' => 'Carlo Dacuista',  'email' => 'carlo@example.com',  'address' => 'Quezon City',   'minutes_ago' => 5],
                ['full_name' => 'Ana Reyes',        'email' => 'ana@example.com',    'address' => 'Makati City',    'minutes_ago' => 45],
                ['full_name' => 'Jose Santos',      'email' => 'jose@example.com',   'address' => 'Pasig City',     'minutes_ago' => 120],
                ['full_name' => 'Maria Cruz',       'email' => 'maria@example.com',  'address' => 'Taguig City',    'minutes_ago' => 480],
                ['full_name' => 'Pedro Guevarra',   'email' => 'pedro@example.com',  'address' => 'Marikina City',  'minutes_ago' => 1440],
            ];

            foreach ($seekers as $s) {
                Jobseeker::firstOrCreate(
                    ['email' => $s['email']],
                    [
                        'full_name'  => $s['full_name'],
                        'password'   => bcrypt('password123'),
                        'address'    => $s['address'],
                        'status'     => 'active',
                        'created_at' => now()->subMinutes($s['minutes_ago']),
                        'updated_at' => now()->subMinutes($s['minutes_ago']),
                    ]
                );
            }
            $this->command->info('ActivityFeedSeeder: seeded sample jobseekers.');
        }

        // ── Events (if very few exist) ─────────────────────────────────────
        if (Event::count() < 3) {
            $events = [
                [
                    'title'       => 'Job Fair 2025 — Quezon City',
                    'description' => 'Annual job fair connecting employers and jobseekers.',
                    'event_date'  => now()->addDays(3)->toDateString(),
                    'location'    => 'Quezon City Hall',
                    'slots'       => 200,
                    'status'      => 'upcoming',
                    'created_by'  => $admin->id,
                    'created_at'  => now()->subHours(2),
                ],
                [
                    'title'       => 'Skills Training Workshop',
                    'description' => 'Free skills training for registered jobseekers.',
                    'event_date'  => now()->addDays(7)->toDateString(),
                    'location'    => 'PESO Office',
                    'slots'       => 50,
                    'status'      => 'upcoming',
                    'created_by'  => $admin->id,
                    'created_at'  => now()->subHours(12),
                ],
                [
                    'title'       => 'Employment Forum — BGC',
                    'description' => 'Forum for industry leaders and jobseekers.',
                    'event_date'  => now()->subDays(5)->toDateString(),
                    'location'    => 'BGC Corporate Center',
                    'slots'       => 300,
                    'status'      => 'completed',
                    'created_by'  => $admin->id,
                    'created_at'  => now()->subDays(10),
                ],
            ];

            foreach ($events as $ev) {
                Event::firstOrCreate(
                    ['title' => $ev['title']],
                    $ev
                );
            }
            $this->command->info('ActivityFeedSeeder: seeded sample events.');
        }

        $this->command->info('ActivityFeedSeeder complete.');
    }
}
