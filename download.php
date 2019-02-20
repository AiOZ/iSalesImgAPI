<?php
if(isset($_GET)) {
    $modulepart = null;
    $original_file = null;
    $dolapikey = null;
    
    if(isset($_GET['module_part'])) {
        $modulepart = $_GET['module_part'];
    }
    if(isset($_GET['original_file'])) {
        $original_file = $_GET['original_file'];
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
    if($modulepart == null) {
        echo 'no_modulepart';
        return;
    }
    
    // no original_file
    if($original_file == null) {
        echo 'no_original_file';
        return;
    }
    
    // url de telechargement de l'image
    // $url="http://dolibarr.bananafw.com/api/index.php/documents/download?module_part=".$modulepart."&original_file=".$original_file."&DOLAPIKEY=".$dolapikey;
    $url="http://82.253.71.109/prod/francefood_v8/api/index.php/documents/download?module_part=".$modulepart."&original_file=".$original_file."&DOLAPIKEY=".$dolapikey;
    
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