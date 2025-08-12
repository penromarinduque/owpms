<?php

use App\Models\LtpApplication;
use App\Models\LtpApplicationProgress;
use App\Notifications\LtpApplicationExpired;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();


Schedule::call(function () {
    $applications = LtpApplication::with(['permittee.user']) 
        ->whereNotIn('application_status', ['draft', 'expired'])
        ->whereDate('transport_date', '<=', Carbon::now())
        ->get();

    $now = now();
    $progressData = [];

    foreach ($applications as $application) {
        Notification::send($application->permittee->user, new LtpApplicationExpired($application));

        $progressData[] = [
            'ltp_application_id' => $application->id,
            'status' => 'expired',
            'user_id' => $application->permittee->user->id,
            'remarks' => 'The application has expired due to the transport date being in the past.',
            'created_at' => $now,
            'updated_at' => $now,
        ];
    }

    LtpApplicationProgress::insert($progressData);

    $applicationIds = $applications->pluck('id');
    if ($applicationIds->isNotEmpty()) {
        LtpApplication::whereIn('id', $applicationIds)->update(['application_status' => 'expired']);
    }
})->dailyAt('00:00');

// FOR TESTING CRON JOBS
// Schedule::call(function () {
//     Log::debug('Running Cron Job');
// })->everyMinute();