<?php

namespace Database\Seeders;

use App\Models\Employer;
use App\Models\Notification;
use App\Models\NotificationRead;
use App\Models\User;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        $adminId = $admin?->id;

        $employers = Employer::all();

        if ($employers->isEmpty()) {
            $this->command->warn('No employers found — skipping NotificationSeeder.');
            return;
        }

        // ---------- Templates ----------
        // Each notification that targets 'employers' will get a NotificationRead per employer.
        $templates = [
            [
                'subject'    => 'New Application Received',
                'message'    => 'A jobseeker has applied to your Software Engineer position. Check new applicants in your dashboard.',
                'type_hint'  => 'applicant',
                'recipients' => 'employers',
                'offset_min' => 44, // 44 mins ago
            ],
            [
                'subject'    => 'Job listing expiring',
                'message'    => 'Your job listing is expiring in 3 days. Extend the deadline or close the listing.',
                'type_hint'  => 'job',
                'recipients' => 'employers',
                'offset_min' => 200,
            ],
            [
                'subject'    => 'New High Match applicant',
                'message'    => 'A jobseeker with a high skill match applied to your job listing. Review the application now.',
                'type_hint'  => 'match',
                'recipients' => 'employers',
                'offset_min' => 480,
            ],
        ];

        $notifCount = 0;
        $readCount  = 0;

        foreach ($templates as $tpl) {
            $notif = Notification::create([
                'subject'    => $tpl['subject'],
                'message'    => $tpl['message'],
                'recipients' => $tpl['recipients'],
                'status'     => 'sent',
                'sent_at'    => now()->subMinutes($tpl['offset_min']),
                'created_by' => $adminId,
            ]);

            // Create a NotificationRead record for every employer
            foreach ($employers as $employer) {
                // Vary read status: older notifications tend to be read
                $isRead = $tpl['offset_min'] > 200;

                NotificationRead::create([
                    'notification_id' => $notif->id,
                    'recipient_type'  => 'employer',
                    'recipient_id'    => $employer->id,
                    'read_at'         => $isRead ? now()->subMinutes($tpl['offset_min'] - 10) : null,
                    'created_at'      => now()->subMinutes($tpl['offset_min']),
                    'updated_at'      => now()->subMinutes($tpl['offset_min']),
                ]);
                $readCount++;
            }

            $notifCount++;
        }

        $this->command->info("NotificationSeeder: created {$notifCount} notifications, {$readCount} notification_reads ({$employers->count()} employers).");
    }
}
