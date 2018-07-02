<?php
ini_set("display_errors", "on");
error_reporting(E_ALL);


use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\UploadedFileInterface;


// commento github test
$app->post('/api/upload/{user}', function ($request, $response, $args) {
    $owner = $request->getAttribute('user');

    $files = $request->getUploadedFiles();
    if (empty($files['newfile'])) {
        throw new Exception('Expected a newfile');
    }
 
    $newfile = $files['newfile'];
    
    if ($newfile->getError() === UPLOAD_ERR_OK) {
        $uploadedFileName = $newfile->getClientFilename();

        $extension = pathinfo($uploadedFileName, PATHINFO_EXTENSION);
        $basename = bin2hex(random_bytes(8)); // see http://php.net/manual/en/function.random-bytes.php
        $filename = sprintf('%s.%0.8s', $basename, $extension);
    
        
        
        //$newfile->moveTo("/var/www/html/slimdrive/src/uploads" . DIRECTORY_SEPARATOR . $filename); 
        $newfile->moveTo("C:/xampp/htdocs/slimdrive\src/uploads" . DIRECTORY_SEPARATOR . $filename); 

        insertFile($uploadedFileName, $filename, $owner);

        echo $uploadedFileName;
    }

});

function insertFile($nome, $path, $owner){

    $sql = "INSERT INTO t_files (filename, path, owner) VALUES
    (:filename, :path, :owner) ";

    try{
        //Get DB Object
        $db = new db();
        //Connetti
        $db = $db->connect();
        $stmt = $db->prepare($sql);

            $stmt->bindParam(':filename', $nome);
            $stmt->bindParam(':path',  $path);
            $stmt->bindParam(':owner',  $owner);

        $stmt->execute();
        $db = null;
        echo '{"notice": {"text": "File aggiunto"}}';

    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}}';
    }

};
