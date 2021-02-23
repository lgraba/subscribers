<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Models\Subscription;
use App\Services\YoutubeApi;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class ChannelController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function subscriberCount(string $youtubeId) {
        // Get current subscriber count via API call to YT
        try {
            $channelInfo = YoutubeApi::getChannels($youtubeId)[0];
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        // Create Channel in our DB if it doesn't already exist
        $channel = Channel::firstOrCreate(
            ['youtube_id' => $youtubeId],
            [
                'title'       => $channelInfo['snippet']['title'],
                'description' => strlen($channelInfo['snippet']['description']) > 100 ?
                    substr($channelInfo['snippet']['description'], 0, 100) . '...' :
                    $channelInfo['snippet']['description']
            ]);

        $subscriberCount = $channelInfo['statistics']['subscriberCount'];

        // Write Subscription to DB (only up to one record/day/channel)
        Subscription::updateOrCreate(
            ['channel_id' => $channel->id, 'date' => Carbon::now()->format('Y-m-d')],
            ['count' => $subscriberCount]
        );

        // Update channels.retrieved_at in case this is useful for some future functionality
        $channel->retrieved_at = Carbon::now();
        $channel->save();

        return ['subscribers' => $subscriberCount];
    }

    public function subscriberHistory(string $youtubeId) {
        // Get daily subscriber history from DB
        $channel = Channel::where('youtube_id', $youtubeId)->first();

        // If the channel doesn't exist in our DB, write it and retrieve subscriptions
        if (!$channel) {
            // Get current subscriber count via API call to YT
            try {
                $channelInfo = YoutubeApi::getChannels($youtubeId)[0];
            } catch (\Exception $e) {
                return $e->getMessage();
            }

            // Create Channel in our DB if it doesn't already exist
            $channel = Channel::firstOrCreate(
                ['youtube_id' => $youtubeId],
                [
                    'title'       => $channelInfo['snippet']['title'],
                    'description' => strlen($channelInfo['snippet']['description']) > 100 ?
                        substr($channelInfo['snippet']['description'], 0, 100) . '...' :
                        $channelInfo['snippet']['description']
                ]);

            $subscriberCount = $channelInfo['statistics']['subscriberCount'];

            // Write Subscription to DB (only up to one record/day/channel)
            Subscription::updateOrCreate(
                ['channel_id' => $channel->id, 'date' => Carbon::now()->format('Y-m-d')],
                ['count' => $subscriberCount]
            );

            // Update channels.retrieved_at in case this is useful for some future functionality
            $channel->retrieved_at = Carbon::now();
            $channel->save();
        }

        $subs = $channel->subscriptions()->select(['date', 'count'])->orderBy('date')->get();

        $subscriberHistory = collect([]);

        foreach ($subs as $sub) {
            $subscriberHistory[] = [
                'date'  => $sub->date,
                'count' => $sub->count,
                'delta' => isset($previousSub) ? $sub->count - $previousSub['count'] : null // ToDo: Handle deltas in DB
            ];

            $previousSub = $sub;
        }

        return $subscriberHistory;
    }
}
