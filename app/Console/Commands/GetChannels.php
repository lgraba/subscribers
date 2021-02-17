<?php

namespace App\Console\Commands;

use App\Models\Channel;
use App\Models\Subscription;
use App\Services\YoutubeApi;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

/**
 * Class GetChannels
 * Get daily subscription counts for all Channels in the platform and write them to the DB
 *
 * @package App\Console\Commands
 */
class GetChannels extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'channels:get';

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

        // Arbitrary chunk size
        foreach ($channels->chunk(10) as $channelsChunk) {
            $youtubeIdsString = implode(',', $channelsChunk->pluck('youtube_id')->toArray());

            // Get chunk of channels info
            try {
                $channelsInfo = YoutubeApi::getChannels($youtubeIdsString);
            } catch (\Exception $e) {
                Log::error('YoutubeApi::getChannels failed: ' . $youtubeIdsString);
                continue;
            }

            foreach ($channelsInfo as $channelInfo) {
                // ToDo: We'd probably want to update the Channel record, as well, in case title/description changed
                $channel         = Channel::where('youtube_id', $channelInfo['id'])->first();
                $subscriberCount = $channelInfo['statistics']['subscriberCount'];

                // Write Subscription to DB (only up to one record/day/channel)
                Subscription::updateOrCreate(
                    ['channel_id' => $channel->id, 'date' => Carbon::now()->format('Y-m-d')],
                    ['count' => $subscriberCount]
                );

                // Update channels.retrieved_at in case this is useful for some reason
                $channel->retrieved_at = Carbon::now();
                $channel->save();
            }
        }
    }
}
