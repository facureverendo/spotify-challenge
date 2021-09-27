<?php

use GuzzleHttp\Client;
use Slim\Http\Request;
use Slim\Http\Response;

require '../../vendor/autoload.php';


$app = new \Slim\App;
$app->get('/albums', [
    'controller' => [Controller\Upload::class, 'uploadFiles']
]);
/*
$app->get('/albums', function (Request $request, Response $response, $args) {
    try{
        $params = $request->getQueryParams();
        /**
         * VALIDAR QUE VENGA SETEADO EL PARAMETRO q.
         * VER DE RECIBIR EL TOKEN POR PARAMETRO.
         * CREAR SERVICIO CON LA LOGICA
         * CREAR UTILITY CON GUZZLE PARA SPOTIFY
         * MANEJAR EXCEPCIONES
         * MANEJAR RESPONSES DE ERROR
         * DOCUMENTAR
         * UNIT TESTS
         * EMBELLECER MENSAJE DE ERROR 500
         * CONSEGUIR TOKEN DE SPOTIFY QUE NO EXPIRE
         */

        // Variables http conexion API spotify
  /*      $token = 'BQDzgdxjrFaYKwNhHdUC3b6xeC2sNIjvAjtHvw4o3eFmkKRLKnMQIaw5Or6cvv9VQfjUtHkbsT7wX7xRvbRXRHucPZ06XD2TiowE_tiAt9OKi7Pny5AWt2kFeKlnMMZLNQYGeBTokLZkYzAUEbQ';
        $headers = [
            "Accept" => "application/json",
            "Content-Type" => "application/json",
            "Authorization" => "Bearer " . $token
        ];
        $client = new Client();
        //  Obtengo Spotify ID a partir del parametro recibido
        $artist = $client->get(
                                'https://api.spotify.com/v1/search', 
                                [
                                    'headers' => $headers, 
                                    'query' => [
                                        'type' => 'artist', 
                                        'q' => $params['q']
                                    ]
                                ]);

        //  Busco los albums relacionados al artista
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
    
});*/
$app->run();