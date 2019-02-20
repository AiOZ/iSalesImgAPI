<?php
if(isset($_GET)) {
    $idParent = null;
    
    if(isset($_GET['id'])) {
        $idParent = $_GET['id'];
    }
    
    // no module_part
    if($idParent == null) {
        echo 'no_product';
        return;
    }

    $db = new PDO("mysql:host=localhost; dbname=soif_express", "root", "admin");
    $query = "SELECT * FROM llx_product_association pa, llx_product p where pa.fk_product_pere = p.rowid AND pa.fk_product_fils = :id";
    
    $stmt = $db->prepare($query);
    $stmt->execute(['id' => $idParent]); 
    $pdtVirtuels = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo(json_encode($pdtVirtuels));
    
}