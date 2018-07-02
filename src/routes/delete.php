<?php
ini_set("display_errors", "on");
error_reporting(E_ALL);


use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


//Elimina file
$app->get('/api/delete/{id}', function($req, $res, $args) {
    
    
    $id = $req->getAttribute('id');
    $sql = "SELECT path FROM t_files WHERE id_file = $id";
    try{
        //Get DB Object
        $db = new db();
        //Connetti
        $db = $db->connect();
        $stmt = $db->query($sql);
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        $db = null;
        
    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}}';
    }
    
    $path = $result->path;



    //$file = "/var/www/html/slimdrive/src/uploads" . DIRECTORY_SEPARATOR . $path;
    $file = "C:/xampp/htdocs/slimdrive\src/uploads" . DIRECTORY_SEPARATOR . $path;
    
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

//Rimuovi dai condivisi/ rimuovi condivisione
$app->get('/api/delete/share/{id_file}/{user}', function($req, $res, $args) {
    
    $user = $req->getAttribute('user');
    $sql = "SELECT id_user FROM t_users as u WHERE u.user = '$user'";
    
    try{
        //Get DB Object
        $db = new db();
        //Connetti
        $db = $db->connect();
        $stmt = $db->query($sql);
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        $db = null;
        
    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}}';
    }
   
    
    $id_user = $result->id_user;
    echo $id_user;

    $id_file = $req->getAttribute('id_file');
    echo $id_file;
    
   
    $sql = "DELETE FROM t_condivisioni WHERE id_file = :id_file AND id_user = :id_user";
    

try{
    //Get DB Object
    $db = new db();
    //Connetti
    $db = $db->connect();
    $stmt = $db->prepare($sql);
    
        $stmt->bindParam(':id_file', $id_file);
        $stmt->bindParam(':id_user', $id_user);
    
    $stmt->execute();
    
    $db = null;
    echo '{"notice": {"text": "Condivisione eliminata"}}';

} catch(PDOException $e){
    echo '{"error": {"text": '.$e->getMessage().'}}';
}
});