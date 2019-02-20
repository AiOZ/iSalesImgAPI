<?php
if(isset($_GET)) {
    $ref = null;
    $dolapikey = null;
    
    if(isset($_GET['ref'])) {
        $ref = $_GET['ref'];
    }
    if(isset($_GET['DOLAPIKEY'])) {
        $dolapikey = $_GET['DOLAPIKEY'];
    }
    
    // no credential
    if($dolapikey == null) {
        echo 'no_credentials';
        return;
    }
    
    // no module_part
    if($ref == null) {
        echo 'no_ref';
        return;
    }

    $filePath = "produit/".$ref;
    $db = new PDO("mysql:host=localhost; dbname=soif_express", "root", "admin");
    $query = "SELECT * FROM llx_ecm_files WHERE filepath = :filpath";
    
    $stmt = $db->prepare($query);
    $stmt->execute(['filpath' => $filePath]); 
    $ecmFiles = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // var_dump($ecmFiles);

    $picFile = $ecmFiles[0];
    
    // var_dump($picFile);

    $original_file = $ref."/".$picFile['filename'];

    // echo $original_file;
    // url de telechargement de l'image
    $url="http://82.253.71.109/prod/soif_express/api/index.php/documents/download?module_part=product&original_file=".$original_file."&DOLAPIKEY=".$dolapikey;
    
    //  Initiate curl
    $ch = curl_init();
    // Will return the response, if false it print the response
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Set the url
    curl_setopt($ch, CURLOPT_URL,$url);
    // Execute
    $result=curl_exec($ch);
    // Closing
    curl_close($ch);
    
    // Will dump a beauty json :3
    $resultArr = json_decode($result, true);
    
    // var_dump($resultArr); return;
    if (isset($resultArr['error'])) {
        echo $resultArr['error']['message'];
        return;
    }

    // Si le dossier du module_part n'existe pas, alors on le crée
    if (!file_exists("images/".$modulepart)) {
        mkdir("images/".$modulepart, 0777, true);
    }

    // $targetPath="D:/timekeeping/logs/94-20160908.dat";
    $file_name = "images/".$modulepart."/".$resultArr['filename'];
    // $data = file_get_contents($targetPath);
    $content= base64_decode($resultArr['content']);
    $file = fopen($file_name, 'w');    
    fwrite($file, $content);
    fclose($file);
    
    header('content-type: image/jpg');
    readfile($file_name);
    ob_clean();
    flush();
    return;
    
    echo 'modulepart = '.$modulepart.'\n original_file = '.$original_file.'\n dolapikey = '.$dolapikey;
}