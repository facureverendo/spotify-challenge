<?php

use GuzzleHttp\Client;
use Slim\Http\Request;
use Slim\Http\Response;

require '../../vendor/autoload.php';


$app = new \Slim\App;

$app->get('/albums', function (Request $request, Response $response) {
    $responseData = [];
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
        $token = 'BQD6azCwHOyGhsKh04TADGNeBfLCA9T9ShKfg1SsBtswWukl1BL4zm8B3p0iR8sdrdc6vrjc4GcsU-FyDOx6lKgUSeGu1VpTM2LftdkmcsEUMLYt66a_izDlidhku9g6X1rjo1R7Hl0nrZsXkak';
        $headers = [
            "Accept" => "application/json",
            "Content-Type" => "application/json",
            "Authorization" => "Bearer " . $token
        ];
        $client = new Client();
        //  Obtengo Spotify ID a partir del parametro recibido
        if($params['q']){
            $artist = $client->get(
                                    'https://api.spotify.com/v1/search', 
                                    [
                                        'headers' => $headers, 
                                        'query' => [
                                            'type' => 'artist', 
                                            'q' => $params['q']
                                        ]
                                    ]);
    
            $artistBody = json_decode($artist->getBody(), true);
            $spotifyId = $artistBody['artists']['items'][0]['id'];
            //  Busco los albums relacionados al artista
            if($spotifyId){
                $albums = $client->get(
                                        'https://api.spotify.com/v1/artists/'. $spotifyId .'/albums', //cambiar id hardcode
                                        [
                                            'headers' => $headers,
                                            'query' => [
                                                'include_groups' => 'album'
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
            }
        }else{
            return $response
                    ->withStatus(201)
                    ->getBody()
                    ->write('Missing query param');
        }
        return $response->withJson($responseData);
    }
    catch (Exception $exception)
    {
        return $response
                ->withStatus($exception->getCode())
                ->getBody()
                ->write($exception->getMessage());
    }
    
});

$app->run();