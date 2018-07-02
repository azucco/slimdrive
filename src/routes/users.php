<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

//Aggiungi user
$app->get('/api/users/add/{user}/{pwd}', function(Request $request, Response $response){
    $user = $request->getAttribute('user');
    $pwd = $request->getAttribute('pwd');
    $sql = "INSERT INTO t_users (user, pwd) VALUES
    (:user, :pwd) ";

    try{
        //Get DB Object
        $db = new db();
        //Connetti
        $db = $db->connect();
        $stmt = $db->prepare($sql);

            $stmt->bindParam(':user', $user);
            $stmt->bindParam(':pwd',  $pwd);
            

        $stmt->execute();
        $db = null;
        echo '{"notice": {"text": "User registrato"}}';

    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}}';
    }
});