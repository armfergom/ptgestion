<?php
	
	include_once('inc/header.php');				
	//obtemos los clientes con sus atributons principales
	try 
	{
		$query = "SELECT IdCliente, NIF, Titulo, Nombre, Apellidos, Tlf1, Direccion,CP FROM cliente ORDER BY Apellidos ASC";
		$stmt = $dbh->query($query);	

		listaCli($stmt);		
	}
	catch(PDOException $e ) {
		// tratamiento del error
		die("Error PDO: ".$e->GetMessage());
	}

	include_once('inc/footer.php');

?>