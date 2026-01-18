<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Repositories\BookingRepository;

class DeleteOldBookings extends Command
{
    protected $signature = 'bookings:delete-old';
    protected $description = 'Delete bookings older than 30 days';

    // public function __construct(
    //     private BookingRepository $bookingRepository
    // ) {
    //     parent::__construct();
    // }

    // public function handle()
    // {
    //     $thirtyDaysAgo = Carbon::now()->subDays(30);
        
    //     $deletedCount = $this->bookingRepository->deleteOlderThan($thirtyDaysAgo);
        
    //     $this->info("Deleted {$deletedCount} booking(s) older than 30 days.");
        
    //     return Command::SUCCESS;
    // }
}
