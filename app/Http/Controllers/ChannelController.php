<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Services\YoutubeApi;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;

class ChannelController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function subscriberCount(string $youtubeId) {
        // Get current subscriber count via API call to YT
        return ['subscribers' => YoutubeApi::getSubscriberCount($youtubeId)];

        // ToDo: If the channel doesn't exist in our DB, create with info from YT call
    }

    public function subscriberHistory(string $youtubeId) {
        // Get daily subscriber history from DB
        $channel = Channel::where('youtube_id', $youtubeId)->firstOrFail();

        $subs = $channel->subscriptions()->select(['created_at', 'count'])->get();

        $subscriberHistory = collect([]);

        foreach ($subs as $sub) {
            $subscriberHistory[] = [
                'date'  => $sub->created_at->format('Y-m-d'),
                'count' => $sub->count,
                'delta' => isset($previousSub) ? $sub->count - $previousSub['count'] : null // ToDo: Handle deltas in DB
            ];

            $previousSub = $sub;
        }

        return $subscriberHistory;

        // ToDo: If the channel doesn't exist in our DB, create with info from YT call
    }
}
