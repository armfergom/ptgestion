<?php

    include_once ('inc/db_connect.php');
	
	$IdCompra = $_REQUEST['compra'];
	$Referencia = $_REQUEST['articulo'];
	
	try
	{
		//decrementamos las unidades de ese artículo
		
		//obtenemos las unidades actuales
		$query = "SELECT Unidades FROM articulo WHERE Referencia = '$Referencia'";
		$stmt = $dbh -> query($query);
		$row = $stmt -> fetch();		
		$unidades = $row['Unidades'];

		//quitamos las que pertenecían a esta línea de compra
		$query = "SELECT Unidades FROM lineacompra WHERE IdCompra = $IdCompra AND Referencia = '$Referencia'";
		$stmt = $dbh -> query($query);
		$row = $stmt -> fetch();		
		$unidades -= $row['Unidades'];

		//finalmente actualizamos la tabla artículo
		$sql = "UPDATE articulo SET Unidades = $unidades WHERE Referencia = '$Referencia'";
		$dbh -> exec($sql);
		
		//y borramos la linea compra
		$sql = "DELETE FROM lineacompra WHERE IdCompra = $IdCompra AND Referencia = $Referencia";
		$dbh->exec($sql);
		
		//si no quedan más lineas de compra de esa compra, borramos la compra
		$query = "SELECT Referencia FROM lineacompra WHERE IdCompra = $IdCompra";
		$stmt = $dbh -> query($query);
		if ($stmt->rowCount() == 0)
		{
			//y borramos la compra
			$sql = "DELETE FROM compra WHERE IdCompra = $IdCompra";
			$dbh->exec($sql);
			
			header("Location: listadoCompras.php");
		}
		else
			header("Location: datosCompra.php?compra=$IdCompra");
			
	}
	catch(PDOException $e ) 
	{
		// tratamiento del error
		die("Error PDO: ".$e->GetMessage());
	}
	
	

?>