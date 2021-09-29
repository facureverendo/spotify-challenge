<?php
namespace App\Services;

use DateTime;
use Exception;
use GuzzleHttp\Client;
use Slim\Http\Request;
use Slim\Http\Response;

class ArtistService
{

    public function __construct()
    {
    }

    /**
     * Obtiene los data sobre los albums de un artista recibido por parametro.
     */
    public static function getAlbumsByArtist(Request $request, Response $response) : Response{
        $responseData = [];
        try{
            $params = $request->getQueryParams();

            $client = new Client();
            //  Obtengo Spotify ID a partir del parametro recibido
            if($params['q']){
                $artist = $params['q'];
                $artist = $client->get(
                                        'https://api.spotify.com/v1/search', 
                                        [
                                            'headers' => self::getHeaders(), 
                                            'query' => [
                                                'type' => 'artist', 
                                                'q' => $artist
                                            ]
                                        ]);
        
                $artistBody = json_decode($artist->getBody(), true);
                $spotifyId = $artistBody['artists']['items'][0]['id'];
                //  Busco los albums relacionados al artista
                if($spotifyId){
                    $albums = $client->get(
                                            'https://api.spotify.com/v1/artists/'. $spotifyId .'/albums',
                                            [
                                                'headers' => self::getHeaders(),
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
    }

    /**
     * Token temporal provisto por Spotify
     */
    private static function getSpotifyToken(){
        return 'BQCCH6W0t2bNjr2fDN5TqUgisH0dYtkWUC5RgeXAvjl5sz0e05lTieEd4rM14ephvDDFSxoCiapXo_7yICwOSIVtkJ8iHhohAEw_T2GIuis4bCSuw5ni48YMm2lNoISwZpYgPS_oyLrbTrEoQZQ';
    }

    /**
     * Headers para el correcto armado del request de API SPOTIFY
     */
    private static function getHeaders(){
        return [
            "Accept" => "application/json",
            "Content-Type" => "application/json",
            "Authorization" => "Bearer " . self::getSpotifyToken()
        ];
    }
}   