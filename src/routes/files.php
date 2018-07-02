<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;




//Get tutti i miei file
$app->get('/api/files/{user}', function(Request $request, Response $response){
    $owner = $request->getAttribute('user');

    $sql = 'SELECT * FROM t_files WHERE owner = :owner';

    try{
        //Get DB Object
        $db = new db();
        //Connetti
        $db = $db->connect();
        
        $stmt = $db->prepare($sql); //qua si usa query() al posto di prepare(), pag 143 manuale PHP
        $stmt->bindParam(':owner',  $owner);
        $stmt->execute();
        $files = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($files);

    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}}';
    }
});


//Get idShareWith
$app->get('/api/files/sharedwith/{user}', function(Request $request, Response $response){
    $owner = $request->getAttribute('user');

    $sql = 'SELECT c.id_user, c.id_file, u.user FROM t_files as f INNER JOIN t_condivisioni as c ON f.id_file = c.id_file INNER JOIN t_users as u ON c.id_user = u.id_user WHERE owner = :owner';

    try{
        //Get DB Object
        $db = new db();
        //Connetti
        $db = $db->connect();
        
        $stmt = $db->prepare($sql); //qua si usa query() al posto di prepare(), pag 143 manuale PHP
        $stmt->bindParam(':owner',  $owner);
        $stmt->execute();
        $idShareWith = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($idShareWith);

    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}}';
    }

});


//Condividi file
$app->get('/api/files/share/{id_file}/{uts}', function(Request $request, Response $response){
    $uts = $request->getAttribute('uts');

    $id_file = $request->getAttribute('id_file');

    $sql = 'SELECT path FROM t_files WHERE id_file = :id_file';

    try{
        //Get DB Object
        $db = new db();
        //Connetti
        $db = $db->connect();
        
        $stmt = $db->prepare($sql); //qua si usa query() al posto di prepare(), pag 143 manuale PHP
        $stmt->bindParam(':id_file',  $id_file);
        $stmt->execute();
        $path = $stmt->fetch(PDO::FETCH_OBJ);
        $db = null;
        

    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}}';
    }

    
    $pathStr = $path->path;
    //$id_userStr = $id_user->id_user;
    echo $pathStr;
    echo $uts;
    
    
    $sql = 'INSERT INTO t_condivisioni (id_file, id_user)
            SELECT f.id_file, u.id_user
            FROM t_files AS f
            CROSS JOIN t_users AS u
            WHERE f.path = :path
            AND u.user = :user';

    echo $sql;

    try{
        //Get DB Object
        $db = new db();
        //Connetti
        $db = $db->connect();
        
        $stmt = $db->prepare($sql); 
        $stmt->bindParam(':path',  $pathStr);
        $stmt->bindParam(':user',  $uts);
        $stmt->execute();
        $condivisione = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($condivisione);

    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}}';
    }
    

});

//GET file condivisi con me
$app->get('/api/files/condivisi/{user}', function(Request $request, Response $response){
    $user = $request->getAttribute('user');

    $sql = 'SELECT f.* FROM t_files as f INNER JOIN t_condivisioni as c ON f.id_file = c.id_file INNER JOIN t_users as u ON c.id_user = u.id_user WHERE u.user = :user';

    try{
        //Get DB Object
        $db = new db();
        //Connetti
        $db = $db->connect();
        
        $stmt = $db->prepare($sql); //qua si usa query() al posto di prepare(), pag 143 manuale PHP
        $stmt->bindParam(':user',  $user);
        $stmt->execute();
        $files = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($files);

    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}}';
    }
});
