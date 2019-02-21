<?php

if(isset($_GET)) 
{
    $idParent = null;
    
    if(isset($_GET['id'])) {
        $idParent = $_GET['id'];
    }
    $idParent = 1;
	
	// no module_part
    if($idParent == null) {
        echo 'no_product';
        return;
    }
	$pdtVirtuels = "";
	g_a_l($idParent,$pdtVirtuels);
    echo(json_encode($pdtVirtuels));
}

function g_a_l($id, &$table='')
{
	$connex	= mysqli_connect("localhost","root","admin","soif_express");
	if(mysqli_connect_errno())
	{
		print "Failed connect " . mysqli_connect_error();
	}
	$sql		= "SELECT * FROM llx_product_association pa, llx_product p where pa.fk_product_pere = p.rowid AND pa.fk_product_fils =".$id." ";
	$result		= mysqli_query($connex,$sql);
	$rowcount	= mysqli_num_rows($result);
	
	if($rowcount > 0)
	{
		while( $ligne = mysqli_fetch_array($result) )
		{
			$table[] = $ligne;
			g_a_l($ligne[1],$table);
		}
	}	
	mysqli_close($connex);
}

/*
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
    
    $pdtVirtuels[] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $query = "SELECT * FROM llx_product_association pa, llx_product p where pa.fk_product_pere = p.rowid AND pa.fk_product_fils = :id";
    $stmt = $db->prepare($query);
    $stmt->execute(['id' => $idParent]); 
    
    echo(json_encode($pdtVirtuels));
    
}

*/
