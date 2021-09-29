define({ "api": [
  {
    "type": "get",
    "url": "/api/v1/albums?q={band-name}",
    "title": "Obtener albums",
    "name": "Obtener_albums",
    "group": "Artist",
    "description": "<p>Endpoint to get information about the albums of a particular band</p>",
    "version": "0.0.0",
    "filename": "api/v1/routes.php",
    "groupTitle": "Artist",
    "success": {
      "examples": [
        {
          "title": "Respuesta",
          "content": "  HTTP/1.1 200 OK\n{\n    \"success\": true,\n    \"data\":  \n        [\n          {\n              \"name\": \"Album name\",\n              \"released\": \"Release date\",\n              \"tracks\": \"Number of tracks\",\n              \"cover\": \n                 {\n                    \"height\": \"Cover height\",\n                    \"url\": \"Cover url\",\n                    \"width\": \"Cover width\"\n                 }\n          },\n          ...\n        ]\n}",
          "type": "json"
        }
      ]
    }
  }
] });
