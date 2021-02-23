<?php

namespace App\Jobs;

use App\Models\Channel;
use App\Models\Subscription;
use App\Services\YoutubeApi;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * Class RetrieveSubscriptions
 * Retrieves subscriptions for a given channel and writes subscription count for the day to the DB.
 *  Run php artisan queue:work to start processing jobs from the SQS queue.
 *
 * @package App\Jobs
 */
class RetrieveSubscriptions implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $channel;

    /**
     * Create a new job instance.
     *
     * @param Channel $channel
     */
    public function __construct(Channel $channel)
    {
        $this->channel = $channel;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Get subscriptions for the given channel
        try {
            $channelInfo = YoutubeApi::getChannels($this->channel->youtube_id);
        } catch (\Exception $e) {
            Log::error('YoutubeApi::getChannels failed: ' . $this->channel->youtube_id);
            $this->fail();
            return;
        }

        $subscriberCount = $channelInfo['statistics']['subscriberCount'];

        // Write Subscription to DB (only up to one record/day/channel)
        Subscription::updateOrCreate(
            ['channel_id' => $this->channel->id, 'date' => Carbon::now()->format('Y-m-d')],
            ['count' => $subscriberCount]
        );

        // Update channels.retrieved_at in case this is useful for some reason
        $this->channel->retrieved_at = Carbon::now();
        $this->channel->save();
    }
}
