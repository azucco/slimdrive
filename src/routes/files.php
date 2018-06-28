<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;




//Get All Files
$app->get('/api/files', function(Request $request, Response $response){

    $sql = 'SELECT * FROM t_files ';

    try{
        //Get DB Object
        $db = new db();
        //Connetti
        $db = $db->connect();
        $stmt = $db->query($sql); //qua si usa query() al posto di prepare(), pag 143 manuale PHP
        $files = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($files);

    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}}';
    }

});