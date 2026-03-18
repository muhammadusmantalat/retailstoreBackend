<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Vendor;
use App\Models\Notification;
use App\Models\NotificationDay;
use Illuminate\Console\Command;
use App\Helpers\NotificationHelper;
use Illuminate\Support\Facades\Log;

class sendNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:sendNotification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // $notifyDays = NotificationDay::where('notification_date', Carbon::now())->get();
        $currentDate = Carbon::now()->startOfDay(); // This will be '2024-09-14 00:00:00' if today is September 14, 2024
        $notifyDays = NotificationDay::where('notification_date', $currentDate)->get();
        if ($notifyDays->isNotEmpty()) {
            foreach ($notifyDays as $day) {
                $vendor = Vendor::find($day->vendor_id);
                $notification = Notification::create([
                    'store_manager_id' => $day->store_manager_id,
                    'store_id' => $day->store_id,
                    'vendor_id' => $day->vendor_id,
                    'title' => "Order Reminder: " . $vendor->vendor_name,
                    'body' => "Delivery scheduled on " . $vendor->delivery_days . ".",
                ]);
                Log::info('Notification sent for vendor ID: ' . $day->vendor_id);
                $date = NotificationHelper::deliveryNotification($vendor->delivery_frequency, $vendor->delivery_days);
                $day->update([
                    'notification_date' => $date,
                ]);
            }
            $this->info('Notification sent successfuly.');
        }
        else
        {
            Log::info('No notifications for today.');
        }
    }
}
