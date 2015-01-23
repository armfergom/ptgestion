<?php
	
	include_once('inc/header.php');				
	//obtemos los artículos con todos sus atributos
	try 
	{
		$query = "SELECT IdProveedor,Nombre, Direccion, Localidad, Tlf1, Email FROM proveedor ORDER BY Nombre ASC";
		$stmt = $dbh->query($query);	

		listaProv($stmt);
		
	}
	catch(PDOException $e ) {
		// tratamiento del error
		die("Error PDO: ".$e->GetMessage());
	}

	include_once('inc/footer.php');

?>