<?php
ini_set("display_errors", "on");
error_reporting(E_ALL);


use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;



$app->get('/api/delete/{path}', function($req, $res, $args) {
    
    $path = $req->getAttribute('path');
    $file = "/var/www/html/slimdrive/src/uploads" . DIRECTORY_SEPARATOR . $path;
    
    unlink($file);
   
    $sql = "DELETE FROM t_files 
    WHERE path = :path ";

try{
    //Get DB Object
    $db = new db();
    //Connetti
    $db = $db->connect();
    $stmt = $db->prepare($sql);
    
        $stmt->bindParam(':path', $path);

    $stmt->execute();
    $db = null;
    echo '{"notice": {"text": "File eliminato"}}';

} catch(PDOException $e){
    echo '{"error": {"text": '.$e->getMessage().'}}';
}
});