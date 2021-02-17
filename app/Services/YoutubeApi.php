<?php

namespace App\Services;

use Google_Client;
use Google_Service_YouTube;
use Illuminate\Http\Client\HttpClientException;
use Illuminate\Support\Facades\Http;

/**
 * Class YoutubeApi
 * Service class to make external API calls to YouTube
 *
 * @package App\Services
 */
class YoutubeApi
{
    /**
     * Get the info, including stats, of one or more YouTube channels
     *
     * @param string $youtubeId
     * @return array
     * @throws HttpClientException
     */
    public static function getChannels(string $youtubeId): array {
        $apiKey = 'fakeApiKey33kj3lk2j34p2j4k'; // ToDo: Store this in config management for security

        // This is nice and simple for our purposes
        $response = Http::get("https://youtube.googleapis.com/youtube/v3/channels?part=snippet%2CcontentDetails%2Cstatistics&id=$youtubeId&key=$apiKey");

        // Handle error
        if ($response->failed() || !isset($response['items'])) {
            throw new HttpClientException('Failed to retrieve YouTube channel data for channel ' . $youtubeId);
        }

        return $response['items'];

        // Using the Google API Client is a possibility, though (example follows)
//        $client = new Google_Client();
//        // ... more auth stuff here
//        $service = new Google_Service_YouTube($client);
//        $queryParams = ['id' => 'UC_x5XG1OV2P6uZZ5FSM9Ttw'];
//        $response = $service->channels->listChannels('snippet,contentDetails,statistics', $queryParams);
    }
}
