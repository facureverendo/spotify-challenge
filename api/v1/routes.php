<?php

/** Services\MediaService::getAlbumsByArtist()
       * 
        * @apiDefine getAlbumsByArtist
        *
        * @apiSuccessExample {json} Respuesta
        *     HTTP/1.1 200 OK
        *   {
        *       "success": true,
        *       "data":  
        *           [
        *             {
        *                 "name": "Album name",
        *                 "released": "Release date",
        *                 "tracks": "Number of tracks",
        *                 "cover": 
        *                    {
        *                       "height": "Cover height",
        *                       "url": "Cover url",
        *                       "width": "Cover width"
        *                    }
        *             },
        *             ...
        *           ]
        *   }
        */
        /**
        * @api {get} /api/v1/albums?q={band-name} Obtener albums
        * @apiName Obtener albums
        * @apiGroup Artist
        *
        * @apiDescription Endpoint to get information about the albums of a particular band
        *
        *
        * @apiUse getAlbumsByArtist
        *
      */
