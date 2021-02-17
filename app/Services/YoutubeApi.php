<?php

namespace App\Services;

/**
 * Class YoutubeApi
 * Service class to make external API calls to YouTube
 *
 * @package App\Services
 */
class YoutubeApi
{
    /**
     * Get the subscriber count of a given YouTube channel id
     *
     * @param string $youtubeId
     * @return int
     */
    public static function getSubscriberCount(string $youtubeId): int {
//        $client = new Google_Client();
//        $client->setApplicationName('API code samples');
//        $client->setScopes([
//            'https://www.googleapis.com/auth/youtube.readonly',
//        ]);
//
//        // TODO: For this request to work, you must replace
//        //       "YOUR_CLIENT_SECRET_FILE.json" with a pointer to your
//        //       client_secret.json file. For more information, see
//        //       https://cloud.google.com/iam/docs/creating-managing-service-account-keys
//        $client->setAuthConfig('YOUR_CLIENT_SECRET_FILE.json');
//        $client->setAccessType('offline');
//
//        // Request authorization from the user.
//        $authUrl = $client->createAuthUrl();
//        printf("Open this link in your browser:\n%s\n", $authUrl);
//        print('Enter verification code: ');
//        $authCode = trim(fgets(STDIN));
//
//        // Exchange authorization code for an access token.
//        $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
//        $client->setAccessToken($accessToken);
//
//        // Define service object for making API requests.
//        $service = new Google_Service_YouTube($client);
//
//        $queryParams = [
//            'id' => 'UC_x5XG1OV2P6uZZ5FSM9Ttw'
//        ];
//
//        $response = $service->channels->listChannels('snippet,contentDetails,statistics', $queryParams);
//        print_r($response);
        return rand(0, 500000);
    }
}
