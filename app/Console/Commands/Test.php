<?php

namespace App\Console\Commands;

use App\Models\Positions;
use App\Models\Trader;
use App\Notifications\SendNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
       Positions::create([
           'symbol' => 'BTCUSDT',
           'amount' => 1,
           'markPrice' => 1,
           'roe' => 1,
           'yellow' => 1,
           'trader_id' => 1,
       ]);
    }
}
