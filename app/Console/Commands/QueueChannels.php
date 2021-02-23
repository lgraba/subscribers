<?php

namespace App\Console\Commands;

use App\Jobs\RetrieveSubscriptions;
use App\Models\Channel;
use App\Models\Subscription;
use App\Services\YoutubeApi;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

/**
 * Class QueueChannels
 * Queue channels in SQS for retrieval of subscription counts (assumes SQS queue exists and configured)
 *
 * @package App\Console\Commands
 */
class QueueChannels extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'channels:queue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make bulk requests to YouTube API to retrieve/write subscription info';

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
        $channels = Channel::all();

        foreach ($channels as $channel) {
            RetrieveSubscriptions::dispatch($channel);
        }
    }
}
