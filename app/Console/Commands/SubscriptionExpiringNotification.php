<?php

namespace App\Console\Commands;

use App\Models\OrderDetail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SubscriptionExpiringNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

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
        $details=OrderDetail::where(DB::raw('total_quantiy'));
    }
}
