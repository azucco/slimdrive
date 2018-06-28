<?php
ini_set("display_errors", "on");
error_reporting(E_ALL);


use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;



$app->get('/api/download/{path}', function($req, $res, $args) {
    
    $path = $req->getAttribute('path');
    $file = "/var/www/html/slimdrive/src/uploads" . DIRECTORY_SEPARATOR . $path;
    
    $response = $res->withHeader('Content-Description', 'File Transfer')
   ->withHeader('Content-Type', 'application/octet-stream')
   ->withHeader('Content-Disposition', 'attachment;filename="'.basename($file).'"')
   ->withHeader('Expires', '0')
   ->withHeader('Cache-Control', 'must-revalidate')
   ->withHeader('Pragma', 'public')
   ->withHeader('Content-Length', filesize($file));

readfile($file);
return $response;
});