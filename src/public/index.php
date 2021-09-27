<?php

use GuzzleHttp\Client;
use Slim\Http\Request;
use Slim\Http\Response;

require '../../vendor/autoload.php';


$app = new \Slim\App;
$app->get('/albums', function (Request $request, Response $response) {
    try{
        // Variables http conexion API spotify
        $token = 'BQDzgdxjrFaYKwNhHdUC3b6xeC2sNIjvAjtHvw4o3eFmkKRLKnMQIaw5Or6cvv9VQfjUtHkbsT7wX7xRvbRXRHucPZ06XD2TiowE_tiAt9OKi7Pny5AWt2kFeKlnMMZLNQYGeBTokLZkYzAUEbQ';
        $headers = [
            "Accept" => "application/json",
            "Content-Type" => "application/json",
            "Authorization" => "Bearer " . $token
        ];
        $client = new Client();
        $params = $request->getQueryParams();
        $artist = $client->get(
                                'https://api.spotify.com/v1/search', 
                                [
                                    'headers' => $headers, 
                                    'query' => [
                                        'type' => 'artist', 
                                        'q' => $params['q']
                                    ]
                                ]);

        
        $albums = $client->get(
                                'https://api.spotify.com/v1/artists/6XyY86QOPPrYVGvF9ch6wz/albums', //cambiar id hardcode
                                [
                                    'headers' => $headers,
                                    'query' => [
                                        'groups' => 'album'
                                    ]
                                ]);

        $albumsBody = json_decode($albums->getBody(), true);
        foreach ($albumsBody['items'] as $album){
            $released = new DateTime($album['release_date']);
            $formattedReleased = $released->format('d-m-Y');
            $responseData[] = [
                'name' => $album['name'],
                'released' => $formattedReleased,
                'tracks' => $album['total_tracks'],
                'cover' => $album['images'][0]
            ];
        }
        return $response->withJson($responseData);
    }
    catch (Exception $exception)
    {
        return $response
                ->withStatus(500)
                ->getBody()
                ->write($exception);
    }
});
$app->run();