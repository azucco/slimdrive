<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;




//Update token tramide user e id
$app->get('/api/login/{user}/{pwd}', function(Request $request, Response $response){
    $user = $request->getAttribute('user');
    $pwd = $request->getAttribute('pwd');
    $sql = "SELECT * FROM t_users WHERE user = '$user' AND pwd = '$pwd'";

    try{
        //Get DB Object
        $db = new db();
        //Connetti
        $db = $db->connect();
        $stmt = $db->query($sql);
        $autenticato = $stmt->fetch(PDO::FETCH_OBJ);
        $db = null;
       
        
        login($autenticato, $user);
        
        
       

        
    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}}';
    }
});


function login($autenticato, $username) {
 
    if ($autenticato != null && $autenticato != "") { //implement your own validation method against your db
        
        
        $token = bin2hex(openssl_random_pseudo_bytes(8)); //generate a random token

        $tokenExpiration = date('Y-m-d H:i:s', strtotime('+1 hour'));//the expiration date will be in one hour from the current moment
        
        updateToken($token, $tokenExpiration, $username); //This function can update the token on the database and set the expiration date-time, implement your own
        
        
     
        
    }
    echo "";
}

function updateToken($token, $tokenExpiration, $username){
    $sql = "UPDATE t_users SET token = :token, token_expire = :tokenExpiration WHERE user = :username";

    try{
        //Get DB Object
        $db = new db();
        //Connetti
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $data = [
            ':token' => $token,
            ':tokenExpiration' => $tokenExpiration,
            ':username' => $username
        ];

        
        if(! $stmt->execute($data)){
            throw new Exception(sprintf(
                "Error PDO exec: %s", implode(',', $db->errorInfo())
            ));
        }
        
        $db = null;

        echo $token;

        
        

    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}}';
    }
}