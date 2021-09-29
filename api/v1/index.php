<?php

use App\Services\ArtistService;
use Slim\Http\Request;
use Slim\Http\Response;

require '../../vendor/autoload.php';
require '../../App/Services/ArtistService.php';


$app = new \Slim\App;

$app->get('/albums', function (Request $request, Response $response) {
    $artistService  = new ArtistService();
    return $artistService->getAlbumsByArtist($request, $response);
});

$app->run();