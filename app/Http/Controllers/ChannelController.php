<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;

class ChannelController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function subscriberCount(Channel $channel) {
        // Get current subscriber count via API call to YT
        dump('Yep!');
    }

    public function subscriberHistory(Channel $channel) {
        // Get daily subscriber history from DB
        return $channel->subscriptions()->get()->toArray();
    }
}
