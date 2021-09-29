<?php
namespace App\Services;

use DateTime;
use Dotenv\Dotenv;
use Exception;
use GuzzleHttp\Client;
use Slim\Http\Request;
use Slim\Http\Response;

require('../../vendor/autoload.php');

class ArtistService
{

    public function __construct()
    {
        $dotenv = Dotenv::createImmutable('../../');
        $dotenv->load();
    }

    /**
     * Obtiene los data sobre los albums de un artista recibido por parametro.
     */
    public static function getAlbumsByArtist(Request $request, Response $response){
        $responseData = [];
        try{
            $params = $request->getQueryParams();

            $client = new Client();
            //  Obtengo Spotify ID a partir del parametro recibido
            if($params['q']){
                $headers = self::getHeaders();
                $artist = $params['q'];
                $artist = $client->get(
                                        'https://api.spotify.com/v1/search', 
                                        [
                                            'headers' => $headers, 
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
                        ->withStatus(400)
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
        $clientCredentials = self::getAuthToken();
        return $clientCredentials['access_token'];  
    }

    /**
     * Headers para el correcto armado del request de API SPOTIFY
     */
    private static function getHeaders(){
        return [
            "Content-Type" => "application/x-www-form-urlencoded",
            "Authorization" => "Bearer " . self::getSpotifyToken()
        ];
    }

    private static function getAuthToken(){
        $credentials = base64_encode($_ENV['SPOTIFY_CLIENT_ID'].':'.$_ENV['SPOTIFY_CLIENT_SECRET']);
        $client = new Client();
        $clientCredentials = $client->post(
            'https://accounts.spotify.com/api/token', 
            [
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Authorization' => 'Basic ' . $credentials
                ], 
                'form_params' => [
                    'grant_type' => 'client_credentials'
                    ]
            ]);
        return json_decode($clientCredentials->getBody(), true);
    }
}   