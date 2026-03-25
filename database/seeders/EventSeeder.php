<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use Carbon\Carbon;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $events = [
            [
                'title' => 'Job Fair 2024 — Quezon City',
                'type' => 'Job Fair',
                'description' => 'Annual job fair connecting top employers with qualified jobseekers.',
                'event_date' => Carbon::today()->addDays(5)->format('Y-m-d'),
                'start_time' => '08:00:00',
                'end_time' => '17:00:00',
                'location' => 'QC Sports Club, Quezon City',
                'max_participants' => 200,
                'status' => 'upcoming',
                'created_by' => 1,
            ],
            [
                'title' => 'Livelihood Seminar Series',
                'type' => 'Seminar',
                'description' => 'A series of livelihood seminars for unemployed residents.',
                'event_date' => Carbon::today()->addDays(12)->format('Y-m-d'),
                'start_time' => '09:00:00',
                'end_time' => '12:00:00',
                'location' => 'City Hall Annex, Manila',
                'max_participants' => 80,
                'status' => 'upcoming',
                'created_by' => 1,
            ],
            [
                'title' => 'BPO Skills Training',
                'type' => 'Training',
                'description' => 'Hands-on BPO and call center readiness training program.',
                'event_date' => Carbon::today()->format('Y-m-d'),
                'start_time' => '13:00:00',
                'end_time' => '17:00:00',
                'location' => 'TESDA Center, Marikina',
                'max_participants' => 50,
                'status' => 'ongoing',
                'created_by' => 1,
            ],
            [
                'title' => 'Carpentry & Welding Workshop',
                'type' => 'Workshop',
                'description' => 'Practical skills workshop for NCII certification.',
                'event_date' => Carbon::today()->subDays(10)->format('Y-m-d'),
                'start_time' => '08:00:00',
                'end_time' => '16:00:00',
                'location' => 'PESO Training Hall',
                'max_participants' => 30,
                'status' => 'completed',
                'created_by' => 1,
            ],
            [
                'title' => 'Negosyo sa Barangay Program',
                'type' => 'Livelihood Program',
                'description' => 'Micro-enterprise training for barangay residents.',
                'event_date' => Carbon::today()->addDays(20)->format('Y-m-d'),
                'start_time' => '09:00:00',
                'end_time' => '15:00:00',
                'location' => 'Brgy. Hall, Caloocan',
                'max_participants' => 60,
                'status' => 'upcoming',
                'created_by' => 1,
            ]
        ];

        foreach ($events as $event) {
            Event::create($event);
        }
    }
}
